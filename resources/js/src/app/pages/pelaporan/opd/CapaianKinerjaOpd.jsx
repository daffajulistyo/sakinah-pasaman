import React from 'react'
import { StaticTable } from '@/app/components/Table'
import Layout from '@/app/components/Layout/Layout'
import GoodNotes from "@assets/GoodNotes.png"
import PrimaryBtn from '@/app/components/Button/PrimaryBtn'
import { useDispatch, useSelector } from 'react-redux'
import { getListPelaporanCapaianKinerjaOpd } from '@/redux/ducks/pelaporanopd/action'
import Swal from 'sweetalert2'
import { alphaNumeric } from '@/app/helper/Common'

const CapaianKinerjaOpd = () => {
    const dispatch = useDispatch()
    const pelaporanOpdState = useSelector((state) => state.pelaporanOpdState)
    const getDataTable = async (tahun = "") => {
        let response = await dispatch(getListPelaporanCapaianKinerjaOpd({ tahun: tahun == "" ? selectedYear : tahun }))
        if(response.error !== null){
            Swal.fire({
                icon: 'error',
                title: "something went wrong",
                showConfirmButton: true,
                confirmButtonText: 'Refresh Halaman',
                timer: 3000
            }).then(async (result) => {
                if(result.isConfirmed) window.location.reload()
            })
        }
    }
    const [selectedYear, setSelectedYear] = React.useState(new Date().getFullYear());
    const handleYearChange = (event) => {
        setSelectedYear(event.target.value);
        getDataTable(event.target.value)
    };
    React.useEffect(() => {
        getDataTable()
    },[])
    const tableHeader = () => (
        <>
        <tr>
            <th scope="col" className="px-4 py-3 border w-[3%]" rowSpan="3">No.</th>
            <th scope="col" className="px-4 py-3 border text-center" rowSpan="3">Tujuan/Sasaran</th>
            <th scope="col" className="px-4 py-3 border text-center" rowSpan="3">Indikator</th>
            <th scope="col" className="px-4 py-3 border text-center" colSpan="3">Capaian Kinerja Tahun n</th>
            <th scope="col" className="px-4 py-3 border text-center" colSpan="4">Peningkatan dari tahun lalu (n-1)</th>
            <th scope="col" className="px-4 py-3 border text-center" colSpan="3">Perbandingan dengan tahun Terakhir RPJMD</th>
            <th scope="col" className="px-4 py-3 border text-center" colSpan="3">Perbandingan dengan Nasional</th>
        </tr>
        <tr>
            <th scope="col" className="px-4 py-3 border text-center" rowSpan="2">T</th>
            <th scope="col" className="px-4 py-3 border text-center" rowSpan="2">R</th>
            <th scope="col" className="px-4 py-3 border text-center" rowSpan="2">C</th>
            <th scope="col" className="px-4 py-3 border text-center" rowSpan="2">Realisasi <br /> (n - 1)</th>
            <th scope="col" className="px-4 py-3 border text-center" rowSpan="2">Capaian <br /> (n - 1)</th>
            <th scope="col" className="px-4 py-3 border text-center" colSpan="2">
                Perbandingan dengan tahun lalu
            </th>
            <th scope="col" className="px-4 py-3 border text-center" rowSpan="2">
                Target Tahun Terakhir RPJMD
            </th>
            <th scope="col" className="px-4 py-3 border text-center" rowSpan="2">
                Realisasi tahun n
            </th>
            <th scope="col" className="px-4 py-3 border text-center" rowSpan="2">
                Selisih
            </th>
            <th scope="col" className="px-4 py-3 border text-center" rowSpan="2">
                Rata-rata Nasional
            </th>
            <th scope="col" className="px-4 py-3 border text-center" rowSpan="2">
                Realisasi tahun n
            </th>
            <th scope="col" className="px-4 py-3 border text-center" rowSpan="2">
                Peringkat Nasional
            </th>
        </tr>
        <tr>
            <th scope="col" className="px-4 py-3 border text-center">
                Realisasi
            </th>
            <th scope="col" className="px-4 py-3 border text-center">
                Capaian
            </th>
        </tr>
        <tr>
            <th scope="col" className="px-4 py-3 border text-center font-normal italic text-slate-400 ">
                1
            </th>
            <th scope="col" className="px-4 py-3 border text-center font-normal italic text-slate-400 ">
                2
            </th>
            <th scope="col" className="px-4 py-3 border text-center font-normal italic text-slate-400 ">
                3
            </th>
            <th scope="col" className="px-4 py-3 border text-center font-normal italic text-slate-400 ">
                4
            </th>
            <th scope="col" className="px-4 py-3 border text-center font-normal italic text-slate-400 ">
                5
            </th>
            <th scope="col" className="px-4 py-3 border text-center font-normal italic text-slate-400 ">
                6
            </th>
            <th scope="col" className="px-4 py-3 border text-center font-normal italic text-slate-400 ">
                7
            </th>
            <th scope="col" className="px-4 py-3 border text-center font-normal italic text-slate-400 ">
                8
            </th>
            <th scope="col" className="px-4 py-3 border text-center font-normal italic text-slate-400 ">
                9 = 5 - 7
            </th>
            <th scope="col" className="px-4 py-3 border text-center font-normal italic text-slate-400 ">
                10 = 6 - 8
            </th>
            <th scope="col" className="px-4 py-3 border text-center font-normal italic text-slate-400 ">
                11
            </th>
            <th scope="col" className="px-4 py-3 border text-center font-normal italic text-slate-400 ">
                12 = 5
            </th>
            <th scope="col" className="px-4 py-3 border text-center font-normal italic text-slate-400 ">
                13 = 11 -12
            </th>
            <th scope="col" className="px-4 py-3 border text-center font-normal italic text-slate-400 ">
                14
            </th>
            <th scope="col" className="px-4 py-3 border text-center font-normal italic text-slate-400 ">
                15 = 5
            </th>
            <th scope="col" className="px-4 py-3 border text-center font-normal italic text-slate-400 ">
                16
            </th>
        </tr>
        </>
    )

    const renderTujuan = (data) => {
        return (
            data.length > 0 ?
            data.map((item, key) => (
                <>
                <tr key={key}>
                    <td colSpan="100%" className="px-12 py-3 border font-bold italic bg-violet-50 dark:bg-violet-900 dark:text-slate-50">Tujuan {key+1} :  {item.tujuan.toUpperCase()}</td>
                </tr>
                {
                    renderIndikatorTujuan(item.indikator_tujuan)
                }
                {
                    renderSasaran(item.sasaran)
                }
                </>
            )) : 
            <tr className="border-b dark:border-gray-700">
                <td colSpan="100%" className="px-4 py-3 border text-center">No Data</td>
            </tr>
        )
    }
    const renderIndikatorTujuan = (data) => {
        return (
            data.length > 0 ?
            data.map((item, key) => (
                <tr key={"it" + key} className="bg-slate-50 dark:bg-slate-900">
                    <td className="px-4 py-3 border font-semibold" colSpan="3">Indikator : {item.indikator}</td>
                    <td className="px-4 py-3 border">{item.target_tahun_sekarang}</td>
                    <td className="px-4 py-3 border">{item.realisasi_tahun_sekarang}</td>
                    <td className="px-4 py-3 border">{item.capaian_tahun_sekarang}</td>
                    <td className="px-4 py-3 border">{item.realisasi_tahun_lalu}</td>
                    <td className="px-4 py-3 border">{item.capaian_tahun_lalu}</td>
                    <td className="px-4 py-3 border">
                        {item.realisasi_tahun_sekarang - item.realisasi_tahun_lalu}
                    </td>
                    <td className="px-4 py-3 border">
                        {item.capaian_tahun_sekarang - item.capaian_tahun_lalu}
                    </td>
                    <td className="px-4 py-3 border">{item.target_tahun_terakhir}</td>
                    <td className="px-4 py-3 border">{item.realisasi_tahun_sekarang}</td>
                    <td className="px-4 py-3 border">
                        {item.target_tahun_terakhir - item.realisasi_tahun_sekarang}
                    </td>
                    <td className="px-4 py-3 border">-</td>
                    <td className="px-4 py-3 border">-</td>
                    <td className="px-4 py-3 border">-</td>
                </tr>
            ))
            :
            null
        )
    }
    const renderSasaran = (data) => {
        return (
            data.length > 0 ?
            data.map((item, key) => (
                <>
                <tr key={"sas" + key}>
                    <td className="px-4 py-3 border"
                        rowSpan={item.indikator_sasaran.length > 1 ? item.indikator_sasaran.length : 1}>
                            {alphaNumeric[key]}
                    </td>
                    <td className="px-4 py-3 border" 
                        rowSpan={item.indikator_sasaran.length > 1 ? item.indikator_sasaran.length : 1}>
                            {item.sasaran}
                    </td>
                    <td className="px-4 py-3 border">1. {item.indikator_sasaran[0]?.indikator ?? ""}</td>
                    <td className="px-4 py-3 border">{item.indikator_sasaran[0]?.target_tahun_sekarang}</td>
                    <td className="px-4 py-3 border">{item.indikator_sasaran[0]?.realisasi_tahun_sekarang}</td>
                    <td className="px-4 py-3 border">{item.indikator_sasaran[0]?.capaian_tahun_sekarang}</td>
                    <td className="px-4 py-3 border">{item.indikator_sasaran[0]?.realisasi_tahun_lalu}</td>
                    <td className="px-4 py-3 border">{item.indikator_sasaran[0]?.capaian_tahun_lalu}</td>
                    <td className="px-4 py-3 border">
                        {item.indikator_sasaran[0]?.realisasi_tahun_sekarang - item.indikator_sasaran[0]?.realisasi_tahun_lalu}
                    </td>
                    <td className="px-4 py-3 border">
                        {item.indikator_sasaran[0]?.capaian_tahun_sekarang - item.indikator_sasaran[0]?.capaian_tahun_lalu}
                    </td>
                    <td className="px-4 py-3 border">{item.indikator_sasaran[0]?.target_tahun_terakhir}</td>
                    <td className="px-4 py-3 border">{item.indikator_sasaran[0]?.realisasi_tahun_sekarang}</td>
                    <td className="px-4 py-3 border">
                        {item.indikator_sasaran[0]?.target_tahun_terakhir - item.indikator_sasaran[0]?.realisasi_tahun_sekarang}
                    </td>
                    <td className="px-4 py-3 border">-</td>
                    <td className="px-4 py-3 border">-</td>
                    <td className="px-4 py-3 border">-</td>
                </tr>
                {
                    renderIndikatorSasaran(item.indikator_sasaran)
                }
                </>
            ))
            :
            null
        )
    }
    const renderIndikatorSasaran = (data) => {
        return (
            data.length > 0 ?
            data.map((item, key) => (
                key > 0 ?
                <tr key={"isas" + key}>
                    <td className="px-4 py-3 border">{key+1}. {item.indikator ?? ""}</td>
                    <td className="px-4 py-3 border">{item.target_tahun_sekarang}</td>
                    <td className="px-4 py-3 border">{item.realisasi_tahun_sekarang}</td>
                    <td className="px-4 py-3 border">{item.capaian_tahun_sekarang}</td>
                    <td className="px-4 py-3 border">{item.realisasi_tahun_lalu}</td>
                    <td className="px-4 py-3 border">{item.capaian_tahun_lalu}</td>
                    <td className="px-4 py-3 border">
                        {item.realisasi_tahun_sekarang - item.realisasi_tahun_lalu}
                    </td>
                    <td className="px-4 py-3 border">
                        {item.capaian_tahun_sekarang - item.capaian_tahun_lalu}
                    </td>
                    <td className="px-4 py-3 border">{item.target_tahun_terakhir}</td>
                    <td className="px-4 py-3 border">{item.realisasi_tahun_sekarang}</td>
                    <td className="px-4 py-3 border">
                        {item.target_tahun_terakhir - item.realisasi_tahun_sekarang}
                    </td>
                    <td className="px-4 py-3 border">-</td>
                    <td className="px-4 py-3 border">-</td>
                    <td className="px-4 py-3 border">-</td>
                </tr> : null
            ))
            :
            null
        )
    }
    return (
        <Layout loading={pelaporanOpdState.loading}>
            <div className="bg-white h-auto dark:bg-gray-800 rounded-lg drop-shadow-xl py-1 px-3 w-full">
                <div className="dark:text-white">
                    <div className="flex flex-row items-center gap-3">
                        <div>
                            <img src={GoodNotes} alt="Perencanaan Kepala Daerah" className="object-contain" />
                        </div>
                        <div className="lg:text-lg font-bold text-teal-500 dark:text-white">Pelaporan Perangkat Daerah</div>
                    </div>
                </div>
            </div>
            <div className="bg-white h-auto dark:bg-gray-800 rounded-lg drop-shadow-xl py-1 sm:px-3 px-1 w-full flex flex-col lg:min-h-[35rem]">
                <div className="block w-full p-4">
                    <div className="text-center font-bold">
                        CAPAIAN KINERJA
                    </div>
                </div>
                <div className="w-full flex sm:justify-between px-6">
                    <div className="w-full md:w-1/4 sm:w-1/3 py-5">
                        <label htmlFor="" className="py-2 font-semibold dark:text-white">Tahun N</label>
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
                    
                    <div className="w-full flex justify-end items-end md:w-1/4 sm:w-1/3 py-5">
                        <PrimaryBtn>Export</PrimaryBtn>
                    </div>
                </div>
                <div className="block w-full p-4">
                    <StaticTable header={tableHeader()}>
                    {
                        pelaporanOpdState.loading ? 
                        <tr className="border-b dark:border-gray-700">
                            <td colSpan="100%" className="px-4 py-3 border text-center">Loading...</td>
                        </tr> : (
                            pelaporanOpdState.capaian_kinerja.length > 0 ?  
                            renderTujuan(pelaporanOpdState.capaian_kinerja)
                            :
                            <tr className="border-b dark:border-gray-700">
                                <td colSpan="100%" className="px-4 py-3 border text-center">No Data</td>
                            </tr>
                        )
                    }
                    </StaticTable>
                </div>
            </div>
        </Layout>
    )
}

export default CapaianKinerjaOpd