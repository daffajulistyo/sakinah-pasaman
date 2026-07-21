import React from 'react'
import Layout from '@/app/components/Layout/Layout'
import HeaderPohonKinerja from '@/app/components/HeaderPohonKinerja'
import TabMenuPohonKinerja from '@/app/components/TabMenuPohonKinerja'
import PrimaryBtn from '@/app/components/Button/PrimaryBtn'
import { PencilSquareIcon, PlusCircleIcon, TrashIcon } from '@heroicons/react/24/outline'
import { MyTable, TableHeader, TableSection, TableBody } from '@/app/components/Table'
import { useSelector, useDispatch } from 'react-redux'
import { useNavigate, useSearchParams, Link } from 'react-router-dom'
import { getListSasaranKdh, createSasaranKdh, updateSasaranKdh, deleteSasaranKdh } from '@/redux/ducks/sasarankdh/action'
import { getTujuanKdh } from '@/redux/ducks/tujuankdh/action'
import { initFlowbite } from 'flowbite'
import { useFormik } from 'formik'
import * as Yup from 'yup'
import Swal from 'sweetalert2'
import { PacmanLoader } from 'react-spinners'
import MyModal from '@/app/components/Form/MyModal'
import MyInput from '@/app/components/Form/MyInput'
import MyTextarea from '@/app/components/Form/MyTextarea'
import MyToggle from '@/app/components/Form/MyToggle'

