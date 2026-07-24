import React from 'react'
import { initFlowbite } from 'flowbite'
import { useFormik } from 'formik'
import * as Yup from 'yup'
import Swal from 'sweetalert2'
import { useSearchParams, useNavigate } from 'react-router-dom'
import Layout from '@/app/components/Layout/Layout'
import GoodNotes from "@assets/GoodNotes.png"
import TabMenuSasaranOperasional from '@/app/components/TabMenuSasaranOperasional'
import { getSasaranOpd } from '@/redux/ducks/sasaranopd/action'
import { useDispatch, useSelector } from 'react-redux'
import PrimaryBtn from '@/app/components/Button/PrimaryBtn'
import { PencilSquareIcon, PlusCircleIcon, TrashIcon } from '@heroicons/react/24/outline'
import { MyTable, TableHeader, TableSection, TableBody } from '@/app/components/Table'
import MyModal from '@/app/components/Form/MyModal'
import MyInput from '@/app/components/Form/MyInput'
import MySelect2 from '@/app/components/Form/MySelect2'
import MyToggle from '@/app/components/Form/MyToggle'
import { getListSasaranOperasionalOpd, createSasaranOperasionalOpd, deleteSasaranOperasionalOpd, getRefSasaranOperasionalOpd } from '@/redux/ducks/sasaranoperasional/action'
import { PacmanLoader } from 'react-spinners'

