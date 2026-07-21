import React from 'react'
import Layout from '@/app/components/Layout/Layout'
import GoodNotes from "@assets/GoodNotes.png"
import PrimaryBtn from '@/app/components/Button/PrimaryBtn'
import { useSelector, useDispatch } from 'react-redux'
import { PlusCircleIcon, PencilSquareIcon, TrashIcon } from '@heroicons/react/24/outline'
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
import { getListDatamasterOpd, createDatamasterOpd, updateDatamasterOpd, deleteDatamasterOpd } from '@/redux/ducks/datamasteropd/action'

const Opd = () => {
    const datamasterOpdState = useSelector((state) => state.datamasterOpdState)
    const dispatch = useDispatch()
    const [openModal, setOpenModal] = React.useState(false)
    const [formTitle, setFormTitle] = React.useState("FORM TAMBAH DATA SATUAN")
    const [editId, setEditId] = React.useState("")

    const getDataTable = async (page = 1, per_page = 10, search = "") => {
        const response = await dispatch(getListDatamasterOpd({ page, per_page, search }))
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
    React.useEffect(() => { initFlowbite() },[datamasterOpdState])
    React.useEffect(() => { getDataTable() },[])

    const formik = useFormik({
        initialValues: {
            kode_opd: undefined,
            simpeg_opd_id: undefined,
            ikd_opd_id: undefined,
            nama_opd: undefined,
            alias_opd: undefined,
            order: 0,
            isActive: false
        },
        validationSchema: Yup.object({ 
            kode_opd:           Yup.string().required().strict(true),
            simpeg_opd_id:           Yup.string().required().strict(true),
            ikd_opd_id:           Yup.string().required().strict(true),
            nama_opd:           Yup.string().required().strict(true),
            alias_opd:           Yup.string().required().strict(true),
            order:           Yup.number().required().strict(true),
            isActive:       Yup.boolean().required()
        }),
        enableReinitialize: true
    })
    const openModalAction = () => {
        formik.resetForm()
        setEditId("")
        setFormTitle("FORM TAMBAH DATA OPD")
        setOpenModal(true)
    }

    const validationForm = async () => {
        //validation
        formik.setFieldTouched('kode_opd', true, true)
        formik.setFieldTouched('simpeg_opd_id', true, true)
        formik.setFieldTouched('ikd_opd_id', true, true)
        formik.setFieldTouched('nama_opd', true, true)
        formik.setFieldTouched('alias_opd', true, true)
        formik.setFieldTouched('order', true, true)
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
                kode_opd: form.kode_opd,
                simpeg_opd_id: form.simpeg_opd_id,
                ikd_opd_id: form.ikd_opd_id,
                nama_opd: form.nama_opd,
                alias_opd: form.alias_opd,
                order: form.order,
                is_active: form.isActive
            }
            
            // submit payload with dispatch action redux
            let response = null
            if(editId !== "") response = await dispatch(updateDatamasterOpd(editId, payload))
            else response = await dispatch(createDatamasterOpd(payload));
            if(response.status !== "failed"){
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
        formik.setFieldValue('kode_opd', data.kode_opd);
        formik.setFieldValue('simpeg_opd_id', data.simpeg_opd_id.toString());
        formik.setFieldValue('ikd_opd_id', data.ikd_opd_id.toString());
        formik.setFieldValue('nama_opd', data.nama_opd);
        formik.setFieldValue('alias_opd', data.alias_opd);
        formik.setFieldValue('order', data.order);
        formik.setFieldValue('isActive', data.is_active)

        setEditId(data.id)
        
        setFormTitle("FORM EDIT DATA OPD")
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
                const response = await dispatch(deleteDatamasterOpd(id))
                
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
                        <div className="lg:text-lg font-bold text-teal-500 dark:text-white">Data Master OPD</div>
                    </div>

                </div>
            </div>
            <div className="bg-white h-auto dark:bg-gray-800 rounded-lg drop-shadow-xl py-5 px-4 w-full flex min-h-[35rem]">
                <div className="flex flex-col w-full p-3 border border-teal-500/30 dark:border-gray-600 rounded-lg">
                    <div className="w-full flex">
                        <PrimaryBtn loading={datamasterOpdState.loading} 
                            onClick={()=> openModalAction()}>
                            <PlusCircleIcon className="w-5 h-5" />
                            Tambah Data OPD
                        </PrimaryBtn>
                    </div>
                    <TableSection getDataAction={getDataTable} pagination={datamasterOpdState.pagination}>
                        <MyTable>
                            <TableHeader>
                                <tr>
                                    <th scope="col" className="px-4 py-3 w-[3%]">No.</th>
                                    <th scope="col" className="px-4 py-3">Kode OPD</th>
                                    <th scope="col" className="px-4 py-3">Simpeg ID</th>
                                    <th scope="col" className="px-4 py-3">IKD ID</th>
                                    <th scope="col" className="px-4 py-3">Simonev ID</th>
                                    <th scope="col" className="px-4 py-3">Nama OPD</th>
                                    <th scope="col" className="px-4 py-3">Singkatan</th>
                                    <th scope="col" className="px-4 py-3 w-[10%]">Active</th>
                                    <th scope="col" className="px-4 py-3 w-[5%]">
                                        <span className="sr-only">Actions</span>
                                    </th>
                                </tr>
                            </TableHeader>
                            <TableBody>
                                {
                                    datamasterOpdState.loading ? 
                                    <tr className="border-b dark:border-gray-700">
                                        <td scope="row" className="px-4 py-5 bg-gray-100 dark:bg-gray-800 dark:text-white" colSpan="100%">
                                            <div className="flex flex-row justify-center w-full gap-12">
                                                <PacmanLoader size={10} color='gray' />
                                                Please Wait...
                                            </div>
                                        </td>
                                    </tr> : 
                                    (datamasterOpdState.list.length > 0 ? 
                                        datamasterOpdState.list.map((item, key) => (
                                        <tr key={item.id} className="border-b dark:border-gray-700">
                                            <th scope="row"
                                                className="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">{item.order}</th>
                                            <td className="px-4 py-3">
                                                {item.kode_opd}
                                            </td>
                                            <td className="px-4 py-3">
                                                {item.simpeg_opd_id}
                                            </td>
                                            <td className="px-4 py-3">
                                                {item.ikd_opd_id}
                                            </td>
                                            <td className="px-4 py-3">
                                                {item.simonev_opd_id}
                                            </td>
                                            <td className="px-4 py-3">
                                                {item.nama_opd}
                                            </td>
                                            <td className="px-4 py-3">
                                                {item.alias_opd}
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
                            <MyInput id="order" name="order" label="Urutan" type="number" placeholder='Inputkan urutan...'
                                value={formik.values.order} onChange={formik.handleChange} onBlur={formik.handleBlur}
                                error={(formik.errors.order && formik.touched.order) ? formik.errors.order : ""} 
                            />
                            <MyInput id="kode_opd" name="kode_opd" label="Kode OPD" type="text" placeholder='Inputkan kode opd...'
                                value={formik.values.kode_opd} onChange={formik.handleChange} onBlur={formik.handleBlur}
                                error={(formik.errors.kode_opd && formik.touched.kode_opd) ? formik.errors.kode_opd : ""} 
                            />
                            <MyInput id="simpeg_opd_id" name="simpeg_opd_id" label="SIMPEG ID" type="text" placeholder='Inputkan kode opd untuk simpeg...'
                                value={formik.values.simpeg_opd_id} onChange={formik.handleChange} onBlur={formik.handleBlur}
                                error={(formik.errors.simpeg_opd_id && formik.touched.simpeg_opd_id) ? formik.errors.simpeg_opd_id : ""} 
                            />
                            <MyInput id="ikd_opd_id" name="ikd_opd_id" label="IKD ID" type="text" placeholder='Inputkan kode opd untuk IKD...'
                                value={formik.values.ikd_opd_id} onChange={formik.handleChange} onBlur={formik.handleBlur}
                                error={(formik.errors.ikd_opd_id && formik.touched.ikd_opd_id) ? formik.errors.ikd_opd_id : ""} 
                            />
                            <MyInput id="nama_opd" name="nama_opd" label="Nama" type="text" placeholder='Inputkan nama opd...'
                                value={formik.values.nama_opd} onChange={formik.handleChange} onBlur={formik.handleBlur}
                                error={(formik.errors.nama_opd && formik.touched.nama_opd) ? formik.errors.satuan : ""} 
                            />
                            <MyInput id="alias_opd" name="alias_opd" label="Singkatan" type="text" placeholder='Inputkan nama singkatan atau nama lain opd...'
                                value={formik.values.alias_opd} onChange={formik.handleChange} onBlur={formik.handleBlur}
                                error={(formik.errors.alias_opd && formik.touched.alias_opd) ? formik.errors.satuan : ""} 
                            />
                            <div className="flex w-fill justify-center">
                                <MyToggle id="isActive" name="isActive" label="Aktif" value={formik.values.isActive}
                                    error={formik.errors.isActive}
                                    onChange={formik.handleChange} />
                            </div>
                        </div>
                        
                        <div className="mt-5 sm:mt-6 flex justify-center">
                            <PrimaryBtn onClick={()=> simpanData()} loading={datamasterOpdState.loading} >
                                Simpan Data
                            </PrimaryBtn>
                        </div>
                    </MyModal>
                </div>
            </div>
        </Layout>
    )
}

export default Opd