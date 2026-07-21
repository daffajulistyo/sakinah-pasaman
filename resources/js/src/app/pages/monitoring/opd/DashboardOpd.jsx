import Layout from '@/app/components/Layout/Layout'
import React from 'react'
import GoodNotes from "@assets/GoodNotes.png"
import OakTree from "@assets/Oak Tree.png"
import Workflow from "@assets/Workflow.png"
import Reminders from "@assets/Reminders.png"
import TodoList from "@assets/Todo List.png"
import Task from "@assets/Task.png"
import OpenedFolder from "@assets/OpenedFolder.png"
import Notes from "@assets/Notes.png"
import Diploma from "@assets/Diploma.png"
import Statistics from "@assets/Statistics.png"
import RescanDocument from "@assets/RescanDocument.png"
import { Link, useSearchParams } from "react-router-dom"
import MySelect2 from '@/app/components/Form/MySelect2'
import { useSelector, useDispatch } from 'react-redux'
import {getListDatamasterOpd} from '@/redux/ducks/datamasteropd/action'
import {setSelectedOpd as setSelectedOpdMonitoring, clearSelectedOpd as clearSelectedOpdMonitoring} from '@/redux/ducks/monitoring/action'
import PrimaryBtn from '@/app/components/Button/PrimaryBtn'

