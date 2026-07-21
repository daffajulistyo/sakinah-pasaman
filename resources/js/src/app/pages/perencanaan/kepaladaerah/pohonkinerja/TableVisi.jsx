
import { MyTable, TableBody, TableHeader, TableSection } from '@/app/components/Table'
import React from 'react'
import { PacmanLoader } from 'react-spinners'
import { initFlowbite } from 'flowbite'
import { Link } from 'react-router-dom'
import { PencilSquareIcon, TrashIcon } from '@heroicons/react/24/outline'

const TableVisi = ({ data = [], loading = false, pagination = {}, getData, editAction, deleteAction }) => {
    React.useEffect(() => { initFlowbite() },[data])
    return (
        <TableSection getDataAction={getData} pagination={pagination}>
            <MyTable>
                <TableHeader>
                    <tr>
                        <th scope="col" className="px-4 py-3">No.</th>
                        <th scope="col" className="px-4 py-3">Periode</th>
                        <th scope="col" className="px-4 py-3">Visi</th>
                        <th scope="col" className="px-4 py-3">Active</th>
                        <th scope="col" className="px-4 py-3">
                            <span className="sr-only">Actions</span>
                        </th>
                    </tr>
                </TableHeader>
                <TableBody>
                    {
                        loading ? 
                        <tr className="border-b dark:border-gray-700">
                            <td scope="row" className="px-4 py-5 bg-gray-100 dark:bg-gray-800 dark:text-white" colSpan="100%">
                                <div className="flex flex-row justify-center w-full gap-12">
                                    <PacmanLoader size={10} color='gray' />
                                    Please Wait...
                                </div>
                            </td>
                        </tr> :
                        (data.length > 0 ?
                        data.map((item, key) => (
                            <tr key={item.id} className="border-b dark:border-gray-700 odd:bg-gray-100 dark:odd:bg-gray-900">
                                <th scope="row"
                                    className="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">{key+1}</th>
                                <td className="px-4 py-3">{`${item.period_starts} - ${item.period_ends}`}</td>
                                <td className="px-4 py-3">
                                    <Link to={`/perencanaan/kdh/pohonkinerja/misi?visiId=${item.id}`} className="hover:text-blue-500 hover:font-bold" >
                                        {item.visi}
                                    </Link>
                                </td>
                                <td className="px-4 py-3">{item.is_active ? "Aktif" : "Non-Aktif"}</td>
                                <td className="px-4 py-3 flex items-center justify-end">
                                    <button id={`btn-${key}`} data-dropdown-toggle={`toggle-btn${key}`}
                                        className="inline-flex items-center p-0.5 text-sm font-medium text-center text-gray-500 hover:text-gray-800 rounded-lg focus:outline-none dark:text-gray-400 dark:hover:text-gray-100"
                                        type="button">
                                        <svg className="w-5 h-5" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM16 12a2 2 0 100-4 2 2 0 000 4z" />
                                        </svg>
                                    </button>
                                    <div id={`toggle-btn${key}`}
                                        className="hidden z-10 w-44 bg-gray-50 rounded divide-y divide-gray-100 drop-shadow-lg dark:bg-gray-700 dark:divide-gray-600">
                                        <ul className="py-1 text-sm text-gray-700 dark:text-gray-200"
                                            aria-labelledby={`btn-${key}`}>
                                            <li>
                                                <a href="#" onClick={() => editAction(item)}
                                                    className="flex gap-1 py-2 px-4 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">
                                                    <PencilSquareIcon className='w-5 h-5' />
                                                    Edit
                                                </a>
                                            </li>
                                        </ul>
                                        <div className="py-1">
                                            <a href="#" onClick={() => deleteAction(item.id)}
                                                className="flex gap-1 py-2 px-4 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">
                                                <TrashIcon className='w-5 h-5' />
                                                Hapus
                                            </a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        )) :
                        <tr className="border-b dark:border-gray-700">
                            <td scope="row" className="px-4 py-3 text-center" colSpan="100%">No Data</td>
                        </tr>
                        )
                    }
                    
                </TableBody>
            </MyTable>
        </TableSection>
                        
                    
    )
}

export default TableVisi