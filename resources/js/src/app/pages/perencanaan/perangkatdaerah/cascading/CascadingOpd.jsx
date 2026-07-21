import React from 'react'
import Layout from '@/app/components/Layout/Layout'
import GoodNotes from "@assets/GoodNotes.png"
import { StaticTable } from '@/app/components/Table'
import PrimaryBtn from '@/app/components/Button/PrimaryBtn'
import { PlusCircleIcon, TrashIcon, ChevronDownIcon, ChevronRightIcon } from '@heroicons/react/24/outline'
import { useNavigate } from 'react-router-dom'
import { useSelector, useDispatch } from 'react-redux'
import { initFlowbite } from 'flowbite'
import { getListCascadingOpd } from '@/redux/ducks/cascadingopd/action'
import { AlphabetList } from '@/helper/common.js'

const CascadingOpd = () => {
    const navigate = useNavigate()
    const dispatch = useDispatch()
    const cascadingOpdState = useSelector((state) => state.cascadingOpdState)
    
    // State untuk mengelola status collapse setiap parent row
    // Default semua child dalam keadaan collapse (tersembunyi)
    const [collapsedRows, setCollapsedRows] = React.useState(new Set())
    const tableHeader = () => (
        <tr>
            {/* <th scope="col" className="px-4 py-3 w-[3%]">No.</th> */}
            <th scope="col" className="px-4 py-3 text-center">Sasaran</th>
            <th scope="col" className="px-4 py-3 text-center w-[25%]">Program</th>
            <th scope="col" className="px-4 py-3 w-[5%]">
                <span className="sr-only">Actions</span>
            </th>
        </tr>
    )
    
    React.useEffect(() => {
        const response = dispatch(getListCascadingOpd())
    },[])

    React.useEffect(() => { initFlowbite() },[cascadingOpdState.list])

    // Effect untuk menginisialisasi semua parent rows sebagai collapsed
    React.useEffect(() => {
        if (cascadingOpdState.list.length > 0) {
            const allParentIds = new Set()
            
            const collectParentIds = (data) => {
                data.forEach(item => {
                    if (item.sub_sasaran && item.sub_sasaran.length > 0) {
                        allParentIds.add(item.id)
                        collectParentIds(item.sub_sasaran)
                    }
                })
            }
            
            collectParentIds(cascadingOpdState.list)
            setCollapsedRows(allParentIds)
        }
    }, [cascadingOpdState.list])

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
                    item.program_pendukung.length > 0 ? 
                    item.program_pendukung.map((val, key) => (
                        <ul className="list-disc list-inside">
                            <li className="px-4 py-3">{val.nama_program}</li>
                            
                        </ul>
                    )) : <div className="px-4 py-3 text-xs italic text-center">( Belum ada program )</div>
                }
                </td>
                <td className="px-4 py-3 border">
                    <button id={`btn-${item.id}`} data-dropdown-toggle={`toggle-btn${item.id}`}
                        className="inline-flex items-center p-0.5 text-sm font-medium text-center text-gray-500 hover:text-gray-800 rounded-lg focus:outline-none dark:text-gray-400 dark:hover:text-gray-100"
                        type="button">
                        <svg className="w-5 h-5" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM16 12a2 2 0 100-4 2 2 0 000 4z" />
                        </svg>
                    </button>
                    <div id={`toggle-btn${item.id}`}
                        className="hidden z-10 w-48 bg-gray-50 rounded divide-y divide-gray-100 drop-shadow-lg dark:bg-gray-700 dark:divide-gray-600">
                        {/* <ul className="py-1 text-sm text-gray-700 dark:text-gray-200"
                            aria-labelledby={`btn-${key}`}>
                            <li>
                                <a href="#" onClick={() => editAction(item)}
                                    className="flex gap-1 py-2 px-4 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">
                                    <PencilSquareIcon className='w-5 h-5' />
                                    Edit
                                </a>
                            </li>
                        </ul> */}
                        <div className="py-1">
                            <a href="#" onClick={() => deleteAction(item.id)}
                                className="flex gap-1 py-2 px-4 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">
                                <TrashIcon className='w-5 h-5' />
                                Hapus Program
                            </a>
                        </div>
                    </div>
                </td>
            </tr>
            {
                item.sub_sasaran.length > 0 && !collapsedRows.has(item.id) ?
                renderTable(item.sub_sasaran, level + 1) : null
            }
            </>
        ))
    }

    return (
        <Layout>
            <div className="bg-white h-auto dark:bg-gray-800 rounded-lg drop-shadow-xl py-1 px-3 w-full">
                <div className="dark:text-white">
                    <div className="flex flex-row items-center gap-3">
                        <div>
                            <img src={GoodNotes} alt="Perencanaan Kepala Daerah" className="object-contain" />
                        </div>
                        <div className="lg:text-lg font-bold text-teal-500 dark:text-white">Perencanaan Cascading Perangkat Daerah</div>
                    </div>
                </div>
            </div>
            <div className="bg-white h-auto dark:bg-gray-800 rounded-lg drop-shadow-xl py-1 sm:px-3 px-1 w-full flex flex-col lg:min-h-[35rem]">
                <div className="block w-full p-4">
                    <div className="w-full flex justify-end">
                        <PrimaryBtn loading={false} onClick={() => navigate('/perencanaan/opd/cascading/add')} >
                            <PlusCircleIcon className="w-5 h-5" />
                            Tambah Program
                        </PrimaryBtn>
                    </div>
                </div>
                <div className="block w-full p-4">
                    <StaticTable header={tableHeader()}>
                    {
                        cascadingOpdState.list.length > 0 ? 
                        renderTable(cascadingOpdState.list, 0)
                            
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

export default CascadingOpd