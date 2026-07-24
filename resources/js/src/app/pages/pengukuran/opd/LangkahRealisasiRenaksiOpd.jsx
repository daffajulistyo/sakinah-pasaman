import Layout from '@/app/components/Layout/Layout'
import React from 'react'
import GoodNotes from "@assets/GoodNotes.png"
import { useNavigate, useSearchParams } from 'react-router-dom'
import { StaticTable } from '@/app/components/Table'
import { PencilSquareIcon, ArrowLeftCircleIcon, PlusCircleIcon, TrashIcon } from '@heroicons/react/24/outline'
import MyModal from '@/app/components/Form/MyModal'
import MyInput from '@/app/components/Form/MyInput'
import PrimaryBtn from '@/app/components/Button/PrimaryBtn'
import PrimaryLinkBtn from '@/app/components/Button/PrimaryLinkBtn'
import MyTextarea from '@/app/components/Form/MyTextarea'
import Swal from 'sweetalert2'
import { initFlowbite } from 'flowbite'
import { useFormik } from 'formik'
import * as Yup from "yup"
import { useSelector, useDispatch } from 'react-redux'
import { getListRealisasiRenaksiOpdLangkah, getListRealisasiRenaksiOpd, createRealisasiRenaksiOpdLangkah } from '@/redux/ducks/realisasirenaksiopd/action'

