import React from 'react'
import Layout from '@/app/components/Layout/Layout'
import GoodNotes from "@assets/GoodNotes.png"
import { StaticTable } from '@/app/components/Table'
import { ChevronDownIcon, ChevronRightIcon } from '@heroicons/react/24/outline'
import { useParams, Link } from 'react-router-dom'
import { useSelector, useDispatch } from 'react-redux'
import { initFlowbite } from 'flowbite'
import { getListMonitorCascadingOpd } from '@/redux/ducks/monitoring/action'
import { getDatamasterOpd } from '@/redux/ducks/datamasteropd/action'
import { AlphabetList } from '@/helper/common.js'

const MonitorCascadingOpd = () => {
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
    
    // State untuk mengelola status collapse setiap parent row
    // Default semua child dalam keadaan collapse (tersembunyi)
    const [collapsedRows, setCollapsedRows] = React.useState(new Set())
    const tableHeader = () => (
        <tr>
            {/* <th scope="col" className="px-4 py-3 w-[3%]">No.</th> */}
            <th scope="col" className="px-4 py-3 text-center">Sasaran</th>
            <th scope="col" className="px-4 py-3 text-center w-[25%]">Program</th>
        </tr>
    )
    
    React.useEffect(() => {
        const response = dispatch(getListMonitorCascadingOpd({ master_opd_id: idopd }))
    },[])

    React.useEffect(() => { initFlowbite() },[monitoringState.data_cascading])

    // Effect untuk menginisialisasi semua parent rows sebagai collapsed
    React.useEffect(() => {
        if (monitoringState.data_cascading.length > 0) {
            const allParentIds = new Set()
            
            const collectParentIds = (data) => {
                data.forEach(item => {
                    if (item.sub_sasaran && item.sub_sasaran.length > 0) {
                        allParentIds.add(item.id)
                        collectParentIds(item.sub_sasaran)
                    }
                })
            }
            
            collectParentIds(monitoringState.data_cascading)
            setCollapsedRows(allParentIds)
        }
    }, [monitoringState.data_cascading])

    // Fungsi untuk toggle collapse/expand
    const toggleCollapse = (itemId) => {
        setCollapsedRows(prev => {
            const newSet = new Set(prev)
            if (newSet.has(itemId)) {
                newSet.delete(itemId)
            } else {
                newSet.add(itemId)
            }
            return newSet
        })
    }

    const renderTable = (data, level = 0) => {
        return data.map((item, i) => (
            <>
            <tr className="border-b dark:border-gray-700">
                <td className={`px-4 py-3 border tracking-widest ${level === 0 ? 'font-bold uppercase' : (level === 1 ? 'font-semibold' : 'italic')}`} 
                    style={{ paddingLeft: 20 + (level*25) + 'px' }}>
                    <div className="flex items-center gap-2">
                        {/* Tombol collapse/expand untuk parent yang memiliki child */}
                        {item.sub_sasaran && item.sub_sasaran.length > 0 && (
                            <button
                                onClick={() => toggleCollapse(item.id)}
                                className="flex items-center justify-center w-5 h-5 hover:bg-gray-200 dark:hover:bg-gray-600 rounded transition-colors"
                                title={collapsedRows.has(item.id) ? 'Expand' : 'Collapse'}
                            >
                                {collapsedRows.has(item.id) ? (
                                    <ChevronRightIcon className="w-4 h-4" />
                                ) : (
                                    <ChevronDownIcon className="w-4 h-4" />
                                )}
                            </button>
                        )}
                        {/* Spacer untuk child yang tidak memiliki tombol collapse */}
                        {(!item.sub_sasaran || item.sub_sasaran.length === 0) && (
                            <div className="w-5 h-5"></div>
                        )}
                        {/* Kolom sasaran yang dapat diklik untuk collapse/expand */}
                        <span 
                            className={`${item.sub_sasaran && item.sub_sasaran.length > 0 ? 'cursor-pointer hover:text-blue-600 dark:hover:text-blue-400 transition-colors' : ''}`}
                            onClick={() => {
                                if (item.sub_sasaran && item.sub_sasaran.length > 0) {
                                    toggleCollapse(item.id)
                                }
                            }}
                            title={item.sub_sasaran && item.sub_sasaran.length > 0 ? (collapsedRows.has(item.id) ? 'Klik untuk expand' : 'Klik untuk collapse') : ''}
                        >
                            { level === 0 ? AlphabetList[i] + ". " : null }
                            { level === 1 ? (i + 1) + ". " : null }
                            { level === 2 ? AlphabetList[i] + ". " : null }
                            { level > 2 ? i + 1 + ". " : null }
                            {item.sasaran}
                        </span>
                    </div>
                </td>
                <td className="px-4 py-3 border">
                {
                    item.program_pendukung?.length > 0 ? 
                    item.program_pendukung.map((val, key) => (
                        <ul className="list-disc list-inside">
                            <li className="px-4 py-3">{val.nama_program}</li>
                            
                        </ul>
                    )) : <div className="px-4 py-3 text-xs italic text-center">( Belum ada program )</div>
                }
                </td>
            </tr>
            {
                item.sub_sasaran.length > 0 && !collapsedRows.has(item.id) ?
                renderTable(item.sub_sasaran, level + 1) : null
            }
            </>
        ))
    }

    let loading = monitoringState.loading

    return (
        <Layout loading={loading}>
            <div className="bg-white h-auto dark:bg-gray-800 rounded-lg drop-shadow-xl py-1 px-3 w-full">
                <div className="dark:text-white">
                    <div className="flex flex-row items-center gap-3">
                        <div>
                            <img src={GoodNotes} alt="Perencanaan Kepala Daerah" className="object-contain" />
                        </div>
                        <div className="lg:text-lg font-bold text-teal-500 dark:text-white">Cascading Perangkat Daerah</div>
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
                    CASCADING KINERJA
                    <div className="w-full text-center italic text-lg dark:text-white font-semibold mb-3">{getSelectedOpd().nama_opd}</div>
                </div>
                <div className="block w-full p-4">
                    <StaticTable header={tableHeader()}>
                    {
                        loading ? 
                        <tr className="border-b dark:border-gray-700">
                            <td className="px-4 py-3 text-center" colSpan="100%">Loading...</td>
                        </tr> :
                        monitoringState.data_cascading.length > 0 ? 
                        renderTable(monitoringState.data_cascading, 0)
                            
                        : <tr className="border-b dark:border-gray-700">
                                <td className="px-4 py-3 text-center" colSpan="100%">Belum ada data</td>
                            </tr>
                    }
                    </StaticTable>
                </div>
            </div>
        </Layout>
    )
}

export default MonitorCascadingOpd