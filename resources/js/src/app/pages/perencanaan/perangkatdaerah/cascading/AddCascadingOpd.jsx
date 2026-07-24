import React from 'react'
import Layout from '@/app/components/Layout/Layout'
import GoodNotes from "@assets/GoodNotes.png"
import MySelect2 from '@/app/components/Form/MySelect2'
import { StaticTable } from '@/app/components/Table'
import PrimaryBtn from '@/app/components/Button/PrimaryBtn'
import PrimaryLinkBtn from '@/app/components/Button/PrimaryLinkBtn'
import { ArrowLeftCircleIcon, PlusCircleIcon } from '@heroicons/react/24/outline'
import { useSelector, useDispatch } from 'react-redux'
import { getListProgramAnggaranOpd } from '@/redux/ducks/programanggaran/action'
import { getListCascadingOpd, createCascadingOpd } from '@/redux/ducks/cascadingopd/action'
import Swal from 'sweetalert2'

const AddCascadingOpd = () => {
    const dispatch = useDispatch()
    const authState = useSelector((state) => state.authState)
    const cascadingOpdState = useSelector((state) => state.cascadingOpdState)
    const programAnggaranState = useSelector((state) => state.programAnggaranState)
    const [selectedSasaran, setSelectedSasaran] = React.useState({})
    
    const [selectedValues, setSelectedValues] = React.useState([]);

    React.useEffect(() => {
        let payload = {
            year : new Date().getFullYear(),
            periode : "murni"
        }
        
        dispatch(getListProgramAnggaranOpd(payload))
    },[])

    const dataProgram = () => (
        programAnggaranState.data.length > 0 ? 
        programAnggaranState.data.map((item) => (
            {
                nama_program: item.nama_program,
                id_program: item.id_program
            }
        )) : []
    )

    const sasaranOnChange = (val) => {
        setSelectedSasaran(val)
        let sasaran = cascadingOpdState.list.filter(item => {
            return item.id === val.value
        })
        if(sasaran.length > 0){
            setExistingProgram(sasaran[0])
        }  
    }
    React.useEffect(() => {
        if(cascadingOpdState.list.length  === 0) dispatch(getListCascadingOpd())
    },[])

    const sasaranOptions = () => {
        if(cascadingOpdState.list.length > 0){
            return cascadingOpdState.list.map((item) => ({
                value: item.id,
                label: item.sasaran
            }))
        }
    }
    const tableHeader = () => (
        <tr>
            <th scope="col" className="px-4 py-3 w-[3%]">No.</th>
            <th scope="col" className="px-4 py-3">Program</th>
            <th scope="col" className="px-4 py-3 w-[10%]">
                <span className="sr-only">Actions</span>
            </th>
        </tr>
    )

    const handleCheckboxChange = (event) => {
        const { value, checked } = event.target;
    
        if (checked) {
          // Tambahkan nilai jika checkbox dicentang
          setSelectedValues([...selectedValues, value]);
        } else {
          // Hapus nilai jika checkbox tidak dicentang
          setSelectedValues(selectedValues.filter((item) => item !== value));
        }
    };

    const setExistingProgram = (currentSasaran) => {
        const opd_id = authState?.biodata?.opd?.ikd_opd_id ?? ""
        if(currentSasaran.program_pendukung){
            if(currentSasaran.program_pendukung.length > 0){
                let filterProgram = currentSasaran.program_pendukung.filter((item) => item.id_skpd === opd_id)
                let existFile = filterProgram.map((item) => item.id_program)
                setSelectedValues(existFile)
            }
            else setSelectedValues([])
        }
        else setSelectedValues([])
    }
    const simpanData = async () => {
        if(selectedValues.length > 0){
            const selectedProgram = programAnggaranState.data.filter((item) => selectedValues.includes(item.id_program.toString()))
            const payload = selectedProgram.map((item) => (
                {
                    sasaran_opd_id: selectedSasaran.value,
                    tahun: new Date().getFullYear(),
                    id_skpd: item.id_skpd,
                    id_program: item.id_program,
                    kode_program: item.kode_program,
                    nama_program: item.nama_program,
                }
            ))
            // console.log(payload); return false;
            
            const response = await dispatch(createCascadingOpd(payload))
            
            if(response.error === null){
                Swal.fire({
                    icon: 'success',
                    title: response.data.message,
                    showConfirmButton: false,
                    timer: 1500
                })
                
            }
            else{
                Swal.fire({
                    icon: 'error',
                    title: "something went wrong",
                    showConfirmButton: false,
                    timer: 1500
                })
            }
        } else {
            Swal.fire({
                icon: 'warning',
                title: "periksa kembali form isian anda",
                showConfirmButton: false,
                timer: 1500
            })
            
        }
    }
    return (
        <Layout>
            <div className="bg-white h-auto dark:bg-gray-800 rounded-lg drop-shadow-xl py-1 px-3 w-full">
                <div className="dark:text-white">
                    <div className="flex flex-row items-center gap-3">
                        <div>
                            <img src={GoodNotes} alt="Perencanaan Kepala Daerah" className="object-contain" />
                        </div>
                        <div className="lg:text-lg font-bold text-teal-500 dark:text-white">Perencanaan Cascading KDH</div>
                    </div>
                </div>
            </div>
            <div className="bg-white h-auto dark:bg-gray-800 rounded-lg drop-shadow-xl py-1 sm:px-3 px-1 w-full flex flex-col lg:min-h-[35rem]">
                <div className="block w-full p-4">
                    <h2 className="font-bold text-blue-500 w-full text-center">FORM TAMBAH PROGRAM</h2>
                    <div className="flex">
                        <PrimaryLinkBtn to="/perencanaan/opd/cascading">
                            <ArrowLeftCircleIcon className='w-5 h-5' />
                            Kembali
                        </PrimaryLinkBtn>
                    </div>
                </div>
                <div className="block w-full p-4">
                    
                    <MySelect2
                        id="sasaranSelector"
                        label="Sasaran"
                        options={sasaranOptions()}
                        value={selectedSasaran}
                        onChange={sasaranOnChange}
                    />
                </div>
                <div className="block w-full p-4">
                    <StaticTable header={tableHeader()}>
                        {
                            !programAnggaranState.loading ?
                                (dataProgram().length > 0 ? 
                                dataProgram().map((item, key) => (
                                    <tr className="border-b dark:border-gray-700">
                                        <td className="px-4 py-3 text-right">{key+1}</td>
                                        <td className="px-4 py-3">{item.nama_program}</td>
                                        <td className="px-4 py-3">
                                        <div className="flex items-center mb-4">
                                            <input 
                                                id={`checkbox-${item.id_program}`} 
                                                type="checkbox" 
                                                value={item.id_program} 
                                                onChange={handleCheckboxChange}
                                                checked={selectedValues.includes(item.id_program.toString())}
                                                className="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 
                                                    dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 
                                                    dark:border-gray-600"
                                            />
                                            <label for="default-checkbox" className="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300"></label>
                                        </div>
                                        </td>
                                    </tr>
                                )) :
                                <tr className="border-b dark:border-gray-700">
                                    <td className="px-4 py-3 text-center" colSpan="100%">No Data</td>
                                </tr>) :
                            <tr className="border-b dark:border-gray-700">
                                <td className="px-4 py-3 text-center" colSpan="100%">Loading...</td>
                            </tr>
                        }
                    </StaticTable>
                </div>
                <div className="block w-full p-4">
                    <div className="w-full flex justify-end">
                        <PrimaryBtn loading={cascadingOpdState.loading} onClick={() => simpanData()} >
                            <PlusCircleIcon className="w-5 h-5" />
                            Simpan Data
                        </PrimaryBtn>
                    </div>
                </div>
            </div>
        </Layout>
    )
}

export default AddCascadingOpd