const LangkahRealisasiRenaksiOpd = () => {
    const realisasiRenaksiOpdState = useSelector((state) => state.realisasiRenaksiOpdState)
    const [searchParams, setSearchParams] = useSearchParams()
    const [baseDataActive, setBaseDataActive] = React.useState(null)
    const navigate = useNavigate()
    const dispatch = useDispatch()
    const sasaranidActive = searchParams.get('sasaranid')
    const indikatoridActive = searchParams.get('indikatorid')
    const yearActive = searchParams.get('tahun')
    const getDataTable = () => {
        dispatch(getListRealisasiRenaksiOpdLangkah({
            sasaran_opd_id: sasaranidActive,
            indikator_opd_id: indikatoridActive,
            tahun: yearActive
        }))
    }
    React.useEffect(() => {
        getDataTable()
    },[])
    React.useEffect(() => {
        if(realisasiRenaksiOpdState.list.length === 0){ 
            dispatch(getListRealisasiRenaksiOpd({ tahun: yearActive }))
        }
        else{
            let baseData = null
            let dataSasaran = realisasiRenaksiOpdState.list.find((item) => {
                return item.id === sasaranidActive
            })
            if(dataSasaran){
                let dataIndikator = dataSasaran.indikator_sasaran.find((item) => {
                    return item.id === indikatoridActive
                })
                if(dataIndikator){
                    baseData = {
                        ...dataSasaran,
                        indikator: dataIndikator
                    }
                    setBaseDataActive(baseData)
                }
            }
            
        }
    }, [realisasiRenaksiOpdState.list])
    React.useEffect(() => { initFlowbite() }, [realisasiRenaksiOpdState.list_langkah])
    const [formContent, setFormContent] = React.useState({
        langkah: "",
        target_tw1: 0,
        target_tw2: 0,
        target_tw3: 0,
        target_tw4: 0
    })

    const tableHeader = () => (
        <>
            <tr>
                <th scope="col" className="px-4 py-3 border w-[3%]" rowSpan="2">No.</th>
                <th scope="col" className="px-4 py-3 border text-center" rowSpan="2">Langkah-langkah Pencapaian Target</th>
                <th scope="col" className="px-4 py-3 border text-center" colSpan="3">TW 1</th>
                <th scope="col" className="px-4 py-3 border text-center" colSpan="3">TW 2</th>
                <th scope="col" className="px-4 py-3 border text-center" colSpan="3">TW 3</th>
                <th scope="col" className="px-4 py-3 border text-center" colSpan="3">TW 4</th>
                <th scope="col" className="px-4 py-3 border w-[5%]" rowSpan="2">
                    <span className="sr-only">Actions</span>
                </th>
            </tr>
            <tr>
                <th scope="col" className="px-4 py-3 border text-center w-[5%]">T</th>
                <th scope="col" className="px-4 py-3 border text-center w-[5%]">R</th>
                <th scope="col" className="px-4 py-3 border text-center w-[5%]">C</th>
                <th scope="col" className="px-4 py-3 border text-center w-[5%]">T</th>
                <th scope="col" className="px-4 py-3 border text-center w-[5%]">R</th>
                <th scope="col" className="px-4 py-3 border text-center w-[5%]">C</th>
                <th scope="col" className="px-4 py-3 border text-center w-[5%]">T</th>
                <th scope="col" className="px-4 py-3 border text-center w-[5%]">R</th>
                <th scope="col" className="px-4 py-3 border text-center w-[5%]">C</th>
                <th scope="col" className="px-4 py-3 border text-center w-[5%]">T</th>
                <th scope="col" className="px-4 py-3 border text-center w-[5%]">R</th>
                <th scope="col" className="px-4 py-3 border text-center w-[5%]">C</th>
            </tr>
        </>
    )

    const renderTable = () => (
        realisasiRenaksiOpdState.list_langkah.length > 0 ? realisasiRenaksiOpdState.list_langkah.map((item, x) => (
            <tr key={x} className="border-b dark:border-gray-700">
                <td className="px-4 py-3 border text-right">{x+1}</td>
                <td className="px-4 py-3 border">{item.langkah}</td>
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
                <td className="px-4 py-3 border text-center">
                    <button id={`btn-${x}`} data-dropdown-toggle={`toggle-btn${x}`}
                        className="inline-flex items-center p-0.5 text-sm font-medium text-center text-gray-500 hover:text-gray-800 rounded-lg focus:outline-none dark:text-gray-400 dark:hover:text-gray-100"
                        type="button">
                        <svg className="w-5 h-5" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM16 12a2 2 0 100-4 2 2 0 000 4z" />
                        </svg>
                    </button>
                    <div id={`toggle-btn${x}`}
                        className="hidden z-10 w-48 bg-gray-50 rounded divide-y divide-gray-100 drop-shadow-lg dark:bg-gray-700 dark:divide-gray-600">
                        <ul className="py-1 text-sm text-gray-700 dark:text-gray-200"
                            aria-labelledby={`btn-${x}`}>
                            <li>
                                <button onClick={() => inputRealisasi(item)}
                                    className="flex gap-1 py-2 px-4 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">
                                    <PencilSquareIcon className='w-4 h-4' />
                                    Realisasi
                                </button>
                            </li>
                        </ul>
                    </div>
                </td>
            </tr>
        )) :
        <tr className="border-b dark:border-gray-700">
                <td className="px-4 py-3 border text-center" colSpan="100%">No Data</td>
        </tr>
        
    )

    const [openModal, setOpenModal] = React.useState(false)
    const [editId, setEditId] = React.useState("")
    const [formTitle, setFormTitle] = React.useState("Form Realisasi Langkah-langkah Pencapaian Target")
    const formik = useFormik({
        initialValues: {
            realisasi_tw1: "",
            realisasi_tw2: "",
            realisasi_tw3: "",
            realisasi_tw4: ""
        },
        validationSchema: Yup.object({ 
            realisasi_tw1:           Yup.string().required().strict(true),
            realisasi_tw2:           Yup.string().required().strict(true),
            realisasi_tw3:           Yup.string().required().strict(true),
            realisasi_tw4:           Yup.string().required().strict(true),
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
    const inputRealisasi = (data) => {
        setEditId(data.id)
        setFormContent({
            ...formContent,
            langkah: data.langkah,
            target_tw1: data.target_tw1,
            target_tw2: data.target_tw2,
            target_tw3: data.target_tw3,
            target_tw4: data.target_tw4
        })
        
        formik.setFieldValue('realisasi_tw1', data.realisasi_tw1);
        formik.setFieldValue('realisasi_tw2', data.realisasi_tw2);
        formik.setFieldValue('realisasi_tw3', data.realisasi_tw3);
        formik.setFieldValue('realisasi_tw4', data.realisasi_tw4);
        setOpenModal(true)
    }

    const simpanData= async ()=> {
        
        const errors = await validationForm()
        console.log(errors);
        
        if (Object.keys(errors).length === 0) {
            // Form is valid, do any success call, mapping payload before submit
            const form = formik.values
            const payload = form
            
            // console.log(payload); return false
            
            // submit payload with dispatch action redux
            let response = await dispatch(createRealisasiRenaksiOpdLangkah(editId, payload));
            if(response.error === null){
                console.log(response);
                
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
    return (
        <Layout>
            <div className="bg-white h-auto dark:bg-gray-800 rounded-lg drop-shadow-xl py-1 px-3 w-full">
                <div className="dark:text-white">
                    <div className="flex flex-row items-center gap-3">
                        <div>
                            <img src={GoodNotes} alt="Pengukuran Perangkat Daerah" className="object-contain" />
                        </div>
                        <div className="lg:text-lg font-bold text-teal-500 dark:text-white">Pengukuran Perangkat Daerah</div>
                    </div>
                </div>
            </div>
            <div className="bg-white h-auto dark:bg-gray-800 rounded-lg drop-shadow-xl py-1 sm:px-3 px-1 w-full flex flex-col lg:min-h-[35rem]">
                <div className="block w-full p-4">
                    <h1 className="text-center font-semibold text-lg dark:text-white">Realisasi Langkah Pencapaian Target Rencana Aksi</h1>
                    <h1 className="text-center italic text-lg dark:text-white">" {baseDataActive?.indikator?.indikator ?? "(no data)"} "</h1>
                    <div className="flex justify-between gap-3">
                        <PrimaryLinkBtn to={`/pengukuran/opd/realisasirenaksi`}>
                            <ArrowLeftCircleIcon className='w-5 h-5' />
                            Kembali
                        </PrimaryLinkBtn>
                    </div>
                </div>
                <div className="block w-full p-4">
                    <StaticTable header={tableHeader()}>
                    {
                        !realisasiRenaksiOpdState.loading ? renderTable() :
                        <tr className="border-b dark:border-gray-700">
                                <td className="px-4 py-3 border text-center" colSpan="100%">Loading...</td>
                        </tr>
                    }
                    </StaticTable>
                    <MyModal  ModalTitle={formTitle} openModal={openModal} setOpenModal={setOpenModal} >
                        <div className="flex flex-col w-full p-4">
                            <MyInput id="indikator" name="indikator" label="Indikator" value={baseDataActive?.indikator?.indikator ?? "(no data)"} disabled />
                            <MyTextarea 
                                id="langkah" 
                                name="langkah" 
                                label="Langkah" 
                                placeholder='Inputkan langkah...'
                                value={formContent.langkah}
                                onChange={(e) => setFormContent({...formContent, langkah: e.target.value})} 
                                disabled
                            />
                            
                            <div className="block w-full py-2">
                                <h1 className="font-bold dark:text-white">Target per Triwulan (TW)</h1>
                            </div>
                            <div className="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-2 sm:gap-3 md:gap-5">
                                <MyInput id="target_tw1" name="target_tw1" 
                                    label="TW ke-1" 
                                    placeholder='Input target...'
                                    value={formContent.target_tw1}
                                    disabled
                                />
                                <MyInput id="target_tw2" name="target_tw2" 
                                    label="TW ke-2" 
                                    placeholder='Input target...'
                                    value={formContent.target_tw2}
                                    disabled
                                />
                                <MyInput id="target_tw3" name="target_tw3" 
                                    label="TW ke-3" 
                                    placeholder='Input target...'
                                    value={formContent.target_tw3}
                                    disabled
                                />
                                <MyInput id="target_tw4" name="target_tw4" 
                                    label="TW ke-4" 
                                    placeholder='Input target...'
                                    value={formContent.target_tw4}
                                    disabled
                                />
                            </div>
                            <div className="block w-full py-2">
                                <h1 className="font-bold dark:text-white">Realisasi per Triwulan (TW)</h1>
                            </div>
                            <div className="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-2 sm:gap-3 md:gap-5">
                                <MyInput id="realisasi_tw1" name="realisasi_tw1" 
                                    label="TW ke-1" 
                                    placeholder='Input realisasi...'
                                    value={formik.values.realisasi_tw1} onChange={formik.handleChange} onBlur={formik.handleBlur}
                                    error={(formik.errors.realisasi_tw1 && formik.touched.realisasi_tw1) ? formik.errors.realisasi_tw1 : ""}
                                />
                                <MyInput id="realisasi_tw2" name="realisasi_tw2" 
                                    label="TW ke-2" 
                                    placeholder='Input realisasi...'
                                    value={formik.values.realisasi_tw2} onChange={formik.handleChange} onBlur={formik.handleBlur}
                                    error={(formik.errors.realisasi_tw2 && formik.touched.realisasi_tw2) ? formik.errors.realisasi_tw2 : ""}
                                />
                                <MyInput id="realisasi_tw3" name="realisasi_tw3" 
                                    label="TW ke-3" 
                                    placeholder='Input realisasi...'
                                    value={formik.values.realisasi_tw3} onChange={formik.handleChange} onBlur={formik.handleBlur}
                                    error={(formik.errors.realisasi_tw3 && formik.touched.realisasi_tw3) ? formik.errors.realisasi_tw3 : ""}
                                />
                                <MyInput id="realisasi_tw4" name="realisasi_tw4" 
                                    label="TW ke-4" 
                                    placeholder='Input realisasi...'
                                    value={formik.values.realisasi_tw4} onChange={formik.handleChange} onBlur={formik.handleBlur}
                                    error={(formik.errors.realisasi_tw4 && formik.touched.realisasi_tw4) ? formik.errors.realisasi_tw4 : ""}
                                />
                            </div>
                        </div>
                        <div className="mt-5 sm:mt-6 flex justify-center">
                            <PrimaryBtn loading={realisasiRenaksiOpdState.loading} onClick={() => simpanData()} >
                                Simpan Data
                            </PrimaryBtn>
                        </div>
                    </MyModal>
                </div>
            </div>
        </Layout>
    )
}

export default LangkahRealisasiRenaksiOpd