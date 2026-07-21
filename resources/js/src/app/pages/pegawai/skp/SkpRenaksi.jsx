import React from 'react'
import Layout from '@/app/components/Layout/Layout'
import GoodNotes from "@assets/GoodNotes.png"
import { useSelector, useDispatch } from 'react-redux'
import { Link } from 'react-router-dom'
import { PlayIcon } from '@heroicons/react/24/solid'
import PrimaryBtn from '@/app/components/Button/PrimaryBtn'
import { PlusCircleIcon, PencilSquareIcon, TrashIcon } from '@heroicons/react/24/outline'
import { useParams } from 'react-router-dom'
import { StaticTable } from '@/app/components/Table'
import { PacmanLoader } from 'react-spinners'
import MyTextarea from '@/app/components/Form/MyTextarea'
import MyModal from '@/app/components/Form/MyModal'
import MyInput from '@/app/components/Form/MyInput'
import { useFormik } from 'formik'
import * as Yup from "yup"
import Swal from 'sweetalert2'
import { getIndikatorSkp, getListRencanaAksi, createRencanaAksi, updateRencanaAksi, deleteRencanaAksi, getPeriodeSkp } from '@/redux/ducks/skp/action'
import { initFlowbite } from 'flowbite'
import { indonesianDate } from '@/app/helper/Common'

