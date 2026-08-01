import React from 'react'
import { initFlowbite } from 'flowbite'
import { useFormik } from 'formik'
import * as Yup from 'yup'
import Swal from 'sweetalert2'
import Layout from '@/app/components/Layout/Layout'
import GoodNotes from "@assets/GoodNotes.png"
import TabMenuSasaranOperasional from '@/app/components/TabMenuSasaranOperasional'
import { useSearchParams, useNavigate } from 'react-router-dom'
import { useDispatch, useSelector } from 'react-redux'
import PrimaryBtn from '@/app/components/Button/PrimaryBtn'
import { UserIcon, PlusCircleIcon, TrashIcon } from '@heroicons/react/24/outline'
import { MyTable, TableHeader, TableSection, TableBody, StaticTable } from '@/app/components/Table'
import IconBtn from '@/app/components/Button/IconBtn'
import MyModal from '@/app/components/Form/MyModal'
import MyInput from '@/app/components/Form/MyInput'
import MySelect2 from '@/app/components/Form/MySelect2'
import MyToggle from '@/app/components/Form/MyToggle'
import { PacmanLoader } from 'react-spinners'
import { getSasaranOpd } from '@/redux/ducks/sasaranopd/action'
import { getSasaranOperasionalOpd } from '@/redux/ducks/sasaranoperasional/action'
import { getRefIndikatorOperasionalOpd, getListIndikatorOperasionalOpd, createIndikatorOperasionalOpd, deleteIndikatorOperasionalOpd } from '@/redux/ducks/indikatoroperasional/action'
import { getListPegawaiPengampuIndikatorOpd, createPengampuIndikatorOpd, updatePengampuIndikatorOpd, getListPengampuIndikatorOpd, deletePengampuIndikatorOpd } from '@/redux/ducks/pengampuindikatoropd/actions'

