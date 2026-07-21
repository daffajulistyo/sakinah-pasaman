import React from 'react'
import Layout from '@/app/components/Layout/Layout'
import GoodNotes from "@assets/GoodNotes.png"
import { StaticTable } from '@/app/components/Table'
import { Link, useParams } from 'react-router-dom'
import { useSelector, useDispatch } from 'react-redux'
import PrimaryBtn from '@/app/components/Button/PrimaryBtn'
import { getListMonitorIkuOpd } from '@/redux/ducks/monitoring/action'
import { getDatamasterOpd } from '@/redux/ducks/datamasteropd/action'

const MonitorIkuOpd = () => {
    const dispatch = useDispatch()
    const { idopd } = useParams()
    const monitoringState = useSelector((state) => state.monitoringState)
    const datamasterOpdState = useSelector((state) => state.datamasterOpdState)
    React.useEffect(() => {
        dispatch(getListMonitorIkuOpd({ master_opd_id: idopd }))
    },[])

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
    const tableHeader = () => (
        <tr>
            <th scope="col" className="px-4 py-3 border w-[3%]">No.</th>
            <th scope="col" className="px-4 py-3 border">Indikator</th>
            <th scope="col" className="px-4 py-3 border">Baseline</th>
            <th scope="col" className="px-4 py-3 border">Rilis</th>
            <th scope="col" className="px-4 py-3 border">Sumber Data</th>
            <th scope="col" className="px-4 py-3 border w-[40%]">Definisi</th>
        </tr>
    )

    // delete soon
    const loading = monitoringState.loading
    return (
        <Layout loading={loading}>
            <div className="bg-white h-auto dark:bg-gray-800 rounded-lg drop-shadow-xl py-1 px-3 w-full">
                <div className="dark:text-white">
                    <div className="flex flex-row items-center gap-3">
                        <div>
                            <img src={GoodNotes} alt="Perencanaan Kepala Daerah" className="object-contain" />
                        </div>
                        <div className="lg:text-lg font-bold text-teal-500 dark:text-white">IKU Perangkat Daerah</div>
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
                    INDIKATOR KINERJA UTAMA
                    <div className="w-full text-center italic text-lg dark:text-white font-semibold mb-3">{getSelectedOpd().nama_opd}</div>
                </div>

                
                <div className="block w-full p-4">
                    <StaticTable header={tableHeader()}>
                    {
                        !loading ?
                        monitoringState.data_iku.length > 0 ?
                            monitoringState.data_iku.map((item, x) => (
                                <tr key={x} className="border-b odd:bg-gray-50 odd:dark:bg-gray-600 dark:border-gray-700">
                                    <td className="px-4 py-3 border align-top text-right">{x+1}</td>
                                    <td className="px-4 py-3 border align-top">{item.indikator}</td>
                                    <td className="px-4 py-3 border align-top">{item.baseline}</td>
                                    <td className="px-4 py-3 border align-top">{item.rilis}</td>
                                    <td className="px-4 py-3 border align-top">{item.sumber_data}</td>
                                    <td className="px-4 py-3 border">{item.defenisi}</td>
                                </tr> 
                            )) 
                            :
                            <tr className="border-b dark:border-gray-700">
                                <td className="px-4 py-3 border text-center" colSpan="100%">No Data</td>
                            </tr>
                        : 
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

export default MonitorIkuOpd