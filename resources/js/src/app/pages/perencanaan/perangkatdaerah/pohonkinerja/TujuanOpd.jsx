import React from 'react'
import Layout from '@/app/components/Layout/Layout'
import HeaderPohonKinerjaOpd from '@/app/components/HeaderPohonKinerjaOpd'
import TabMenuPohonKinerjaOpd from '@/app/components/TabMenuPohonKinerjaOpd'
import PrimaryBtn from '@/app/components/Button/PrimaryBtn'
import { PlusCircleIcon, PencilSquareIcon, TrashIcon } from '@heroicons/react/24/outline'
import { MyTable, TableHeader, TableSection, TableBody } from '@/app/components/Table'
import { useNavigate, Link } from 'react-router-dom'
import { initFlowbite } from 'flowbite'
import MyInput from '@/app/components/Form/MyInput'
import MyToggle from '@/app/components/Form/MyToggle'
import MyModal from '@/app/components/Form/MyModal'
import MyTextarea from '@/app/components/Form/MyTextarea'
import MySelect2 from '@/app/components/Form/MySelect2'
import Swal from 'sweetalert2'
import { PacmanLoader } from 'react-spinners'
import { getListSasaranDiampuOpd, getListTujuanOpd, createTujuanOpd, updateTujuanOpd, deleteTujuanOpd } from '@/redux/ducks/tujuanopd/action'
import { useSelector, useDispatch } from 'react-redux'
import { useFormik } from 'formik'
import * as Yup from "yup"

