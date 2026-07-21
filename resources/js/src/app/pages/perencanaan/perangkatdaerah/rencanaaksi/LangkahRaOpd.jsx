import React from 'react'
import Layout from '@/app/components/Layout/Layout'
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
import { getListRenaksiOpdLangkah, createRenaksiOpdLangkah, updateRenaksiOpdLangkah, deleteRenaksiOpdLangkah, getListRenaksiOpd } from '@/redux/ducks/renaksiopd/action'

const LangkahRaOpd = () => {
    const [searchParams, setSearchParams] = useSearchParams()
    const navigate = useNavigate()
    const dispatch = useDispatch()
    const sasaranidActive = searchParams.get('sasaranid')
    const indikatoridActive = searchParams.get('indikatorid')
    const yearActive = searchParams.get('tahun')
    const [baseDataActive, setBaseDataActive] = React.useState(null)
    const renaksiOpdState = useSelector((state) => state.renaksiOpdState)
    const getDataTable = async () => {
        await dispatch(getListRenaksiOpdLangkah({
            sasaran_opd_id: sasaranidActive,
            indikator_opd_id: indikatoridActive,
            tahun: yearActive
        }))
    }
    React.useEffect(() => {
        getDataTable()
    },[])

    React.useEffect(() => {
        if(renaksiOpdState.list.length === 0){ 
            dispatch(getListRenaksiOpd({ tahun: yearActive })) 
        }
        else{
            let baseData = null
            let dataRenaksiOpd = renaksiOpdState.list
            let dataSasaran = dataRenaksiOpd.find((item) => {
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
    }, [renaksiOpdState.list])

    React.useEffect(() => { initFlowbite() }, [renaksiOpdState.list_langkah]) // renaksiKdhState.list_langkah
    const [openModal, setOpenModal] = React.useState(false)
    const [editId, setEditId] = React.useState("")
    const [formTitle, setFormTitle] = React.useState("Form Tambah Langkah-langkah Pencapaian Target")
    const inputTarget = () => {
        formik.resetForm();
        setEditId("")
        setFormTitle("Form Tambah Langkah-langkah Pencapaian Target")
        setOpenModal(true)
    }
    const editTarget = (data) => {
        
        
        formik.resetForm()
        formik.setFieldValue('langkah', data.langkah);
        formik.setFieldValue('target_tw1', data.target_tw1);
        formik.setFieldValue('target_tw2', data.target_tw2);
        formik.setFieldValue('target_tw3', data.target_tw3);
        formik.setFieldValue('target_tw4', data.target_tw4);

        setEditId(data.id)
        setFormTitle("Form Edit Langkah-langkah Pencapaian Target")
        setOpenModal(true)
    }
    const tableHeader = () => (
        <>
            <tr>
                <th scope="col" className="px-4 py-3 border w-[3%]" rowSpan="2">No.</th>
                <th scope="col" className="px-4 py-3 border text-center" rowSpan="2">Langkah-langkah Pencapaian Target</th>
                <th scope="col" className="px-4 py-3 border text-center" rowSpan="2">Satuan</th>
                <th scope="col" className="px-4 py-3 border text-center" colSpan="4">Target TW</th>
                <th scope="col" className="px-4 py-3 border text-center" rowSpan="2">Ket</th>
                <th scope="col" className="px-4 py-3 border w-[5%]" rowSpan="2">
                    <span className="sr-only">Actions</span>
                </th>
            </tr>
            <tr>
                <th scope="col" className="px-4 py-3 border text-center w-[5%]">I</th>
                <th scope="col" className="px-4 py-3 border text-center w-[5%]">II</th>
                <th scope="col" className="px-4 py-3 border text-center w-[5%]">III</th>
                <th scope="col" className="px-4 py-3 border text-center w-[5%]">VI</th>
            </tr>
        </>
    )

    const renderTable = () => (
        renaksiOpdState.list_langkah.length > 0 ? renaksiOpdState.list_langkah.map((item, x) => (
            <tr key={x} className="border-b dark:border-gray-700">
                <td className="px-4 py-3 border text-right">{x+1}</td>
                <td className="px-4 py-3 border">{item.langkah}</td>
                <td className="px-4 py-3 border text-right">{item.satuan ?? ""}</td>
                <td className="px-4 py-3 border text-right">{item.target_tw1}</td>
                <td className="px-4 py-3 border text-right">{item.target_tw2}</td>
                <td className="px-4 py-3 border text-right">{item.target_tw3}</td>
                <td className="px-4 py-3 border text-right">{item.target_tw4}</td>
                <td className="px-4 py-3 border text-right">{item.ket ?? ""}</td>
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
                                <button onClick={() => editTarget(item)}
                                    className="flex gap-1 py-2 px-4 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">
                                    <PencilSquareIcon className='w-4 h-4' />
                                    Edit Langkah
                                </button>
                            </li>
                            <li>
                                <button onClick={() => deleteAction(item.id)}
                                    className="flex gap-1 py-2 px-4 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">
                                    <TrashIcon  className='w-4 h-4' />
                                    Hapus Langkah
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

    const formik = useFormik({
        initialValues: {
            langkah: "",
            target_tw1: "",
            target_tw2: "",
            target_tw3: "",
            target_tw4: ""
        },
        validationSchema: Yup.object({ 
            langkah:           Yup.string().required().strict(true),
            target_tw1:           Yup.number().required().strict(true),
            target_tw2:           Yup.number().required().strict(true),
            target_tw3:           Yup.number().required().strict(true),
            target_tw4:           Yup.number().required().strict(true),
        }),
        enableReinitialize: true
    })
    
    const validationForm = async () => {
        //validation
        formik.setFieldTouched('langkah', true, true)
        formik.setFieldTouched('target_tw1', true, true)
        formik.setFieldTouched('target_tw2', true, true)
        formik.setFieldTouched('target_tw3', true, true)
        formik.setFieldTouched('target_tw4', true, true)
        const errors = await formik.validateForm();

        return errors
    }
    const simpanData= async ()=> {
        
        const errors = await validationForm()
        if (Object.keys(errors).length === 0) {
            // Form is valid, do any success call, mapping payload before submit
            const form = formik.values
            const payload = {
                ...form,
                tahun: yearActive,
                sasaran_opd_id: sasaranidActive,
                indikator_opd_id: indikatoridActive,
            }
            
            // console.log(payload); return false
            
            // submit payload with dispatch action redux
            let response = null
            if(editId !== "") response = await dispatch(updateRenaksiOpdLangkah(editId, payload))
            else response = await dispatch(createRenaksiOpdLangkah(payload));
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
                const response = await dispatch(deleteRenaksiOpdLangkah(id))
                
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
                <div className="dark:text-white">
                    <div className="flex flex-row items-center gap-3">
                        <div>
                            <img src={GoodNotes} alt="Perencanaan Kepala Daerah" className="object-contain" />
                        </div>
                        <div className="lg:text-lg font-bold text-teal-500 dark:text-white">Perencanaan Rencana Aksi Perangkat Daerah</div>
                    </div>
                </div>
            </div>
            <div className="bg-white h-auto dark:bg-gray-800 rounded-lg drop-shadow-xl py-1 sm:px-3 px-1 w-full flex flex-col lg:min-h-[35rem]">
                <div className="block w-full p-4">
                    <h1 className="text-center font-semibold text-lg dark:text-white">Langkah Pencapaian Target</h1>
                    <h1 className="text-center italic text-lg dark:text-white">" {baseDataActive?.indikator?.indikator ?? "(no data)"} "</h1>
                    <div className="flex justify-between gap-3">
                        <PrimaryLinkBtn to={`/perencanaan/opd/ra`}>
                            <ArrowLeftCircleIcon className='w-5 h-5' />
                            Kembali
                        </PrimaryLinkBtn>
                        <PrimaryBtn onClick={() => inputTarget()}>
                            <PlusCircleIcon className="w-4 h-4" /> Tambah Langkah
                        </PrimaryBtn>
                    </div>
                </div>
                <div className="block w-full p-4">
                    <StaticTable header={tableHeader()}>
                    {
                        !renaksiOpdState.loading ? renderTable() :
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
                                value={formik.values.langkah} onChange={formik.handleChange} onBlur={formik.handleBlur}
                                error={(formik.errors.langkah && formik.touched.langkah) ? formik.errors.langkah : ""}
                            />

                            <MyInput id="satuan" name="satuan" 
                                label="Satuan" 
                                type='text'
                                placeholder='Input target...'
                                value={formik.values.satuan} onChange={formik.handleChange} onBlur={formik.handleBlur}
                                error={(formik.errors.satuan && formik.touched.satuan) ? formik.errors.satuan : ""}
                            />
                            <div className="block w-full py-2">
                                <h1 className="font-bold dark:text-white">Target per Triwulan (TW)</h1>
                            </div>
                            <div className="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-2 sm:gap-3 md:gap-5">
                                <MyInput id="target_tw1" name="target_tw1" 
                                    label="TW ke-1" 
                                    type='number'
                                    placeholder='Input target...'
                                    value={formik.values.target_tw1} onChange={formik.handleChange} onBlur={formik.handleBlur}
                                    error={(formik.errors.target_tw1 && formik.touched.target_tw1) ? formik.errors.target_tw1 : ""}
                                />
                                <MyInput id="target_tw2" name="target_tw2" 
                                    label="TW ke-2" 
                                    type='number'
                                    placeholder='Input target...'
                                    value={formik.values.target_tw2} onChange={formik.handleChange} onBlur={formik.handleBlur}
                                    error={(formik.errors.target_tw2 && formik.touched.target_tw2) ? formik.errors.target_tw2 : ""}
                                />
                                <MyInput id="target_tw3" name="target_tw3" 
                                    label="TW ke-3" 
                                    type='number'
                                    placeholder='Input target...'
                                    value={formik.values.target_tw3} onChange={formik.handleChange} onBlur={formik.handleBlur}
                                    error={(formik.errors.target_tw3 && formik.touched.target_tw3) ? formik.errors.target_tw3 : ""}
                                />
                                <MyInput id="target_tw4" name="target_tw4" 
                                    label="TW ke-4" 
                                    type='number'
                                    placeholder='Input target...'
                                    value={formik.values.target_tw4} onChange={formik.handleChange} onBlur={formik.handleBlur}
                                    error={(formik.errors.target_tw4 && formik.touched.target_tw4) ? formik.errors.target_tw4 : ""}
                                />
                            </div>
                            <MyTextarea 
                                id="keterangan" 
                                name="keterangan" 
                                label="Keterangan" 
                                placeholder='Inputkan keterangan...' 
                                value={formik.values.keterangan} onChange={formik.handleChange} onBlur={formik.handleBlur}
                                error={(formik.errors.keterangan && formik.touched.keterangan) ? formik.errors.keterangan : ""}
                            />
                        </div>
                        <div className="mt-5 sm:mt-6 flex justify-center">
                            <PrimaryBtn loading={renaksiOpdState.loading} onClick={() => simpanData()} >
                                Simpan Data
                            </PrimaryBtn>
                        </div>
                    </MyModal>
                </div>
            </div>
        </Layout>
    )
}

export default LangkahRaOpd