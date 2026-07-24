import Layout from '@/app/components/Layout/Layout'
import React from 'react'
import GoodNotes from "@assets/GoodNotes.png"
import { useNavigate } from 'react-router-dom'
import { StaticTable } from '@/app/components/Table'
import { PencilSquareIcon, ArrowLeftCircleIcon } from '@heroicons/react/24/outline'
import MyModal from '@/app/components/Form/MyModal'
import MyInput from '@/app/components/Form/MyInput'
import PrimaryBtn from '@/app/components/Button/PrimaryBtn'
import PrimaryLinkBtn from '@/app/components/Button/PrimaryLinkBtn'
import { Link } from 'react-router-dom'
import { RupiahFormatter } from '@/helper/common'
import { createRenaksiKdh, getListRenaksiKdh } from '@/redux/ducks/ranaksikdh/action'
import { useDispatch, useSelector } from 'react-redux'
import { initFlowbite } from 'flowbite'
import Swal from 'sweetalert2'
import axios from 'axios'

const RaPage = () => {
    const dispatch = useDispatch()
    const renaksiKdhState = useSelector((state) => state.renaksiKdhState)
    const [openModal, setOpenModal] = React.useState(false)
    const [formTitle, setFormTitle] = React.useState("Form Target Rencana Aksi per Triwulan")
    const [selectedYear, setSelectedYear] = React.useState(new Date().getFullYear());
    const [formContent, setFormContent] = React.useState({
        sasaranId: "",
        sasaran: "",
        indikatorId: "",
        indikator: "",
    })
    const [targettw1, setTargettw1] = React.useState(0)
    const [targettw2, setTargettw2] = React.useState(0)
    const [targettw3, setTargettw3] = React.useState(0)
    const [targettw4, setTargettw4] = React.useState(0)
    const inputTarget = (data) => {
        setFormContent({
            sasaranId: data.sasaranId ?? "",
            sasaran: data.sasaran ?? "",
            indikatorId: data.indikatorId ?? "",
            indikator: data.indikator ?? "",
        })
        setTargettw1(0)
        setTargettw2(0)
        setTargettw3(0)
        setTargettw4(0)
        setOpenModal(true)
    }
    const getDataTable = (tahun = "") => {
        dispatch(getListRenaksiKdh({tahun: tahun !== "" ? tahun : selectedYear }))
    }
    React.useEffect(() => {
        getDataTable()
    },[])
    React.useEffect(() => {
        initFlowbite()
    },[renaksiKdhState.list])
    const handleYearChange = (event) => {
        setSelectedYear(event.target.value);
        getDataTable(event.target.value)
    };

    const simpanData = async () => {
        let payload = {
            pohon_kinerja_sasaran_id: formContent.sasaranId,
            pohon_kinerja_indikator_id: formContent.indikatorId,
            tahun: selectedYear,
            target_tw1: targettw1,
            target_tw2: targettw2,
            target_tw3: targettw3,
            target_tw4: targettw4
        }

        
        let response = await dispatch(createRenaksiKdh(payload))
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
    }

    const tableHeader = () => (
        <>
            <tr>
                <th scope="col" className="px-4 py-3 border w-[3%]" rowSpan="2">No.</th>
                <th scope="col" className="px-4 py-3 border text-center" rowSpan="2">Tujuan/Sasaran</th>
                <th scope="col" className="px-4 py-3 border text-center" rowSpan="2">Indikator Kinerja</th>
                <th scope="col" className="px-4 py-3 border text-center" colSpan="4">Target Kinerja</th>
                <th scope="col" className="px-4 py-3 border text-center w-1/6" rowSpan="2">Langkah-langkah pencapaian target</th>
                <th scope="col" className="px-4 py-3 border text-center w-[10%]" rowSpan="2">Anggaran</th>
                <th scope="col" className="px-4 py-3 border w-[5%]" rowSpan="2">
                    <span className="sr-only">Actions</span>
                </th>
            </tr>
            <tr>
                <th scope="col" className="px-4 py-3 border text-center w-[2%]">I</th>
                <th scope="col" className="px-4 py-3 border text-center w-[2%]">II</th>
                <th scope="col" className="px-4 py-3 border text-center w-[2%]">III</th>
                <th scope="col" className="px-4 py-3 border text-center w-[2%]">IV</th>
            </tr>
        </>
    )

    const download = async () => {
        try {
            const BASE_HOST_URL =import.meta.env.VITE_BASE_HOST_URL
            const apiUrl = `${BASE_HOST_URL}/v1/kdh/aksi-cetak?tahun=${selectedYear}`
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
            let filename = 'Rencana_Aksi_Kepala_Daerah.pdf'; //    fallback filename
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
    }

    return (
        <Layout>
            <div className="bg-white h-auto dark:bg-gray-800 rounded-lg drop-shadow-xl py-1 px-3 w-full">
                <div className="dark:text-white">
                    <div className="flex flex-row items-center gap-3">
                        <div>
                            <img src={GoodNotes} alt="Perencanaan Kepala Daerah" className="object-contain" />
                        </div>
                        <div className="lg:text-lg font-bold text-teal-500 dark:text-white">Perencanaan Rencana Aksi KDH</div>
                    </div>
                </div>
            </div>
            <div className="bg-white h-auto dark:bg-gray-800 rounded-lg drop-shadow-xl py-1 sm:px-3 px-1 w-full flex flex-col lg:min-h-[35rem]">
                <div className="block w-full p-4">
                    <h1 className="text-center font-semibold text-lg dark:text-white">Rencana Aksi</h1>
                    <div className="flex">
                        <PrimaryLinkBtn to={`/perencanaan/kdh`}>
                            <ArrowLeftCircleIcon className='w-5 h-5' />
                            Kembali
                        </PrimaryLinkBtn>
                    </div>
                </div>
                <div className="w-full flex sm:justify-end px-6">
                    <div className="w-full md:w-1/4 sm:w-1/3 py-5">
                        <label htmlFor="" className="py-2 font-semibold dark:text-white">Tahun</label>
                        <select 
                            value={selectedYear} 
                            onChange={handleYearChange} 
                            className="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        >
                            {[...Array(10)].map((_, index) => {
                                const year = new Date().getFullYear() - index;
                                return <option key={year} value={year}>{year}</option>;
                            })}
                        </select>
                    </div>
                    <div className="w-full md:w-1/4 sm:w-1/3 py-5 flex items-end justify-end">
                        <PrimaryBtn onClick={() => download()}>Export</PrimaryBtn>
                    </div>
                </div>
                <div className="block w-full p-4">
                    <StaticTable header={tableHeader()}>
                    {
                        renaksiKdhState.list.map((item, key) => (
                            <>
                            <tr key={key} className="border-b dark:border-gray-700">
                                <td 
                                    className="px-4 py-3 border text-right"
                                    rowSpan={item.indikator.length > 0 ? item.indikator.length : 1}
                                >
                                    {key+1}
                                </td>
                                <td 
                                    className="px-4 py-3 border"
                                    rowSpan={item.indikator.length > 0 ? item.indikator.length : 1}
                                >
                                    {item.sasaran}
                                </td>
                                <td className="px-4 py-3 border">{item.indikator[0]?.indikator ?? "(belum ada data)"}</td>
                                <td className="px-4 py-3 border text-right">{item.indikator[0]?.rencana_aksi?.target_tw1 ?? ""}</td>
                                <td className="px-4 py-3 border text-right">{item.indikator[0]?.rencana_aksi?.target_tw2 ?? ""}</td>
                                <td className="px-4 py-3 border text-right">{item.indikator[0]?.rencana_aksi?.target_tw3 ?? ""}</td>
                                <td className="px-4 py-3 border text-right">{item.indikator[0]?.rencana_aksi?.target_tw4 ?? ""}</td>
                                <td className="px-4 py-3 border">
                                    {
                                        item.indikator[0]?.langkah?.length > 0 ? 
                                        <ul className='list-disc px-5'>
                                            {
                                                item.indikator[0].langkah.map((i) => (
                                                    <li>{i.langkah}</li>
                                                ))
                                            }
                                        </ul> : null
                                    }
                                </td>
                                <td className="px-4 py-3 border text-right" rowSpan={item.indikator.length > 0 ? item.indikator.length : 1}>{
                                    item.anggaran_perjanjian_kinerja.perubahan > 0 ? RupiahFormatter(item.anggaran_perjanjian_kinerja.perubahan) :
                                    RupiahFormatter(item.anggaran_perjanjian_kinerja.murni)
                                }</td>
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
                                        className="hidden z-10 w-48 bg-gray-50 rounded divide-y divide-gray-100 drop-shadow-lg dark:bg-gray-700 dark:divide-gray-600">
                                        <ul className="py-1 text-sm text-gray-700 dark:text-gray-200"
                                            aria-labelledby={`btn-${key}`}>
                                            <li>
                                                <button onClick={() => inputTarget({
                                                    sasaranId:item.id,
                                                    sasaran:item.sasaran,
                                                    indikatorId: item.indikator[0]?.id ?? "",
                                                    indikator: item.indikator[0]?.indikator ?? "",
                                                })}
                                                    className="flex gap-1 py-2 px-4 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">
                                                    <PencilSquareIcon className='w-4 h-4' />
                                                    Input Target
                                                </button>
                                            </li>
                                            <li>
                                                <Link to={`/perencanaan/kdh/ra/langkah?sasaranid=${item.id}&indikatorid=${item.indikator[0]?.id ?? "0"}&tahun=${selectedYear}`}
                                                    className="flex gap-1 py-2 px-4 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">
                                                    <PencilSquareIcon className='w-4 h-4' />
                                                    Input Langkah
                                                </Link>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            {
                                item.indikator.length > 1 ? 
                                    item.indikator.map((val,x) => {
                                        if(x > 0){
                                            return (
                                                <tr key={x} className="border-b dark:border-gray-700">
                                                    <td className="px-4 py-3 border">{val.indikator}</td>
                                                    <td className="px-4 py-3 border text-right">{val.rencana_aksi?.target_tw1 ?? ""}</td>
                                                    <td className="px-4 py-3 border text-right">{val.rencana_aksi?.target_tw2 ?? ""}</td>
                                                    <td className="px-4 py-3 border text-right">{val.rencana_aksi?.target_tw3 ?? ""}</td>
                                                    <td className="px-4 py-3 border text-right">{val.rencana_aksi?.target_tw4 ?? ""}</td>
                                                    <td className="px-4 py-3 border">
                                                    {
                                                        val.langkah?.length > 0 ? 
                                                        <ul className='list-disc px-5'>
                                                            {
                                                                val.langkah.map((i) => (
                                                                    <li>{i.langkah}</li>
                                                                ))
                                                            }
                                                        </ul> : null
                                                    }
                                                    </td>
                                                    <td className="px-4 py-3 border text-center">
                                                        <button id={`btn-${val.id}`} data-dropdown-toggle={`toggle-btn${val.id}`}
                                                            className="inline-flex items-center p-0.5 text-sm font-medium text-center text-gray-500 hover:text-gray-800 rounded-lg focus:outline-none dark:text-gray-400 dark:hover:text-gray-100"
                                                            type="button">
                                                            <svg className="w-5 h-5" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20"
                                                                xmlns="http://www.w3.org/2000/svg">
                                                                <path
                                                                    d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM16 12a2 2 0 100-4 2 2 0 000 4z" />
                                                            </svg>
                                                        </button>
                                                        <div id={`toggle-btn${val.id}`}
                                                            className="hidden z-10 w-48 bg-gray-50 rounded divide-y divide-gray-100 drop-shadow-lg dark:bg-gray-700 dark:divide-gray-600">
                                                            <ul className="py-1 text-sm text-gray-700 dark:text-gray-200"
                                                                aria-labelledby={`btn-${val.id}`}>
                                                                <li>
                                                                    <button onClick={() => inputTarget({
                                                                        sasaranId:item.id,
                                                                        sasaran:item.sasaran,
                                                                        indikatorId: val.id ?? "",
                                                                        indikator: val.indikator ?? "",
                                                                    })}
                                                                        className="flex gap-1 py-2 px-4 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">
                                                                        <PencilSquareIcon className='w-4 h-4' />
                                                                        Input Target
                                                                    </button>
                                                                </li>
                                                                <li>
                                                                    <Link to={`/perencanaan/kdh/ra/langkah?sasaranid=${item.id}&indikatorid=${val.id}&tahun=${selectedYear}`}
                                                                        className="flex gap-1 py-2 px-4 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">
                                                                        <PencilSquareIcon className='w-4 h-4' />
                                                                        Input Langkah
                                                                    </Link>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </td>
                                                </tr>
                                            )
                                        }
                                    })
                                : null
                            }
                            </>
                        ))
                    }
                    </StaticTable>
                    <MyModal  ModalTitle={formTitle} openModal={openModal} setOpenModal={setOpenModal} >
                        <div className="flex flex-col w-full p-4">
                            <MyInput id="sasaran" name="sasaran" label="Sasaran" value={formContent.sasaran} disabled />
                            <MyInput id="indikator" name="indikator" label="Indikator" value={formContent.indikator} disabled />
                            <div className="block w-full py-2">
                                <h1 className="font-bold dark:text-white">Target per Triwulan (TW)</h1>
                            </div>
                            <div className="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-2 sm:gap-3 md:gap-5">
                                <MyInput id="tw1" name="tw1" 
                                    label="TW ke-1" 
                                    placeholder='Input target...'
                                    type='number'
                                    value={targettw1}
                                    onChange={(e) => setTargettw1(e.target.value)}
                                />
                                <MyInput id="tw2" name="tw2" 
                                    label="TW ke-2" 
                                    placeholder='Input target...'
                                    type='number'
                                    value={targettw2}
                                    onChange={(e) => setTargettw2(e.target.value)}
                                />
                                <MyInput id="tw3" name="tw3" 
                                    label="TW ke-3" 
                                    placeholder='Input target...'
                                    type='number'
                                    value={targettw3}
                                    onChange={(e) => setTargettw3(e.target.value)}
                                />
                                <MyInput id="tw4" name="tw4" 
                                    label="TW ke-4" 
                                    placeholder='Input target...'
                                    type='number'
                                    value={targettw4}
                                    onChange={(e) => setTargettw4(e.target.value)}
                                />
                            </div>
                        </div>
                        
                        <div className="mt-5 sm:mt-6 flex justify-center">
                            <PrimaryBtn loading={renaksiKdhState.loading} onClick={() => simpanData()} >
                                Simpan Data
                            </PrimaryBtn>
                        </div>
                    </MyModal>
                </div>
            </div>
        </Layout>
    )
}

export default RaPage