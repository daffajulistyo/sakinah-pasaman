import Layout from '@/app/components/Layout/Layout'
import React from 'react'
import GoodNotes from "@assets/GoodNotes.png"
import { StaticTable } from '@/app/components/Table'
import PrimaryBtn from '@/app/components/Button/PrimaryBtn'
import { PencilSquareIcon } from '@heroicons/react/24/outline'
import { Link } from 'react-router-dom'
import { getListRpjmdKdh } from '@/redux/ducks/rpjmdkdh/action'
import { useSelector, useDispatch } from 'react-redux'
import axios from 'axios'

const IndikatorKinerjaUtama = () => {
    const dispatch = useDispatch()
    const rpjmdKdhState = useSelector((state) => state.rpjmdKdhState)
    React.useEffect(() => {
        dispatch(getListRpjmdKdh())
    },[])
    const dataIndikator = () => {
        let data = []
        if(rpjmdKdhState.data?.misi){
            // rpjmdKdhState.data.map((item) => {
                // check if list misi exist
                if(rpjmdKdhState.data.misi.length > 0){
                    rpjmdKdhState.data.misi.map((m) => {

                        // check if list tujuan exist
                        if(m.tujuan.length > 0){
                            m.tujuan.map((t) => {

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
                                                    satuan: i.satuan?.satuan ?? "-",
                                                    rilis: i.rilis ?? "-",
                                                    sumber_data: i.sumber_data ?? "-",
                                   
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
            const apiUrl = `${BASE_HOST_URL}/v1/kdh/indikatorkinerjautama/cetak`
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

    return (
        <Layout>
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
                <div className="flex items-center justify-between w-full p-4">
                    <h1 className="text-center font-semibold text-lg dark:text-white flex-1">Indikator Kinerja Utama</h1>
                </div>
                <div className="w-full flex sm:justify-end px-6">                    
                    <div className="w-full flex justify-end items-end md:w-1/4 sm:w-1/3 py-5">
                        <PrimaryBtn onClick={() => download()}>Export</PrimaryBtn>
                    </div>
                </div>
                <div className="block w-full p-4">
                    <StaticTable header={tableHeader()}>
                    {
                        !rpjmdKdhState.loading ?
                        dataIndikator().map((item, x) => (
                            <tr key={x} className="border-b dark:border-gray-700 odd:bg-gray-100 dark:odd:bg-gray-900">
                                <td className="px-4 py-3 border text-right">{x+1}</td>
                                <td className="px-4 py-3 border">{item.indikator}</td>
                                <td className="px-4 py-3 border">{item.baseline}</td>
                                <td className="px-4 py-3 border">{item.rilis}</td>
                                <td className="px-4 py-3 border">{item.sumber_data}</td>
                                <td className="px-4 py-3 border flex justify-center">
                                    <Link to={`/perencanaan/kdh/iku/${item.id}`}>
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

export default IndikatorKinerjaUtama