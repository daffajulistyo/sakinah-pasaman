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
import { Link } from "react-router-dom"

const Opd = () => {
    return (
        <Layout>
            <div className="bg-white h-auto dark:bg-gray-800 rounded-lg drop-shadow-xl py-1 px-3 w-full">
                <div className="dark:text-white">
                    <div className="flex flex-row items-center gap-3">
                        <div>
                            <img src={GoodNotes} alt="Perencanaan Kepala Daerah" className="object-contain" />
                        </div>
                        <div className="lg:text-lg font-bold text-teal-500 dark:text-white">Perencanaan Perangkat Daerah Provinsi Sumatera Barat</div>
                    </div>
                </div>
            </div>
            
            <div className="bg-white h-auto dark:bg-gray-800 rounded-lg drop-shadow-xl py-1 sm:px-3 px-1 w-full flex lg:min-h-[35rem] lg:items-center">
                <div className="w-full grid lg:grid-cols-5 md:grid-cols-3 grid-cols-2 lg:gap-6 md:gap-4 gap-2 sm:p-4 py-4 px-2">
                    <Link to={'/perencanaan/opd/pohonkinerja/tujuan'} 
                        className="lg:h-40 h-32 bg-teal-300 dark:bg-teal-700 hover:bg-teal-400 dark:hover:bg-teal-800 rounded-lg drop-shadow-lg hover:drop-shadow-none flex flex-col justify-center items-center p-2">
                        <img src={OakTree} alt="" />
                        <div className="text-teal-500 text-sm lg:text-xl md:text-lg sm:text-base dark:text-white font-bold text-center">Pohon Kinerja</div>
                    </Link>
                    <Link to={'/perencanaan/opd/cascading'} 
                        className="lg:h-40 h-32 bg-teal-300 dark:bg-teal-700 hover:bg-teal-400 dark:hover:bg-teal-800 rounded-lg drop-shadow-lg hover:drop-shadow-none flex flex-col justify-center items-center p-2">
                        <img src={Workflow} alt="" />
                        <div className="text-teal-500 text-sm lg:text-xl md:text-lg sm:text-base dark:text-white font-bold text-center">Cascading Kinerja</div>
                    </Link>
                    <Link to={'/perencanaan/opd/renstra'} 
                        className="lg:h-40 h-32 bg-teal-300 dark:bg-teal-700 hover:bg-teal-400 dark:hover:bg-teal-800 rounded-lg drop-shadow-lg hover:drop-shadow-none flex flex-col justify-center items-center p-2">
                        <img src={Reminders} alt="" />
                        <div className="text-teal-500 text-sm lg:text-xl md:text-lg sm:text-base dark:text-white font-bold text-center">Renstra</div>
                    </Link>
                    <Link to={'/perencanaan/opd/iku'} 
                        className="lg:h-40 h-32 bg-teal-300 dark:bg-teal-700 hover:bg-teal-400 dark:hover:bg-teal-800 rounded-lg drop-shadow-lg hover:drop-shadow-none flex flex-col justify-center items-center p-2">
                        <img src={TodoList} alt="" />
                        <div className="text-teal-500 text-sm lg:text-xl md:text-lg sm:text-base dark:text-white font-bold text-center">IKU</div>
                    </Link>
                    <Link to={'/perencanaan/opd/renja'} 
                        className="lg:h-40 h-32 bg-teal-300 dark:bg-teal-700 hover:bg-teal-400 dark:hover:bg-teal-800 rounded-lg drop-shadow-lg hover:drop-shadow-none flex flex-col justify-center items-center p-2">
                        <img src={Task} alt="" />
                        <div className="text-teal-500 text-sm lg:text-xl md:text-lg sm:text-base dark:text-white font-bold text-center">Renja</div>
                    </Link>
                    <Link to={'/perencanaan/opd/pk'} 
                        className="lg:h-40 h-32 bg-teal-300 dark:bg-teal-700 hover:bg-teal-400 dark:hover:bg-teal-800 rounded-lg drop-shadow-lg hover:drop-shadow-none flex flex-col justify-center items-center p-2">
                        <img src={OpenedFolder} alt="" />
                        <div className="text-teal-500 text-sm lg:text-xl md:text-lg sm:text-base dark:text-white font-bold text-center">Perjanjian Kinerja</div>
                    </Link>
                    <Link to={'/perencanaan/opd/ra'} 
                        className="lg:h-40 h-32 bg-teal-300 dark:bg-teal-700 hover:bg-teal-400 dark:hover:bg-teal-800 rounded-lg drop-shadow-lg hover:drop-shadow-none flex flex-col justify-center items-center p-2">
                        <img src={Notes} alt="" />
                        <div className="text-teal-500 text-sm lg:text-xl md:text-lg sm:text-base dark:text-white font-bold text-center">Rencana Aksi</div>
                    </Link>
                    
                </div>
            </div>
        </Layout>
    )
}

export default Opd