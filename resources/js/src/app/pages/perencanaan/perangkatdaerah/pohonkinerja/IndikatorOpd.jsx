import React from 'react'
import Layout from '@/app/components/Layout/Layout'
import HeaderPohonKinerjaOpd from '@/app/components/HeaderPohonKinerjaOpd'
import TabMenuPohonKinerjaOpd from '@/app/components/TabMenuPohonKinerjaOpd'
import PrimaryBtn from '@/app/components/Button/PrimaryBtn'
import DangerBtn from '@/app/components/Button/DangerBtn'
import { ArrowPathIcon, PencilSquareIcon, PlusCircleIcon, TrashIcon, UserCircleIcon, CheckIcon, XMarkIcon } from '@heroicons/react/24/outline'
import { MyTable, TableHeader, TableSection, TableBody, StaticTable } from '@/app/components/Table'
import { useNavigate, useSearchParams, Link } from 'react-router-dom'
import { initFlowbite } from 'flowbite'
import { useFormik } from 'formik'
import * as Yup from "yup"
import Swal from 'sweetalert2'
import { PacmanLoader } from 'react-spinners'
import MyModal from '@/app/components/Form/MyModal'
import MyInput from '@/app/components/Form/MyInput'
import MyTextarea from '@/app/components/Form/MyTextarea'
import MyToggle from '@/app/components/Form/MyToggle'
import { useSelector, useDispatch } from 'react-redux'
import { getListIndikatorOpd, createIndikatorOpd, updateIndikatorOpd, deleteIndikatorOpd } from '@/redux/ducks/indikatoropd/actions'
import { getListPegawaiPengampuIndikatorOpd, createPengampuIndikatorOpd, updatePengampuIndikatorOpd, getListPengampuIndikatorOpd, deletePengampuIndikatorOpd } from '@/redux/ducks/pengampuindikatoropd/actions'
import { getTujuanOpd } from '@/redux/ducks/tujuanopd/action'
import { getSasaranOpd } from '@/redux/ducks/sasaranopd/action'
import MySelect2 from '@/app/components/Form/MySelect2'
import IconBtn from '@/app/components/Button/IconBtn'

