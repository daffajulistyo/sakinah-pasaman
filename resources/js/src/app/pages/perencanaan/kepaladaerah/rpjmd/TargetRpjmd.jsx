import Layout from '@/app/components/Layout/Layout'
import React from 'react'
import GoodNotes from "@assets/GoodNotes.png"
import { StaticTable } from '@/app/components/Table'
import PrimaryBtn from '@/app/components/Button/PrimaryBtn'
import PrimaryLinkBtn from '@/app/components/Button/PrimaryLinkBtn'
import { ArrowTurnDownRightIcon, ArrowLeftCircleIcon } from '@heroicons/react/24/outline'
import MyModal from '@/app/components/Form/MyModal'
import MyInput from '@/app/components/Form/MyInput'
import { getListRpjmdKdh, createTargetRpjmdKdh } from '@/redux/ducks/rpjmdkdh/action'
import { useSelector, useDispatch } from 'react-redux'
import MySelect2 from '@/app/components/Form/MySelect2'
import { getOptionsDatamasterSatuan } from '@/redux/ducks/datamastersatuan/action'
import { useFormik } from 'formik'
import * as Yup from "yup"
import Swal from 'sweetalert2'
import axios from 'axios'

const TargetRpjmd = () => {
    const dispatch = useDispatch()
    const rpjmdKdhState = useSelector((state) => state.rpjmdKdhState)
    const [openModal, setOpenModal] = React.useState(false)
    const tableHeader = () => (
        <>
        <tr>
            <th scope="col" className="px-4 py-3 border w-[3%]" rowSpan="2">No.</th>
            <th scope="col" className="px-4 py-3 border" rowSpan="2">Sasaran</th>
            <th scope="col" className="px-4 py-3 border" rowSpan="2">Indikator</th>
            <th scope="col" className="px-4 py-3 border" rowSpan="2">Satuan</th>
            <th scope="col" className="px-4 py-3 border" rowSpan="2">Baseline</th>
            <th scope="col" className="px-4 py-3 border text-center w-[25%]" colSpan="5">Target</th>
            <th scope="col" className="px-4 py-3 border w-[5%]" rowSpan="2">
                <span className="sr-only">Actions</span>
            </th>
        </tr>
        <tr>
            <th scope="col" className="px-4 py-3 border">n ke-1</th>
            <th scope="col" className="px-4 py-3 border">n ke-2</th>
            <th scope="col" className="px-4 py-3 border">n ke-3</th>
            <th scope="col" className="px-4 py-3 border">n ke-4</th>
            <th scope="col" className="px-4 py-3 border">n ke-5</th>
        </tr>
        </>
    )
    const [activeIndikator, setActiveIndikator] = React.useState(null)
    
    React.useEffect(() => {
        dispatch(getListRpjmdKdh())
    },[])
    const [rpjmdForm, setRpjmdForm] = React.useState({
        sasaran: "",
        indikator: ""
    })

    const aplhabet = ["A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z"];

    const formik = useFormik({
        initialValues: {
            target_1: "",
            target_2: "",
            target_3: "", 
            target_4: "",
            target_5: "",
            baseline: ""
        },
        validationSchema: Yup.object({ 
            target_1: Yup.number().required("Target tahun ke-1 harus diisi"),
            target_2: Yup.number().required("Target tahun ke-2 harus diisi"),
            target_3: Yup.number().required("Target tahun ke-3 harus diisi"),
            target_4: Yup.number().required("Target tahun ke-4 harus diisi"), 
            target_5: Yup.number().required("Target tahun ke-5 harus diisi"),
            baseline: Yup.string(),
        }),
        onSubmit: (values) => {
            simpanData(values)
        },
        enableReinitialize: true
    })
    const dataRpjmd = () => {
        let data = []
        
        if(rpjmdKdhState.data?.misi){
            // rpjmdKdhState.data.map((item) => {
                // check if list misi exist
                if(rpjmdKdhState.data.misi.length > 0){
                    rpjmdKdhState.data.misi.map((m) => {

                        // check if list tujuan exist
                        if(m.tujuan.length > 0){
                            m.tujuan.map((t,idx) => {

                                if(idx === 0){
                                    t.indikator_tujuan.length > 0 ?
                                    t.indikator_tujuan.map((i) => {
                                        data.push({
                                            id: i.id,
                                            tujuan: t.tujuan,
                                            sasaran: "",
                                            indikator: i.indikator,
                                            baseline: i.baseline,
                                            target_1: i.target_1,
                                            target_2: i.target_2,
                                            target_3: i.target_3,
                                            target_4: i.target_4,
                                            target_5: i.target_5,
                                            satuan: i.satuan ?? "-"
                                        })
                                    }) : null
                                }
                                //check if list sasaran exist
                                if(t.sasaran.length > 0){
                                    t.sasaran.map((s) => {
                                        
                                        // check if list indikator
                                        if(s.indikator_sasaran.length > 0){
                                            s.indikator_sasaran.map((i) => {
                                                data.push({
                                                    id: i.id,
                                                    sasaran: s.sasaran,
                                                    indikator: i.indikator,
                                                    baseline: i.baseline,
                                                    target_1: i.target_1,
                                                    target_2: i.target_2,
                                                    target_3: i.target_3,
                                                    target_4: i.target_4,
                                                    target_5: i.target_5,
                                                    satuan: i.satuan ?? "-"
                                                })
                                            })
                                        }
                                    })
                                }
                            })
                        }
                    })
                }
            // })
        }
        
        return data
    }

    
    const [selectedSatuan, setSelectedSatuan] = React.useState({})
    const datamastersatuan = useSelector((state) => state.datamasterSatuanState.options)
    const optionsSatuan = () => (
        datamastersatuan.length > 0 ?
            datamastersatuan.map((item) => ({ label: item.satuan, value: item.id })) : []
    )
    const getOptionSatuan = (id) => (
        optionsSatuan().length > 0 ?
        optionsSatuan().map((val) => {
            if(val.value === id) return val
        }) : null
    )
    React.useLayoutEffect(() => {        
        dispatch( getOptionsDatamasterSatuan() )
    },[])
    const setTargetRpjmd = (i) => {
        formik.resetForm()
        setActiveIndikator(i.id)
        setRpjmdForm({
            sasaran: i.sasaran,
            indikator: i.indikator
        })
        formik.setValues({
            target_1: i.target_1,
            target_2: i.target_2,
            target_3: i.target_3,
            target_4: i.target_4,
            target_5: i.target_5,
            baseline: i.baseline ?? "",
        })
        if(i.satuan !== null){ 
            setSelectedSatuan({ label: i.satuan?.satuan ?? "", value: i.satuan?.id ?? "" }) 
            
        }
        else{ setSelectedSatuan(null) }
        setOpenModal(true)
    }

    const simpanData = async () => {
        if (formik.isValid && selectedSatuan.value) {
            const payload = {
                target_1: formik.values.target_1.toString(),
                target_2: formik.values.target_2.toString(),
                target_3: formik.values.target_3.toString(),
                target_4: formik.values.target_4.toString(),
                target_5: formik.values.target_5.toString(),
                target_6: "-",
                satuan_id: selectedSatuan.value,
                baseline: formik.values.baseline.toString()
            }

            let response = null
            response = await dispatch(createTargetRpjmdKdh(activeIndikator, payload))
            if(response.error === null){
                Swal.fire({
                    icon: 'success',
                    title: response.data.message,
                    showConfirmButton: false,
                    timer: 1500
                })
            
                setOpenModal(false)
                dispatch(getListRpjmdKdh())
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
    let numbering1 = 0;
    let numbering2 = 0;
    const download = async () => {
        try {
            const BASE_HOST_URL =import.meta.env.VITE_BASE_HOST_URL
            const apiUrl = `${BASE_HOST_URL}/v1/kdh/rpjmd/cetak`
            const token = localStorage.getItem('token')
            const resp = await axios.get(apiUrl, {
                    responseType: 'blob',
                    headers: {
                    // jika butuh auth
                    ...(token ? { Authorization: `Bearer ${token}` } : {}),
                    },
                    // jika menggunakan cookie-based auth dan CORS: withCredentials: true
                    // withCredentials: true,
                    onDownloadProgress: (progressEvent) => {
                    // progressEvent.loaded / progressEvent.total (total mungkin undefined)
                    if (progressEvent.lengthComputable) {
                        const percent = Math.round((progressEvent.loaded * 100) / progressEvent.total);
                        console.log('download progress', percent);
                    } else {
                        console.log('downloaded', progressEvent.loaded);
                    }
                },
            });
        
            // jika backend mengembalikan JSON error, content-type bukan PDF.
            const contentType = resp.headers['content-type'] || '';
            if (!contentType.includes('application/pdf')) {
                // coba parse isi blob sebagai text lalu JSON
                const text = await new Response(resp.data).text();
                let json;
                try { json = JSON.parse(text); } catch(e) { json = { message: text } }
                throw new Error(json.message || 'Server returned non-pdf response');
            }
        
            // ambil filename dari header Content-Disposition (jika tersedia)
            const disposition = resp.headers['content-disposition'];
            let filename = 'RPJMD.pdf'; //    fallback filename
            if (disposition) {
                const match = disposition.match(/filename\*?=(?:UTF-8'')?["']?([^;"']+)["']?/i);
                if (match && match[1]) {
                    filename = decodeURIComponent(match[1]);
                }
            }
        
            // buat blob & trigger download
            const blob = new Blob([resp.data], { type: 'application/pdf' });
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = filename;
            document.body.appendChild(a);
            a.click();
            a.remove();
            window.URL.revokeObjectURL(url);
            } catch (err) {
            console.error('Download failed', err);
            alert('Gagal mengunduh file: ' + (err.message || err));
            }
    };
    return (
        <Layout>
            <div className="bg-white h-auto dark:bg-gray-800 rounded-lg drop-shadow-xl py-1 px-3 w-full">
                <div className="dark:text-white">
                    <div className="flex flex-row items-center gap-3">
                        <div>
                            <img src={GoodNotes} alt="Perencanaan Kepala Daerah" className="object-contain" />
                        </div>
                        <div className="lg:text-lg font-bold text-teal-500 dark:text-white">Perencanaan RPJMD KDH</div>
                    </div>
                </div>
            </div>
            <div className="w-full flex sm:justify-end px-6">                    
                <div className="w-full flex justify-end items-end md:w-1/4 sm:w-1/3 py-5">
                    <PrimaryBtn onClick={() => download()}>Export</PrimaryBtn>
                </div>
            </div>
            <div className="bg-white h-auto dark:bg-gray-800 rounded-lg drop-shadow-xl py-1 sm:px-3 px-1 w-full flex flex-col lg:min-h-[35rem]">
                
                <div className="block w-full p-4">
                    <div className="flex">
                        <PrimaryLinkBtn to={`/perencanaan/kdh/rpjmd`}>
                            <ArrowLeftCircleIcon className='w-5 h-5' />
                            Kembali
                        </PrimaryLinkBtn>
                    </div>
                    <StaticTable header={tableHeader()}>
                        {
                            !rpjmdKdhState.loading ? dataRpjmd().map((item, x) => (
                                <tr key={x} className={`border-b dark:border-gray-700 ${item.tujuan ? "font-bold italic bg-teal-100 dark:bg-teal-900 dark:text-white" : " odd:bg-gray-100 dark:odd:bg-gray-900"}`}>
                                    {item.tujuan ? null : <td className="px-4 py-3 border text-right">{numbering2 = numbering2 + 1}</td>}
                                    <td colSpan={item.tujuan ? 2 : 1} className="px-4 py-3 border">{ item.tujuan ? aplhabet[numbering1++] + ". " + item.tujuan : item.sasaran}{item.tujuan ? () => { numbering2=0; return null; } : null}</td>
                                    <td className="px-4 py-3 border">{item.indikator}</td>
                                    <td className="px-4 py-3 border text-center">{item.satuan?.satuan ?? "-"}</td>
                                    <td className="px-4 py-3 border text-right">{item.baseline}</td>
                                    <td className="px-4 py-3 border text-right">{item.target_1}</td>
                                    <td className="px-4 py-3 border text-right">{item.target_2}</td>
                                    <td className="px-4 py-3 border text-right">{item.target_3}</td>
                                    <td className="px-4 py-3 border text-right">{item.target_4}</td>
                                    <td className="px-4 py-3 border text-right">{item.target_5}</td>
                                    <td className="px-4 py-3 border flex justify-center h-full">
                                        <PrimaryBtn onClick={() => setTargetRpjmd(item)}>
                                            <ArrowTurnDownRightIcon className='w-2 h-2' />
                                        </PrimaryBtn>                                        
                                    </td>
                                </tr>
                            )) :
                            <tr className="border-b dark:border-gray-700">
                                    <td className="px-4 py-3 border text-center" colSpan="100%">Loading...</td>
                            </tr>
                        }
                    </StaticTable>
                </div>

            </div>
            <MyModal ModalTitle={"FORM TARGET RPJMD"} openModal={openModal} setOpenModal={setOpenModal} size='md' >
                <form onSubmit={formik.handleSubmit}>
                    <div className="flex flex-col w-full p-4">
                        <MyInput id="indikator" name="indikator" label="Indikator"
                            value={rpjmdForm.indikator ?? ""} disabled />
                        
                        <MySelect2
                            id="satuan"
                            label="Satuan"
                            options={optionsSatuan()}
                            value={selectedSatuan}
                            onChange={setSelectedSatuan}
                        />
                        
                        <MyInput 
                            id="baseline" 
                            name="baseline" 
                            label="Baseline"
                            placeholder='Inputkan Baseline'
                            value={formik.values.baseline} 
                            onChange={formik.handleChange}
                            onBlur={formik.handleBlur}
                            error={formik.touched.baseline && formik.errors.baseline}
                            disabled
                        />
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
                    </div>
                    
                    <div className="mt-5 sm:mt-6 flex justify-center">
                        <PrimaryBtn type="submit" loading={rpjmdKdhState.loading}>
                            Simpan Data
                        </PrimaryBtn>
                    </div>
                </form>
            </MyModal>
        </Layout>
    )
}

export default TargetRpjmd