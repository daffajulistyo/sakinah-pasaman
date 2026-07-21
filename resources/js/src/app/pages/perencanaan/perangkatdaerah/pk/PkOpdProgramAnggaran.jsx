import React from 'react'
import Layout from '@/app/components/Layout/Layout'
import GoodNotes from "@assets/GoodNotes.png"
import MySelect2 from '@/app/components/Form/MySelect2'
import PrimaryLinkBtn from '@/app/components/Button/PrimaryLinkBtn'
import { ArrowLeftCircleIcon } from '@heroicons/react/24/outline'
import MyInput from '@/app/components/Form/MyInput'
import { useParams, useNavigate, useSearchParams } from 'react-router-dom'
import { useSelector, useDispatch } from 'react-redux'
import { getListProgramAnggaranOpd } from '@/redux/ducks/programanggaran/action' 
import TablePA from '@/app/components/ProgramAnggaran/TableProgramAnggaran'
import Swal from 'sweetalert2'
import { createProgramPkOpd, getListPkOpdProgram } from '@/redux/ducks/pkopd/action'

const PkOpdProgramAnggaran = () => {
    const { type, period } = useParams()
    const dispatch = useDispatch()
    const [searchParams, setSearchParams] = useSearchParams()
    const sasaran_opd_id = searchParams.get('id')
    const programAnggaranState = useSelector((state) => state.programAnggaranState)
    const pkOpdState = useSelector((state) => (state.pkOpdState))
    const authState = useSelector((state) => state.authState)
    const getOpdId = () => (authState.biodata?.opd?.simonev_opd_id ?? "")
    const navigate = useNavigate()
    React.useEffect(() => {
        let payload = {
            sasaran_opd_id: sasaran_opd_id,
            tahun: period,
            murni: type === 'murni'
        }
        dispatch(getListPkOpdProgram(payload))
    },[])
    const [programExisted,setProgramExisted] = React.useState(null)
    React.useEffect(() => {
        if(pkOpdState.sasaran !== null){
            if(pkOpdState.sasaran.list_program){
                if(pkOpdState.sasaran.list_program.list_program){
                    var existedProgram = pkOpdState.sasaran.list_program.list_program
                    
                    setProgramExisted(existedProgram)
                }
            }
        }
    },[pkOpdState])
    const existedProgramList = () => {
        
        if(programExisted !== null){                
            if(('opd_id_'+getOpdId()) in programExisted) return programExisted['opd_id_'+getOpdId()]
            else return []
        }
        return []
        
    }
    React.useEffect(() => {
        let payload = {
            year : period,
            periode : "murni"
        }
        dispatch(getListProgramAnggaranOpd(payload))
    }, [])
    
    const simpanData = (programAnggaranData) => {
        // hitung total anggaran
        let list_kegiatan = {}

        for(var key in programExisted){
            list_kegiatan[key] = programExisted[key]
        }

        list_kegiatan['opd_id_'+getOpdId()] = programAnggaranData
        let tAnggaran = 0
        for(var xk in list_kegiatan){
            let existedPA = list_kegiatan[xk]
                if(existedPA.length > 0){
                    existedPA.map((item) => {
                        tAnggaran += item.uAnggaran ?? 0
                    })
                }
        }
        
        let payload = {
            sasaran_opd_id: sasaran_opd_id, // sesuaikan dengan sasaran yang dipilih
            tahun: period,
            murni: type === "murni",
            list_program: JSON.stringify(list_kegiatan), // mapping ulang menggunakan kode opd sebagai objectkey
            anggaran: tAnggaran,
            is_active: true
        }
        console.log(payload);
        
        Swal.fire({
            title: 'Apakah anda yakin?',
            text: "pastikan data program anggaran sudah sesuai dan benar",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes!'
            }).then(async (result) => {
                if (result.isConfirmed) {
                    const response = await dispatch(createProgramPkOpd(payload))
                
                    if(response.error === null){
                        Swal.fire({
                            icon: 'success',
                            title: response.data.message,
                            showConfirmButton: false,
                            timer: 1500
                        })
                        navigate(`/perencanaan/opd/pk/${period}/${type}`)
                    }
                    else{
                        Swal.fire({
                            icon: 'error',
                            title: "something went wrong",
                            showConfirmButton: false,
                            timer: 1500
                        })
                    }
                    
                }
            })
    }

    const mixedLoading = () => (programAnggaranState.loading || pkOpdState.loading)
    return (
        <Layout>
            <div className="bg-white h-auto dark:bg-gray-800 rounded-lg drop-shadow-xl py-1 px-3 w-full">
                <div className="dark:text-white">
                    <div className="flex flex-row items-center gap-3">
                        <div>
                            <img src={GoodNotes} alt="Perencanaan Perangkat Daerah" className="object-contain" />
                        </div>
                        <div className="lg:text-lg font-bold text-teal-500 dark:text-white">Perencanaan PK Perangkat Daerah</div>
                    </div>
                </div>
            </div>
            <div className="bg-white h-auto dark:bg-gray-800 rounded-lg drop-shadow-xl py-1 sm:px-3 px-1 w-full flex flex-col lg:min-h-[35rem]">
                <div className="block w-full p-4">
                    <h2 className="font-bold text-blue-500 dark:text-blue-100 w-full text-center">
                        Pilih Program/Kegiatan/Subkegiatan <br /> 
                        Perjanjian Kinerja {type.toUpperCase()} <br />
                        {period}
                    </h2>
                    <div className="flex">
                        <PrimaryLinkBtn to={`/perencanaan/opd/pk/${period}/${type}`}>
                            <ArrowLeftCircleIcon className='w-5 h-5' />
                            Kembali
                        </PrimaryLinkBtn>
                    </div>
                </div>
                <div className="block w-full p-4">
                    
                    <MyInput
                        id="sasaranSelector"
                        label="Sasaran"
                        value={pkOpdState.sasaran?.sasaran?.sasaran ?? ""}
                        disabled
                    />
                </div>
                <div className="block w-full p-4">
                    <TablePA 
                        programAnggaranData={programAnggaranState.data} 
                        existingData={existedProgramList()}
                        loading={mixedLoading()}
                        action={simpanData}
                     />
                </div>
                
            </div>
        </Layout>
    )
}

export default PkOpdProgramAnggaran