const IndikatorOperasionalOpd = () => {
    const navigate = useNavigate()
    const [openModal, setOpenModal] = React.useState(false)
    const [formTitle, setFormTitle] = React.useState("FORM TAMBAH INDIKATOR")
    const [editId, setEditId] = React.useState("")
    const dispatch = useDispatch()

    const [searchParams, setSearchParams] = useSearchParams()
    const selectedSasaranId = searchParams.get("sasaranId")
    const selectedSasaranOpId = searchParams.get("sasaranOpId")
    const sasaranOpdState = useSelector((state) => state.sasaranOpdState)
    const sasaranOperasionalOpdState = useSelector((state) => state.sasaranOperasionalOpdState)
    const indikatorOperasionalOpdState = useSelector((state) => state.indikatorOperasionalOpdState)
    const authState = useSelector((state) => state.authState)
    const [selectedSasaranInduk, setSelectedSasaranInduk] = React.useState("")
    const [selectedSasaranOperasional, setSelectedSasaranOperasional] = React.useState("")
    React.useEffect(() => {
        dispatch(getSasaranOperasionalOpd(selectedSasaranOpId)).then((res) => {
            if(res.error === null){
                setSelectedSasaranOperasional(res?.data?.data?.sasaran ?? "")
                dispatch(getListIndikatorOperasionalOpd({
                    tujuan_opd_id: res?.data?.data?.tujuan_opd_id ?? "",
                    sasaran_opd_id: res?.data?.data?.id ?? "",
                    page: 1,
                    per_page: 10,
                }))
            }
        })
    },[selectedSasaranOpId])
    React.useEffect(() => {
        dispatch(getSasaranOpd(selectedSasaranId)).then(
            (res) => { 
                if(res.error === null){
                    setSelectedSasaranInduk(res?.data?.data?.sasaran ?? "")
                }
            }
        )
    },[selectedSasaranId])

    let loading = indikatorOperasionalOpdState.loading

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

    const openModalAction = () => {
        formik.resetForm();
        setEditId("")
        setFormTitle("FORM TAMBAH INDIKATOR OPERASIONAL")
        setOpenModal(true);
    }
    const getDataTable = async (page = 1, per_page = 10, search = "") => {
        const response = await dispatch(getListIndikatorOperasionalOpd({ 
                                    tujuan_opd_id: sasaranOperasionalOpdState.data?.tujuan_opd_id ?? "",
                                    sasaran_opd_id: sasaranOperasionalOpdState.data?.id ?? "",
                                    page, 
                                    per_page, 
                                    search
                                }))
        if(response.error !== null){
            Swal.fire({
                icon: 'error',
                title: "Gagal memuat data Indikator Operasional",
                showConfirmButton: true,
                confirmButtonText: 'Refresh Halaman',
                timer: 1500
            }).then(async (result) => {
                if(result.isConfirmed) {

                }
            })
        }
    }
    const pagination = {}
    React.useEffect(() => {
        if(sasaranOperasionalOpdState.data){

            sasaranOperasionalOpdState.data.sasaran_operasional_id ? dispatch(getRefIndikatorOperasionalOpd(sasaranOperasionalOpdState.data.sasaran_operasional_id)) : null
        }
    },[sasaranOperasionalOpdState.data])
    const refIndikatorOperasional = () =>{
        let data = []
        if(indikatorOperasionalOpdState.ref){
            data = indikatorOperasionalOpdState.ref.length > 0 ? indikatorOperasionalOpdState.ref.map((item) => ({
                value: item.id,
                label: item.indikator
            })) : []
        }
        return data
    }
    const setPilihIndikator = (e) => {
        setSelectedIndikator(e)
    }
    const renderContentTable = (data) => {
        return data.map((item,key) => (
            <tr key={item.id} 
                className="border-b dark:border-gray-700 hover:bg-slate-100 dark:hover:bg-gray-700 odd:bg-gray-100"
            >
                <td className="px-4 py-1 text-right border-x" >
                    { key+1 }
                </td>
                <td className="px-4 py-1 border-x" >
                    { item.indikator }
                </td>
                <td className="px-4 py-1 text-center border-x">{item.is_active ? "Aktif" : "Non-Aktif"}</td>
                <td className="px-4 py-1 border-x w-full flex flex-row items-center">
                    <button onClick={() => deleteAction(item.id)}
                        className="flex gap-1 py-2 px-4 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">
                        <TrashIcon className='w-5 h-5' />
                    </button>
                    <button onClick={() => pengampuPanel(item)} popovertarget='Pengampu'
                        className="flex gap-1 py-2 px-4 text-blue-600 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">
                        <UserIcon className='w-5 h-5' />
                    </button>
                </td>
            </tr>
        ))
    }


    const tableHeader = () => (
        <tr>
            <th scope="col" className="px-4 py-3 border w-[3%]">No.</th>
            <th scope="col" className="px-4 py-3 border text-center">Jabatan</th>
            <th scope="col" className="px-4 py-3 border text-center w-[20%]">NIP</th>
            <th scope="col" className="px-4 py-3 border text-center">Nama</th>
            <th scope="col" className="px-4 py-3 border w-[5%]">
                <span className="sr-only">Actions</span>
            </th>
        </tr>
    )
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
                tujuan_opd_id: sasaranOperasionalOpdState.data?.tujuan_opd_id,
                sasaran_opd_id: sasaranOperasionalOpdState.data?.id,
                order: form.order,
                indikator: selectedIndikator.label,
                satuan_id: "10e18042-1622-41c6-b85a-c4097d606309",
                is_active: form.isActive
            }
            // console.log(payload); return
            
            // submit payload with dispatch action redux
            let response = await dispatch(createIndikatorOperasionalOpd(payload));
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
                const response = await dispatch(deleteIndikatorOperasionalOpd(id))
                
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
    const [openModal2, setOpenModal2] = React.useState(false)
    const [selectedIndikator, setSelectedIndikator] = React.useState('')
    const [selectedIndikatorId, setSelectedIndikatorId] = React.useState('')
    const [editState, setEditState] = React.useState(null)
    const pengampuPanel = (data) => {
        setOpenModal2(true)
        setSelectedPegawai(null)
        setSelectedIndikator(data.indikator)
        setSelectedIndikatorId(data.id)
    }
    const pengampuIndikatorOpdState = useSelector((state) => state.pengampuIndikatorOpdState)
    React.useEffect(() => {
        dispatch(getListPegawaiPengampuIndikatorOpd(authState?.biodata?.opd?.id ?? ""))
    },[])
    const daftarPegawai = () => {
        let data = []
        if(pengampuIndikatorOpdState.list_pegawai.length > 0){
            
            data = pengampuIndikatorOpdState.list_pegawai.map((item) => {
                let jabatan_nm = item.jabatan_nm ? item.jabatan_nm : "Jabatan belum diatur"
                return{
                label: jabatan_nm.toUpperCase() + " - " + item.nip + " - " + item.nama_pns, //renderOptionItem(item.jabatan_nm, item.nama_pns, item.nip),
                value: item
            }})
        }
        return data
    }
    const [selectedPegawai, setSelectedPegawai] = React.useState(null)
    const listPegawaiOnchange = (item) => {
        setSelectedPegawai(item.value)
    }

    const simpanPengampu = async () => {

        if(selectedPegawai === null){
            return Swal.fire({
                icon: 'warning',
                title: "Pilih pegawai sebagai pengampu terlebih dahulu",
                showConfirmButton: false,
                timer: 1500
            })
        }

        Swal.fire({
        title: 'Apakah anda yakin?',
        text: "Tambahkan pegawai ini sebagai pengampu!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes!'
        }).then(async (result) => {
            if (result.isConfirmed) {
                let payload = {
                    sasaran_opd_id: selectedSasaranOpId,
                    indikator_opd_id : selectedIndikatorId,
                    nip: selectedPegawai.nip ?? "",
                    nama : selectedPegawai.nama_pns ?? "",
                    jns_jbtn_id : selectedPegawai.jns_jbtn_id ?? "",
                    jns_jbtn_nm : selectedPegawai.jns_jbtn_nm ?? "",
                    jabatan_id : selectedPegawai.jabatan_id ?? "",
                    jabatan_nm : selectedPegawai.jabatan_nm ?? "",
                    eselon_id : selectedPegawai.eselon_id ?? "",
                    eselon_nm : selectedPegawai.eselon_nm ?? "",
                    is_ketua: false,
                    is_active: true
                }
                
                let response = null
                if(editState === null) response = await dispatch(createPengampuIndikatorOpd(payload)) 
                else response = await dispatch(updatePengampuIndikatorOpd(selectedPegawai.id, payload))
                
                
                if(response.error === null){
                    Swal.fire({
                        icon: 'success',
                        title: response.data.message,
                        showConfirmButton: false,
                        timer: 1500
                    })
                    setSelectedPegawai(null)
                    getlistpengampu()
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
    const getlistpengampu = () => {
        if(selectedIndikatorId !== ""){
            dispatch(getListPengampuIndikatorOpd(selectedIndikatorId))
        }
    }
    React.useEffect(() => { getlistpengampu() },[selectedIndikatorId])
    const deletePengampu = (id) => {
        Swal.fire({
        title: 'Hapus pengampu ini?',
        text: "data yang sudah dihapus tidak dapat dikembalikan!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes!'
        }).then(async (result) => {
            if (result.isConfirmed) {
                const response = await dispatch(deletePengampuIndikatorOpd(id))
                
                if(response.error === null){
                    Swal.fire({
                        icon: 'success',
                        title: response.data.message,
                        showConfirmButton: false,
                        timer: 1500
                    })
                    getlistpengampu()
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
                        <div className="lg:text-lg font-bold text-teal-500 dark:text-white">Indikator Operasional Perangkat Daerah</div>
                    </div>

                </div>
            </div>
            <div className="bg-white h-auto dark:bg-gray-800 rounded-lg drop-shadow-xl py-5 px-4 w-full flex min-h-[35rem]">
                <TabMenuSasaranOperasional active='indikator' params={{ sasaranId: selectedSasaranId }} />
                <div className="flex flex-col w-full mt-14 p-3 border border-teal-500/30 dark:border-gray-600 rounded-lg">
                    <div className="w-full text-center text-lg text-teal-500 dark:text-white font-bold mb-3">INDIKATOR OPERASIONAL</div>
                    {
                        selectedSasaranId !== null ? <div className="w-full text-center text-lg dark:text-white italic mb-3">Sasaran Induk : " {selectedSasaranInduk} "</div> : null
                    }
                    {
                        selectedSasaranOpId !== null ? <div className="w-full text-center text-lg dark:text-white italic mb-3">Sasaran Operasional : " {selectedSasaranOperasional} "</div> : null
                    }
                    <div className="w-full flex">
                        <PrimaryBtn loading={loading} onClick={() => openModalAction()} >
                            <PlusCircleIcon className="w-5 h-5" />
                            Tambah Indikator
                        </PrimaryBtn>
                    </div>
                    <TableSection getDataAction={getDataTable} pagination={pagination}>
                        <MyTable>
                            <TableHeader>
                            <tr>
                                <th scope="col" className="px-4 py-3 border w-[3%] text-center">No</th>
                                <th scope="col" className="px-4 py-3 border text-center">Indikator</th>
                                <th scope="col" className="px-4 py-3 border w-[10%] text-center">Active</th>
                                <th scope="col" className="px-4 py-3 border w-[10%]">
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
                                    (indikatorOperasionalOpdState.list.length > 0 ? 
                                        renderContentTable(indikatorOperasionalOpdState.list)
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
                            <MySelect2 id="ref_indikator" name="ref_indikator" label="Indikator Operasional"
                                placeholder="pilih indikatopr..."
                                options={refIndikatorOperasional()}
                                onChange={setPilihIndikator}
                                value={selectedIndikator} />
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
                    {/* modal pengampu */}
                    <MyModal ModalTitle={"Pengampu"} size='lg' openModal={openModal2} setOpenModal={setOpenModal2} >
                        <div className="flex flex-col w-full p-4">
                            <div className="w-full justify-center flex flex-row">
                                <h1 className=" text-center text-xl font-semibold italic dark:text-white">
                                    " { selectedIndikator } "
                                </h1>
                            </div>
                            <div className="flex flex-col justify-between w-full py-2">
                                <div className="w-full">
                                    <MySelect2
                                        id="pengampu"
                                        label="Pegawai"
                                        options={daftarPegawai()}
                                        onChange={listPegawaiOnchange}
                                    />
                                </div>
                                <div className="w-full flex gap-3 justify-end">
                                    <PrimaryBtn onClick={() => simpanPengampu()} loading={pengampuIndikatorOpdState.loading} >
                                        Tambahkan
                                    </PrimaryBtn>
                                </div>
                            </div>
                            <StaticTable header={tableHeader()} >
                                {
                                    pengampuIndikatorOpdState.loading ?
                                    <tr>
                                        <th className='px-4 py-3 border text-center' colSpan="100%">Loading...</th>
                                    </tr>
                                    :
                                    (
                                        pengampuIndikatorOpdState.list.length > 0 ? 
                                        pengampuIndikatorOpdState.list.map((item, key) => (
                                            <tr key={key}>
                                                <td className="px-4 py-3 border text-right">{key+1}</td>
                                                <td className="px-4 py-3 border">{item.jabatan_nm}</td>
                                                <td className="px-4 py-3 border text-center">{item.nip}</td>
                                                <td className="px-4 py-3 border">{item.nama}</td>
                                                
                                                <td className="px-4 py-3 border flex">
                                                    <IconBtn 
                                                        loading={pengampuIndikatorOpdState.loading}
                                                        onClick={() => deletePengampu(item.id)}
                                                    >
                                                        <TrashIcon className='w-5 h-5' />
                                                        <span className="sr-only">Delete button</span>
                                                    </IconBtn>
                                                </td>
                                            </tr>
                                        ))
                                        : 
                                        <tr>
                                            <th className='px-4 py-3 border text-center' colSpan="100%">No Data</th>
                                        </tr>
                                    )
                                }
                                
                            </StaticTable>
                        </div>
                    </MyModal>
                    {/* modal pengampu */}
                </div>
            </div>
        </Layout>
    )
}

export default IndikatorOperasionalOpd