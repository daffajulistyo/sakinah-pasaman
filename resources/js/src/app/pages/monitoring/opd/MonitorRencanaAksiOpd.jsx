import React from 'react'
import Layout from '@/app/components/Layout/Layout'
import GoodNotes from "@assets/GoodNotes.png"
import { useParams } from 'react-router-dom'
import { StaticTable } from '@/app/components/Table'
import PrimaryBtn from '@/app/components/Button/PrimaryBtn'
import { Link } from 'react-router-dom'
import { RupiahFormatter } from '@/helper/common'
import { getListMonitorRencanaAksiOpd } from '@/redux/ducks/monitoring/action'
import { useDispatch, useSelector } from 'react-redux'
import { initFlowbite } from 'flowbite'
import axios from 'axios'

const MonitorRencanaAksiOpd = () => {
    const dispatch = useDispatch()
    const monitoringState = useSelector((state) => state.monitoringState)
    const datamasterOpdState = useSelector((state) => state.datamasterOpdState)

    const [selectedYear, setSelectedYear] = React.useState("");
    const { idopd } = useParams()

    React.useEffect(() => {
        if(monitoringState.selected_opd === null) dispatch(getDatamasterOpd(idopd))
    },[idopd])

    const getSelectedOpd = () => {
        if(monitoringState.selected_opd === null) return {
            id: datamasterOpdState.data?.id ?? null,
            nama_opd: datamasterOpdState.data?.nama_opd ?? "-"
        }
        return {
            id: monitoringState.selected_opd?.value ?? null,
            nama_opd: monitoringState.selected_opd?.label ?? "-"
        }
    }

    const getDataTable = (tahun = "") => {
        dispatch(getListMonitorRencanaAksiOpd({tahun: tahun !== "" ? tahun : selectedYear, master_opd_id: idopd }))
    }
    React.useEffect(() => {
        getDataTable()
    },[selectedYear])
    React.useEffect(() => {
        initFlowbite()
    },[monitoringState.data_rencanaaksi]) //renaksiKdhState.list

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
                <th scope="col" className="px-4 py-3 border text-center w-[2%]">VI</th>
            </tr>
        </>
    )

    const download = async () => {
        try {
            const BASE_HOST_URL =import.meta.env.VITE_BASE_HOST_URL
            const apiUrl = `${BASE_HOST_URL}/v1/monitoring/opd/aksi/cetak?master_opd_id=${idopd}&tahun=${selectedYear}`
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
            let namaOpd = getSelectedOpd().nama_opd ?? "Nama_Opd"
            namaOpd = namaOpd.replace(' ', '_')
            let filename = `Rencana_Aksi_Perangkat_Daerah_${namaOpd}.pdf`; //    fallback filename
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

    let loading = monitoringState.loading

    return (
        <Layout loading={loading}>
            <div className="bg-white h-auto dark:bg-gray-800 rounded-lg drop-shadow-xl py-1 px-3 w-full">
                <div className="dark:text-white">
                    <div className="flex flex-row items-center gap-3">
                        <div>
                            <img src={GoodNotes} alt="Perencanaan Perangkat Daerah" className="object-contain" />
                        </div>
                        <div className="lg:text-lg font-bold text-teal-500 dark:text-white">Rencana Aksi Perangkat Daerah</div>
                    </div>
                </div>
            </div>
            <div className="bg-white h-auto dark:bg-gray-800 rounded-lg drop-shadow-xl py-1 sm:px-3 px-1 w-full flex flex-col lg:min-h-[35rem]">
                <div className="block w-full p-4">
                    <div className="w-full flex">
                        <Link 
                            to={'/monitoring/opd?idopd='+idopd} 
                            className="bg-blue-500 hover:bg-blue-700 text-white py-1 px-3 rounded flex justify-center items-center gap-1">
                            <svg 
                                xmlns="http://www.w3.org/2000/svg" 
                                fill="none" 
                                viewBox="0 0 24 24" 
                                strokeWidth="1.5" 
                                stroke="currentColor" 
                                className="size-4"
                            >
                                <path strokeLinecap="round" strokeLinejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                            </svg>

                            Kembali
                        </Link>
                    </div>
                </div>

                <div className="w-full text-center text-lg dark:text-white font-bold mb-3">
                    RENCANA AKSI
                    <div className="w-full text-center italic text-lg dark:text-white font-semibold mb-3">{getSelectedOpd().nama_opd}</div>
                </div>
                <div className="w-full flex md:flex-row flex-col justify-between px-6">
                    <div className="w-full md:w-1/4 sm:w-1/3 md:py-5 py-2">
                        <label htmlFor="" className="py-2 font-semibold dark:text-white">Tahun</label>
                        <select 
                            value={selectedYear} 
                            onChange={(e) => setSelectedYear(e.target.value)}
                            className="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        >
                            <option value="">Pilih Tahun</option>
                            {[...Array(10)].map((_, index) => {
                                const year = new Date().getFullYear() - index;
                                return <option key={year} value={year}>{year}</option>;
                            })}
                        </select>
                    </div>
                </div>
                {
                    selectedYear !== "" ?
                    <div className="w-full flex sm:justify-end px-6">                    
                        <div className="w-full flex justify-end items-end md:w-1/4 sm:w-1/3 py-5">
                            <PrimaryBtn onClick={() => download()}>Export</PrimaryBtn>
                        </div>
                    </div> : null
                }
                <div className="block w-full p-4">
                    <StaticTable header={tableHeader()}>
                    {
                        loading ? 
                        <tr className="border-b dark:border-gray-700">
                            <td className='px-4 py-3 border text-center' colSpan="100%">
                                Loading...
                            </td>
                        </tr> :
                        selectedYear === "" ?
                        <tr className="border-b dark:border-gray-700">
                            <td className="px-4 py-3 border text-center" colSpan="100%">Silakan Pilih Tahun terlebih dahulu</td>
                        </tr> :
                        monitoringState.data_rencanaaksi.length === 0 ?
                        <tr className="border-b dark:border-gray-700">
                            <td className="px-4 py-3 border text-center" colSpan="100%">( no data )</td>
                        </tr> :
                        monitoringState.data_rencanaaksi.map((item, key) => (
                            <>
                            <tr key={key} className="border-b dark:border-gray-700">
                                <td 
                                    className="px-4 py-3 border text-right align-top nowrap"
                                    rowSpan={item.indikator_sasaran.length > 0 ? item.indikator_sasaran.length : 1}
                                >
                                    {key+1}
                                </td>
                                <td 
                                    className="px-4 py-3 border align-top"
                                    rowSpan={item.indikator_sasaran.length > 0 ? item.indikator_sasaran.length : 1}
                                >
                                    {item.sasaran}
                                </td>
                                <td className="px-4 py-3 border align-top">{item.indikator_sasaran[0]?.indikator ?? "(belum ada data)"}</td>
                                <td className="px-4 py-3 border text-right align-top">{item.indikator_sasaran[0]?.rencana_aksi?.target_tw1 ?? ""}</td>
                                <td className="px-4 py-3 border text-right align-top">{item.indikator_sasaran[0]?.rencana_aksi?.target_tw2 ?? ""}</td>
                                <td className="px-4 py-3 border text-right align-top">{item.indikator_sasaran[0]?.rencana_aksi?.target_tw3 ?? ""}</td>
                                <td className="px-4 py-3 border text-right align-top">{item.indikator_sasaran[0]?.rencana_aksi?.target_tw4 ?? ""}</td>
                                <td className="px-4 py-3 border align-top">
                                    {
                                        item.indikator_sasaran[0]?.langkah?.length > 0 ? 
                                        <ul className='list-disc px-5'>
                                            {
                                                item.indikator_sasaran[0].langkah.map((i) => (
                                                    <li>{i.langkah}</li>
                                                ))
                                            }
                                        </ul> : null
                                    }
                                </td>
                                <td className="px-4 py-3 border text-right align-top" rowSpan={item.indikator_sasaran.length > 0 ? item.indikator_sasaran.length : 1}>{RupiahFormatter(231000000*(key+1))}</td>
                                
                            </tr>
                            {
                                item.indikator_sasaran.length > 1 ? 
                                    item.indikator_sasaran.map((val,x) => {
                                        if(x > 0){
                                            return (
                                                <tr key={x} className="border-b dark:border-gray-700">
                                                    <td className="px-4 py-3 border align-top">{val.indikator}</td>
                                                    <td className="px-4 py-3 border text-right align-top">{val.rencana_aksi?.target_tw1 ?? ""}</td>
                                                    <td className="px-4 py-3 border text-right align-top">{val.rencana_aksi?.target_tw2 ?? ""}</td>
                                                    <td className="px-4 py-3 border text-right align-top">{val.rencana_aksi?.target_tw3 ?? ""}</td>
                                                    <td className="px-4 py-3 border text-right align-top">{val.rencana_aksi?.target_tw4 ?? ""}</td>
                                                    <td className="px-4 py-3 border align-top">
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
                </div>
            </div>
        </Layout>
    )
}

export default MonitorRencanaAksiOpd