const SkpRenaksi = () => {
    const dispatch = useDispatch()
    const authState = useSelector((state) => state.authState)
    const skpState = useSelector((state) => state.skpState)
    const { idskp, id } = useParams()

    React.useEffect(() => {
        const params = {skp_id: idskp}
        dispatch(getIndikatorSkp(id,params))
    },[])

    const getDataTable = () => {
        dispatch(getListRencanaAksi(idskp, id))
    }

    React.useEffect(() => {
        if(skpState.periode_skp !== null){
            if(skpState.periode_skp.id !== idskp){
                dispatch(getPeriodeSkp(idskp)).then(() => {
                    getDataTable()
                })
            }
            else{
                getDataTable()
            }
        }
        else{
            dispatch(getPeriodeSkp(idskp)).then(() => {
                console.log("tes");
                getDataTable()
            })
        }
    }, [])

    React.useEffect(() => {
        initFlowbite()
    }, [skpState.list_rencana_aksi])

    const tableHeader = () => (
        <>
        <tr>
            <th scope="col" rowSpan="2" className="px-4 py-3 border text-center w-[1%]">No.</th>
            <th scope="col" rowSpan="2" className="px-4 py-3 border text-center">Langkah-langkah pencapaian target</th>
            <th scope="col" rowSpan="2" className="px-4 py-3 border text-center">Satuan</th>
            <th scope="col" colSpan="4" className="px-4 py-3 border text-center">Target per Triwulan</th>
            <th scope="col" rowSpan="2" className="px-4 py-3 border text-center">Keterangan</th>
            <th scope="col" rowSpan="2" className="px-4 py-3 border">
                <span className="sr-only">Actions</span>
            </th>
        </tr>
        <tr>
            <th scope="col" className="px-4 py-3 border text-center w-[5%]">I</th>
            <th scope="col" className="px-4 py-3 border text-center w-[5%]">II</th>
            <th scope="col" className="px-4 py-3 border text-center w-[5%]">III</th>
            <th scope="col" className="px-4 py-3 border text-center w-[5%]">IV</th>
        </tr>
        </>
    )
    const renderTable = () => {
        return (
            loading ? 
            <tr className="border-b dark:border-gray-700">
                <td scope="row" className="px-4 py-5 bg-gray-100 dark:bg-gray-800 dark:text-white" colSpan="100%">
                    <div className="flex flex-row justify-center w-full gap-12">
                        <PacmanLoader size={10} color='gray' />
                        Please Wait...
                    </div>
                </td>
            </tr> : 
            (skpState.list_rencana_aksi.length > 0 ? 
                skpState.list_rencana_aksi.map((item, key) =>(
                    <tr key={item.id} className="border-b dark:border-gray-700 odd:bg-gray-100 dark:odd:bg-gray-900">
                        <td className="px-4 py-3 border text-right">{key+1}</td>
                        <td className="px-4 py-3 border">{item.langkah}</td>
                        <td className="px-4 py-3 border">{item.satuan}</td>
                        <td className="px-4 py-3 border text-right">{item.target_tw1}</td>
                        <td className="px-4 py-3 border text-right">{item.target_tw2}</td>
                        <td className="px-4 py-3 border text-right">{item.target_tw3}</td>
                        <td className="px-4 py-3 border text-right">{item.target_tw4}</td>
                        <td className="px-4 py-3 border">{item.keterangan}</td>
                        <td className="px-4 py-3 border text-center">
                            <button id={`btn-${key}`} data-dropdown-toggle={`toggle-btn${key}`}
                                className="inline-flex items-center p-0.5 text-sm font-medium text-center text-gray-500 hover:text-gray-800 rounded-lg focus:outline-none dark:text-gray-400 dark:hover:text-gray-100"
                                type="button">
                                <svg className="w-5 h-5" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM16 12a2 2 0 100-4 2 2 0 000 4z" />
                                </svg>
                            </button>
                            <div id={`toggle-btn${key}`}
                                className="hidden z-10 w-44 bg-gray-50 rounded divide-y divide-gray-100 drop-shadow-lg dark:bg-gray-700 dark:divide-gray-600">
                                <ul className="py-1 text-sm text-gray-700 dark:text-gray-200"
                                    aria-labelledby={`btn-${key}`}>
                                    <li>
                                        <a href="#" onClick={() => editAction(item)}
                                            className="flex gap-1 py-2 px-4 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">
                                            <PencilSquareIcon className='w-5 h-5' />
                                            Edit
                                        </a>
                                    </li>
                                </ul>
                                <div className="py-1">
                                    <a href="#" onClick={() => deleteAction(item.id)}
                                        className="flex gap-1 py-2 px-4 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">
                                        <TrashIcon className='w-5 h-5' />
                                        Hapus
                                    </a>
                                </div>
                            </div>
                        </td>
                    </tr>
                )) :
                <tr className="border-b dark:border-gray-700">
                    <td scope="row" className="px-4 py-5 bg-gray-100 dark:bg-gray-800 dark:text-white" colSpan="100%">
                        <div className="flex flex-row justify-center w-full gap-12">
                            No Data
                        </div>
                    </td>
                </tr>
            )
        )
    }

    const [openModal, setOpenModal] = React.useState(false)
    const [editId, setEditId] = React.useState("")
    const [formTitle, setFormTitle] = React.useState("Form Tambah Langkah-langkah Pencapaian Target")
    const openModalAction = () => {
        formik.resetForm();
        setEditId("")
        setFormTitle("Form Tambah Langkah-langkah Pencapaian Target")
        setOpenModal(true)
    }
    const formik = useFormik({
        initialValues: {
            langkah: "",
            satuan: "",
            target_tw1: "",
            target_tw2: "",
            target_tw3: "",
            target_tw4: "",
            keterangan: ""
        },
        validationSchema: Yup.object({ 
            langkah:           Yup.string().required().strict(true),
            satuan:           Yup.string().required().strict(true),
            target_tw1:           Yup.string().required().strict(true),
            target_tw2:           Yup.string().required().strict(true),
            target_tw3:           Yup.string().required().strict(true),
            target_tw4:           Yup.string().required().strict(true),
            keterangan:           Yup.string().strict(true),
        }),
        enableReinitialize: true
    })
    
    const validationForm = async () => {
        //validation
        formik.setFieldTouched('langkah', true, true)
        formik.setFieldTouched('satuan', true, true)
        formik.setFieldTouched('target_tw1', true, true)
        formik.setFieldTouched('target_tw2', true, true)
        formik.setFieldTouched('target_tw3', true, true)
        formik.setFieldTouched('target_tw4', true, true)
        formik.setFieldTouched('keterangan', true, true)
        const errors = await formik.validateForm();

        return errors
    }
    const simpanData= async ()=> {
        
        const errors = await validationForm()
        if (Object.keys(errors).length === 0) {
            // Form is valid, do any success call, mapping payload before submit
            const form = formik.values
            const payload = {
                ...form,
                skp_id: idskp,
                indikator_skp_id: id
            }
            
            // console.log(payload); return false;
            
            // submit payload with dispatch action redux
            let response = null
            if(editId !== "") response = await dispatch(updateRencanaAksi(editId, payload))
            else response = await dispatch(createRencanaAksi(payload));
            if(response.status !== "failed"){
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
        formik.setFieldValue('langkah', data.langkah);
        formik.setFieldValue('satuan', data.satuan);
        formik.setFieldValue('target_tw1', data.target_tw1);
        formik.setFieldValue('target_tw2', data.target_tw2);
        formik.setFieldValue('target_tw3', data.target_tw3);
        formik.setFieldValue('target_tw4', data.target_tw4);
        formik.setFieldValue('keterangan', data?.keterangan ?? "");
        setEditId(data.id)
        setFormTitle("Form Edit Langkah-langkah Pencapaian Target")
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
                const response = await dispatch(deleteRencanaAksi(id))
                
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
    // delete soon
    const loading = skpState.loading
    return (
        <Layout loading={loading}>
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
                        <Link to={`/pegawai/skp/${idskp}`} className='hover:text-teal-200 hover:dark:text-teal-500'>SKP</Link>
                        <PlayIcon className='w-5 h-5' /> 
                        <Link to={`/pegawai/skp/${idskp}/details/${id}`} className='hover:text-teal-200 hover:dark:text-teal-500'>Detail</Link>
                        <PlayIcon className='w-5 h-5' /> 
                        Rencana Aksi
                        </div>
                    </div>
                </div>
            </div>
            <div className="bg-white h-auto dark:bg-gray-800 rounded-lg drop-shadow-xl py-1 sm:px-3 px-1 w-full flex flex-col lg:min-h-[35rem]">
                <div className="flex md:flex-row flex-col justify-between w-full p-4">
                    <div>
                        <h1 className="font-semibold flex dark:text-white">
                            <div className="hidden md:block">Periode : &nbsp;</div>
                            <span className="bg-blue-500 dark:bg-blue-800 text-white px-1 py-0.5 rounded-md">
                                { indonesianDate(skpState.periode_skp?.periode_awal ?? "") } - { indonesianDate(skpState.periode_skp?.periode_akhir ?? "") }
                            </span>
                        </h1>
                        <h2 className="dark:text-white text-sm">
                            <span className="italic">{skpState.indikator?.sasaran ?? "( no data )"}</span>
                        </h2>
                        <h3 className="text-xs text-gray-400 dark:text-white">
                            Indikator : &nbsp;
                            {skpState.indikator?.indikator ?? "( no data )" }
                        </h3>
                    </div>
                    <div className="mr-0">
                        <PrimaryBtn loading={loading} onClick={() => openModalAction()} >
                            <PlusCircleIcon className="w-5 h-5" />
                            Tambah Rencana Aksi
                        </PrimaryBtn>
                    </div>
                </div>
                <StaticTable header={tableHeader()}>   
                {
                    renderTable()                    
                }
                </StaticTable>
                <MyModal  ModalTitle={formTitle} openModal={openModal} setOpenModal={setOpenModal} >
                    <div className="flex flex-col w-full p-4">
                        <MyTextarea 
                            id="langkah" 
                            name="langkah" 
                            label="Langkah" 
                            placeholder='Inputkan langkah...' 
                            value={formik.values.langkah} onChange={formik.handleChange} onBlur={formik.handleBlur}
                            error={(formik.errors.langkah && formik.touched.langkah) ? formik.errors.langkah : ""}
                        />
                        <MyInput id="satuan" name="satuan" 
                            label="Satuan" 
                            placeholder='Input satuan...' 
                            value={formik.values.satuan} onChange={formik.handleChange} onBlur={formik.handleBlur}
                            error={(formik.errors.satuan && formik.touched.satuan) ? formik.errors.satuan : ""}
                        />
                        <div className="block w-full py-2">
                            <h1 className="font-bold dark:text-white">Target per Triwulan (TW)</h1>
                        </div>
                        <div className="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-2 sm:gap-3 md:gap-5 py-2">
                            <MyInput id="target_tw1" name="target_tw1" 
                                label="TW ke-1" 
                                placeholder='Input target...'
                                value={formik.values.target_tw1} onChange={formik.handleChange} onBlur={formik.handleBlur}
                                error={(formik.errors.target_tw1 && formik.touched.target_tw1) ? formik.errors.target_tw1 : ""}
                            />
                            <MyInput id="target_tw2" name="target_tw2" 
                                label="TW ke-2" 
                                placeholder='Input target...'
                                value={formik.values.target_tw2} onChange={formik.handleChange} onBlur={formik.handleBlur}
                                error={(formik.errors.target_tw2 && formik.touched.target_tw2) ? formik.errors.target_tw2 : ""}
                            />
                            <MyInput id="target_tw3" name="target_tw3" 
                                label="TW ke-3" 
                                placeholder='Input target...'
                                value={formik.values.target_tw3} onChange={formik.handleChange} onBlur={formik.handleBlur}
                                error={(formik.errors.target_tw3 && formik.touched.target_tw3) ? formik.errors.target_tw3 : ""}
                            />
                            <MyInput id="target_tw4" name="target_tw4" 
                                label="TW ke-4" 
                                placeholder='Input target...'
                                value={formik.values.target_tw4} onChange={formik.handleChange} onBlur={formik.handleBlur}
                                error={(formik.errors.target_tw4 && formik.touched.target_tw4) ? formik.errors.target_tw4 : ""}
                            />
                        </div>
                        <MyTextarea 
                            id="keterangan" 
                            name="keterangan" 
                            label="Keterangan" 
                            placeholder='Inputkan keterangan...' 
                            value={formik.values.keterangan} onChange={formik.handleChange} onBlur={formik.handleBlur}
                            error={(formik.errors.keterangan && formik.touched.keterangan) ? formik.errors.keterangan : ""}
                        />
                    </div>
                    <div className="mt-5 sm:mt-6 flex justify-center">
                        <PrimaryBtn loading={loading} onClick={() => simpanData()} >
                            Simpan Data
                        </PrimaryBtn>
                    </div>
                </MyModal>
            </div>
        </Layout>
    )
}

const exampleData = [
    {
        id: 1,
        langkah: "Langkah 1",
        target_tw1: 1,
        target_tw2: 2,
        target_tw3: 3,
        target_tw4: 4,
    },
    {
        id: 2,
        langkah: "Langkah 2",
        target_tw1: 1,
        target_tw2: 2,
        target_tw3: 3,
        target_tw4: 4,
    }
]


export default SkpRenaksi