const Sasaran = () => {
    const navigate = useNavigate()
    const tujuanKdhState = useSelector((state) => state.tujuanKdhState)
    const sasaranKdhState = useSelector((state) => state.sasaranKdhState)
    const dispatch = useDispatch()
    const [searchParams, setSearchParams] = useSearchParams()
    const [openModal, setOpenModal] = React.useState(false)
    const [formTitle, setFormTitle] = React.useState("FORM TAMBAH SASARAN")
    const [selectedTujuan, setSelectedTujuan] = React.useState("Tujuan")
    const [editId, setEditId] = React.useState("")
    const selectedVisiId = searchParams.get("visiId")
    const selectedMisiId = searchParams.get("misiId")
    const selectedTujuanId = searchParams.get('tujuanId')
    React.useEffect(() => { initFlowbite() },[sasaranKdhState])
    const getDataTable = async (page = 1, per_page = 10, search = "") => {
        const response = await dispatch(getListSasaranKdh({ page, per_page, search, tujuan_id: selectedTujuanId ?? "" }))
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
    React.useEffect(() => { tujuanKdhState.data ? setSelectedTujuan(tujuanKdhState.data.tujuan) : null },[tujuanKdhState.data])
    React.useEffect(() => {

        selectedTujuanId ? 
        dispatch( getTujuanKdh(selectedTujuanId ?? "") ).then((result) => {
            if(result.error === null){
                getDataTable()
            }
            else navigate('/perencanaan/kdh/pohonkinerja/visi')
        }) : navigate('/perencanaan/kdh/pohonkinerja/visi')

    },[])

    const formik = useFormik({
        initialValues: {
            order: 0,
            sasaran: undefined,
            isActive: false
        },
        validationSchema: Yup.object({ 
            order:           Yup.number().required().strict(true),
            sasaran:           Yup.string().required().strict(true),
            isActive:       Yup.boolean().required()
        }),
        enableReinitialize: true
    })

    const openModalAction = () => {
        formik.resetForm();
        setEditId("")
        setFormTitle("FORM TAMBAH SASARAN")
        setOpenModal(true);
    }
    
    const validationForm = async () => {
        //validation
        formik.setFieldTouched('order', true, true)
        formik.setFieldTouched('sasaran', true, true)
        const errors = await formik.validateForm();

        return errors
    }

    const simpanData= async ()=> {
        
        const errors = await validationForm()
        if (Object.keys(errors).length === 0) {
            // Form is valid, do any success call, mapping payload before submit
            const form = formik.values
            const payload = {
                pohon_kinerja_tujuan_id: selectedTujuanId,
                order: form.order,
                sasaran: form.sasaran,
                is_active: form.isActive
            }
            
            // submit payload with dispatch action redux
            let response = null
            if(editId !== "") response = await dispatch(updateSasaranKdh(editId, payload))
            else response = await dispatch(createSasaranKdh(payload));
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
        formik.setFieldValue('order', data.order);
        formik.setFieldValue('sasaran', data.sasaran);
        formik.setFieldValue('isActive', data.is_active)

        setEditId(data.id)
        
        setFormTitle("FORM EDIT SASARAN")
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
                const response = await dispatch(deleteSasaranKdh(id))
                
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
            <HeaderPohonKinerja />
            <div className="bg-white h-auto dark:bg-gray-800 rounded-lg drop-shadow-xl py-5 px-4 w-full flex min-h-[35rem]">
                <TabMenuPohonKinerja 
                    active='sasaran' 
                    params={{ 
                        visiId: selectedVisiId,
                        misiId: selectedMisiId,
                        tujuanId: selectedTujuanId
                    }}
                />
                <div className="flex flex-col w-full mt-14 p-3 border border-teal-500/30 dark:border-gray-600 rounded-lg">
                    <div className="w-full text-center text-lg text-teal-500 dark:text-white font-bold mb-3">SASARAN KEPALA DAERAH</div>
                    <div className="w-full text-center text-lg dark:text-white italic mb-3">Tujuan : " {selectedTujuan} "</div>
                    <div className="w-full flex">
                        <PrimaryBtn loading={sasaranKdhState.loading} onClick={() => openModalAction()} >
                            <PlusCircleIcon className="w-5 h-5" />
                            Tambah Sasaran
                        </PrimaryBtn>
                    </div>
                    <TableSection getDataAction={getDataTable} pagination={sasaranKdhState.pagination}>
                        <MyTable>
                            <TableHeader>
                            <tr>
                                <th scope="col" className="px-4 py-3 w-[3%]">No.</th>
                                <th scope="col" className="px-4 py-3">Sasaran</th>
                                <th scope="col" className="px-4 py-3 w-[15%]">Active</th>
                                <th scope="col" className="px-4 py-3 w-[10%]">
                                    <span className="sr-only">Actions</span>
                                </th>
                            </tr>
                            </TableHeader>
                            <TableBody>
                                {
                                    sasaranKdhState.loading ? 
                                    <tr className="border-b dark:border-gray-700">
                                        <td scope="row" className="px-4 py-5 bg-gray-100 dark:bg-gray-800 dark:text-white" colSpan="100%">
                                            <div className="flex flex-row justify-center w-full gap-12">
                                                <PacmanLoader size={10} color='gray' />
                                                Please Wait...
                                            </div>
                                        </td>
                                    </tr> : 
                                    (sasaranKdhState.list.length > 0 ? 
                                        sasaranKdhState.list.map((item, key) => (
                                            <tr key={item.id} className="border-b dark:border-gray-700 odd:bg-gray-100 dark:odd:bg-gray-900">
                                                <th scope="row"
                                                    className="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">{item.order}</th>
                                                <td className="px-4 py-3">
                                                    <Link to={`/perencanaan/kdh/pohonkinerja/indikator?visiId=${selectedVisiId}&misiId=${selectedMisiId}&tujuanId=${selectedTujuanId}&sasaranId=${item.id}`} className="hover:text-blue-500 hover:font-bold" >
                                                        {item.sasaran}
                                                    </Link>
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
                                                        className="hidden z-10 w-48 bg-gray-50 rounded divide-y divide-gray-100 drop-shadow-lg dark:bg-gray-700 dark:divide-gray-600">
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
                    <MyModal ModalTitle={formTitle} openModal={openModal} setOpenModal={setOpenModal} >
                        <div className="flex flex-col w-full p-4">
                            <MyInput id="tujuanId" name="tujuanId" label="Tujuan"
                                value={selectedTujuan} disabled />
                            <MyInput id="order" name="order" label="Urutan" type="number" placeholder='Inputkan nomor urut...'
                                value={formik.values.order} onChange={formik.handleChange} onBlur={formik.handleBlur}
                                error={(formik.errors.order && formik.touched.order) ? formik.errors.order : ""} 
                            />
                            <MyTextarea id="sasaran" name="sasaran" label="Sasaran" placeholder='Inputkan sasaran...' 
                                value={formik.values.sasaran} onChange={formik.handleChange} onBlur={formik.handleBlur}
                                error={(formik.errors.sasaran && formik.touched.sasaran) ? formik.errors.sasaran : ""}
                            />
                            <div className="flex w-fill justify-center">
                                <MyToggle id="isActive" name="isActive" label="Aktif" value={formik.values.isActive}
                                    error={formik.errors.isActive}
                                    onChange={formik.handleChange} />
                            </div>
                        </div>
                        
                        <div className="mt-5 sm:mt-6 flex justify-center">
                            <PrimaryBtn onClick={()=> simpanData()} loading={sasaranKdhState.loading} >
                                Simpan Data
                            </PrimaryBtn>
                        </div>
                    </MyModal>
                </div>
            </div>
        </Layout>
    )
}

export default Sasaran