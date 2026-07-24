import Layout from '@/app/components/Layout/Layout'
import React from 'react'
import GoodNotes from "@assets/GoodNotes.png"
import { useSelector, useDispatch } from 'react-redux'
import { PlusCircleIcon, PencilSquareIcon, TrashIcon, EyeIcon } from '@heroicons/react/24/outline'
import { PlayIcon } from '@heroicons/react/24/solid'
import PrimaryBtn from '@/app/components/Button/PrimaryBtn'
import { MyTable, TableHeader, TableSection, TableBody } from '@/app/components/Table'
import { PacmanLoader } from 'react-spinners'
import Swal from 'sweetalert2'
import MyModal from '@/app/components/Form/MyModal'
import MyInput from '@/app/components/Form/MyInput'
import MySelect from '@/app/components/Form/MySelect'
import { Link } from 'react-router-dom'
import { getListPeriodeSkp, createPeriodeSkp } from '@/redux/ducks/skp/action'
import { indonesianDate } from '@/app/helper/Common'
import { useFormik } from 'formik'
import * as Yup from "yup"
import { initFlowbite } from 'flowbite'

const SkpList = () => {
    const [openModal, setOpenModal] = React.useState(false)
    const [formTitle, setFormTitle] = React.useState("FORM TAMBAH PERIODE SKP")
    const authState = useSelector((state) => state.authState)
    const skpState = useSelector((state) => state.skpState)
    const dispatch = useDispatch()

    const getDataTable = () => {
        dispatch(getListPeriodeSkp())
    }
    React.useEffect(() => {
        getDataTable()
    }, [])
    React.useEffect(() => {
        initFlowbite()
    }, [skpState.list_periode])
    const openModalAction = () => {
        formik.resetForm();
        setFormTitle("FORM TAMBAH PERIODE SKP")
        setOpenModal(true);
    }

    const formik = useFormik({
        initialValues: {
            periode_awal: "",
            periode_akhir: "",
            pendekatan: ""
        },
        validationSchema: Yup.object({ 
            periode_awal:           Yup.string().required().strict(true),
            periode_akhir:           Yup.string().required().strict(true),
            pendekatan:           Yup.string().required().strict(true),
        }),
        enableReinitialize: true
    })
    const validationForm = async () => {
        //validation
        formik.setFieldTouched('periode_awal', true, true)
        formik.setFieldTouched('periode_akhir', true, true)
        formik.setFieldTouched('pendekatan', true, true)
        const errors = await formik.validateForm();

        return errors
    }

    const simpanData= async ()=> {
        
        const errors = await validationForm()
        
        if (Object.keys(errors).length === 0) {
            // Form is valid, do any success call, mapping payload before submit
            const form = formik.values
            const payload = {
                periode_awal: form.periode_awal,
                periode_akhir: form.periode_akhir,
                pendekatan: form.pendekatan
            }
            // submit payload with dispatch action redux
            let response = await dispatch(createPeriodeSkp(payload));
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
    
    let loading = skpState.loading
    return (
        <Layout>
            <div className="bg-white h-auto dark:bg-gray-800 rounded-lg drop-shadow-xl py-1 px-3 w-full">
                <div className="dark:text-white">
                    <div className="flex flex-row items-center gap-3">
                        <div>
                            <img src={GoodNotes} alt="Pengukuran Pegawai" className="object-contain" />
                        </div>
                        <div className="flex flex-row items-center gap-4 lg:text-lg font-bold text-teal-500 dark:text-white">
                        <Link to="/dashboard" className='hover:text-teal-200 hover:dark:text-teal-500'>{authState.biodata.name} </Link>
                        <PlayIcon className='w-5 h-5' /> Periode
                        </div>
                    </div>
                </div>
            </div>
            <div className="bg-white h-auto dark:bg-gray-800 rounded-lg drop-shadow-xl py-1 sm:px-3 px-1 w-full flex flex-col lg:min-h-[35rem]">
                <div className="flex flex-row justify-between w-full p-4">
                    <div>
                        <h1 className="font-semibold text-lg dark:text-white">
                            Daftar Periode SKP
                        </h1>
                        <h2 className="text-sm text-gray-500 dark:text-gray-400">
                            Sasaran Kinerja Pegawai
                        </h2>
                    </div>
                    <div className="mr-0">
                        <PrimaryBtn loading={false} onClick={() => openModalAction()} >
                            <PlusCircleIcon className="w-5 h-5" />
                            Tambah Periode SKP
                        </PrimaryBtn>
                    </div>
                </div>
                    <TableSection getDataAction={() => null} pagination={{ page: 1, per_page: 10 }}>
                        <MyTable>
                            <TableHeader>
                            <tr>
                            <th scope="col" className="px-4 py-3 text-center w-[2%]">No.</th>
                                <th scope="col" className="px-4 py-3 text-center w-[25%]">Periode</th>
                                <th scope="col" className="px-4 py-3 text-center">Pendekatan</th>
                                {/* <th scope="col" className="px-4 py-3 text-center">Atasan</th> */}
                                <th scope="col" className="px-4 py-3 w-[10%]">
                                    <span className="sr-only">Actions</span>
                                </th>
                            </tr>
                            </TableHeader>
                            <TableBody>
                            {
                                loading ? 
                                <tr className="border-b dark:border-gray-700">
                                    <td scope="row" className="px-4 py-5 bg-gray-100 dark:bg-gray-800 dark:text-white" colSpan="100%">
                                        <div className="flex flex-row justify-center w-full gap-12">
                                            <PacmanLoader size={10} color='gray' />
                                            Please Wait...
                                        </div>
                                    </td>
                                </tr> : 
                                (skpState.list_periode.length > 0 ? 
                                    skpState.list_periode.map((item, key) =>(
                                        <tr key={item.id} className="border-b dark:border-gray-700 odd:bg-gray-100 dark:odd:bg-gray-900">
                                            <th scope="row"
                                                className="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white align-top">{key+1}</th>
                                            <td className="px-4 py-3 text-center">
                                            <Link to={`/pegawai/skp/${item.id}`} className='hover:text-black hover:dark:text-white hover:font-bold'>
                                                {indonesianDate(item.periode_awal)} - {indonesianDate(item.periode_akhir)}
                                                </Link>
                                            </td>
                                            <td className="px-4 py-3 text-center">
                                                {item.pendekatan ?? '-'}
                                            </td>
                                            {/* <td className="px-4 py-3 text-center">
                                                {item.atasan ?? '-'}
                                            </td> */}
                                            
                                            <td className="px-4 py-3 flex items-center justify-end">
                                                <button id={`btn-${item.id}`} data-dropdown-toggle={`toggle-btn${item.id}`}
                                                    className="inline-flex items-center p-0.5 text-sm font-medium text-center text-gray-500 hover:text-gray-800 rounded-lg focus:outline-none dark:text-gray-400 dark:hover:text-gray-100"
                                                    type="button">
                                                    <svg className="w-5 h-5" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <path
                                                            d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM16 12a2 2 0 100-4 2 2 0 000 4z" />
                                                    </svg>
                                                </button>
                                                <div id={`toggle-btn${item.id}`}
                                                    className="hidden z-10 w-44 bg-gray-50 rounded divide-y divide-gray-100 drop-shadow-lg dark:bg-gray-700 dark:divide-gray-600">
                                                    <ul className="py-1 text-sm text-gray-700 dark:text-gray-200"
                                                        aria-labelledby={`btn-${item.id}`}>
                                                        <li>
                                                            <Link to={`/pegawai/skp/${item.id}`}
                                                                className="flex gap-1 py-2 px-4 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">
                                                                <EyeIcon className='w-5 h-5' />
                                                                Detail SKP
                                                            </Link>
                                                        </li>
                                                        {/* <li>
                                                            <a href="#" onClick={() => editAction(item)}
                                                                className="flex gap-1 py-2 px-4 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">
                                                                <PencilSquareIcon className='w-5 h-5' />
                                                                Edit
                                                            </a>
                                                        </li> */}
                                                    </ul>
                                                    {/* <div className="py-1">
                                                        <a href="#" onClick={() => deleteAction(item.id)}
                                                            className="flex gap-1 py-2 px-4 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">
                                                            <TrashIcon className='w-5 h-5' />
                                                            Hapus
                                                        </a>
                                                    </div> */}
                                                </div>
                                            </td>
                                        </tr>
                                    ))
                                    :
                                <tr className="border-b dark:border-gray-700">
                                    <td scope="row" className="px-4 py-3 text-center" colSpan="100%">No Data</td>
                                </tr>
                                )
                            }
                            </TableBody>
                        </MyTable>
                    </TableSection>
            </div>
            <MyModal ModalTitle={formTitle} openModal={openModal} setOpenModal={setOpenModal} >
                <div className="flex flex-col w-full p-4">
                    {/* <div className="flex gap-4 mb-4">
                        <div className="w-fulll md:w-1/2">
                            <div className="font-semibold text-teal-500">
                                JENIS PEGAWAI
                            </div>
                            <div className="text-gray-400">
                                Pegawai
                            </div>
                        </div>
                        <div className="w-fulll md:w-1/2">
                            <div className="font-semibold text-teal-500">
                                ATASAN
                            </div>
                            <div className="text-gray-400">
                                LIZDA HANDAYANI <br />
                                Kepala BIDANG APLIKASI INFORMATIKA
                            </div>
                        </div>
                    </div>
                    <div className="flex gap-4 mb-4">
                        <div className="w-fulll md:w-1/2">
                            <div className="font-semibold text-teal-500">
                                UNIT KERJA ANDA
                            </div>
                            <div className="text-gray-400">
                                BIDANG APLIKASI INFORMATIKA
                            </div>
                        </div>
                        <div className="w-fulll md:w-1/2">
                            <div className="font-semibold text-teal-500">
                                UNIT KERJA ATASAN
                            </div>
                            <div className="text-gray-400">
                                DINAS KOMUNIKASI, INFORMATIKA DAN STATISTIK
                            </div>
                        </div>
                    </div> */}
                    <MyInput id="periode_awal" name="periode_awal" label="Periode Awal"
                        type='date'
                        error={(formik.errors.periode_awal && formik.touched.periode_awal) ? formik.errors.periode_awal : ""}
                        onChange={formik.handleChange} onBlur={formik.handleBlur}
                        value={formik.values.periode_awal} />
                    <MyInput id="periode_akhir" name="periode_akhir" label="Periode Akhir"
                        type="date"
                        error={(formik.errors.periode_akhir && formik.touched.periode_akhir) ? formik.errors.periode_akhir : ""}
                        onChange={formik.handleChange} onBlur={formik.handleBlur}
                        value={formik.values.periode_akhir} />
                    <MySelect id="pendekatan" name="pendekatan" label="Pendekatan"
                        placeholder="pilih pendakatan..."
                        error={(formik.errors.pendekatan && formik.touched.pendekatan) ? formik.errors.pendekatan : ""}
                        onChange={formik.handleChange} onBlur={formik.handleBlur}
                        options={pendekatanOptions}
                        value={formik.values.pendekatan} />
                </div>
                
                <div className="mt-5 sm:mt-6 flex justify-center">
                    <PrimaryBtn onClick={()=> simpanData()} loading={loading} >
                        Simpan Data
                    </PrimaryBtn>
                </div>
            </MyModal>
        </Layout>
    )
}
const pendekatanOptions = [
    { value: "Kuantitatif", name: "Kuantitatif" },
    { value: "Kualitatif", name: "Kualitatif" },
]

export default SkpList