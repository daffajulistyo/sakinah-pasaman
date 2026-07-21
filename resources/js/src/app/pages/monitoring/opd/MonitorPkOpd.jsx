import React from 'react'
import Layout from '@/app/components/Layout/Layout'
import GoodNotes from "@assets/GoodNotes.png"
import { useParams } from 'react-router-dom'
import { StaticTable } from '@/app/components/Table'
import PrimaryBtn from '@/app/components/Button/PrimaryBtn'
import { Link } from 'react-router-dom'
import { useDispatch, useSelector } from 'react-redux'
import { initFlowbite } from 'flowbite'
import { numberFormatter } from '@/helper/common'
import { getListMonitorPerjanjianKinerjaOpd } from '@/redux/ducks/monitoring/action'
import { getDatamasterOpd } from '@/redux/ducks/datamasteropd/action'
import axios from 'axios'

const MonitorPkOpd = () => {
    const dispatch = useDispatch()
    const monitoringState = useSelector((state) => state.monitoringState)
    const datamasterOpdState = useSelector((state) => state.datamasterOpdState)

    const [selectedPeriod, setSelectedPeriod] = React.useState(null)
    const [selectedYear, setSelectedYear] = React.useState("")
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

    React.useEffect(() => {
        initFlowbite()
    },[monitoringState.data_pk])

    React.useEffect(() => {
        if(selectedYear !== "" && selectedPeriod !== null) getDataTable(selectedYear, selectedPeriod)
    },[selectedYear, selectedPeriod])

    const getDataTable = async (year, period) => {
        let tahun = year // date.getFullYear()
        let murni = period
        
        const response = await dispatch(getListMonitorPerjanjianKinerjaOpd({tahun, murni, master_opd_id: idopd, eselon: "II"}))
    }

    
    const tableHeader = () => (
        <tr>
            <th scope="col" className="px-4 py-3 border w-[3%]">No.</th>
            <th scope="col" className="px-4 py-3 border text-center">Sasaran</th>
            <th scope="col" className="px-4 py-3 border text-center">Indikator</th>
            <th scope="col" className="px-4 py-3 border text-center w-[5%]">Target <br /> N</th>
            <th scope="col" className="px-4 py-3 border text-center">Anggaran</th>
        </tr>
    )

    const renderTable = () => {
        if(selectedYear !== "" && selectedPeriod !== null) {
            return monitoringState.data_pk.length > 0 ? monitoringState.data_pk.map((item, x) => (
                <>
                <tr key={x} className="border-b dark:border-gray-700">
                    <td className="px-4 py-3 border text-right" rowSpan={item.indikator_sasaran.length > 0 ? item.indikator_sasaran.length : 1}>{x+1}</td>
                    <td className="px-4 py-3 border" rowSpan={item.indikator_sasaran.length > 0 ? item.indikator_sasaran.length : 1}>{item.sasaran}</td>
                    <td className="px-4 py-3 border">{item.indikator_sasaran.length > 0 ? (`1. ${item.indikator_sasaran[0].indikator}`) : "-"}</td>
                    <td className="px-4 py-3 border text-right">
                        {
                            item.indikator_sasaran.length > 0 ? (item.indikator_sasaran[0].perjanjian_kinerja?.target ?? "-") : "-"
                        }
                    </td>
                    <td className="px-4 py-3 border text-right" rowSpan={item.indikator_sasaran.length > 0 ? item.indikator_sasaran.length : 1}>
                        {
                            item.anggaran ? numberFormatter(item.anggaran) : 0
                        }
                    </td>
                </tr>
                {
                    item.indikator_sasaran.length > 1 ? item.indikator_sasaran.map((i, n) => (
                        n > 0 ? 
                        <tr key={n} className="border-b dark:border-gray-700">
                            <td className="px-4 py-3 border">{`${n+1}. ${i.indikator}`}</td>
                            <td className="px-4 py-3 border text-right">
                                {
                                    i.perjanjian_kinerja ? i.perjanjian_kinerja.target : "-"
                                }
                            </td>
                        </tr> : null
                    )) : null
                }
                </>
            ))
            :
            <tr className="border-b dark:border-gray-700">
                <td className="px-4 py-3 border text-center" colSpan="100%">No Data</td>
            </tr>
        }
        else {
            return <tr className="border-b dark:border-gray-700">
                <td className="px-4 py-3 border text-center" colSpan="100%">Pilih Tahun dan Periode</td>
            </tr>
        }
    }
    
    const download = async () => {
        try {
            const BASE_HOST_URL =import.meta.env.VITE_BASE_HOST_URL
            const apiUrl = `${BASE_HOST_URL}/v1/monitoring/opd/perjanjian_kinerja/cetak?tahun=${selectedYear}&murni=${selectedPeriod}&eselon=II&master_opd_id=${idopd}`
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
            let namaOpd = dgetSelectedOpd().nama_opd ?? "Nama_Opd"
            namaOpd = namaOpd.replace(' ', '_')
            let filename = `Perjanjian_Kinerja_Perangkat_Daerah_${namaOpd}.pdf`; //    fallback filename
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

    //delete soon
    const loading = monitoringState.loading

    return (
        <Layout loading={loading}>
            <div className="bg-white h-auto dark:bg-gray-800 rounded-lg drop-shadow-xl py-1 px-3 w-full">
                <div className="dark:text-white">
                    <div className="flex flex-row items-center gap-3">
                        <div>
                            <img src={GoodNotes} alt="Perencanaan Kepala Daerah" className="object-contain" />
                        </div>
                        <div className="lg:text-lg font-bold text-teal-500 dark:text-white">PK Perangkat Daerah</div>
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
                    PERJANJIAN KINERJA
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
                    <div className="w-full md:w-1/4 sm:w-1/3 md:py-5 py-2">
                        <label htmlFor="" className="py-2 font-semibold dark:text-white">Periode</label>
                        <select 
                            value={selectedPeriod} 
                            onChange={(e) => setSelectedPeriod(e.target.value)} 
                            className="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        >
                            <option value={null}>Pilih Periode</option>
                            <option value={true}>Murni</option>
                            <option value={false}>Perubahan</option>
                        </select>
                    </div>
                </div>
                {
                    (selectedYear !== "" && selectedPeriod !== null) ?
                    <div className="w-full flex sm:justify-end px-6">                    
                        <div className="w-full flex justify-end items-end md:w-1/4 sm:w-1/3 py-5">
                            <PrimaryBtn onClick={() => download()}>Export</PrimaryBtn>
                        </div>
                    </div> : null
                }
                
                <div className="block w-full p-4">
                    <StaticTable header={tableHeader()}>
                    {
                        !loading ? renderTable() :
                        <tr className="border-b dark:border-gray-700">
                            <td className="px-4 py-3 border text-center" colSpan="100%">Loading...</td>
                        </tr>
                    }
                    </StaticTable>
                    
                </div>
            </div>
        </Layout>
    )
}

export default MonitorPkOpd