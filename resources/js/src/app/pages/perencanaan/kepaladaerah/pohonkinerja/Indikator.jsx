import React from 'react'
import Layout from '@/app/components/Layout/Layout'
import HeaderPohonKinerja from '@/app/components/HeaderPohonKinerja'
import TabMenuPohonKinerja from '@/app/components/TabMenuPohonKinerja'
import PrimaryBtn from '@/app/components/Button/PrimaryBtn'
import { PencilSquareIcon, PlusCircleIcon, TrashIcon, EyeIcon } from '@heroicons/react/24/outline'
import { MyTable, TableHeader, TableSection, TableBody } from '@/app/components/Table'
import { useSelector, useDispatch } from 'react-redux'
import { useNavigate, useSearchParams, Link } from 'react-router-dom'
import { getListIndikatorKdh, createIndikatorKdh, updateIndikatorKdh, deleteIndikatorKdh, uploadFormulaPerhitunganKdh } from '@/redux/ducks/indikatorkdh/action'
import { getOptionsDatamasterOpd } from '@/redux/ducks/datamasteropd/action'
import { getTujuanKdh } from '@/redux/ducks/tujuankdh/action'
import { getSasaranKdh } from '@/redux/ducks/sasarankdh/action'
import { initFlowbite } from 'flowbite'
import { useFormik } from 'formik'
import * as Yup from "yup"
import Swal from 'sweetalert2'
import { PacmanLoader } from 'react-spinners'
import MyModal from '@/app/components/Form/MyModal'
import MyInput from '@/app/components/Form/MyInput'
import MyTextarea from '@/app/components/Form/MyTextarea'
import MyToggle from '@/app/components/Form/MyToggle'
import MyMultiSelect from '@/app/components/Form/MyMultiSelect'
import MySelect2 from '@/app/components/Form/MySelect2'
import { getOptionsDatamasterSatuan } from '@/redux/ducks/datamastersatuan/action'
import MyUpload from '@/app/components/Form/MyUpload'
import axios from 'axios'

