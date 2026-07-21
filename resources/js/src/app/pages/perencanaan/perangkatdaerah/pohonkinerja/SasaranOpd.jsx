import React from 'react'
import Layout from '@/app/components/Layout/Layout'
import HeaderPohonKinerjaOpd from '@/app/components/HeaderPohonKinerjaOpd'
import TabMenuPohonKinerjaOpd from '@/app/components/TabMenuPohonKinerjaOpd'
import PrimaryBtn from '@/app/components/Button/PrimaryBtn'
import { PencilSquareIcon, PlusCircleIcon, TrashIcon } from '@heroicons/react/24/outline'
import { useNavigate, useSearchParams, Link } from 'react-router-dom'
import { initFlowbite } from 'flowbite'
import { useFormik } from 'formik'
import * as Yup from 'yup'
import Swal from 'sweetalert2'
import { PacmanLoader } from 'react-spinners'
import { MyTable, TableHeader, TableSection, TableBody } from '@/app/components/Table'
import MyModal from '@/app/components/Form/MyModal'
import MyInput from '@/app/components/Form/MyInput'
import MyTextarea from '@/app/components/Form/MyTextarea'
import MyToggle from '@/app/components/Form/MyToggle'
import { useSelector, useDispatch } from 'react-redux'
import { getListSasaranOpd, createSasaranOpd, updateSasaranOpd, deleteSasaranOpd } from '@/redux/ducks/sasaranopd/action'
import { getTujuanOpd } from '@/redux/ducks/tujuanopd/action'
import { AlphabetList } from '@/helper/common'