const SasaranOperasionalOpd = () => {
    const navigate = useNavigate()
    const [openModal, setOpenModal] = React.useState(false)
    const [formTitle, setFormTitle] = React.useState("FORM TAMBAH SASARAN")
    const [editId, setEditId] = React.useState("")
    const dispatch = useDispatch()
    const [searchParams, setSearchParams] = useSearchParams()
    const selectedTujuanId = searchParams.get('tujuanId')
    const sasaranOpdState = useSelector((state) => state.sasaranOpdState)
    const sasaranOperasionalOpdState = useSelector((state) => state.sasaranOperasionalOpdState)
    const [selectedSasaranInduk, setSelectedSasaranInduk] = React.useState('')
    const selectedSasaranId = searchParams.get("sasaranId")
    React.useEffect(() => {initFlowbite()},[])
    React.useEffect(() => {
        if(selectedSasaranId !== null){
            dispatch(getSasaranOpd(selectedSasaranId))
            .then((result) => {
                if(result.error === null){
                    
                }
            })
        }
    },[selectedSasaranId])

    React.useEffect(() => {
        dispatch(getRefSasaranOperasionalOpd())
    },[])

    React.useEffect(() => {
        let sasaran = sasaranOpdState.data?.sasaran ?? ""
                    setSelectedSasaranInduk(sasaran)
    },[sasaranOpdState.data])
    const openModalAction = () => {
        formik.resetForm();
        setEditId("")
        setFormTitle("FORM TAMBAH SASARAN OPERASIONAL")
        setSelectedSasaran(null)
        setOpenModal(true);
    }

    const formik = useFormik({
        initialValues: {
            order: 1,
            isActive: true
        },
        validationSchema: Yup.object({ 
            order:           Yup.number().required().strict(true),
            isActive:       Yup.boolean().required()
        }),
        enableReinitialize: true
    })
    let loading = sasaranOperasionalOpdState.loading
    let getDataTable = async (page = 1, per_page = 10, search = "") => {
        const response = await dispatch(getListSasaranOperasionalOpd({ page, per_page, search, tujuan_opd_id: selectedTujuanId ?? "" }))
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
    let pagination = {}
    const refSasaranOperasional = () =>{
        let data = []
        if(sasaranOperasionalOpdState.ref){
            if(sasaranOperasionalOpdState.ref.length > 0){
                data = sasaranOperasionalOpdState.ref.map((item, key) => ({
                    value: item.id,
                    label: item.sasaran
                }))
            }
        }
        return data
    }
    const [selectedSasaran, setSelectedSasaran] = React.useState(null)
    const setPilihSasaran = (e) => {
            setSelectedSasaran(e)
    }
    const renderContentTable = (data) => {
        return data.map((item,key) => (
            <tr key={item.id} 
                className="border-b dark:border-gray-700 hover:bg-slate-100 dark:hover:bg-gray-700"
            >
                <td className="pr-4 py-3 text-right" >
                    { key+1 }
                </td>
                <td className="pr-4 py-3" >
                    { item.sasaran }
                </td>
                <td className="px-4 py-3">{item.is_active ? "Aktif" : "Non-Aktif"}</td>
                <td className="px-4 py-3 w-full flex flex-row items-center">
                    <button onClick={() => deleteAction(item.id)}
                        className="flex gap-1 py-2 px-4 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">
                        <TrashIcon className='w-5 h-5' />
                    </button>
                    <button onClick={() => navigate(`/perencanaan/opd/indikator_operasional?sasaranId=${selectedSasaranId}&sasaranOpId=${item.id}`)}
                        className="flex gap-1 py-2 px-4 text-red-600 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">
                        <PlusCircleIcon className='w-5 h-5' />
                    </button>
                </td>
            </tr>
        ))
    }
    const validationForm = async () => {
        //validation
        formik.setFieldTouched('order', true, true)
        const errors = await formik.validateForm();

        return errors
    }
    const simpanData= async ()=> {
        
        const errors = await validationForm()
        if (Object.keys(errors).length === 0) {
            // Form is valid, do any success call, mapping payload before submit
            const form = formik.values
            const payload = {
                tujuan_opd_id: selectedTujuanId,
                order: form.order,
                sasaran: selectedSasaran.label,
                sasaran_operasional_id: selectedSasaran.value,
                parent_id: selectedSasaranId,
                is_active: form.isActive
            }
            // console.log(payload); return
            
            // submit payload with dispatch action redux
            let response = await dispatch(createSasaranOperasionalOpd(payload));
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
                const response = await dispatch(deleteSasaranOperasionalOpd(id))
                
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
        <Layout loading={loading}>
            <div className="bg-white h-auto dark:bg-gray-800 rounded-lg drop-shadow-xl py-1 px-3 w-full">
                <div className="dark:text-white flex w-full justify-between">
                    <div className="flex flex-row items-center gap-3">
                        <div>
                            <img src={GoodNotes} alt="Perencanaan Perangkat Daerah" className="object-contain" />
                        </div>
                        <div className="lg:text-lg font-bold text-teal-500 dark:text-white">Sasaran Operasional Perangkat Daerah</div>
                    </div>

                </div>
            </div>
            <div className="bg-white h-auto dark:bg-gray-800 rounded-lg drop-shadow-xl py-5 px-4 w-full flex min-h-[35rem]">
                <TabMenuSasaranOperasional active='sasaran' />
                <div className="flex flex-col w-full mt-14 p-3 border border-teal-500/30 dark:border-gray-600 rounded-lg">
                    <div className="w-full text-center text-lg text-teal-500 dark:text-white font-bold mb-3">SASARAN OPERASIONAL</div>
                    {
                        selectedSasaranId !== null ? <div className="w-full text-center text-lg dark:text-white italic mb-3">Sasaran Induk : " {selectedSasaranInduk} "</div> : null
                    }
                    <div className="w-full flex">
                        <PrimaryBtn loading={loading} onClick={() => openModalAction()} >
                            <PlusCircleIcon className="w-5 h-5" />
                            Tambah Sasaran
                        </PrimaryBtn>
                    </div>
                    {/* tabel  */}
                    <TableSection getDataAction={getDataTable} pagination={pagination}>
                        <MyTable>
                            <TableHeader>
                            <tr>
                                <th scope="col" className="px-4 py-3 w-[3%] text-center">No</th>
                                <th scope="col" className="px-4 py-3 text-center">Sasaran</th>
                                <th scope="col" className="px-4 py-3 text-center">Active</th>
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
                                    (sasaranOperasionalOpdState.list.length > 0 ? 
                                        renderContentTable(sasaranOperasionalOpdState.list)
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
                            
                            <MyInput id="order" name="order" label="Urutan" type="number" placeholder='Inputkan nomor urut...'
                                value={formik.values.order} onChange={formik.handleChange} onBlur={formik.handleBlur}
                                error={(formik.errors.order && formik.touched.order) ? formik.errors.order : ""} 
                            />
                            <MySelect2 id="ref_sasaran" name="ref_sasaran" label="Sasaran Operasional"
                                placeholder="pilih sasaran..."
                                options={refSasaranOperasional()}
                                onChange={setPilihSasaran}
                                value={selectedSasaran} />
                            <div className="flex w-fill justify-center">
                                <MyToggle id="isActive" name="isActive" label="Aktif" value={formik.values.isActive}
                                    error={formik.errors.isActive}
                                    onChange={formik.handleChange} />
                            </div>
                        </div>
                        
                        <div className="mt-5 sm:mt-6 flex justify-center">
                            <PrimaryBtn onClick={()=> simpanData()} loading={loading} >
                                Simpan Data
                            </PrimaryBtn>
                        </div>
                    </MyModal>
                </div>
                
            </div>
        </Layout>
    )
}

export default SasaranOperasionalOpd