const TujuanOpd = () => {
    const navigate = useNavigate()
    const dispatch = useDispatch()
    React.useEffect(() => { initFlowbite() },[])
    const [openModal, setOpenModal] = React.useState(false)
    const [formTitle, setFormTitle] = React.useState("FORM TAMBAH TUJUAN")
    const tujuanOpdState = useSelector((state) => state.tujuanOpdState)
    const [editId, setEditId] = React.useState("")
    React.useEffect(() => {
        dispatch(getListSasaranDiampuOpd())
    },[])
    const getDataTable = async (page = 1, per_page = 10, search = "") => {
        const response = await dispatch(getListTujuanOpd({ page, per_page, search}))
        if(response.error !== null){
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
    React.useEffect(() => { getDataTable() }, [])
    React.useEffect(() => { initFlowbite() }, [tujuanOpdState.list])
    const formik = useFormik({
        initialValues: {
            order: 0,
            tujuan: undefined,
            isActive: false
        },
        validationSchema: Yup.object({ 
            order:           Yup.number().required().strict(true),
            tujuan:           Yup.string().required().strict(true),
            isActive:       Yup.boolean().required()
        }),
        enableReinitialize: true
    })
    const resetForm = () => {
        setSelectedSasaran(null)
        setSelectedJenisSasaran(null)
    }
    const openModalAction = () => {
        setEditId("")
        resetForm()
        setFormTitle("FORM TAMBAH TUJUAN")
        setOpenModal(true);
    }
    const sasaranOptions = () => {
        if(tujuanOpdState.list_sasaranDiampu){
            if(tujuanOpdState.list_sasaranDiampu.length > 0){
                return tujuanOpdState.list_sasaranDiampu.map((item) => ({ label: item.sasaran, value: item.id }))
            }
            return []
        }
        return []
    }
    const [selectedSasaran, setSelectedSasaran] = React.useState(null)
    const sasaranOnChange = (e) => {
        setSelectedSasaran(e)
        formik.setFieldValue('tujuan',e.label);
    }
    const jenisSasaranList = () => ([
        {
            value: true,
            label: "Direct"
        },
        {
            value: false,
            label: "Indirect"
        },
    ])
    const [selectedJenisSasaran, setSelectedJenisSasaran] = React.useState({ value: true, label: "Direct" })
    const jenisSasaranOnchange = (e) => {
        setSelectedJenisSasaran(e)
        
        setLockTujuan(e.value ?? true)
        formik.setFieldValue('tujuan', selectedSasaran.label ?? "")
    }
    const [tujuanOpd, setTujuanOpd] = React.useState("")
    const [lockTujuan, setLockTujuan] = React.useState(true)
    const findSasaran = (id) => {
        let search = sasaranOptions().find((item) => { return item.value === id })
        return search
        
    }
    const validationForm = async () => {
        //validation
        formik.setFieldTouched('order', true, true)
        formik.setFieldTouched('tujuan', true, true)
        const errors = await formik.validateForm();

        return errors
    }

    const simpanData= async ()=> {
        
        const errors = await validationForm()
        if (Object.keys(errors).length === 0) {
            // Form is valid, do any success call, mapping payload before submit
            const form = formik.values
            const payload = {
                pohon_kinerja_sasaran_id: selectedSasaran.value,
                order: form.order,
                tujuan: form.tujuan,
                is_direct: selectedJenisSasaran.value,
                is_active: form.isActive
            }
            
            // submit payload with dispatch action redux
            let response = null
            if(editId !== "") response = await dispatch(updateTujuanOpd(editId, payload))
            else response = await dispatch(createTujuanOpd(payload));
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
        resetForm()
        
        setSelectedSasaran(findSasaran(data.pohon_kinerja_sasaran_id))
        formik.setFieldValue('tujuan', data.tujuan)
        formik.setFieldValue('order', data.order)
        setSelectedJenisSasaran({ value: data.is_direct, label: data.is_direct ? "Direct":"Indirect" })
        setLockTujuan(data.is_direct)
        setEditId(data.id)
        
        setFormTitle("FORM EDIT TUJUAN")
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
                const response = await dispatch(deleteTujuanOpd(id))
                
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
            <HeaderPohonKinerjaOpd />
            <div className="bg-white h-auto dark:bg-gray-800 rounded-lg drop-shadow-xl py-5 px-4 w-full flex min-h-[35rem]">
                <TabMenuPohonKinerjaOpd active='tujuan' />
                <div className="flex flex-col w-full mt-14 p-3 border border-teal-500/30 dark:border-gray-600 rounded-lg">
                <div className="w-full text-center text-lg text-teal-500 dark:text-white font-bold mb-3">TUJUAN PERANGKAT DAERAH</div>
                    <div className="w-full flex">
                        <PrimaryBtn loading={false} onClick={() => openModalAction()} >
                            <PlusCircleIcon className="w-5 h-5" />
                            Tambah Tujuan
                        </PrimaryBtn>
                    </div>
                    <TableSection getDataAction={() => null} pagination={{ page: 1, per_page:10 }}>
                        <MyTable>
                            <TableHeader>
                            <tr>
                                <th scope="col" className="px-4 py-3 w-[3%]">No.</th>
                                <th scope="col" className="px-4 py-3">Sasaran yang diampu</th>
                                <th scope="col" className="px-4 py-3">Jenis Sasaran</th>
                                <th scope="col" className="px-4 py-3">Tujuan Opd</th>
                                <th scope="col" className="px-4 py-3">Active</th>
                                <th scope="col" className="px-4 py-3 w-[10%]">
                                    <span className="sr-only">Actions</span>
                                </th>
                            </tr>
                            </TableHeader>
                            <TableBody>
                            {
                                tujuanOpdState.loading ? 
                                <tr className="border-b dark:border-gray-700">
                                    <td scope="row" className="px-4 py-5 bg-gray-100 dark:bg-gray-800 dark:text-white" colSpan="100%">
                                        <div className="flex flex-row justify-center w-full gap-12">
                                            <PacmanLoader size={10} color='gray' />
                                            Please Wait...
                                        </div>
                                    </td>
                                </tr> : 
                                (tujuanOpdState.list.length > 0 ? 
                                    tujuanOpdState.list.map((item, key) => (
                                        <tr key={item.id} className="border-b dark:border-gray-700">
                                            <th scope="row"
                                                className="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white text-right">{item.order}</th>
                                            
                                            <td className="px-4 py-3">{item.sasaran}</td>
                                            <td className="px-4 py-3">{item.is_direct ? "Direct" : "Indirect"}</td>
                                            <td className="px-4 py-3">
                                                <Link to={`/perencanaan/opd/pohonkinerja/sasaran?tujuanId=${item.id}`} className="hover:text-blue-500 hover:font-bold" >
                                                    {item.tujuan}
                                                </Link>
                                            </td>
                                            <td className="px-4 py-3 align-top">{item.is_active ? "Aktif" : "Non-Aktif"}</td>
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
                                                    className="hidden z-10 w-48 bg-gray-50 rounded divide-y divide-gray-100 drop-shadow-lg dark:bg-gray-700 dark:divide-gray-600">
                                                    <ul className="py-1 text-sm text-gray-700 dark:text-gray-200"
                                                        aria-labelledby={`btn-${key}`}>
                                                        <li>
                                                            <Link to={`/perencanaan/opd/pohonkinerja/indikator?tujuanId=${item.id}`}
                                                                className="flex gap-1 py-2 px-4 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">
                                                                <PlusCircleIcon className="w-5 h-5" />
                                                                Tambahkan Indikator
                                                            </Link>
                                                        </li>
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
                    <MyModal ModalTitle={formTitle} openModal={openModal} setOpenModal={setOpenModal} >
                        <div className="flex flex-col w-full p-4">
                            <MySelect2
                                id="sasaranSelector"
                                label="Sasaran yang diampu"
                                options={sasaranOptions()}
                                value={selectedSasaran}
                                onChange={sasaranOnChange}
                            />
                            <MySelect2
                                id="jenissasaran"
                                label="Jenis Sasaran"
                                options={jenisSasaranList()}
                                value={selectedJenisSasaran}
                                onChange={jenisSasaranOnchange}
                            />
                            <MyTextarea 
                                id="tujuan" 
                                name="tujuan" 
                                label="Tujuan" 
                                placeholder='Inputkan tujuan...' 
                                value={formik.values.tujuan} 
                                onChange={formik.handleChange}
                                onBlur={formik.handleBlur}
                                error={(formik.errors.tujuan && formik.touched.tujuan) ? formik.errors.tujuan : ""}
                                disabled={lockTujuan}
                            />
                            <MyInput id="order" name="order" label="Urutan"
                                type="number" placeholder='Inputkan nomor urut...'
                                value={formik.values.order} onChange={formik.handleChange} onBlur={formik.handleBlur}
                                error={(formik.errors.order && formik.touched.order) ? formik.errors.order : ""} 
                            />
                            <div className="flex w-fill justify-center">
                                <MyToggle id="isActive" name="isActive" label="Aktif" value={formik.values.isActive}
                                    error={formik.errors.isActive}
                                    onChange={formik.handleChange} 
                                />
                            </div>
                        </div>
                        
                        <div className="mt-5 sm:mt-6 flex justify-center">
                            <PrimaryBtn onClick={()=> simpanData()} loading={false} >
                                Simpan Data
                            </PrimaryBtn>
                        </div>
                    </MyModal>
                </div>
            </div>
        </Layout>
    )
}

export default TujuanOpd