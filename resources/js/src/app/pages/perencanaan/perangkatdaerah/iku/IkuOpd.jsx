import React from 'react'
import Layout from '@/app/components/Layout/Layout'
import GoodNotes from "@assets/GoodNotes.png"
import { StaticTable } from '@/app/components/Table'
import { PencilSquareIcon } from '@heroicons/react/24/outline'
import { Link } from 'react-router-dom'
import { useSelector, useDispatch } from 'react-redux'
import { getListIkuOpd } from '@/redux/ducks/ikuopd/action'
import PrimaryBtn from '@/app/components/Button/PrimaryBtn'
import axios from 'axios'

const IkuOpd = () => {
    const dispatch = useDispatch()
    const ikuOpdState = useSelector((state) => state.ikuOpdState)
    const authState = useSelector((state) => state.authState)
    React.useEffect(() => {
        dispatch(getListIkuOpd())
    },[])
    const tableHeader = () => (
        <tr>
            <th scope="col" className="px-4 py-3 border w-[3%]">No.</th>
            <th scope="col" className="px-4 py-3 border">Indikator</th>
            <th scope="col" className="px-4 py-3 border">Baseline</th>
            <th scope="col" className="px-4 py-3 border">Rilis</th>
            <th scope="col" className="px-4 py-3 border">Sumber Data</th>
            <th scope="col" className="px-4 py-3 border w-[5%]">
                <span className="sr-only">Actions</span>
            </th>
        </tr>
    )
    const download = async () => {
        try {
            const BASE_HOST_URL =import.meta.env.VITE_BASE_HOST_URL
            const apiUrl = `${BASE_HOST_URL}/v1/opd/indikatorkinerjautama/cetak`
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
            let filename = 'Indikator_Kinerja_Utama.pdf'; //    fallback filename
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
    const dataIndikator = () => {
        let data = []
        if(renstraOpdState.data.length > 0){
            renstraOpdState.data.map((item) => {
                
                //check if list sasaran exist
                if(item.sasaran.length > 0){
                    item.sasaran.map((s) => {
                        
                        // check if list indikator
                        if(s.indikator_sasaran.length > 0){
                            s.indikator_sasaran.map((i) => {
                                data.push({
                                    id: i.id,
                                    sasaran: s.sasaran,
                                    indikator: i.indikator,
                                    definisi: i.defenisi,
                                    kegunaan: i.kegunaan,
                                    baseline: i.baseline,
                                    formula: i.formula_perhitungan,
                                    sumber_data: i.sumber_data,
                                    rilis: i.rilis
                                })
                            })
                        }
                    })
                } 
            })
        }
        
        return data
    }

    // delete soon
    const loading = false
    return (
        <Layout>
            <div className="bg-white h-auto dark:bg-gray-800 rounded-lg drop-shadow-xl py-1 px-3 w-full">
                <div className="dark:text-white">
                    <div className="flex flex-row items-center gap-3">
                        <div>
                            <img src={GoodNotes} alt="Perencanaan Kepala Daerah" className="object-contain" />
                        </div>
                        <div className="lg:text-lg font-bold text-teal-500 dark:text-white">Perencanaan IKU Perangkat Daerah</div>
                    </div>
                </div>
            </div>
            <div className="bg-white h-auto dark:bg-gray-800 rounded-lg drop-shadow-xl py-1 sm:px-3 px-1 w-full flex flex-col lg:min-h-[35rem]">
                <div className="block w-full p-4">
                    <h1 className="text-center font-semibold text-lg dark:text-white">Indikator Kinerja Utama</h1>
                </div>

                <div className="w-full flex sm:justify-end px-6">                    
                    <div className="w-full flex justify-end items-end md:w-1/4 sm:w-1/3 py-5">
                        <PrimaryBtn onClick={() => download()}>Export</PrimaryBtn>
                    </div>
                </div>
                <div className="block w-full p-4">
                    <StaticTable header={tableHeader()}>
                    {
                        !ikuOpdState.loading ?
                        ikuOpdState.list.map((item, x) => (
                            <tr key={x} className="border-b dark:border-gray-700">
                                <td className="px-4 py-3 border text-right">{x+1}</td>
                                <td className="px-4 py-3 border">{item.indikator}</td>
                                <td className="px-4 py-3 border">{item.baseline}</td>
                                <td className="px-4 py-3 border">{item.rilis}</td>
                                <td className="px-4 py-3 border">{item.sumber_data}</td>
                                <td className="px-4 py-3 border flex justify-center">
                                    <Link to={`/perencanaan/opd/iku/${item.id}`}>
                                        <PencilSquareIcon className='w-5 h-5' />
                                    </Link>                                        
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
        </Layout>
    )
}

export default IkuOpd