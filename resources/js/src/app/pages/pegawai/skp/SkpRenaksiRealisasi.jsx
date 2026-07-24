import Layout from '@/app/components/Layout/Layout'
import React from 'react'
import GoodNotes from "@assets/GoodNotes.png"
import { useNavigate, useParams } from 'react-router-dom'
import PrimaryBtn from '@/app/components/Button/PrimaryBtn'
import PrimaryLinkBtn from '@/app/components/Button/PrimaryLinkBtn'
import { PencilSquareIcon, ArrowLeftCircleIcon, PlusCircleIcon, TrashIcon } from '@heroicons/react/24/outline'
import { StaticTable } from '@/app/components/Table'
import { useDispatch, useSelector } from 'react-redux'
import { getListRencanaAksiSkpRealisasi, updateRencanaAksiSkpRealisasi } from '@/redux/ducks/skp/action'
import { PacmanLoader } from 'react-spinners'
import { initFlowbite } from 'flowbite'
import MyModal from '@/app/components/Form/MyModal'
import MyInput from '@/app/components/Form/MyInput'
import MyTextarea from '@/app/components/Form/MyTextarea'
import { useFormik } from 'formik'
import * as Yup from "yup"
import Swal from 'sweetalert2'

const SkpRenaksiRealisasi = () => {
    const [baseDataActive, setBaseDataActive] = React.useState(null)
    const { idskp, id } = useParams()
    const dispatch = useDispatch()
    const skpState = useSelector((state) => state.skpState)
    React.useEffect(() => {
        getDataTable()
    },[idskp, id])
    React.useEffect(() => { initFlowbite() },[skpState.list_rencana_aksi_realisasi])

    const getDataTable = () => {
        dispatch(getListRencanaAksiSkpRealisasi({skp_id: idskp, indikator_skp_id: id}))
    }

    const tableHeader = () => (
        <>
        <tr>
            <th scope="col" rowSpan="3" className="px-4 py-3 border text-center w-[1%]">No.</th>
            <th scope="col" rowSpan="3" className="px-4 py-3 border text-center">Langkah-langkah pencapaian target</th>
            <th scope="col" rowSpan="3" className="px-4 py-3 border text-center">Satuan</th>
            <th scope="col" colSpan="12" className="px-4 py-3 border text-center">Triwulan</th>
            <th scope="col" rowSpan="3" className="px-4 py-3 border text-center">Keterangan</th>
            <th scope="col" rowSpan="3" className="px-4 py-3 border">
                <span className="sr-only">Actions</span>
            </th>
        </tr>
        <tr>
            <th scope="col" colSpan="3" className="px-4 py-3 border text-center w-[5%]">I</th>
            <th scope="col" colSpan="3" className="px-4 py-3 border text-center w-[5%]">II</th>
            <th scope="col" colSpan="3" className="px-4 py-3 border text-center w-[5%]">III</th>
            <th scope="col" colSpan="3" className="px-4 py-3 border text-center w-[5%]">IV</th>
        </tr>
        <tr>
            <th scope="col" className="px-4 py-3 border text-center w-[1%]">T</th>
            <th scope="col" className="px-4 py-3 border text-center w-[1%]">R</th>
            <th scope="col" className="px-4 py-3 border text-center w-[1%]">C</th>
            <th scope="col" className="px-4 py-3 border text-center w-[1%]">T</th>
            <th scope="col" className="px-4 py-3 border text-center w-[1%]">R</th>
            <th scope="col" className="px-4 py-3 border text-center w-[1%]">C</th>
            <th scope="col" className="px-4 py-3 border text-center w-[1%]">T</th>
            <th scope="col" className="px-4 py-3 border text-center w-[1%]">R</th>
            <th scope="col" className="px-4 py-3 border text-center w-[1%]">C</th>
            <th scope="col" className="px-4 py-3 border text-center w-[1%]">T</th>
            <th scope="col" className="px-4 py-3 border text-center w-[1%]">R</th>
            <th scope="col" className="px-4 py-3 border text-center w-[1%]">C</th>
        </tr>
        </>
    )
    const renderTable = () => {
        return (
            loading ? 
            <tr className="border-b dark:border-gray-700">
                <td scope="row" className="px-4 py-5 bg-gray-100 dark:bg-gray-800 dark:text-white" colSpan="100%">
                    <div className="flex flex-row justify-center w-full gap-12">
                        <PacmanLoader size={10} color='gray' />
                        Please Wait...
                    </div>
                </td>
            </tr> : 
            (skpState.list_rencana_aksi_realisasi.length > 0 ? 
                skpState.list_rencana_aksi_realisasi.map((item, key) =>(
                    <tr key={item.id} className="border-b dark:border-gray-700 odd:bg-gray-100 dark:odd:bg-gray-900">
                        <td className="px-4 py-3 border text-right">{key+1}</td>
                        <td className="px-4 py-3 border">{item.langkah}</td>
                        <td className="px-4 py-3 border">{item.satuan}</td>
                        <td className="px-4 py-3 border text-right">{item.target_tw1}</td>
                        <td className="px-4 py-3 border text-right">{item.realisasi_tw1}</td>
                        <td className="px-4 py-3 border text-right">{item.capaian_tw1}</td>
                        <td className="px-4 py-3 border text-right">{item.target_tw2}</td>
                        <td className="px-4 py-3 border text-right">{item.realisasi_tw2}</td>
                        <td className="px-4 py-3 border text-right">{item.capaian_tw2}</td>
                        <td className="px-4 py-3 border text-right">{item.target_tw3}</td>
                        <td className="px-4 py-3 border text-right">{item.realisasi_tw3}</td>
                        <td className="px-4 py-3 border text-right">{item.capaian_tw3}</td>
                        <td className="px-4 py-3 border text-right">{item.target_tw4}</td>
                        <td className="px-4 py-3 border text-right">{item.realisasi_tw4}</td>
                        <td className="px-4 py-3 border text-right">{item.capaian_tw4}</td>
                        <td className="px-4 py-3 border">{item.keterangan}</td>
                        <td className="px-4 py-3 border text-center">
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
                                            Input Realisasi
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                )) :
                <tr className="border-b dark:border-gray-700">
                    <td scope="row" className="px-4 py-5 bg-gray-100 dark:bg-gray-800 dark:text-white" colSpan="100%">
                        <div className="flex flex-row justify-center w-full gap-12">
                            No Data
                        </div>
                    </td>
                </tr>
            )
        )
    }
    const [openModal, setOpenModal] = React.useState(false)
    const [editId, setEditId] = React.useState("")
    const [formTitle, setFormTitle] = React.useState("Form Input Realisasi Rencana Aksi")
    const editAction = (data) => {
        formik.resetForm();
        setEditId(data.id)
        setOpenModal(true)
        setInitFormData({
            langkah: data.langkah,
            satuan: data.satuan,
            target_tw1: data.target_tw1,
            target_tw2: data.target_tw2,
            target_tw3: data.target_tw3,
            target_tw4: data.target_tw4,
            keterangan: data.keterangan,
        })
    }

    const [initFormData, setInitFormData] = React.useState({
        langkah: "",
        satuan: "",
        target_tw1: 0,
        target_tw2: 0,
        target_tw3: 0,
        target_tw4: 0,
        keterangan: 0
    })

    const formik = useFormik({
        initialValues: {
            realisasi_tw1: "",
            realisasi_tw2: "",
            realisasi_tw3: "",
            realisasi_tw4: "",
        },
        validationSchema: Yup.object({ 
            realisasi_tw1:           Yup.number().required().strict(true),
            realisasi_tw2:           Yup.number().required().strict(true),
            realisasi_tw3:           Yup.number().required().strict(true),
            realisasi_tw4:           Yup.number().required().strict(true),
        }),
        enableReinitialize: true
    })

    const validationForm = async () => {
        //validation
        formik.setFieldTouched('realisasi_tw1', true, true)
        formik.setFieldTouched('realisasi_tw2', true, true)
        formik.setFieldTouched('realisasi_tw3', true, true)
        formik.setFieldTouched('realisasi_tw4', true, true)
        const errors = await formik.validateForm();

        return errors
    }

    const simpanData= async ()=> {
        
        const errors = await validationForm()
        if (Object.keys(errors).length === 0) {
            // Form is valid, do any success call, mapping payload before submit
            const form = formik.values
            const payload = {
                ...form
            }
            
            // console.log(payload); return false;
            
            // submit payload with dispatch action redux
            let response = await dispatch(updateRencanaAksiSkpRealisasi(editId, payload))
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

    const loading = skpState.loading 

    return (
        <Layout>
            <div className="bg-white h-auto dark:bg-gray-800 rounded-lg drop-shadow-xl py-1 px-3 w-full">
                <div className="dark:text-white">
                    <div className="flex flex-row items-center gap-3">
                        <div>
                            <img src={GoodNotes} alt="Pengukuran Pegawai" className="object-contain" />
                        </div>
                        <div className="lg:text-lg font-bold text-teal-500 dark:text-white">Realisasi Rencana Aksi SKP</div>
                    </div>
                </div>
            </div>
            <div className="bg-white h-auto dark:bg-gray-800 rounded-lg drop-shadow-xl py-1 sm:px-3 px-1 w-full flex flex-col lg:min-h-[35rem]">
                <div className="block w-full p-4">
                    <h1 className="text-center font-semibold text-lg dark:text-white">Realisasi Langkah Pencapaian Target Rencana Aksi</h1>
                    <h1 className="text-center italic text-lg dark:text-white">" {baseDataActive?.indikator?.indikator ?? "(no data)"} "</h1>
                    <div className="flex justify-between gap-3">
                        <PrimaryLinkBtn to={`/pegawai/realisasiskp`}>
                            <ArrowLeftCircleIcon className='w-5 h-5' />
                            Kembali
                        </PrimaryLinkBtn>
                    </div>
                </div>

                <StaticTable header={tableHeader()}>   
                {
                    renderTable()                    
                }
                </StaticTable>
                <MyModal  ModalTitle={formTitle} openModal={openModal} setOpenModal={setOpenModal} >
                    <div className="flex flex-col w-full p-4">
                        <MyInput 
                            id="langkah" 
                            name="langkah" 
                            label="Langkah" 
                            type='text'
                            value={initFormData.langkah}
                            disabled
                        />
                        <MyInput id="satuan" name="satuan" 
                            label="Satuan" 
                            value={initFormData.satuan} 
                            disabled
                        />
                        <div className="block w-full py-2">
                            <h1 className="font-bold dark:text-white">Target per Triwulan (TW)</h1>
                        </div>
                        <div className="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-2 sm:gap-3 md:gap-5 py-2">
                            <MyInput id="target_tw1" name="target_tw1" 
                                label="TW ke-1" 
                                value={initFormData.target_tw1}
                                disabled
                            />
                            <MyInput id="target_tw2" name="target_tw2" 
                                label="TW ke-2" 
                                value={initFormData.target_tw2} 
                                disabled
                            />
                            <MyInput id="target_tw3" name="target_tw3" 
                                label="TW ke-3" 
                                value={initFormData.target_tw3}
                                disabled
                            />
                            <MyInput id="target_tw4" name="target_tw4" 
                                label="TW ke-4" 
                                value={initFormData.target_tw4}
                                disabled
                            />
                        </div>
                        <MyTextarea 
                            id="keterangan" 
                            name="keterangan" 
                            label="Keterangan" 
                            value={initFormData.keterangan}
                            disabled
                        />
                        <div className="block w-full py-2">
                            <h1 className="font-bold dark:text-white">Realisasi per Triwulan (TW)</h1>
                        </div>
                        <div className="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-2 sm:gap-3 md:gap-5 py-2">
                            <MyInput id="realisasi_tw1" name="realisasi_tw1" 
                                label="TW ke-1" 
                                placeholder='Input realiasi...'
                                type='number'
                                value={formik.values.realisasi_tw1} onChange={formik.handleChange} onBlur={formik.handleBlur}
                                error={(formik.errors.realisasi_tw1 && formik.touched.realisasi_tw1) ? formik.errors.realisasi_tw1 : ""}
                            />
                            <MyInput id="realisasi_tw2" name="realisasi_tw2" 
                                label="TW ke-2" 
                                placeholder='Input realiasi...'
                                type='number'
                                value={formik.values.realisasi_tw2} onChange={formik.handleChange} onBlur={formik.handleBlur}
                                error={(formik.errors.realisasi_tw2 && formik.touched.realisasi_tw2) ? formik.errors.realisasi_tw2 : ""}
                            />
                            <MyInput id="realisasi_tw3" name="realisasi_tw3" 
                                label="TW ke-3" 
                                placeholder='Input realiasi...'
                                type='number'
                                value={formik.values.realisasi_tw3} onChange={formik.handleChange} onBlur={formik.handleBlur}
                                error={(formik.errors.realisasi_tw3 && formik.touched.realisasi_tw3) ? formik.errors.realisasi_tw3 : ""}
                            />
                            <MyInput id="realisasi_tw4" name="realisasi_tw4" 
                                label="TW ke-4" 
                                placeholder='Input realiasi...'
                                type='number'
                                value={formik.values.realisasi_tw4} onChange={formik.handleChange} onBlur={formik.handleBlur}
                                error={(formik.errors.realisasi_tw4 && formik.touched.realisasi_tw4) ? formik.errors.realisasi_tw4 : ""}
                            />
                        </div>
                    </div>
                    <div className="mt-5 sm:mt-6 flex justify-center">
                        <PrimaryBtn loading={loading} onClick={() => simpanData()} >
                            Simpan Data
                        </PrimaryBtn>
                    </div>
                </MyModal>
            </div>
        </Layout>
    )
}

export default SkpRenaksiRealisasi