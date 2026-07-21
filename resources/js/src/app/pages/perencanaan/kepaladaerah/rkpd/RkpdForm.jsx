import Layout from '@/app/components/Layout/Layout'
import React from 'react'
import GoodNotes from "@assets/GoodNotes.png"
import { useParams, useNavigate } from 'react-router-dom'
import { StaticTable } from '@/app/components/Table'
import { PencilSquareIcon, ArrowLeftCircleIcon } from '@heroicons/react/24/outline'
import MyModal from '@/app/components/Form/MyModal'
import MyInput from '@/app/components/Form/MyInput'
import PrimaryBtn from '@/app/components/Button/PrimaryBtn'
import PrimaryLinkBtn from '@/app/components/Button/PrimaryLinkBtn'
import { Link } from 'react-router-dom'
import { getListRkpdKdh } from '@/redux/ducks/rkpdkdh/action'
import { useDispatch, useSelector } from 'react-redux'
import { initFlowbite } from 'flowbite'
import { numberFormatter } from '@/helper/common'
import { createRkpdKdh } from '@/redux/ducks/rkpdkdh/action'
import Swal from 'sweetalert2'

const RkpdForm = () => {
    const dispatch = useDispatch()
    const rkpdKdhState = useSelector((state) => state.rkpdKdhState)

    const navigate = useNavigate()
    const { type, period } = useParams()
    const [openModal, setOpenModal] = React.useState(false)
    const [formTitle, setFormTitle] = React.useState("")
    React.useEffect(() => {
        if(type !== "murni" && type !== "perubahan"){
            navigate('/perencanaan/kdh/rkpd')
        }
        setFormTitle("FORM INPUT TARGET RKPD "+type.toUpperCase())
    },[type])
    React.useEffect(() => {
        initFlowbite()
    },[rkpdKdhState.list])

    React.useEffect(() => {
        getDataTable()
    },[])


    const getDataTable = async () => {
        let date = new Date()
        let tahun = period // date.getFullYear()
        let murni = type === "murni"
        
        const response = await dispatch(getListRkpdKdh({tahun, murni}))
    }

    const tableHeader = () => (
        <tr>
            <th scope="col" className="px-4 py-3 border w-[3%]">No.</th>
            <th scope="col" className="px-4 py-3 border text-center">Sasaran</th>
            <th scope="col" className="px-4 py-3 border text-center">Indikator</th>
            <th scope="col" className="px-4 py-3 border text-center w-[5%]">Target</th>
            <th scope="col" className="px-4 py-3 border text-center">Anggaran</th>
            <th scope="col" className="px-4 py-3 border w-[5%]">
                <span className="sr-only">Actions</span>
            </th>
        </tr>
    )

    const renderTable = () => {
        return rkpdKdhState.list.length > 0 ? rkpdKdhState.list.map((item, x) => (
            <>
            <tr key={x} className="border-b dark:border-gray-700 odd:bg-gray-100 dark:odd:bg-gray-900">
                <td className="px-4 py-3 border text-right" rowSpan={item.indikator.length > 0 ? item.indikator.length : 1}>{x+1}</td>
                <td className="px-4 py-3 border" rowSpan={item.indikator.length > 0 ? item.indikator.length : 1}>{item.sasaran}</td>
                <td className="px-4 py-3 border">{item.indikator.length > 0 ? (`1. ${item.indikator[0].indikator}`) : "-"}</td>
                <td className="px-4 py-3 border text-right">
                    {
                        item.indikator.length > 0 ? (item.indikator[0].rkpd.length > 0 ?
                            (item.indikator[0].rkpd[0].target) : "-"
                        ) : "-"
                    }
                </td>
                <td className="px-4 py-3 border text-right" rowSpan={item.indikator.length > 0 ? item.indikator.length : 1}>{item.program[0]?.anggaran ? numberFormatter(item.program[0]?.anggaran) : 0}</td>
                <td className="px-4 py-3 border flex justify-center">
                    <button id={`btn-${x}`} data-dropdown-toggle={`toggle-btn${x}`}
                        className="inline-flex items-center p-0.5 text-sm font-medium text-center text-gray-500 hover:text-gray-800 rounded-lg focus:outline-none dark:text-gray-400 dark:hover:text-gray-100"
                        type="button">
                        <svg className="w-5 h-5" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM16 12a2 2 0 100-4 2 2 0 000 4z" />
                        </svg>
                    </button>
                    <div id={`toggle-btn${x}`}
                        className="hidden z-10 w-48 bg-gray-50 rounded divide-y divide-gray-100 drop-shadow-lg dark:bg-gray-700 dark:divide-gray-600">
                        <ul className="py-1 text-sm text-gray-700 dark:text-gray-200"
                            aria-labelledby={`btn-${x}`}>
                            {
                                item.indikator.length > 0 ? 
                                <li>
                                    <button onClick={() => inputTarget({
                                        sasaran_id: item.id,
                                        sasaran: item.sasaran,
                                        indikator_id: item.indikator[0].id,
                                        indikator: item.indikator[0].indikator,
                                        target: item.indikator[0]?.rkpd[0]?.target ?? ""
                                    })}
                                        className="flex gap-1 py-2 px-4 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">
                                        <PencilSquareIcon className='w-5 h-5' />
                                        Input Target
                                    </button>
                                </li> : null
                            }
                            <li>
                                <Link to={`/perencanaan/kdh/rkpd/${period}/${type}/program?id=${item.id}`}
                                    className="flex gap-1 py-2 px-4 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">
                                    <PencilSquareIcon className='w-5 h-5' />
                                    Input Program
                                </Link>
                            </li>
                        </ul>
                    </div>
                                                            
                </td>
            </tr>
            {
                item.indikator.length > 1 ? item.indikator.map((i, n) => (
                    n > 0 ? 
                    <tr key={n} className="border-b dark:border-gray-700 odd:bg-gray-100 dark:odd:bg-gray-900">
                        <td className="px-4 py-3 border">{`${n+1}. ${i.indikator}`}</td>
                        <td className="px-4 py-3 border text-right">
                            {
                                i.rkpd.length > 0 ? i.rkpd[0].target : "-"
                            }
                        </td>
                        <td className="px-4 py-3 border flex justify-center">
                            <button id={`btn-${i.id}`} data-dropdown-toggle={`toggle-btn${i.id}`}
                                className="inline-flex items-center p-0.5 text-sm font-medium text-center text-gray-500 hover:text-gray-800 rounded-lg focus:outline-none dark:text-gray-400 dark:hover:text-gray-100"
                                type="button">
                                <svg className="w-5 h-5" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM16 12a2 2 0 100-4 2 2 0 000 4z" />
                                </svg>
                            </button>
                            <div id={`toggle-btn${i.id}`}
                                className="hidden z-10 w-48 bg-gray-50 rounded divide-y divide-gray-100 drop-shadow-lg dark:bg-gray-700 dark:divide-gray-600">
                                <ul className="py-1 text-sm text-gray-700 dark:text-gray-200"
                                    aria-labelledby={`btn-${i.id}`}>
                                    <li>
                                        <button onClick={() => inputTarget({
                                                sasaran_id: item.id,
                                                sasaran: item.sasaran,
                                                indikator_id: i.id,
                                                indikator: i.indikator,
                                                target: i.rkpd[0]?.target ?? ""
                                            })}
                                            className="flex gap-1 py-2 px-4 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">
                                            <PencilSquareIcon className='w-5 h-5' />
                                            Input Target
                                        </button>
                                    </li>
                                    <li>
                                        <Link to={`/perencanaan/kdh/rkpd/${period}/${type}/program?id=${item.id}`}
                                            className="flex gap-1 py-2 px-4 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">
                                            <PencilSquareIcon className='w-5 h-5' />
                                            Input Program
                                        </Link>
                                    </li>
                                </ul>
                            </div>
                                                                    
                        </td>
                    </tr> : null
                )) : null
            }
            </>
        ))
        :
        <tr className="border-b dark:border-gray-700">
            <td className="px-4 py-3 border text-center" colSpan="100%">No Data</td>
        </tr>
    }
    const [formContent, setFormContent] = React.useState({
        sasaran_id: "",
        sasaran: "",
        indikator_id: "",
        indikator: "",
        target: 0
    })
    const [targetRkpd, setTargetRkpd] = React.useState(0)
    const inputTarget = (data) => {
        setTargetRkpd(data.target)
        setFormContent({
            sasaran_id: data.sasaran_id,
            sasaran: data.sasaran,
            indikator_id: data.indikator_id,
            indikator: data.indikator
        })
        setOpenModal(true)
    }

    const simpanData = async () => {
        let payload = {
            pohon_kinerja_sasaran_id: formContent.sasaran_id,
            pohon_kinerja_indikator_id: formContent.indikator_id,
            tahun: period,
            murni: type === 'murni',
            target: targetRkpd
        }
        let response = await dispatch(createRkpdKdh(payload))
        if(response.status !== "failed"){
            Swal.fire({
                icon: 'success',
                title: response.data.message,
                showConfirmButton: false,
                timer: 1500
            })
        
            setOpenModal(false)
            getDataTable()
        }
        else{
            Swal.fire({
                icon: 'error',
                title: "something went wrong",
                showConfirmButton: false,
                timer: 1500
            })
        
            setOpenModal(false)
        }
    }

    return (
        <Layout>
            <div className="bg-white h-auto dark:bg-gray-800 rounded-lg drop-shadow-xl py-1 px-3 w-full">
                <div className="dark:text-white">
                    <div className="flex flex-row items-center gap-3">
                        <div>
                            <img src={GoodNotes} alt="Perencanaan Kepala Daerah" className="object-contain" />
                        </div>
                        <div className="lg:text-lg font-bold text-teal-500 dark:text-white">Perencanaan RKPD KDH</div>
                    </div>
                </div>
            </div>
            <div className="bg-white h-auto dark:bg-gray-800 rounded-lg drop-shadow-xl py-1 sm:px-3 px-1 w-full flex flex-col lg:min-h-[35rem]">
                <div className="block w-full p-4">
                    <h1 className="text-center dark:text-white font-semibold text-lg">Rencana Kerja Pemerintah Daerah (RKPD) <br /> {period} <br/ >{type.toUpperCase()}</h1>
                    <div className="flex">
                        <PrimaryLinkBtn to={`/perencanaan/kdh/rkpd`}>
                            <ArrowLeftCircleIcon className='w-5 h-5' />
                            Kembali
                        </PrimaryLinkBtn>
                    </div>
                </div>
                <div className="block w-full p-4">
                    <StaticTable header={tableHeader()}>
                    {
                        !rkpdKdhState.loading ? renderTable() :
                        <tr className="border-b dark:border-gray-700">
                            <td className="px-4 py-3 border text-center" colSpan="100%">Loading...</td>
                        </tr>
                    }
                    </StaticTable>
                    <MyModal  ModalTitle={formTitle} openModal={openModal} setOpenModal={setOpenModal} >
                        <div className="flex flex-col w-full p-4">
                            <MyInput 
                                id="sasaran" 
                                name="sasaran" 
                                label="Sasaran" 
                                value={formContent.sasaran} 
                                disabled 
                            />
                            <MyInput 
                                id="indikator" 
                                name="indikator" 
                                label="Indikator" 
                                value={formContent.indikator} 
                                disabled 
                            />
                            <MyInput 
                                id="target" 
                                name="target" 
                                label={`Target`} 
                                placeholder='Input target...'
                                value={targetRkpd}
                                onChange={(e) => setTargetRkpd(e.target.value)}
                            />
                        </div>
                        
                        <div className="mt-5 sm:mt-6 flex justify-center">
                            <PrimaryBtn onClick={() => simpanData()} >
                                Simpan Data
                            </PrimaryBtn>
                        </div>
                    </MyModal>
                </div>
            </div>
        </Layout>
    )
}

export default RkpdForm