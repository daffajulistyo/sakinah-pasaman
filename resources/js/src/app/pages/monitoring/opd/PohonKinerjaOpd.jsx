import React from 'react'
import Layout from '@/app/components/Layout/Layout'
import GoodNotes from "@assets/GoodNotes.png"
import OakTree from "@assets/Oak Tree.png"
import { Link } from 'react-router-dom'
import { useSelector, useDispatch } from 'react-redux'
import { getListMonitoringPohonKinerjaOpd } from '@/redux/ducks/monitoring/action'
import { getDatamasterOpd } from '@/redux/ducks/datamasteropd/action'
import { useParams } from 'react-router-dom'
import StaticTable from '@/app/components/Table/StaticTable'
import { AlphabetList } from '@/helper/common.js'

const PohonKinerjaOpd = () => {
    const dispatch = useDispatch()
    const monitoringState = useSelector((state) => state.monitoringState)
    const datamasterOpdState = useSelector((state) => state.datamasterOpdState)
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
        dispatch(getListMonitoringPohonKinerjaOpd({master_opd_id: idopd}))
    },[])   

    const tableHeader = () => (
        <tr>
            <th scope="col" className="px-4 py-3 text-center">Tujuan/Sasaran</th>
            <th scope="col" className="px-4 py-3 text-center">Indikator</th>
        </tr>
    )
    
    const renderTable = (data) => {
        return (
            data.length > 0 ?
                data.map((item, x) => (
                    <>
                    <tr key={x} className="dark:text-white dark:bg-gray-900 dark:border-gray-700">
                        <td colSpan={"100%"} className="px-4 font-bold italic pl-4 py-3 ">{AlphabetList[x].toUpperCase()}. {item.tujuan}</td>
                    </tr>
                    {
                        renderIndikatorTujuan(item.indikator_tujuan)
                    }
                    {
                        renderSasaran(item.sasaran)
                    }
                    </>
                ))
            : 
            <tr className="border-b dark:border-gray-700">
                <td colSpan={"100%"} className="px-4 py-3 text-center">No Data</td>
            </tr>
        )
    }
    const renderIndikatorTujuan = (data)=> {
        return (
            data.length > 0 ?
            data.map((item, x) => (
                x === 0 ?
                <tr key={x} className="dark:bg-gray-900 dark:text-white">
                    <td rowSpan={data.length > 1 ? data.length : 1} className="px-4 py-3 text-center">&nbsp;</td>
                    <td colSpan={"100%"} className="px-4 py-3">{AlphabetList[x]}. {item.indikator}</td>
                </tr>
            :
            <tr key={x} className="dark:bg-gray-900 dark:text-white">
                <td colSpan={"100%"} className="px-4 py-3">{AlphabetList[x]}. {item.indikator}</td>
            </tr>
            ))
            : null
        )
    }
    const renderSasaran = (data) => {
        return (
            data.length > 0 ?
            data.map((item, x) => (
                item.indikator_sasaran.length > 0 ?
                item.indikator_sasaran.map((val, key) => (
                    key === 0 ?
                    <tr key={key} className="border-b bg-teal-100 dark:bg-teal-800 dark:text-white dark:border-gray-700">
                        <td rowSpan={item.indikator_sasaran.length > 1 ? item.indikator_sasaran.length : 1} className="px-4 py-3 pl-10 align-top border">{(x+1)}. {item.sasaran}</td>
                        <td className="px-4 py-3 pl-10 border">{AlphabetList[key]}. {val.indikator}</td>
                    </tr>
                    :
                    <tr key={key} className="border-b bg-teal-100 dark:bg-teal-800 dark:text-white dark:border-gray-700">
                        <td className="px-4 py-3 pl-10 border">{AlphabetList[key]}. {val.indikator}</td>
                    </tr>
                ))
                :
                <tr className="border-b bg-teal-100 dark:bg-teal-800 dark:text-white dark:border-gray-700">
                    <td className="px-4 py-3 pl-10 border">{item.sasaran}</td>
                    <td className="px-4 py-3 pl-10 border text-center">Belum Ada Indikator</td>
                </tr>
            ))
            : 
            <tr className="border-b bg-teal-100 dark:border-gray-700">
                <td colSpan={"100%"} className="px-4 py-3 text-center">Belum Ada Sasaran</td>
            </tr>
        )
    }

    let loading = monitoringState.loading || datamasterOpdState.loading
    return (
        <Layout loading={loading}>
            <div className="bg-white h-auto dark:bg-gray-800 rounded-lg drop-shadow-xl py-1 px-3 w-full">
                <div className="dark:text-white flex w-full justify-between">
                    <div className="flex flex-row items-center gap-3">
                        <div>
                            <img src={GoodNotes} alt="Perencanaan Perangkat Daerah" className="object-contain" />
                        </div>
                        <div className="lg:text-lg font-bold text-teal-500 dark:text-white">Pohon Kinerja Perangkat Daerah</div>
                    </div>
                    <div className="flex p-2">
                        <Link to={'/monitoring/opd/pohonkinerja/view/' + idopd} className="flex flex-row items-center gap-3 bg-lime-600 dark:bg-lime-800 sm:px-5 px-1.5 sm:p-0.5 p-1 rounded-lg hover:bg-lime-700 dark:hover:bg-lime-900">
                            <div>
                                <img src={OakTree} alt="Perencanaan Kepala Daerah" className="object-contain sm:h-10 h-5 " />
                            </div>
                            <div className="text-sm sm:block hidden font-bold text-white">Lihat Pohon Kinerja</div>
                        </Link>
                    </div>

                </div>
            </div>
            <div className="bg-white h-auto dark:bg-gray-800 rounded-lg drop-shadow-xl py-5 px-4 w-full flex flex-col min-h-[35rem]">
                <div className="w-full text-center text-lg dark:text-white font-bold mb-3">
                    POHON KINERJA
                    <div className="w-full text-center italic text-lg dark:text-white font-semibold mb-3">{getSelectedOpd().nama_opd}</div>
                </div>
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
                <StaticTable header={tableHeader()}>
                    {
                        loading ? 
                        <tr className="border-b dark:border-gray-700">
                            <td className="px-4 py-3 text-center" colSpan="100%">Loading...</td>
                        </tr> :
                        renderTable(monitoringState.data_pohonkinerja ?? [])
                    }
                </StaticTable>
            </div>
        </Layout>
    )
}

export default PohonKinerjaOpd