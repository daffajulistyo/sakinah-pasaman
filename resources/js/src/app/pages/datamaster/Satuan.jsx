import Layout from '@/app/components/Layout/Layout'
import React from 'react'
import GoodNotes from "@assets/GoodNotes.png"
import PrimaryBtn from '@/app/components/Button/PrimaryBtn'
import { useSelector, useDispatch } from 'react-redux'
import { PlusCircleIcon, PencilSquareIcon, TrashIcon } from '@heroicons/react/24/outline'
import { getListDatamasterSatuan, createDatamasterSatuan, updateDatamasterSatuan, deleteDatamasterSatuan } from '@/redux/ducks/datamastersatuan/action'
import { MyTable, TableHeader, TableSection, TableBody } from '@/app/components/Table'
import Swal from 'sweetalert2'
import { PacmanLoader } from 'react-spinners'
import { useNavigate } from 'react-router-dom'
import { initFlowbite } from 'flowbite'
import { useFormik } from 'formik'
import * as Yup from "yup"
import MyModal from '@/app/components/Form/MyModal'
import MyInput from '@/app/components/Form/MyInput'
import MyToggle from '@/app/components/Form/MyToggle'

const Satuan = () => {
    const datamasterSatuanState = useSelector((state) => state.datamasterSatuanState)
    const dispatch = useDispatch()
    const [openModal, setOpenModal] = React.useState(false)
    const [formTitle, setFormTitle] = React.useState("FORM TAMBAH DATA SATUAN")
    const [editId, setEditId] = React.useState("")
    const navigate = useNavigate()

    const getDataTable = async (page = 1, per_page = 10, search = "") => {
        const response = await dispatch(getListDatamasterSatuan({ page, per_page, search }))
        if(response.error !== null)
        {
            Swal.fire({
                icon: 'error',
                title: "something went wrong",
                showConfirmButton: true,
                confirmButtonText: 'Refresh Halaman',
                timer: 1500
            }).then(async (result) => {
                if(result.isConfirmed) window.location.reload()
            })
        }
    }
    React.useEffect(() => { initFlowbite() },[datamasterSatuanState])
    React.useEffect(() => { getDataTable() },[])

    const formik = useFormik({
        initialValues: {
            satuan: undefined,
            isActive: false
        },
        validationSchema: Yup.object({ 
            satuan:           Yup.string().required().strict(true),
            isActive:       Yup.boolean().required()
        }),
        enableReinitialize: true
    })
    const openModalAction = () => {
        formik.resetForm()
        setEditId("")
        setFormTitle("FORM TAMBAH DATA SATUAN")
        setOpenModal(true)
    }

    
    const validationForm = async () => {
        //validation
        formik.setFieldTouched('satuan', true, true)
        formik.setFieldTouched('isActive', true, true)
        const errors = await formik.validateForm();

        return errors
    }

    const simpanData= async ()=> {
        
        const errors = await validationForm()
        if (Object.keys(errors).length === 0) {
            // Form is valid, do any success call, mapping payload before submit
            const form = formik.values
            const payload = {
                satuan: form.satuan,
                is_active: form.isActive
            }
            
            // submit payload with dispatch action redux
            let response = null
            if(editId !== "") response = await dispatch(updateDatamasterSatuan(editId, payload))
            else response = await dispatch(createDatamasterSatuan(payload));
            if(response.error === null){
                Swal.fire({
                    icon: 'success',
                    title: response.data.message,
                    showConfirmButton: false,
                    timer: 1500
                })
            
                setOpenModal(false)
                dispatch(getDataTable())
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
        formik.setFieldValue('satuan', data.satuan);
        formik.setFieldValue('isActive', data.is_active)

        setEditId(data.id)
        
        setFormTitle("FORM EDIT DATA SATUAN")
        setOpenModal(true);
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
                const response = await dispatch(deleteDatamasterSatuan(id))
                
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
    return (
        <Layout>
            <div className="bg-white h-auto dark:bg-gray-800 rounded-lg drop-shadow-xl py-1 px-3 w-full">
                <div className="dark:text-white flex w-full justify-between">
                    <div className="flex flex-row items-center gap-3">
                        <div>
                            <img src={GoodNotes} alt="Data Master" className="object-contain" />
                        </div>
                        <div className="lg:text-lg font-bold text-teal-500 dark:text-white">Data Master Satuan</div>
                    </div>

                </div>
            </div>
            <div className="bg-white h-auto dark:bg-gray-800 rounded-lg drop-shadow-xl py-5 px-4 w-full flex min-h-[35rem]">
                <div className="flex flex-col w-full p-3 border border-teal-500/30 dark:border-gray-600 rounded-lg">
                    <div className="w-full flex">
                        <PrimaryBtn loading={datamasterSatuanState.loading} 
                            onClick={()=> openModalAction()}>
                            <PlusCircleIcon className="w-5 h-5" />
                            Tambah Data Satuan
                        </PrimaryBtn>
                    </div>
                    
                    <TableSection getDataAction={getDataTable} pagination={datamasterSatuanState.pagination}>
                        <MyTable>
                            <TableHeader>
                                <tr>
                                    <th scope="col" className="px-4 py-3 w-[3%]">No.</th>
                                    <th scope="col" className="px-4 py-3">Satuan</th>
                                    <th scope="col" className="px-4 py-3 w-[15%]">Active</th>
                                    <th scope="col" className="px-4 py-3 w-[10%]">
                                        <span className="sr-only">Actions</span>
                                    </th>
                                </tr>
                            </TableHeader>
                            <TableBody>
                                {
                                    datamasterSatuanState.loading ? 
                                    <tr className="border-b dark:border-gray-700">
                                        <td scope="row" className="px-4 py-5 bg-gray-100 dark:bg-gray-800 dark:text-white" colSpan="100%">
                                            <div className="flex flex-row justify-center w-full gap-12">
                                                <PacmanLoader size={10} color='gray' />
                                                Please Wait...
                                            </div>
                                        </td>
                                    </tr> : 
                                    (datamasterSatuanState.list.length > 0 ? 
                                        datamasterSatuanState.list.map((item, key) => (
                                        <tr key={item.id} className="border-b dark:border-gray-700">
                                            <th scope="row"
                                                className="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">{key + 1}</th>
                                            <td className="px-4 py-3">
                                                {item.satuan}
                                            </td>
                                            <td className="px-4 py-3">{item.is_active ? "Aktif" : "Non-Aktif"}</td>
                                            <td className="px-4 py-3 flex items-center justify-end">
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
                                        <td scope="row" className="px-4 py-3 text-center" colSpan="100%">No Data</td>
                                    </tr>
                                    )
                                }
                                    
                            </TableBody>
                        </MyTable>
                    </TableSection>
                    <MyModal  ModalTitle={formTitle} openModal={openModal} setOpenModal={setOpenModal} >
                        <div className="flex flex-col w-full p-4">
                            <MyInput id="satuan" name="satuan" label="Satuan" type="text" placeholder='Inputkan satuan...'
                                value={formik.values.satuan} onChange={formik.handleChange} onBlur={formik.handleBlur}
                                error={(formik.errors.satuan && formik.touched.satuan) ? formik.errors.satuan : ""} 
                            />
                            <div className="flex w-fill justify-center">
                                <MyToggle id="isActive" name="isActive" label="Aktif" value={formik.values.isActive}
                                    error={formik.errors.isActive}
                                    onChange={formik.handleChange} />
                            </div>
                        </div>
                        
                        <div className="mt-5 sm:mt-6 flex justify-center">
                            <PrimaryBtn onClick={()=> simpanData()} loading={datamasterSatuanState.loading} >
                                Simpan Data
                            </PrimaryBtn>
                        </div>
                    </MyModal>
                </div>
            </div>
        </Layout>
    )
}

export default Satuan