const IndikatorOpd = () => {
    const navigate = useNavigate()
    const dispatch = useDispatch()
    const tujuanOpdState = useSelector((state) => state.tujuanOpdState)
    const sasaranOpdState = useSelector((state) => state.sasaranOpdState)
    const indikatorOpdState = useSelector((state) => state.indikatorOpdState)
    const [searchParams, setSearchParams] = useSearchParams()
    const [openModal, setOpenModal] = React.useState(false)
    const [openModal2, setOpenModal2] = React.useState(false)
    const [formTitle, setFormTitle] = React.useState("FORM TAMBAH INDIKATOR")
    const [selectedTujuan, setSelectedTujuan] = React.useState("Tujuan")
    const [selectedSasaran, setSelectedSasaran] = React.useState("")
    const [editId, setEditId] = React.useState("")
    const selectedTujuanId = searchParams.get("tujuanId")
    const selectedSasaranId = searchParams.get("sasaranId")
    React.useEffect(() => { initFlowbite() },[indikatorOpdState.list])
    const getDataTable = async (page = 1, per_page = 10, search = "") => {
        const response = await dispatch(getListIndikatorOpd({ 
                page, 
                per_page, 
                search, 
                tujuan_opd_id: selectedTujuanId ?? "", 
                sasaran_opd_id: selectedSasaranId ?? ""
            })
        )
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
    React.useEffect(() => { tujuanOpdState.data ? setSelectedTujuan(tujuanOpdState.data.tujuan) : null }, [tujuanOpdState.data])
    React.useEffect(() => { 
        if(selectedSasaranId){
            sasaranOpdState.data ? setSelectedSasaran(sasaranOpdState.data.sasaran) : null 
        }
        else{
            setSelectedSasaran("");
        }
    }, [sasaranOpdState.data])
    const [isTujuan, setIsTujuan] = React.useState(false)

    React.useEffect(() => {
        if(selectedTujuanId){
            dispatch( getTujuanOpd(selectedTujuanId ?? "") ).then((result) => {
                if(result.error === null){
                    if(selectedSasaranId){
                        setIsTujuan(false)
                        dispatch( getSasaranOpd(selectedSasaranId ?? "") ).then((result) => {
                            if(result.error === null){
                                getDataTable()
                            }
                            else navigate('/perencanaan/opd/pohonkinerja/tujuan')
                        })
                    }
                    else{ getDataTable(); setIsTujuan(true); }
                }
                else navigate('/perencanaan/opd/pohonkinerja/tujuan')
            })
        }
        else navigate('/perencanaan/opd/pohonkinerja/tujuan')
        
    },[selectedTujuanId])

    const openModalAction = () => {
        formik.resetForm();
        setEditId("")
        let typeForm = selectedSasaranId ? "FORM TAMBAH INDIKATOR SASARAN" : "FORM TAMBAH INDIKATOR TUJUAN"
        setFormTitle(typeForm)
        setOpenModal(true);
    }
    const formik = useFormik({
        initialValues: {
            order: 0,
            indikator: undefined,
            isActive: false,
            is_indikator_kinerja_utama: false,
            diampu_tim: false
        },
        validationSchema: Yup.object({ 
            order:           Yup.number().required().strict(true),
            indikator:           Yup.string().required().strict(true),
            isActive:       Yup.boolean().required(),
            is_indikator_kinerja_utama:       Yup.boolean().required(),
            diampu_tim:       Yup.boolean().required()
        }),
        enableReinitialize: true
    })
    const validationForm = async () => {
        //validation
        formik.setFieldTouched('order', true, true)
        formik.setFieldTouched('indikator', true, true)
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
                sasaran_opd_id: selectedSasaranId,
                order: form.order,
                indikator: form.indikator,
                is_indikator_kinerja_utama: form.is_indikator_kinerja_utama,
                is_active: form.isActive,
                is_tujuan: isTujuan,
                diampu_tim: form.diampu_tim
            }
            
            
            // submit payload with dispatch action redux
            let response = null
            if(editId !== "") response = await dispatch(updateIndikatorOpd(editId, payload))
            else response = await dispatch(createIndikatorOpd(payload));
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
        console.log(data);
        
        formik.resetForm()
        formik.setFieldValue('order', data.order);
        formik.setFieldValue('indikator', data.indikator);
        formik.setFieldValue('isActive', data.is_active)
        formik.setFieldValue('is_indikator_kinerja_utama', data.is_indikator_kinerja_utama ?? false)
        formik.setFieldValue('diampu_tim', data.diampu_tim ?? false)
        setEditId(data.id)
        
        setFormTitle("FORM EDIT INDIKATOR")
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
                const response = await dispatch(deleteIndikatorOpd(id))
                
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
    /*
     * ========================================================== panel pengampu ==========================================================================================================================================
     */ 
    const tableHeader = () => (
        <tr>
            <th scope="col" className="px-4 py-3 border w-[3%]">No.</th>
            <th scope="col" className="px-4 py-3 border text-center">Jabatan</th>
            <th scope="col" className="px-4 py-3 border text-center w-[20%]">NIP</th>
            <th scope="col" className="px-4 py-3 border text-center">Nama</th>
            {
                isIndikatorTim ? <th scope="col" className="px-4 py-3 border text-center">Tim Kerja</th> : null
            }
            
            <th scope="col" className="px-4 py-3 border w-[5%]">
                <span className="sr-only">Actions</span>
            </th>
        </tr>
    )
    const [selectedIndikator, setSelectedIndikator] = React.useState('')
    const [selectedIndikatorId, setSelectedIndikatorId] = React.useState('')
    const [isIndikatorTim, setIsIndikatorTim] = React.useState(false)
    const [editState, setEditState] = React.useState(null)
    const pengampuPanel = (data) => {
        setOpenModal2(true)
        setSelectedPegawai(null)
        setSelectedIndikator(data.indikator)
        setSelectedIndikatorId(data.id)
        setIsIndikatorTim(data.diampu_tim)
        cancelEditPengampu()
    }
    const pengampuIndikatorOpdState = useSelector((state) => state.pengampuIndikatorOpdState)
    React.useEffect(() => {
        dispatch(getListPegawaiPengampuIndikatorOpd(2496))
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
    const [isKetuaTim, setIsKetuaTim] = React.useState(false)
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
                    sasaran_opd_id: selectedSasaranId,
                    indikator_opd_id : selectedIndikatorId,
                    nip: selectedPegawai.nip ?? "",
                    nama : selectedPegawai.nama_pns ?? "",
                    jns_jbtn_id : selectedPegawai.jns_jbtn_id ?? "",
                    jns_jbtn_nm : selectedPegawai.jns_jbtn_nm ?? "",
                    jabatan_id : selectedPegawai.jabatan_id ?? "",
                    jabatan_nm : selectedPegawai.jabatan_nm ?? "",
                    eselon_id : selectedPegawai.eselon_id ?? "",
                    eselon_nm : selectedPegawai.eselon_nm ?? "",
                    is_ketua: isKetuaTim ? true : false,
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
                    cancelEditPengampu()
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

    const editPengampu = (data) => {
        setEditState(data.jabatan_nm.toUpperCase() + " - " + data.nip + " - " + data.nama)
        setSelectedPegawai({...data, nama_pns: data.nama})
        setIsKetuaTim(data.is_ketua)
    }
    const cancelEditPengampu = () => {
        setEditState(null)
        setSelectedPegawai(null)
        setIsKetuaTim(false)
    }

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

    let paramsNavigation = {
        tujuanId: selectedTujuanId,
    }
    if(selectedSasaranId) paramsNavigation.sasaranId = selectedSasaranId

    return (
        <Layout loading={( tujuanOpdState.loading || sasaranOpdState.loading )}>
            <HeaderPohonKinerjaOpd />
            <div className="bg-white h-auto dark:bg-gray-800 rounded-lg drop-shadow-xl py-5 px-4 w-full flex min-h-[35rem]">
                <TabMenuPohonKinerjaOpd active='indikator' params={paramsNavigation} />
                <div className="flex flex-col w-full mt-14 p-3 border border-teal-500/30 dark:border-gray-600 rounded-lg">
                    <div className="w-full text-center text-lg text-teal-500 dark:text-white font-bold mb-3">INDIKATOR { selectedSasaranId != null ? "SASARAN" : "TUJUAN" }</div>
                    <div className="w-full text-center text-lg dark:text-white mb-3">
                        Tujuan :
                        <span className="italic">" {selectedTujuan} "</span>
                    </div>
                    {
                        selectedSasaranId != null ? 
                        <div className="w-full text-center text-lg dark:text-white mb-3">
                            Sasaran :
                            <span className="italic">" {selectedSasaran} "</span>
                        </div> : null
                    }
                    
                    <div className="w-full flex">
                        <PrimaryBtn loading={indikatorOpdState.loading} onClick={() => openModalAction()} >
                            <PlusCircleIcon className="w-5 h-5" />
                            Tambah Indikator
                        </PrimaryBtn>
                    </div>
                    <TableSection getDataAction={() => null} pagination={{ page: 1, per_page: 10 }}>
                        <MyTable>
                            <TableHeader>
                            <tr>
                                <th scope="col" className="px-4 py-3 w-[3%]">No.</th>
                                <th scope="col" className="px-4 py-3">Indikator</th>
                                <th scope="col" className="px-4 py-3">IKU ?</th>
                                <th scope="col" className="px-4 py-3">Diampu oleh Tim Kerja</th>
                                <th scope="col" className="px-4 py-3">Active</th>
                                <th scope="col" className="px-4 py-3 w-[10%]">
                                    <span className="sr-only">Actions</span>
                                </th>
                            </tr>
                            </TableHeader>
                            <TableBody>
                            {
                                indikatorOpdState.loading ? 
                                <tr className="border-b dark:border-gray-700">
                                    <td scope="row" className="px-4 py-5 bg-gray-100 dark:bg-gray-800 dark:text-white" colSpan="100%">
                                        <div className="flex flex-row justify-center w-full gap-12">
                                            <PacmanLoader size={10} color='gray' />
                                            Please Wait...
                                        </div>
                                    </td>
                                </tr> : 
                                (indikatorOpdState.list.length > 0 ? 
                                    indikatorOpdState.list.map((item, key) =>(
                                        <tr key={item.id} className="border-b dark:border-gray-700">
                                            <th scope="row"
                                                className="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white align-top text-right">{item.order}</th>
                                            <td className="px-4 py-3 align-top">
                                                {item.indikator}
                                            </td>
                                            <td className="px-4 py-3 align-top">
                                                { item.is_indikator_kinerja_utama ? "Ya" : "Tidak" }
                                            </td>
                                            <td className="px-4 py-3 align-top">{item.diampu_tim ? <CheckIcon className='w-4 h-5' /> : <XMarkIcon className='w-4 h-4' />  }</td>
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
                                                        <li>
                                                            <a href="#" onClick={() => pengampuPanel(item)}
                                                                className="flex gap-1 py-2 px-4 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">
                                                                <UserCircleIcon className='w-5 h-5' />
                                                                Pengampu
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
                            {
                                selectedSasaranId ? 
                                <MyInput id="sasaranId" name="sasaranId" label="Sasaran"
                                    value={selectedSasaran} disabled />
                                : null
                            }
                            <MyInput id="order" name="order" label="Urutan" type="number" placeholder='Inputkan nomor urut...'
                                value={formik.values.order} onChange={formik.handleChange} onBlur={formik.handleBlur}
                                error={(formik.errors.order && formik.touched.order) ? formik.errors.order : ""} 
                            />

                            <div className="flex flex-row items-center w-fill">
                                <MyToggle id="is_indikator_kinerja_utama" name="is_indikator_kinerja_utama" label="Ya" value={formik.values.is_indikator_kinerja_utama}
                                    title="Jadikan Indikator Kinerja Utama?"
                                    error={formik.errors.is_indikator_kinerja_utama}
                                    onChange={formik.handleChange} />
                            </div>
                            <div className="flex flex-row items-center w-fill">
                                <MyToggle id="diampu_tim" name="diampu_tim" label="Ya" value={formik.values.diampu_tim}
                                    title="Indikator yang diampu oleh Tim Kerja?"
                                    error={formik.errors.diampu_tim}
                                    onChange={formik.handleChange} />
                            </div>
                            <MyTextarea id="indikator" name="indikator" label="Indikator" placeholder='Inputkan indikator...' 
                                value={formik.values.indikator} onChange={formik.handleChange} onBlur={formik.handleBlur}
                                error={(formik.errors.indikator && formik.touched.indikator) ? formik.errors.indikator : ""}
                            />
                            <div className="flex w-fill justify-center">
                                <MyToggle id="isActive" name="isActive" label="Aktif" value={formik.values.isActive}
                                    error={formik.errors.isActive}
                                    onChange={formik.handleChange} />
                            </div>
                        </div>
                        
                        <div className="mt-5 sm:mt-6 flex justify-center">
                            <PrimaryBtn onClick={()=> simpanData()} loading={indikatorOpdState.loading} >
                                Simpan Data
                            </PrimaryBtn>
                        </div>
                    </MyModal>
                    <MyModal ModalTitle={"Pengampu"} size='lg' openModal={openModal2} setOpenModal={setOpenModal2} >
                        <div className="flex flex-col w-full p-4">
                            <div className="w-full justify-center flex flex-row">
                                <h1 className=" text-center text-xl font-semibold italic dark:text-white">
                                    " { selectedIndikator } "
                                </h1>
                            </div>
                            <div className="flex flex-col justify-between w-full py-2">
                                {
                                    editState !== null ? 
                                    <div className="w-full">
                                        <MyInput id="editPegawai" name="editPegawai" label="Pegawai"
                                        value={editState} disabled />
                                    </div>
                                    : 
                                    <div className="w-full">
                                        <MySelect2
                                            id="pengampu"
                                            label="Pegawai"
                                            options={daftarPegawai()}
                                            onChange={listPegawaiOnchange}
                                        />
                                    </div>
                                }
                                {
                                    isIndikatorTim ?
                                    <div className="flex flex-row items-center w-fill">
                                        <MyToggle id="isKetuaTim" name="isKetuaTim" label="Ya" value={isKetuaTim}
                                            title="Ketua tim?"
                                            onChange={() => setIsKetuaTim(!isKetuaTim)} />
                                    </div> : null
                                }
                                <div className="w-full flex gap-3 justify-end">
                                    {
                                        editState !== null ? 
                                        <DangerBtn onClick={() => cancelEditPengampu()}>
                                            Batalkan update data
                                        </DangerBtn> : null
                                    }
                                    <PrimaryBtn onClick={() => simpanPengampu()} loading={pengampuIndikatorOpdState.loading} >
                                        {
                                            editState !== null ? "Update Data" : "Tambahkan"
                                        }
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
                                                {
                                                    isIndikatorTim ? <td className="px-4 py-3 border">{item.is_ketua ? "Ketua" : "Anggota"}</td> : null
                                                }
                                                
                                                <td className="px-4 py-3 border flex">
                                                    <IconBtn 
                                                        loading={pengampuIndikatorOpdState.loading}
                                                        onClick={() => editPengampu(item)}
                                                    >
                                                        <PencilSquareIcon className='w-5 h-5' />
                                                        <span className="sr-only">Update</span>
                                                    </IconBtn>
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
                </div>
            </div>
        </Layout>
    )
}

export default IndikatorOpd