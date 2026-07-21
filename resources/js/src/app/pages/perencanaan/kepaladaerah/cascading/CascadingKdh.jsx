import Layout from '@/app/components/Layout/Layout'
import React from 'react'
import GoodNotes from "@assets/GoodNotes.png"
import { StaticTable } from '@/app/components/Table'
import PrimaryBtn from '@/app/components/Button/PrimaryBtn'
import { PlusCircleIcon, TrashIcon } from '@heroicons/react/24/outline'
import { useNavigate } from 'react-router-dom'
import { useSelector, useDispatch } from 'react-redux'
import { getListCascadingKdh } from '@/redux/ducks/cascadingkdh/action'
import { initFlowbite } from 'flowbite'

const CascadingKdh = () => {
    const navigate = useNavigate()
    const dispatch = useDispatch()
    const cascadingKdhState = useSelector((state) => state.cascadingKdhState)
    const tableHeader = () => (
        <tr>
            <th scope="col" className="px-4 py-3 w-[3%]">No.</th>
            <th scope="col" className="px-4 py-3">Program</th>
            <th scope="col" className="px-4 py-3 w-[10%]">
                <span className="sr-only">Actions</span>
            </th>
        </tr>
    )

    React.useEffect(() => {
        const response = dispatch(getListCascadingKdh())
    },[])

    React.useEffect(() => { initFlowbite() },[cascadingKdhState.list])

    return (
        <Layout>
            <div className="bg-white h-auto dark:bg-gray-800 rounded-lg drop-shadow-xl py-1 px-3 w-full">
                <div className="dark:text-white">
                    <div className="flex flex-row items-center gap-3">
                        <div>
                            <img src={GoodNotes} alt="Perencanaan Kepala Daerah" className="object-contain" />
                        </div>
                        <div className="lg:text-lg font-bold text-teal-500 dark:text-white">Perencanaan Cascading KDH</div>
                    </div>
                </div>
            </div>
            <div className="bg-white h-auto dark:bg-gray-800 rounded-lg drop-shadow-xl py-1 sm:px-3 px-1 w-full flex flex-col lg:min-h-[35rem]">
                <div className="block w-full p-4">
                    <div className="w-full flex justify-end">
                        <PrimaryBtn loading={false} onClick={() => navigate('/perencanaan/kdh/cascading/add')} >
                            <PlusCircleIcon className="w-5 h-5" />
                            Tambah Program
                        </PrimaryBtn>
                    </div>
                </div>
                <div className="block w-full p-4">
                    <StaticTable header={tableHeader()}>
                    {
                        cascadingKdhState.list.length > 0 ? 
                        cascadingKdhState.list.map((item) => (
                            <>
                            <tr className="border-b bg-green-600 text-gray-50 dark:bg-gray-900 dark:text-green-200 dark:border-gray-700">
                                <td className="px-4 py-3 font-semibold tracking-widest" colSpan="100%">
                                    Sasaran : {item.sasaran}
                                </td>
                            </tr>
                            {
                                item.program.length > 0 ? 
                                item.program.map((val, key) => (
                                    <tr className="border-b dark:border-gray-700">
                                        <td className="px-4 py-3 text-right">{key+1}</td>
                                        <td className="px-4 py-3">{val.nama_program}</td>
                                        <td className="px-4 py-3">
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
                                                    <a href="#" onClick={() => deleteAction(val.id)}
                                                        className="flex gap-1 py-2 px-4 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">
                                                        <TrashIcon className='w-5 h-5' />
                                                        Hapus Program
                                                    </a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                )) : <tr className="border-b dark:border-gray-700">
                                        <td className="px-4 py-3 text-center" colSpan="100%">Belum ada program</td>
                                    </tr>
                            }
                            </>
                        )) : <tr className="border-b dark:border-gray-700">
                                <td className="px-4 py-3 text-center" colSpan="100%">Belum ada data</td>
                            </tr>
                    }
                    </StaticTable>
                </div>
            </div>
        </Layout>
    )
}

export default CascadingKdh