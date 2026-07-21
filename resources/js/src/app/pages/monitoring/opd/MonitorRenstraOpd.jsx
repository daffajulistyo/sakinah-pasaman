import React from 'react'
import Layout from '@/app/components/Layout/Layout'
import GoodNotes from "@assets/GoodNotes.png"
import { StaticTable } from '@/app/components/Table'
import { useSelector, useDispatch } from 'react-redux'
import { useParams, Link } from 'react-router-dom'
import { getListMonitorRenstraOpd } from '@/redux/ducks/monitoring/action'
import { getDatamasterOpd } from '@/redux/ducks/datamasteropd/action'

const MonitorRenstraOpd = () => {
    const dispatch = useDispatch()
    const { idopd } = useParams()
    const monitoringState = useSelector((state) => state.monitoringState)
    const datamasterOpdState = useSelector((state) => state.datamasterOpdState)
    const tableHeader = () => (
        <>
        <tr>
            <th scope="col" className="px-4 py-3 border w-[3%]" rowSpan="2">No.</th>
            <th scope="col" className="px-4 py-3 border" rowSpan="2">Sasaran</th>
            <th scope="col" className="px-4 py-3 border" rowSpan="2">Indikator</th>
            <th scope="col" className="px-4 py-3 border" rowSpan="2">Satuan</th>
            <th scope="col" className="px-4 py-3 border text-center w-[25%]" colSpan="5">Target</th>
        </tr>
        <tr>
            <th scope="col" className="px-4 py-3 border text-nowrap">n ke-1</th>
            <th scope="col" className="px-4 py-3 border text-nowrap">n ke-2</th>
            <th scope="col" className="px-4 py-3 border text-nowrap">n ke-3</th>
            <th scope="col" className="px-4 py-3 border text-nowrap">n ke-4</th>
            <th scope="col" className="px-4 py-3 border text-nowrap">n ke-5</th>
        </tr>
        </>
    )
    React.useEffect(() => {
        dispatch(getListMonitorRenstraOpd({ master_opd_id: idopd }))
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

    const dataRpjmd = () => {
        let data = []
        if(monitoringState.data_renstra.length > 0){
            monitoringState.data_renstra.map((item) => {
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
                                    satuan: i.satuan,
                                    target_1: i.target_1,
                                    target_2: i.target_2,
                                    target_3: i.target_3,
                                    target_4: i.target_4,
                                    target_5: i.target_5,
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
    const loading = monitoringState.loading

    return (
        <Layout loading={loading}>
            <div className="bg-white h-auto dark:bg-gray-800 rounded-lg drop-shadow-xl py-1 px-3 w-full">
                <div className="dark:text-white">
                    <div className="flex flex-row items-center gap-3">
                        <div>
                            <img src={GoodNotes} alt="Perencanaan Kepala Daerah" className="object-contain" />
                        </div>
                        <div className="lg:text-lg font-bold text-teal-500 dark:text-white">Rencana Strategis Perangkat Daerah</div>
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
                    RENCANA STRATEGIS
                    <div className="w-full text-center italic text-lg dark:text-white font-semibold mb-3">{getSelectedOpd().nama_opd}</div>
                </div>
                <div className="block w-full p-4">
                    <StaticTable header={tableHeader()}>
                    {
                        !loading ? dataRpjmd().map((item, x) => (
                            <tr key={x} className="border-b dark:border-gray-700">
                                <td className="px-4 py-3 border text-right">{x+1}</td>
                                <td className="px-4 py-3 border">{item.sasaran}</td>
                                <td className="px-4 py-3 border">{item.indikator}</td>
                                <td className="px-4 py-3 border">{item.satuan}</td>
                                <td className="px-4 py-3 border text-right">{item.target_1}</td>
                                <td className="px-4 py-3 border text-right">{item.target_2}</td>
                                <td className="px-4 py-3 border text-right">{item.target_3}</td>
                                <td className="px-4 py-3 border text-right">{item.target_4}</td>
                                <td className="px-4 py-3 border text-right">{item.target_5}</td>
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

export default MonitorRenstraOpd