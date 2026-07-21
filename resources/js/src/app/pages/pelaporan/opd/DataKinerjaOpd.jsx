import React from 'react'
import { StaticTable } from '@/app/components/Table'
import Layout from '@/app/components/Layout/Layout'
import GoodNotes from "@assets/GoodNotes.png"
import PrimaryBtn from '@/app/components/Button/PrimaryBtn'
import { useDispatch, useSelector } from 'react-redux'
import Swal from 'sweetalert2'
import { alphaNumeric } from '@/app/helper/Common'
import { getListPelaporanDataKinerjaOpd } from '@/redux/ducks/pelaporanopd/action'

const DataKinerjaOpd = () => {
    const dispatch = useDispatch()
    const pelaporanOpdState = useSelector((state) => state.pelaporanOpdState)
    const getDataTable = async () => {
        let response = await dispatch(getListPelaporanDataKinerjaOpd())
        if(response.error !== null){
            Swal.fire({
                icon: 'error',
                title: "something went wrong",
                showConfirmButton: true,
                confirmButtonText: 'Refresh Halaman',
                timer: 1500
            }).then(async (result) => {
                if(result.isConfirmed) window.location.reload()
            })
        }
    }
    React.useEffect(() => {
        getDataTable()
    },[])
    const tableHeader = () => (
        <>
        <tr>
            <th scope="col" className="px-4 py-3 border w-[3%]" rowSpan="2">No.</th>
            <th scope="col" className="px-4 py-3 border text-center" rowSpan="2">Tujuan/Sasaran</th>
            <th scope="col" className="px-4 py-3 border text-center" rowSpan="2">Indikator</th>
            <th scope="col" className="px-4 py-3 border text-center" colSpan="3">Tahun n -1</th>
            <th scope="col" className="px-4 py-3 border text-center" colSpan="3">Tahun 1</th>
            <th scope="col" className="px-4 py-3 border text-center" colSpan="3">Tahun 2</th>
            <th scope="col" className="px-4 py-3 border text-center" colSpan="3">Tahun 3</th>
            <th scope="col" className="px-4 py-3 border text-center" colSpan="3">Tahun 4</th>
            <th scope="col" className="px-4 py-3 border text-center" colSpan="3">Tahun 5</th>
        </tr>
        <tr>
            <th scope="col" className="px-4 py-3 border text-center">T</th>
            <th scope="col" className="px-4 py-3 border text-center">R</th>
            <th scope="col" className="px-4 py-3 border text-center">C</th>
            <th scope="col" className="px-4 py-3 border text-center">T</th>
            <th scope="col" className="px-4 py-3 border text-center">R</th>
            <th scope="col" className="px-4 py-3 border text-center">C</th>
            <th scope="col" className="px-4 py-3 border text-center">T</th>
            <th scope="col" className="px-4 py-3 border text-center">R</th>
            <th scope="col" className="px-4 py-3 border text-center">C</th>
            <th scope="col" className="px-4 py-3 border text-center">T</th>
            <th scope="col" className="px-4 py-3 border text-center">R</th>
            <th scope="col" className="px-4 py-3 border text-center">C</th>
            <th scope="col" className="px-4 py-3 border text-center">T</th>
            <th scope="col" className="px-4 py-3 border text-center">R</th>
            <th scope="col" className="px-4 py-3 border text-center">C</th>
            <th scope="col" className="px-4 py-3 border text-center">T</th>
            <th scope="col" className="px-4 py-3 border text-center">R</th>
            <th scope="col" className="px-4 py-3 border text-center">C</th>
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
                    <td className="px-4 py-3 border">{item.target_1}</td>
                    <td className="px-4 py-3 border">{item.realisasi_1}</td>
                    <td className="px-4 py-3 border">{item.capaian_1}</td>
                    <td className="px-4 py-3 border">{item.target_2}</td>
                    <td className="px-4 py-3 border">{item.realisasi_2}</td>
                    <td className="px-4 py-3 border">{item.capaian_2}</td>
                    <td className="px-4 py-3 border">{item.target_3}</td>
                    <td className="px-4 py-3 border">{item.realisasi_3}</td>
                    <td className="px-4 py-3 border">{item.capaian_3}</td>
                    <td className="px-4 py-3 border">{item.target_4}</td>
                    <td className="px-4 py-3 border">{item.realisasi_4}</td>
                    <td className="px-4 py-3 border">{item.capaian_4}</td>
                    <td className="px-4 py-3 border">{item.target_5}</td>
                    <td className="px-4 py-3 border">{item.realisasi_5}</td>
                    <td className="px-4 py-3 border">{item.capaian_5}</td>
                    <td className="px-4 py-3 border">{item.target_6}</td>
                    <td className="px-4 py-3 border">{item.realisasi_6}</td>
                    <td className="px-4 py-3 border">{item.capaian_6}</td>
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
                    {
                        item.indikator_sasaran.length > 0 ? (
                            <>
                            <td className="px-4 py-3 border">1. {item.indikator_sasaran[0]?.indikator ?? "(No Data)"}</td>
                            <td className="px-4 py-3 border">{item.indikator_sasaran[0]?.target_1 ?? "" }</td>
                            <td className="px-4 py-3 border">{item.indikator_sasaran[0]?.realisasi_1 ?? "" }</td>
                            <td className="px-4 py-3 border">{item.indikator_sasaran[0]?.capaian_1 ?? "" }</td>
                            <td className="px-4 py-3 border">{item.indikator_sasaran[0]?.target_2 ?? "" }</td>
                            <td className="px-4 py-3 border">{item.indikator_sasaran[0]?.realisasi_2 ?? "" }</td>
                            <td className="px-4 py-3 border">{item.indikator_sasaran[0]?.capaian_2 ?? "" }</td>
                            <td className="px-4 py-3 border">{item.indikator_sasaran[0]?.target_3 ?? "" }</td>
                            <td className="px-4 py-3 border">{item.indikator_sasaran[0]?.realisasi_3 ?? "" }</td>
                            <td className="px-4 py-3 border">{item.indikator_sasaran[0]?.capaian_3 ?? "" }</td>
                            <td className="px-4 py-3 border">{item.indikator_sasaran[0]?.target_4 ?? "" }</td>
                            <td className="px-4 py-3 border">{item.indikator_sasaran[0]?.realisasi_4 ?? "" }</td>
                            <td className="px-4 py-3 border">{item.indikator_sasaran[0]?.capaian_4 ?? "" }</td>
                            <td className="px-4 py-3 border">{item.indikator_sasaran[0]?.target_5 ?? "" }</td>
                            <td className="px-4 py-3 border">{item.indikator_sasaran[0]?.realisasi_5 ?? "" }</td>
                            <td className="px-4 py-3 border">{item.indikator_sasaran[0]?.capaian_5 ?? "" }</td>
                            <td className="px-4 py-3 border">{item.indikator_sasaran[0]?.target_6 ?? "" }</td>
                            <td className="px-4 py-3 border">{item.indikator_sasaran[0]?.realisasi_6 ?? "" }</td>
                            <td className="px-4 py-3 border">{item.indikator_sasaran[0]?.capaian_6 ?? "" }</td>
                            </>
                        ) :
                        <td colSpan="19" className="px-4 py-3 border text-center">( No Data )</td>
                    }
                    
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
                    <td className="px-4 py-3 border">{item.target_1 ?? "" }</td>
                    <td className="px-4 py-3 border">{item.realisasi_1 ?? "" }</td>
                    <td className="px-4 py-3 border">{item.capaian_1 ?? "" }</td>
                    <td className="px-4 py-3 border">{item.target_2 ?? "" }</td>
                    <td className="px-4 py-3 border">{item.realisasi_2 ?? "" }</td>
                    <td className="px-4 py-3 border">{item.capaian_2 ?? "" }</td>
                    <td className="px-4 py-3 border">{item.target_3 ?? "" }</td>
                    <td className="px-4 py-3 border">{item.realisasi_3 ?? "" }</td>
                    <td className="px-4 py-3 border">{item.capaian_3 ?? "" }</td>
                    <td className="px-4 py-3 border">{item.target_4 ?? "" }</td>
                    <td className="px-4 py-3 border">{item.realisasi_4 ?? "" }</td>
                    <td className="px-4 py-3 border">{item.capaian_4 ?? "" }</td>
                    <td className="px-4 py-3 border">{item.target_5 ?? "" }</td>
                    <td className="px-4 py-3 border">{item.realisasi_5 ?? "" }</td>
                    <td className="px-4 py-3 border">{item.capaian_5 ?? "" }</td>
                    <td className="px-4 py-3 border">{item.target_6 ?? "" }</td>
                    <td className="px-4 py-3 border">{item.realisasi_6 ?? "" }</td>
                    <td className="px-4 py-3 border">{item.capaian_6 ?? "" }</td>
                </tr> : null
            ))
            :
            null
        )
    }
    return (
        <Layout loading={pelaporanOpdState.loading} >
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
                    <div className="text-center font-bold dark:text-white">
                        DATA KINERJA
                    </div>
                </div>
                <div className="w-full flex sm:justify-end px-6">                    
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
                            pelaporanOpdState.data_kinerja.length > 0 ?  
                            renderTujuan(pelaporanOpdState.data_kinerja)
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

export default DataKinerjaOpd