const DashboardOpd = () => {
    const dispatch = useDispatch()
    const datamasterOpdState = useSelector((state) => state.datamasterOpdState)
    const [selectedOpd, setSelectedOpd] = React.useState(null)
    const [searchParams, setSearchParams] = useSearchParams()
    const idopd = searchParams.get('idopd')
    React.useEffect(() => { 
        dispatch(getListDatamasterOpd({
            page: 1,
            per_page: 99999,
            search: ""
        })) 
    },[])
    const optionsOpd = () => (
        // console.log(datamasterOpdState.list)
        
        datamasterOpdState.list.length > 0 ?
        datamasterOpdState.list.map((item) => ({ label: item.nama_opd, value: item.id })) : []
    )
    React.useEffect(() => {
        if(selectedOpd) dispatch(setSelectedOpdMonitoring(selectedOpd))
        else dispatch(clearSelectedOpdMonitoring())
    },[selectedOpd])
    React.useEffect(() => {
        if(idopd) setSelectedOpd({label: datamasterOpdState.list.find(item => item.id === idopd)?.nama_opd, value: idopd})
    },[idopd, datamasterOpdState.list])

    const handleClearParam = (key) => {
        // Create a new URLSearchParams instance based on current params
        const newSearchParams = new URLSearchParams(searchParams);
        
        // Delete the specific key
        newSearchParams.delete(key);
        
        // Update the URL in the browser
        setSearchParams(newSearchParams);
    };

    const clearOpdParam = () => {
        handleClearParam('idopd')
        setSelectedOpd(null)
    }

    const onChangeSelectedOpd = (data) => {
        setSelectedOpd(data)
        setSearchParams({idopd: data.value})
    }
    return (
        <Layout>
            <div className="bg-white h-auto dark:bg-gray-800 rounded-lg drop-shadow-xl py-1 px-3 w-full">
                <div className="dark:text-white">
                    <div className="flex flex-row items-center gap-3">
                        <div>
                            <img src={GoodNotes} alt="Perencanaan Kepala Daerah" className="object-contain" />
                        </div>
                        <div className="lg:text-lg font-bold text-teal-500 dark:text-white">Monitoring Perangkat Daerah Provinsi Sumatera Barat</div>
                    </div>
                </div>
            </div>
            
            <div className="bg-gradient-to-tr from-green-50 dark:from-teal-900 to-teal-700 dark:to-green-500 h-auto dark:bg-gray-800 rounded-lg drop-shadow-xl py-1 sm:px-3 px-1 w-full flex flex-col lg:min-h-[35rem] lg:items-center">
                <div className={`w-full mx-auto p-4 ${selectedOpd ? 'hidden' : 'block'}`}>
                    <h2 className="text-lg text-center dark:text-white font-bold pb-2 text-white">Pilih Perangkat Daerah</h2>
                    <MySelect2
                        id="opd"
                        label=""
                        options={optionsOpd()}
                        value={selectedOpd}
                        onChange={onChangeSelectedOpd}
                    />
                </div>
                <div className={`w-full py-4 px-2 pt-16 md:pt-4 ${selectedOpd ? 'block' : 'hidden'}`}>

                    <h1 className="text-2xl text-center text-white font-bold">Dashboard OPD</h1>
                    <h2 className="text-lg text-center text-white font-bold">" {selectedOpd ? selectedOpd.label : 'Pilih OPD'} "</h2>
                    <div className="w-full top-4 right-4 flex justify-center">
                        <PrimaryBtn className='absolute top-4 left-4' onClick={() => clearOpdParam()}>
                            Pilih OPD
                        </PrimaryBtn>
                    </div>
                </div>
                <div className={`w-full ${selectedOpd ? 'grid' : 'hidden'} lg:grid-cols-5 md:grid-cols-3 grid-cols-2 lg:gap-6 md:gap-4 gap-2 sm:p-4 py-4 px-2`}>
                    <Link to={`/monitoring/opd/pohonkinerja/${selectedOpd?.value ?? 'null'}`} 
                        className="lg:h-40 h-32 bg-teal-300 dark:bg-teal-700 hover:bg-teal-400 dark:hover:bg-teal-800 rounded-lg drop-shadow-lg hover:drop-shadow-none flex flex-col justify-center items-center p-2">
                        <img src={OakTree} alt="" />
                        <div className="text-teal-500 text-sm lg:text-xl md:text-lg sm:text-base dark:text-white font-bold text-center">Pohon Kinerja</div>
                    </Link>
                    <Link to={`/monitoring/opd/cascading/${selectedOpd?.value ?? 'null'}`} 
                        className="lg:h-40 h-32 bg-teal-300 dark:bg-teal-700 hover:bg-teal-400 dark:hover:bg-teal-800 rounded-lg drop-shadow-lg hover:drop-shadow-none flex flex-col justify-center items-center p-2">
                        <img src={Workflow} alt="" />
                        <div className="text-teal-500 text-sm lg:text-xl md:text-lg sm:text-base dark:text-white font-bold text-center">Cascading Kinerja</div>
                    </Link>
                    <Link to={`/monitoring/opd/renstra/${selectedOpd?.value ?? 'null'}`} 
                        className="lg:h-40 h-32 bg-teal-300 dark:bg-teal-700 hover:bg-teal-400 dark:hover:bg-teal-800 rounded-lg drop-shadow-lg hover:drop-shadow-none flex flex-col justify-center items-center p-2">
                        <img src={Reminders} alt="" />
                        <div className="text-teal-500 text-sm lg:text-xl md:text-lg sm:text-base dark:text-white font-bold text-center">Renstra</div>
                    </Link>
                    <Link to={`/monitoring/opd/iku/${selectedOpd?.value ?? 'null'}`} 
                        className="lg:h-40 h-32 bg-teal-300 dark:bg-teal-700 hover:bg-teal-400 dark:hover:bg-teal-800 rounded-lg drop-shadow-lg hover:drop-shadow-none flex flex-col justify-center items-center p-2">
                        <img src={TodoList} alt="" />
                        <div className="text-teal-500 text-sm lg:text-xl md:text-lg sm:text-base dark:text-white font-bold text-center">IKU</div>
                    </Link>
                    <Link to={`/monitoring/opd/renja/${selectedOpd?.value ?? 'null'}`} 
                        className="lg:h-40 h-32 bg-teal-300 dark:bg-teal-700 hover:bg-teal-400 dark:hover:bg-teal-800 rounded-lg drop-shadow-lg hover:drop-shadow-none flex flex-col justify-center items-center p-2">
                        <img src={Task} alt="" />
                        <div className="text-teal-500 text-sm lg:text-xl md:text-lg sm:text-base dark:text-white font-bold text-center">Renja</div>
                    </Link>
                    <Link to={`/monitoring/opd/pk/${selectedOpd?.value ?? 'null'}`} 
                        className="lg:h-40 h-32 bg-teal-300 dark:bg-teal-700 hover:bg-teal-400 dark:hover:bg-teal-800 rounded-lg drop-shadow-lg hover:drop-shadow-none flex flex-col justify-center items-center p-2">
                        <img src={OpenedFolder} alt="" />
                        <div className="text-teal-500 text-sm lg:text-xl md:text-lg sm:text-base dark:text-white font-bold text-center">Perjanjian Kinerja</div>
                    </Link>
                    <Link to={`/monitoring/opd/ra/${selectedOpd?.value ?? 'null'}`} 
                        className="lg:h-40 h-32 bg-teal-300 dark:bg-teal-700 hover:bg-teal-400 dark:hover:bg-teal-800 rounded-lg drop-shadow-lg hover:drop-shadow-none flex flex-col justify-center items-center p-2">
                        <img src={Notes} alt="" />
                        <div className="text-teal-500 text-sm lg:text-xl md:text-lg sm:text-base dark:text-white font-bold text-center">Rencana Aksi</div>
                    </Link>
                    <Link to={`/monitoring/opd/realisasirenaksi/${selectedOpd?.value ?? 'null'}`} 
                        className="lg:h-40 h-32 bg-teal-300 dark:bg-teal-700 hover:bg-teal-400 dark:hover:bg-teal-800 rounded-lg drop-shadow-lg hover:drop-shadow-none flex flex-col justify-center items-center p-2">
                        <img src={RescanDocument} className="w-[62px] h-[55px]" alt="" />
                        <div className="text-teal-500 text-sm lg:text-xl md:text-lg sm:text-base dark:text-white font-bold text-center">Realisasi Renaksi</div>
                    </Link>
                    <Link to={`/monitoring/opd/datakinerja/${selectedOpd?.value ?? 'null'}`} 
                        className="lg:h-40 h-32 bg-teal-300 dark:bg-teal-700 hover:bg-teal-400 dark:hover:bg-teal-800 rounded-lg drop-shadow-lg hover:drop-shadow-none flex flex-col justify-center items-center p-2">
                        <img src={Diploma} className="w-[62px] h-[55px]" alt="" />
                        <div className="text-teal-500 text-sm lg:text-xl md:text-lg sm:text-base dark:text-white font-bold text-center">Laporan Data Kinerja</div>
                    </Link>
                    <Link to={`/monitoring/opd/capaiankinerja/${selectedOpd?.value ?? 'null'}`} 
                        className="lg:h-40 h-32 bg-teal-300 dark:bg-teal-700 hover:bg-teal-400 dark:hover:bg-teal-800 rounded-lg drop-shadow-lg hover:drop-shadow-none flex flex-col justify-center items-center p-2">
                        <img src={Statistics} className="w-[62px] h-[55px]" alt="" />
                        <div className="text-teal-500 text-sm lg:text-xl md:text-lg sm:text-base dark:text-white font-bold text-center">Laporan Capaian Kinerja</div>
                    </Link>
                    
                </div>
            </div>
        </Layout>
    )
}

export default DashboardOpd