const Indikator = () => {
    const navigate = useNavigate()
    const dispatch = useDispatch()
    const [selectedSatuan, setSelectedSatuan] = React.useState({})
    const [selectedOpd, setSelectedOpd] = React.useState(undefined)
    const [opdOptions, setOpdOptions] = React.useState([])
    const [satuanOptions, setSatuanOptions] = React.useState([])

    const reduxSatuan = useSelector((state) => state.datamasterSatuanState?.options || [])
    const reduxOpd = useSelector((state) => state.datamasterOpdState?.options || [])

    React.useEffect(() => {
        dispatch(getOptionsDatamasterSatuan())
        dispatch(getOptionsDatamasterOpd())
    }, [])

    React.useEffect(() => {
        if (reduxOpd.length > 0 && opdOptions.length === 0) {
            setOpdOptions(reduxOpd.map(item => ({ label: item.nama_opd, value: item.id })))
        }
    }, [reduxOpd])

    React.useEffect(() => {
        if (reduxSatuan.length > 0 && satuanOptions.length === 0) {
            setSatuanOptions(reduxSatuan.map(item => ({ label: item.satuan, value: item.id })))
        }
    }, [reduxSatuan])

    const optionsSatuan = () => satuanOptions
    const optionsOpd = () => opdOptions
    const tujuanKdhState = useSelector((state) => state.tujuanKdhState)
    const sasaranKdhState = useSelector((state) => state.sasaranKdhState)
    const indikatorKdhState = useSelector((state) => state.indikatorKdhState)
    const [searchParams, setSearchParams] = useSearchParams()
    const [openModal, setOpenModal] = React.useState(false)
    const [formTitle, setFormTitle] = React.useState("FORM TAMBAH INDIKATOR")
    const [selectedTujuan, setSelectedTujuan] = React.useState("Tujuan")
    const [selectedSasaran, setSelectedSasaran] = React.useState("")
    const [editId, setEditId] = React.useState("")
    const selectedVisiId = searchParams.get("visiId")
    const selectedMisiId = searchParams.get("misiId")
    const selectedTujuanId = searchParams.get("tujuanId")
    const selectedSasaranId = searchParams.get("sasaranId")
    React.useEffect(() => { initFlowbite() },[indikatorKdhState])
    const getDataTable = async (page = 1, per_page = 10, search = "") => {
        const response = await dispatch(getListIndikatorKdh({ page, per_page, search, tujuan_id: selectedTujuanId ?? "", sasaran_id: selectedSasaranId ?? ""}))
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

    React.useEffect(() => { tujuanKdhState.data ? setSelectedTujuan(tujuanKdhState.data.tujuan) : null }, [tujuanKdhState.data])
    React.useEffect(() => { 
        if(selectedSasaranId){
            sasaranKdhState.data ? setSelectedSasaran(sasaranKdhState.data.sasaran) : null 
        }
        else{
            setSelectedSasaran("");
        }
    }, [sasaranKdhState.data])
    const [isTujuan, setIsTujuan] = React.useState(false)
    React.useEffect(() => {
        if(selectedTujuanId){
            dispatch( getTujuanKdh(selectedTujuanId ?? "") ).then((result) => {
                if(result.error === null){
                    if(selectedSasaranId){
                        setIsTujuan(false)
                        dispatch( getSasaranKdh(selectedSasaranId ?? "") ).then((result) => {
                            if(result.error === null){
                                getDataTable()
                            }
                            else navigate('/perencanaan/kdh/pohonkinerja/visi')
                        })
                    }
                    else{ getDataTable(); setIsTujuan(true); }
                }
                else navigate('/perencanaan/kdh/pohonkinerja/visi')
            })
        }
        else navigate('/perencanaan/kdh/pohonkinerja/visi')
        
    },[selectedTujuanId])

    const formik = useFormik({
        initialValues: {
            order: 0,
            indikator: undefined,
            isActive: false,
            target_1: 0,
            target_2: 0,
            target_3: 0,
            target_4: 0,
            target_5: 0,
            definisi: undefined,
            baseline: undefined,
            sumber_data: undefined,
            rilis: undefined,
        },
        validationSchema: Yup.object({ 
            order:           Yup.number().required().strict(true),
            indikator:           Yup.string().required().strict(true),
            isActive:       Yup.boolean().required(),
            target_1: Yup.number().required().strict(true),
            target_2: Yup.number().required().strict(true),
            target_3: Yup.number().required().strict(true),
            target_4: Yup.number().required().strict(true),
            target_5: Yup.number().required().strict(true),
            definisi: Yup.string().required().strict(true),
            baseline: Yup.string().required().strict(true),
            sumber_data: Yup.string().required().strict(true),
            rilis: Yup.string().required().strict(true),
        }),
        enableReinitialize: true
    })

    const [uploadFile, setUploadFile] = React.useState(null)
    const openModalAction = () => {
        formik.resetForm();
        setSelectedOpd([])
        setEditId("")
        setUploadFile(null)
        setSelectedSatuan({})
        let typeForm = selectedSasaranId ? "FORM TAMBAH INDIKATOR SASARAN" : "FORM TAMBAH INDIKATOR TUJUAN"
        setFormTitle(typeForm)
        setOpenModal(true);
    }

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
            const opd_pendukung = selectedOpd ? selectedOpd.map((item) => (item.value)) : null
            const payload = {
                pohon_kinerja_tujuan_id: selectedTujuanId,
                pohon_kinerja_sasaran_id: selectedSasaranId,
                order: form.order,
                opd_pendukung: opd_pendukung,
                indikator: form.indikator,
                is_tujuan: isTujuan,
                is_active: form.isActive,
                target_1: form.target_1,
                target_2: form.target_2,
                target_3: form.target_3,
                target_4: form.target_4,
                target_5: form.target_5,
                defenisi: form.definisi,
                baseline: form.baseline,
                sumber_data: form.sumber_data,
                rilis: form.rilis,
                satuan_id: selectedSatuan.value
            }
            const formData = new FormData()
            formData.append('pohon_kinerja_tujuan_id', selectedTujuanId)
            formData.append('pohon_kinerja_sasaran_id', selectedSasaranId)
            formData.append('order', form.order)
            formData.append('opd_pendukung', opd_pendukung)
            formData.append('indikator', form.indikator)
            formData.append('is_tujuan', isTujuan ? 1 : 0)
            formData.append('is_active', form.isActive ? 1 : 0)
            formData.append('target_1', form.target_1)
            formData.append('target_2', form.target_2)
            formData.append('target_3', form.target_3)
            formData.append('target_4', form.target_4)
            formData.append('target_5', form.target_5)
            formData.append('definisi', form.definisi)
            formData.append('baseline', form.baseline)
            formData.append('sumber_data', form.sumber_data)
            formData.append('rilis', form.rilis)
            formData.append('satuan_id', selectedSatuan.value)
            if(uploadFile !== null){
                formData.append('formula_perhitungan', uploadFile)
            }
            
            // submit payload with dispatch action redux
            let response = null
            if(editId !== "") response = await dispatch(updateIndikatorKdh(editId, payload))
            else response = await dispatch(createIndikatorKdh(formData));
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
        console.log(data);
        
        formik.resetForm()
        formik.setFieldValue('order', data.order);
        formik.setFieldValue('indikator', data.indikator);
        formik.setFieldValue('isActive', data.is_active)
        formik.setFieldValue('target_1', Number(data.target_1));
        formik.setFieldValue('target_2', Number(data.target_2));
        formik.setFieldValue('target_3', Number(data.target_3));
        formik.setFieldValue('target_4', Number(data.target_4));
        formik.setFieldValue('target_5', Number(data.target_5));
        formik.setFieldValue('definisi', data.defenisi);
        formik.setFieldValue('baseline', data.baseline);
        formik.setFieldValue('sumber_data', data.sumber_data);
        formik.setFieldValue('rilis', data.rilis);
        if(data.satuan_id !== null){ 
            setSelectedSatuan({ label: data.satuan ?? "", value: data.satuan_id ?? "" }) 
            
        }
        setEditId(data.id)

        let opds = data?.opd_pendukung?.length > 0 ? data.opd_pendukung.map((item) => ({label: item.nama_opd, value: item.id })) : []
        setSelectedOpd(opds)
        
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
                const response = await dispatch(deleteIndikatorKdh(id))
                
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

    const [openPdfModal, setOpenPdfModal] = React.useState(false)
    const [pdfViewerUrl, setPdfViewerUrl] = React.useState(null)
    const [pdfLoading, setPdfLoading] = React.useState(false)

    const openFormulaModalAction = (id) => {
        setUploadFile(null)
        setEditId(id)
        showPdfInIframe(id)
    }

    const closePdfModal = () => {
        if (pdfViewerUrl) {
            window.URL.revokeObjectURL(pdfViewerUrl)
            setPdfViewerUrl(null)
        }
        setOpenPdfModal(false)
    }

    const showPdfInIframe = async (id) => {
        try {
            if (pdfViewerUrl) {
                window.URL.revokeObjectURL(pdfViewerUrl)
                setPdfViewerUrl(null)
            }
            setOpenPdfModal(true)
            setPdfLoading(true)
            const BASE_HOST_URL = import.meta.env.VITE_BASE_HOST_URL
            const apiUrl = `${BASE_HOST_URL}/v1/kdh/pohonkinerja/indikator/upload/${id}`
            const token = localStorage.getItem('token')
            const resp = await axios.get(apiUrl, {
                responseType: 'blob',
                headers: {
                    ...(token ? { Authorization: `Bearer ${token}` } : {}),
                },
            })

            const contentType = resp.headers['content-type'] || ''
            if (!contentType.includes('application/pdf')) {
                const text = await new Response(resp.data).text()
                let json
                try { json = JSON.parse(text) } catch (e) { json = { message: text } }
                throw new Error(json.message || 'Server returned non-pdf response')
            }

            const blob = new Blob([resp.data], { type: 'application/pdf' })
            const url = window.URL.createObjectURL(blob)
            setPdfViewerUrl(url)
            setOpenPdfModal(true)
        } catch (err) {
            console.error('Failed to load PDF', err)
            alert('Gagal menampilkan PDF: ' + (err.message || err))
        } finally {
            setPdfLoading(false)
        }
    }

    React.useEffect(() => {
        const url = pdfViewerUrl
        return () => {
            if (url) window.URL.revokeObjectURL(url)
        }
    }, [pdfViewerUrl])

    const simpanFormulaPerhitungan = async () => {
        const formData = new FormData()
        if(uploadFile === null){
            Swal.fire({
                icon: 'error',
                title: "Silakan pilih file formula perhitungan terlebih dahulu",
                showConfirmButton: false,
                timer: 1500
            })
            return false
        }
        formData.append('formula_perhitungan', uploadFile)
        const response = await dispatch(uploadFormulaPerhitunganKdh(editId, formData))
        if(response.status !== "failed"){
            setUploadFile(null)
            showPdfInIframe(editId)
            Swal.fire({
                icon: 'success',
                title: response.data.message,
                showConfirmButton: false,
                timer: 1500
            })
        }
        else{
            Swal.fire({
                icon: 'error',
                title: response.data.message,
                showConfirmButton: false,
                timer: 1500
            })
        }
    }

    return (
        <Layout loading={(tujuanKdhState.loading || sasaranKdhState.loading)}>
            <HeaderPohonKinerja />
            <div className="bg-white h-auto dark:bg-gray-800 rounded-lg drop-shadow-xl py-5 px-4 w-full flex min-h-[35rem]">
                <TabMenuPohonKinerja 
                    active='indikator' 
                    params={{ 
                        visiId: selectedVisiId,
                        misiId: selectedMisiId,
                        tujuanId: selectedTujuanId,
                        sasaranId: selectedSasaranId
                    }}
                />
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
                        <PrimaryBtn loading={indikatorKdhState.loading} onClick={() => openModalAction()} >
                            <PlusCircleIcon className="w-5 h-5" />
                            Tambah Indikator
                        </PrimaryBtn>
                    </div>
                    <TableSection getDataAction={getDataTable} pagination={indikatorKdhState.pagination}>
                        <MyTable>
                            <TableHeader>
                            <tr>
                                <th scope="col" className="px-4 py-3 w-[3%]">No.</th>
                                <th scope="col" className="px-4 py-3">Indikator</th>
                                <th scope="col" className="px-4 py-3">Opd</th>
                                <th scope="col" className="px-4 py-3">Active</th>
                                <th scope="col" className="px-4 py-3 w-[10%]">
                                    <span className="sr-only">Actions</span>
                                </th>
                            </tr>
                            </TableHeader>
                            <TableBody>
                                {
                                    indikatorKdhState.loading ? 
                                    <tr className="border-b dark:border-gray-700">
                                        <td scope="row" className="px-4 py-5 bg-gray-100 dark:bg-gray-800 dark:text-white" colSpan="100%">
                                            <div className="flex flex-row justify-center w-full gap-12">
                                                <PacmanLoader size={10} color='gray' />
                                                Please Wait...
                                            </div>
                                        </td>
                                    </tr> : 
                                    (indikatorKdhState.list.length > 0 ? 
                                        indikatorKdhState.list.map((item, key) =>(
                                            <tr key={item.id} className="border-b dark:border-gray-700 odd:bg-gray-100 dark:odd:bg-gray-900">
                                                <th scope="row"
                                                    className="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white align-top">{item.order}</th>
                                                <td className="px-4 py-3 align-top">
                                                    {item.indikator}
                                                </td>
                                                <td className="px-4 py-3">
                                                    {
                                                        item.opd_pendukung.length > 0 ?
                                                        <ul className="list-disc list-inside">
                                                        {
                                                            item.opd_pendukung.map((val, key) => {
                                                                        return <li>{val.nama_opd} </li>
                                                                    
                                                                }
                                                            )
                                                        }
                                                        </ul> 
                                                        : ""
                                                    }
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
                                                                <a href="#" onClick={() => openFormulaModalAction(item.id)}
                                                                    className="flex gap-1 py-2 px-4 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">
                                                                    <EyeIcon className='w-5 h-5' />
                                                                    Lihat Formula
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
                                selectedSasaranId ? <MyInput id="sasaranId" name="sasaranId" label="Sasaran"
                                value={selectedSasaran ? selectedSasaran : "-"} disabled /> : null
                            }
                            
                            <MyInput id="order" name="order" label="Urutan" type="number" placeholder='Inputkan nomor urut...'
                                value={formik.values.order} onChange={formik.handleChange} onBlur={formik.handleBlur}
                                error={(formik.errors.order && formik.touched.order) ? formik.errors.order : ""} 
                            />
                            <MyMultiSelect
                                id="opd_pendukung"
                                label="OPD Pendukung"
                                options={optionsOpd()}
                                value={selectedOpd}
                                onChange={setSelectedOpd}
                                key={opdOptions.length}
                            />
                            <MyTextarea id="indikator" name="indikator" label="Indikator" placeholder='Inputkan indikator...' 
                                value={formik.values.indikator} onChange={formik.handleChange} onBlur={formik.handleBlur}
                                error={(formik.errors.indikator && formik.touched.indikator) ? formik.errors.indikator : ""}
                            />
                            <div className="flex w-fill justify-center">
                                <MyToggle id="isActive" name="isActive" label="Aktif" value={formik.values.isActive}
                                    error={formik.errors.isActive}
                                    onChange={formik.handleChange} />
                            </div>
                            <div className="flex flex-col gap-1 sm:mb-4 mb-2">
                                <label className="block text-sm font-medium text-gray-900 dark:text-white">Satuan</label>
                                <select id="satuan" name="satuan"
                                    className="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                    value={selectedSatuan?.value || ''}
                                    onChange={(e) => {
                                        const opt = satuanOptions.find(o => o.value === e.target.value)
                                        setSelectedSatuan(opt || {})
                                    }}>
                                    <option value="">- Pilih Satuan -</option>
                                    {satuanOptions.map(s => <option key={s.value} value={s.value}>{s.label}</option>)}
                                </select>
                            </div>
                            <div className="w-full">
                                <h1 className="font-semibold py-2 dark:text-white">
                                TARGET INDIKATOR
                                </h1>
                            </div>
                            <div className="w-full grid sm:grid-cols-5 md:gap-5 sm:gap-3">
                                <MyInput 
                                    id="target_1" 
                                    name="target_1" 
                                    label="Tahun ke-1" 
                                    type='number'
                                    placeholder='Inputkan Target tahun ke-1'
                                    value={formik.values.target_1} 
                                    onChange={formik.handleChange}
                                    onBlur={formik.handleBlur}
                                    error={formik.touched.target_1 && formik.errors.target_1}
                                />
                                <MyInput 
                                    id="target_2" 
                                    name="target_2" 
                                    label="Tahun ke-2" 
                                    type='number'
                                    placeholder='Inputkan Target tahun ke-2'
                                    value={formik.values.target_2} 
                                    onChange={formik.handleChange}
                                    onBlur={formik.handleBlur}
                                    error={formik.touched.target_2 && formik.errors.target_2}
                                />
                                <MyInput 
                                    id="target_3" 
                                    name="target_3" 
                                    label="Tahun ke-3" 
                                    type='number'
                                    placeholder='Inputkan Target tahun ke-3'
                                    value={formik.values.target_3} 
                                    onChange={formik.handleChange}
                                    onBlur={formik.handleBlur}
                                    error={formik.touched.target_3 && formik.errors.target_3}
                                />
                                <MyInput 
                                    id="target_4" 
                                    name="target_4" 
                                    label="Tahun ke-4" 
                                    type='number'
                                    placeholder='Inputkan Target tahun ke-4'
                                    value={formik.values.target_4} 
                                    onChange={formik.handleChange}
                                    onBlur={formik.handleBlur}
                                    error={formik.touched.target_4 && formik.errors.target_4}
                                />
                                <MyInput 
                                    id="target_5" 
                                    name="target_5" 
                                    label="Tahun ke-5" 
                                    type='number'
                                    placeholder='Inputkan Target tahun ke-5'
                                    value={formik.values.target_5} 
                                    onChange={formik.handleChange}
                                    onBlur={formik.handleBlur}
                                    error={formik.touched.target_5 && formik.errors.target_5}
                                />
                            </div>
                            <MyTextarea 
                                id="definisi" 
                                name='definisi' 
                                label='Definisi'
                                value={formik.values.definisi} 
                                onChange={formik.handleChange}
                                onBlur={formik.handleBlur}
                                error={(formik.touched.definisi && formik.errors.definisi) ? formik.errors.definisi : ""}
                            />
                            <MyInput 
                                id="baseline" 
                                name='baseline' 
                                label='Baseline'
                                value={formik.values.baseline} 
                                onChange={formik.handleChange}
                                onBlur={formik.handleBlur}
                                error={(formik.touched.baseline && formik.errors.baseline) ? formik.errors.baseline : ""}
                            />
                            <MyInput 
                                id="sumber_data" 
                                name='sumber_data' 
                                label='Sumber Data'
                                value={formik.values.sumber_data} 
                                onChange={formik.handleChange}
                                onBlur={formik.handleBlur}
                                error={(formik.touched.sumber_data && formik.errors.sumber_data) ? formik.errors.sumber_data : ""}
                            />
                            <MyInput 
                                id="rilis" 
                                name='rilis' 
                                label='Rilis'
                                value={formik.values.rilis} 
                                onChange={formik.handleChange}
                                onBlur={formik.handleBlur}
                                error={(formik.touched.rilis && formik.errors.rilis) ? formik.errors.rilis : ""}
                            />
                            <div className={`block mt-3 ${editId !== "" ? "hidden" : ""}`}>
                                <MyUpload 
                                    id="file"
                                    name="file"
                                    label="Formula Perhitungan"
                                    notes='PDF (Max. 2MB)'
                                    onChange={(e) => setUploadFile(e.target.files[0])}
                                />
                            </div>
                        </div>
                        
                        <div className="mt-5 sm:mt-6 flex justify-center">
                            <PrimaryBtn onClick={()=> simpanData()} loading={indikatorKdhState.loading} >
                                Simpan Data
                            </PrimaryBtn>
                        </div>
                    </MyModal>
                    <MyModal ModalTitle="Formula Perhitungan" openModal={openPdfModal} setOpenModal={closePdfModal} size='lg'>
                        <div className="flex border-t-2 border-gray-300 flex-col w-full p-4">
                            {pdfLoading && (
                                <div className="flex justify-center items-center py-12">
                                    <PacmanLoader size={20} color="#0d9488" />
                                    <span className="ml-2 dark:text-white">Memuat PDF...</span>
                                </div>
                            )}
                            {!pdfLoading && pdfViewerUrl && (
                                <iframe
                                    src={pdfViewerUrl}
                                    title="Preview Perjanjian Kinerja"
                                    className="w-full min-h-[70vh] border-0 rounded"
                                />
                            )}
                        </div>
                        <div className="block mt-3 w-full md:w-1/3 sm:w-1/2">
                            <MyUpload 
                                id="file"
                                name="file"
                                label="Upload Formula Perhitungan"
                                notes='PDF (Max. 2MB)'
                                onChange={(e) => setUploadFile(e.target.files[0])}
                            />
                        </div>
                        <div className="mt-5 sm:mt-6 flex justify-center">
                            <PrimaryBtn onClick={()=> simpanFormulaPerhitungan()} loading={indikatorKdhState.loading} >
                                Simpan Formula Perhitungan
                            </PrimaryBtn>
                        </div>
                    </MyModal>
                </div>
            </div>
        </Layout>
    )
}

export default Indikator