import Layout from '@/app/components/Layout/Layout'
import React from 'react'
import GoodNotes from "@assets/GoodNotes.png"
import MyInput from '@/app/components/Form/MyInput'
import MyTextarea from '@/app/components/Form/MyTextarea'
import TinyMceEditor from '@/app/components/Form/TinyMceEditor'
import MyUpload from '@/app/components/Form/MyUpload'
import PrimaryBtn from '@/app/components/Button/PrimaryBtn'
import PrimaryLinkBtn from '@/app/components/Button/PrimaryLinkBtn'
import { ArrowLeftIcon, PlusCircleIcon } from '@heroicons/react/24/outline'
import { getIndikatorKdh } from '@/redux/ducks/indikatorkdh/action'
import { useSelector, useDispatch } from 'react-redux'
import { useParams, useNavigate } from 'react-router-dom'
import { updateIkuKdh } from '@/redux/ducks/ikukdh/action'
import { useFormik } from 'formik'
import * as Yup from "yup"
import Swal from 'sweetalert2'

const IkuEdit = () => {
    const indikatorKdhState = useSelector((state) => state.indikatorKdhState)
    const ikuKdhState = useSelector((state) => state.ikuKdhState)
    const [ikuData,setIkuData] = React.useState(null)
    const dispatch = useDispatch()
    const navigate = useNavigate()
    const { id } = useParams()
    React.useEffect(() => {
        getIndikator()
    },[])
    React.useEffect(() => {
        setIkuData(null)
        if(indikatorKdhState.data !== null) setIkuData(indikatorKdhState.data[0])
    },[indikatorKdhState.data])


    React.useEffect(() => {
        setForm()
    },[ikuData])
    const getIndikator = async () => {
        const response = await dispatch( getIndikatorKdh(id) )
        if(response.error !== null){
            Swal.fire({
                icon: 'error',
                title: "something went wrong",
                showConfirmButton: true,
                confirmButtonText: 'Kembali',
                timer: 5000
            }).then(async (result) => {
                if(result.isConfirmed) navigate('/perencanaan/kdh/iku')
            })
        }
    }
    const setForm = () => {
        formik.setValues({
            definisi: ikuData?.defenisi ?? "",
            baseline: ikuData?.baseline ?? "",
            sumber_data: ikuData?.sumber_data ?? "",
            rilis: ikuData?.rilis ?? "",
        })
        setFormula(ikuData?.formula_perhitungan ?? "")
    }
    const [formula, setFormula] = React.useState("")
    const onChangeFormula = (val) => {
        setFormula(val)
    }
    const formik = useFormik({
        initialValues: {
            definisi: "",
            baseline: "",
            sumber_data: "",
            rilis: ""
        },
        validationSchema: Yup.object({ 
            definisi: Yup.string().required("Definisi harus diisi"),
            baseline: Yup.string().required("Baseline harus diisi"),
            sumber_data: Yup.string().required("Sumber Data harus diisi"),
            rilis: Yup.string().required("Rilis harus diisi")
        }),
        enableReinitialize: true
    })

    const onSimpan = async () => {
        if (formik.isValid && formula !== "") {
            const payload = {
                defenisi: formik.values.definisi,
                formula_perhitungan: formula,
                baseline: formik.values.baseline,
                sumber_data: formik.values.sumber_data,
                rilis: formik.values.rilis
            }
            let response = null
            response = await dispatch(updateIkuKdh(id, payload))
            if(response.error === null){
                Swal.fire({
                    icon: 'success',
                    title: response.data.message,
                    showConfirmButton: false,
                    timer: 1500
                }).then(async () => {
                    navigate('/perencanaan/kdh/iku')
                })
            
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
        else {
            Swal.fire({
                icon: 'warning',
                title: "periksa kembali form isian anda",
                showConfirmButton: false,
                timer: 1500
            })
            
        }
        
    }
    return (
        <Layout loading={indikatorKdhState.loading}>
            <div className="bg-white h-auto dark:bg-gray-800 rounded-lg drop-shadow-xl py-1 px-3 w-full">
                <div className="dark:text-white">
                    <div className="flex flex-row items-center gap-3">
                        <div>
                            <img src={GoodNotes} alt="Perencanaan Kepala Daerah" className="object-contain" />
                        </div>
                        <div className="lg:text-lg font-bold text-teal-500 dark:text-white">Perencanaan IKU KDH</div>
                    </div>
                </div>
            </div>
            <div className="bg-white h-auto dark:bg-gray-800 rounded-lg drop-shadow-xl py-1 sm:px-3 px-1 w-full flex flex-col lg:min-h-[35rem]">
                <div className="block w-full p-4">
                    <h1 className="text-blue-800 dark:text-blue-100 font-semibold">FORM INDIKATOR KINERJA UTAMA</h1>
                </div>
                <div className="block w-full p-4 border rounded-xl h-full">
                    <MyInput 
                        id="indikator" 
                        name='indikator' 
                        label='Indikator'
                        value={ikuData !== null ? ikuData.indikator : ""} 
                        disabled 
                    />
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
                    <TinyMceEditor label='Formula' id='formula' initialValue={formula} onChange={onChangeFormula} />
                    {/* <MyUpload label="File" id="file" notes='DOC, DOCX, or PDF (Max. 2MB)' /> */}
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
                </div>
                <div className="w-full flex justify-between py-5 px-2">
                    <PrimaryLinkBtn to='/perencanaan/kdh/iku' >
                        <ArrowLeftIcon className="w-5 h-5" />
                        Kembali
                    </PrimaryLinkBtn >
                    <PrimaryBtn onClick={() => onSimpan()} loading={false} >
                        <PlusCircleIcon className="w-5 h-5" />
                        Simpan Data
                    </PrimaryBtn>
                </div>
            </div>
        </Layout>
    )
}

export default IkuEdit