const SasaranOpd = () => {
    const navigate = useNavigate()
    const dispatch = useDispatch()
    const tujuanOpdState = useSelector((state) => state.tujuanOpdState)
    const sasaranOpdState = useSelector((state) => state.sasaranOpdState)
    const [searchParams, setSearchParams] = useSearchParams()
    React.useEffect(() => { 
        setTimeout(() => initFlowbite(), 3000)
    },[sasaranOpdState.list])
    const selectedTujuanId = searchParams.get('tujuanId')
    const [openModal, setOpenModal] = React.useState(false)
    const [formTitle, setFormTitle] = React.useState("FORM TAMBAH SASARAN")
    const [selectedTujuan, setSelectedTujuan] = React.useState("Selected TujuanOpd")
    const [sasaranInduk,setSasaranInduk] = React.useState("")
    const [sasaranIndukId,setSasaranIndukId] = React.useState("")
    const [editId, setEditId] = React.useState("")
    const getDataTable = async (page = 1, per_page = 10, search = "") => {
        const response = await dispatch(getListSasaranOpd({ page, per_page, search, tujuan_opd_id: selectedTujuanId ?? "" }))
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
    React.useEffect(() => { tujuanOpdState.data ? setSelectedTujuan(tujuanOpdState.data.tujuan) : null },[tujuanOpdState.data])
    React.useEffect(() => {

        selectedTujuanId ? 
        dispatch( getTujuanOpd(selectedTujuanId ?? "") ).then((result) => {
            if(result.error === null){
                getDataTable()
            }
            else navigate('/perencanaan/opd/pohonkinerja/tujuan')
        }) : navigate('/perencanaan/opd/pohonkinerja/tujuan')

    },[])
    const openModalAction = () => {
        setSasaranInduk("")
        formik.resetForm();
        setParentId(0)
        setEditId("")
        setFormTitle("FORM TAMBAH SASARAN")
        setOpenModal(true);
    }
    const tambahSubSasaran = (data) => {
        setSasaranInduk(data.sasaran)
        formik.resetForm();
        setParentId(data.id)
        setEditId("")
        setFormTitle("FORM TAMBAH SUB SASARAN")
        setOpenModal(true);
    }
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
    const validationForm = async () => {
        //validation
        formik.setFieldTouched('order', true, true)
        formik.setFieldTouched('sasaran', true, true)
        const errors = await formik.validateForm();

        return errors
    }
    const [parentId, setParentId] = React.useState(0);
    const simpanData= async ()=> {
        
        const errors = await validationForm()
        if (Object.keys(errors).length === 0) {
            // Form is valid, do any success call, mapping payload before submit
            const form = formik.values
            const payload = {
                tujuan_opd_id: selectedTujuanId,
                order: form.order,
                sasaran: form.sasaran,
                parent_id: parentId,
                is_active: form.isActive
            }
            
            // submit payload with dispatch action redux
            let response = null
            if(editId !== "") response = await dispatch(updateSasaranOpd(editId, payload))
            else response = await dispatch(createSasaranOpd(payload));
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
        setParentId(data.parent_id)
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
                const response = await dispatch(deleteSasaranOpd(id))
                
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
    const renderContentTable = (data, startingpoint=0) => {
        return data.map((item, key) => (
            <>
                <tr key={item.id} 
                    className="border-b dark:border-gray-700 hover:bg-slate-100 dark:hover:bg-gray-700"
                    style={{ backgroundColor: startingpoint === 0 ? '#fff' : startingpoint === 1 ? '#f5f5f5' : startingpoint === 2 ? '#d7e1fc' : '#d1cfcf' }}
                >
                    <td className="pr-4 py-3" style={{ paddingLeft: 20 + (startingpoint*25) + 'px' }}>
                        <Link to={`/perencanaan/opd/pohonkinerja/indikator?tujuanId=${selectedTujuanId}&sasaranId=${item.id}`} 
                            className={`hover:text-blue-500 hover:font-bold ${startingpoint === 0 ? 'font-bold uppercase' : (startingpoint === 1 ? 'font-semibold' : 'italic')}`} >
                            { startingpoint === 0 ? AlphabetList[key] + ". " : null }
                            { startingpoint === 1 ? (key + 1) + ". " : null }
                            { startingpoint === 2 ? AlphabetList[key] + ". " : null }
                            { startingpoint > 2 ? key + 1 + ". " : null }
                            { item.sasaran }
                        </Link>
                    </td>
                    <td className="px-4 py-3">{item.is_active ? "Aktif" : "Non-Aktif"}</td>
                    <td className="px-4 py-3 w-full flex flex-row items-center">
                            <button onClick={() => tambahSubSasaran(item)} popovertarget='Tambah Sub Sasaran'
                                className="flex gap-1 py-2 px-4 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">
                                <PlusCircleIcon className='w-5 h-5' />
                            </button>
                            <button onClick={() => editAction(item)}
                                className="flex gap-1 py-2 px-4 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">
                                <PencilSquareIcon className='w-5 h-5' />
                            </button>
                            {
                            item.sub_sasaran.length > 0 ? null :
                                <button onClick={() => deleteAction(item.id)}
                                    className="flex gap-1 py-2 px-4 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">
                                    <TrashIcon className='w-5 h-5' />
                                </button>
                            }
                            {
                                startingpoint > 0 ? 
                                <button onClick={() => navigate(`/perencanaan/opd/sasaran_operasional?sasaranId=${item.id}&tujuanId=${selectedTujuanId}`)} popovertarget='Tambah Sasaran Operasional'
                                    className="flex gap-1 py-2 px-4 text-red-600 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">
                                    <PlusCircleIcon className='w-5 h-5' />
                                </button> : null
                            }
                    </td>
                </tr>
                {
                    item.sub_sasaran.length > 0 ? renderContentTable(item.sub_sasaran, (startingpoint+1)) : null
                }
            </>
        ))
    }
    return (
        <Layout>
            <HeaderPohonKinerjaOpd />
            <div className="bg-white h-auto dark:bg-gray-800 rounded-lg drop-shadow-xl py-5 px-4 w-full flex min-h-[35rem]">
                <TabMenuPohonKinerjaOpd active='sasaran' params={{ tujuanId: selectedTujuanId }} />
                <div className="flex flex-col w-full mt-14 p-3 border border-teal-500/30 dark:border-gray-600 rounded-lg">
                <div className="w-full text-center text-lg text-teal-500 dark:text-white font-bold mb-3">SASARAN PERANGKAT DAERAH</div>
                    <div className="w-full text-center text-lg dark:text-white italic mb-3">" {selectedTujuan} "</div>
                    <div className="w-full flex">
                        <PrimaryBtn loading={sasaranOpdState.loading} onClick={() => openModalAction()} >
                            <PlusCircleIcon className="w-5 h-5" />
                            Tambah Sasaran
                        </PrimaryBtn>
                    </div>
                    {/* tabel  */}
                    <TableSection getDataAction={getDataTable} pagination={sasaranOpdState.pagination}>
                        <MyTable>
                            <TableHeader>
                            <tr>
                                <th scope="col" className="px-4 py-3 text-center">Sasaran</th>
                                <th scope="col" className="px-4 py-3 text-center">Active</th>
                                <th scope="col" className="px-4 py-3 w-[10%]">
                                    <span className="sr-only">Actions</span>
                                </th>
                            </tr>
                            </TableHeader>
                            <TableBody>
                                {
                                    sasaranOpdState.loading ? 
                                    <tr className="border-b dark:border-gray-700">
                                        <td scope="row" className="px-4 py-5 bg-gray-100 dark:bg-gray-800 dark:text-white" colSpan="100%">
                                            <div className="flex flex-row justify-center w-full gap-12">
                                                <PacmanLoader size={10} color='gray' />
                                                Please Wait...
                                            </div>
                                        </td>
                                    </tr> : 
                                    (sasaranOpdState.list.length > 0 ? 
                                        renderContentTable(sasaranOpdState.list)
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
                            {
                                parentId != 0 ? <MyInput id="sasaranInduk" name="sasaranInduk" label="Sasaran Induk"
                                value={sasaranInduk} disabled /> : null
                            }
                            
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
                            <PrimaryBtn onClick={()=> simpanData()} loading={sasaranOpdState.loading} >
                                Simpan Data
                            </PrimaryBtn>
                        </div>
                    </MyModal>
                </div>
            </div>
        </Layout>
    )
}

export default SasaranOpd