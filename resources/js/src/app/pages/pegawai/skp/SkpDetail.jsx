import Layout from '@/app/components/Layout/Layout'
import React from 'react'
import GoodNotes from "@assets/GoodNotes.png"
import { useSelector, useDispatch } from 'react-redux'
import { PlayIcon, PencilSquareIcon, TrashIcon } from '@heroicons/react/24/solid'
import { Link, useNavigate, useParams } from 'react-router-dom'
import PrimaryBtn from '@/app/components/Button/PrimaryBtn'
import { PlusCircleIcon } from '@heroicons/react/24/outline'
import { StaticTable } from '@/app/components/Table'
import { PacmanLoader } from 'react-spinners'
import MyModal from '@/app/components/Form/MyModal'
import MySelect2 from '@/app/components/Form/MySelect2'
import MyInput from '@/app/components/Form/MyInput'
import { getPeriodeSkp, getListSasaranYangDiampu, createSkp, updateSkp, getListSkp, deleteSkp } from '@/redux/ducks/skp/action'
import { indonesianDate } from '@/app/helper/Common'
import { useFormik } from 'formik'
import * as Yup from "yup"
import Swal from 'sweetalert2'
import { initFlowbite } from 'flowbite'

const SkpDetail = () => {
    const [openModal, setOpenModal] = React.useState(false)
    const [formTitle, setFormTitle] = React.useState("FORM TAMBAH SKP")
    const authState = useSelector((state) => state.authState)
    const skpState = useSelector((state) => state.skpState)
    const [editId, setEditId] = React.useState(null)
    const [editParams, setEditParams] = React.useState({
        indikator: "",
        sasaran: ""
    })
    const navigate = useNavigate()
    const dispatch = useDispatch()
    const { id } = useParams()
    React.useEffect(() => {
        initFlowbite()
    }, [skpState.list_skp])
    React.useEffect(() => {
        dispatch(getPeriodeSkp(id)).then((result) => {
            if(result.error !== null){
                navigate('/pegawai/skp');
            }
            else{
                dispatch(getListSkp(id))
            }
        })
    }, [id])
    React.useEffect(() => {
        dispatch(getListSasaranYangDiampu())
    }, [])

    const getDataTable = () => {
        dispatch(getListSkp(id))
    }

    const dataTable = () => {
        let data = skpState.list_skp?.sasaran_atasan ?? []
        let raw = data.length > 0 ? data.map((item,a1) => {
            let rowspan_atasan = 0
            let sasaran_pegawai = item.sasaran_pegawai.length > 0 ? item.sasaran_pegawai.map((item2,a2) => {
                rowspan_atasan = rowspan_atasan + item2.indikator.length
                return {
                    ...item2,
                    rowspan: item2.indikator.length
                }
            }) : []
            return {
                ...item,
                rowspan: rowspan_atasan,
                sasaran_pegawai: sasaran_pegawai
            }

        }) : []
        console.log(raw);
        
        return raw
    }


    const [targettw1, setTargettw1] = React.useState(0)
    const [targettw2, setTargettw2] = React.useState(0)
    const [targettw3, setTargettw3] = React.useState(0)
    const [targettw4, setTargettw4] = React.useState(0)

    const formik = useFormik({
        initialValues: {
            satuan: "",
        },
        validationSchema: Yup.object({ 
            satuan:           Yup.string().required().strict(true),
        }),
        enableReinitialize: true
    })
    const validationForm = async () => {
        //validation
        formik.setFieldTouched('satuan', true, true)
        const errors = await formik.validateForm();

        return errors
    }
    
    const [selectedSasaran, setSelectedSasaran] = React.useState(null)
    const [listIndikator, setListIndikator] = React.useState([])
    const [selectedIndikator, setSelectedIndikator] = React.useState(null)
    const setPilihSasaran = (e, edit = false) => {
        
        // jika bukan proses edit melainkan proses seleksi pada form seperti biasa
        if(edit){
            setSelectedSasaran(e)
        }
        else{
            setSelectedSasaran(e)
        }
        setSelectedIndikator(null)
        let list = e.indikator.length > 0 ? e.indikator.map((item) => ({
            label: item.indikator,
            value: item.id
        })) : []
        setListIndikator(list)
        
    }
    const sasaranYangDiampuList = () => { 
        const list = skpState.list_sasaran_yang_diampu.length > 0 ? skpState.list_sasaran_yang_diampu.map((item) => ({
            label: item.sasaran,
            value: item.id,
            indikator: item.indikator_sasaran
        })) : []
        return list
    }
    const openModalAction = () => {
        setFormTitle("FORM TAMBAH SKP")
        setEditId(null)
        formik.resetForm()
        setSelectedSasaran(null)
        setSelectedIndikator(null)
        setListIndikator([])

        setTargettw1(0)
        setTargettw2(0)
        setTargettw3(0)
        setTargettw4(0)
        setOpenModal(true);
    }

    const tableHeader = () => (
        <>
        <tr>
            <th rowSpan="2" scope="col" className="px-4 py-3 border text-center w-[1%]">No.</th>
            <th rowSpan="2" scope="col" className="px-4 py-3 border text-center">Sasaran Kerja Pimpinan Yang Diintervensi</th>
            <th rowSpan="2" scope="col" className="px-4 py-3 border text-center">Sasaran Kerja Pegawai</th>
            <th rowSpan="2" scope="col" className="px-4 py-3 border text-center">Indikator Kinerja Individu</th>
            <th colspan="4" scope="col" className="px-4 py-3 border text-center">Target</th>
            <th rowSpan="2" scope="col" className="px-4 py-3 border text-center">Satuan</th>
            <th rowSpan="2" scope="col" className="px-4 py-3 border text-center">Rencana Aksi</th>
            <th rowSpan="2" scope="col" className="px-4 py-3 border">
                <span className="sr-only">Actions</span>
            </th>
        </tr>
        <tr>
            <th rowSpan="2" scope="col" className="px-4 py-3 border text-center">TW 1</th>
            <th rowSpan="2" scope="col" className="px-4 py-3 border text-center">TW 2</th>
            <th rowSpan="2" scope="col" className="px-4 py-3 border text-center">TW 3</th>
            <th rowSpan="2" scope="col" className="px-4 py-3 border text-center">TW 4</th>
        </tr>
        </>
    )

    const renderTable = () => {
        let no = 1;
    
        if (loading) {
            return (
                <tr>
                    <td colSpan="100%" className="text-center border py-3 px-4">
                    <div className="flex flex-row justify-center w-full gap-12"> <PacmanLoader size={10} color='gray' /> Please Wait... </div>
                    </td>
                </tr>
            );
        }
    
        const data = dataTable();
    
        if (!data.length) {
            return (
                <tr>
                    <td colSpan="100%" className="text-center py-3 px-4 border">
                        No Data
                    </td>
                </tr>
            );
        }
    
        return data.map((item) => {
            return item.sasaran_pegawai?.map((item1, key1) => {
                return item1.indikator?.map((item2, key2) => {
                    return (
                        <tr key={item2.id} className="border-b align-top">
    
                            {/* LEVEL 1 (ITEM) */}
                            {key1 === 0 && key2 === 0 && (
                                <>
                                    <td rowSpan={item.rowspan} className="border px-4 py-3">
                                        {no++}
                                    </td>
    
                                    {/* kalau ada nama parent */}
                                    <td rowSpan={item.rowspan} className="border px-4 py-3">
                                        {item.sasaran ?? '-'}
                                    </td>
                                </>
                            )}
    
                            {/* LEVEL 2 (SASARAN) */}
                            {key2 === 0 && (
                                <td rowSpan={item1.rowspan} className="border px-4 py-3">
                                    {item1.sasaran}
                                </td>
                            )}
    
                            {/* LEVEL 3 (INDIKATOR) */}
                            <td className="border px-4 py-3">{item2.indikator}</td>
                            <td className="border px-4 py-3">{item2.target_tw1}</td>
                            <td className="border px-4 py-3">{item2.target_tw2}</td>
                            <td className="border px-4 py-3">{item2.target_tw3}</td>
                            <td className="border px-4 py-3">{item2.target_tw4}</td>
                            <td className="border px-4 py-3">{item2.satuan}</td>
    
                            <td className="border px-4 py-3">
                                {renderRencanaAksi(item2?.langkah ?? [])}
                            </td>
    
                            {/* AKSI */}
                            <td className="border px-4 py-3 text-center">
                            <button id={`btn-${item2.id}`} data-dropdown-toggle={`toggle-btn${item2.id}`}
                                    className="inline-flex items-center p-0.5 text-sm h-full font-medium text-center text-gray-500 hover:text-gray-800 rounded-lg focus:outline-none dark:text-gray-400 dark:hover:text-gray-100"
                                    type="button">
                                    <svg className="w-5 h-5" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM16 12a2 2 0 100-4 2 2 0 000 4z" />
                                    </svg>
                                </button>
                                <div id={`toggle-btn${item2.id}`}
                                    className="hidden z-10 w-44 bg-gray-50 rounded divide-y divide-gray-100 drop-shadow-lg dark:bg-gray-700 dark:divide-gray-600">
                                    <ul className="py-1 text-sm text-gray-700 dark:text-gray-200"
                                        aria-labelledby={`btn-${item2.id}`}>
                                        
                                        <li>
                                            <a href="#" onClick={() => editAction({
                                                id: item2.id,
                                                sasaran_opd_id: item1.id,
                                                sasaran: item1.sasaran,
                                                indikator_opd_id: item2.indikator_opd_id,
                                                indikator: item2.indikator,
                                                target: item2.target,
                                                satuan: item2.satuan
                                            })}
                                                className="flex gap-1 py-2 px-4 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">
                                                <PencilSquareIcon className='w-5 h-5' />
                                                Input Target
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#" onClick={() => navigate(`/pegawai/skp/${id}/rencana-aksi/${item2.id}`)}
                                                className="flex gap-1 py-2 px-4 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">
                                                <PencilSquareIcon className='w-5 h-5' />
                                                Input Renaksi
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    );
                });
            });
        });
    };
     const renderRencanaAksi = (data) => {
        try {
            if(data.length > 0){
                return (
                    <ol className="list-decimal">
                    {
                        data.map((item, key) => (
                        
                            <li className='whitespace-nowrap' key={key}>{item.langkah}</li>
                            
                        ))
                    }
                    </ol>
                )
            }
            else{
                return <ol className="list-decimal">( no data )</ol>
            }
        } catch (error) {
            return <ol className="list-decimal">( no data )</ol>
        }
    }
    const simpanData= async ()=> {
        
        const errors = await validationForm()
        
        if (Object.keys(errors).length === 0) {
            // Form is valid, do any success call, mapping payload before submit
            const form = formik.values
            
            const payload = {
                skp_id: id,
                sasaran_opd_id: selectedSasaran.value,
                indikator_opd_id: selectedIndikator.value,
                target_tw1: targettw1.toString(),
                target_tw2: targettw2.toString(),
                target_tw3: targettw3.toString(),
                target_tw4: targettw4.toString(),
                satuan: form.satuan,
            }
            // submit payload with dispatch action redux
            // console.log(payload); return
            
            let response = editId === null ? await dispatch(createSkp(payload)) : await dispatch(updateSkp(editId,payload));
            if(response.error === null){
                Swal.fire({
                    icon: 'success',
                    title: response.data.message,
                    showConfirmButton: false,
                    timer: 1500
                })
            
                setOpenModal(false)
                getDataTable()
            }
            else{
                Swal.fire({
                    icon: 'error',
                    title: "something went wrong",
                    showConfirmButton: false,
                    timer: 1500
                })
            
                setOpenModal(false)
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
    const editAction = (data) => {
        formik.resetForm()
        setEditParams({
            sasaran: data.sasaran,
            indikator: data.indikator
        })
        setSelectedSasaran({value:data.sasaran_opd_id, label:data.sasaran})
        setSelectedIndikator({value:data.indikator_opd_id, label:data.indikator})
        setTargettw1(data.target_tw1);
        setTargettw2(data.target_tw2);
        setTargettw3(data.target_tw3);
        setTargettw4(data.target_tw4);
        formik.setFieldValue('satuan', data.satuan);
        setEditId(data.id)
        setFormTitle("FORM EDIT SKP")
        setOpenModal(true)
    }

    const deleteAction = (id) => {
        Swal.fire({
        title: 'Hapus data ini?',
        text: "data yang sudah dihapus tidak dapat dikembalikan!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes!'
        }).then(async (result) => {
            if (result.isConfirmed) {
                const response = await dispatch(deleteSkp(id))
                
                if(response.error === null){
                    Swal.fire({
                        icon: 'success',
                        title: response.data.message,
                        showConfirmButton: false,
                        timer: 1500
                    })
                    getDataTable()
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

    const loading = skpState.loading
    return (
        <Layout>
            <div className="bg-white h-auto dark:bg-gray-800 rounded-lg drop-shadow-xl py-1 px-3 w-full">
                <div className="dark:text-white">
                    <div className="flex flex-row items-center gap-3">
                        <div>
                            <img src={GoodNotes} alt="Pengukuran Pegawai" className="object-contain" />
                        </div>
                        <div className="flex flex-row items-center gap-4 lg:text-lg font-bold text-teal-500 dark:text-white">
                        <Link to="/" className='hover:text-teal-200 hover:dark:text-teal-500'>{authState.biodata.name} </Link>
                        <PlayIcon className='w-5 h-5' /> 
                        <Link to="/pegawai/skp" className='hover:text-teal-200 hover:dark:text-teal-500'>Periode</Link>
                        <PlayIcon className='w-5 h-5' /> 
                        <Link to={`/pegawai/skp/${id}`} className='hover:text-teal-200 hover:dark:text-teal-500'>SKP</Link>
                        <PlayIcon className='w-5 h-5' /> 
                        Detail SKP
                        </div>
                    </div>
                </div>
            </div>
            <div className="bg-white h-auto dark:bg-gray-800 rounded-lg drop-shadow-xl py-1 sm:px-3 px-1 w-full flex flex-col lg:min-h-[35rem]">
                <div className="flex flex-row justify-between w-full p-4">
                    <div>
                        <h1 className="font-semibold dark:text-white">
                            Periode : &nbsp;
                            <span className="bg-blue-500 dark:bg-blue-800 text-white px-1 py-0.5 rounded-md">
                                {indonesianDate(skpState.periode_skp?.periode_awal ?? "")} - {indonesianDate(skpState.periode_skp?.periode_akhir ?? "")}
                            </span>
                        </h1>
                    </div>
                    <div className="mr-0">
                        
                    </div>
                </div>
                <StaticTable header={tableHeader()}>   
                {
                    renderTable()                    
                }
                </StaticTable>
                <MyModal ModalTitle={formTitle} openModal={openModal} setOpenModal={setOpenModal} >
                    <div className="flex flex-col w-full p-4">
                        {
                            editId !== null ? (
                                <>
                                <MyInput id="sasaran_diampu" name="sasaran_diampu" label="Sasaran yang diampu dari Pohon Kinerja"
                                    type="text"
                                    value={editParams.sasaran}
                                    disabled
                                />
                                <MyInput id="indikator" name="indikator" label="Indikator Kinerja Individu"
                                    type="text"
                                    value={editParams.indikator}
                                    disabled
                                />
                                </>
                            ) : (<>
                            <MySelect2 id="sasaran_diampu" name="sasaran_diampu" label="Sasaran yang diampu dari Pohon Kinerja"
                                placeholder="pilih sasaran..."
                                options={sasaranYangDiampuList()}
                                onChange={setPilihSasaran}
                                value={selectedSasaran} />
                            <MySelect2 id="indikator" name="indikator" label="Indikator Kinerja Individu"
                                placeholder="pilih indikator..."
                                options={listIndikator}
                                onChange={setSelectedIndikator}
                                value={selectedIndikator} />
                            </>)
                        }
                        
                        <div className="block w-full py-2">
                            <h1 className="font-bold dark:text-white">Target per Triwulan (TW)</h1>
                        </div>
                        
                        <div className="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-2 sm:gap-3 md:gap-5">
                            <MyInput id="tw1" name="tw1" 
                                label="TW ke-1" 
                                placeholder='Input target...'
                                type='number'
                                value={targettw1}
                                onChange={(e) => setTargettw1(Number(e.target.value))}
                            />
                            <MyInput id="tw2" name="tw2" 
                                label="TW ke-2" 
                                placeholder='Input target...'
                                type='number'
                                value={targettw2}
                                onChange={(e) => setTargettw2(Number(e.target.value))}
                            />
                            <MyInput id="tw3" name="tw3" 
                                label="TW ke-3" 
                                placeholder='Input target...'
                                type='number'
                                value={targettw3}
                                onChange={(e) => setTargettw3(Number(e.target.value))}
                            />
                            <MyInput id="tw4" name="tw4" 
                                label="TW ke-4" 
                                placeholder='Input target...'
                                type='number'
                                value={targettw4}
                                onChange={(e) => setTargettw4(Number(e.target.value))}
                            />
                        </div>
                        <MyInput id="satuan" name="satuan" label="Satuan"
                            type="text"
                            value={formik.values.satuan}
                            onChange={formik.handleChange}
                            onBlur={formik.handleBlur}
                            error={(formik.errors.satuan && formik.touched.satuan) ? formik.errors.satuan : ""}
                            />
                    </div>
                    
                    <div className="mt-5 sm:mt-6 flex justify-center">
                        <PrimaryBtn onClick={()=> simpanData()} loading={loading} >
                            Simpan Data
                        </PrimaryBtn>
                    </div>
                </MyModal>
            </div>
        </Layout>
    )
}


export default SkpDetail