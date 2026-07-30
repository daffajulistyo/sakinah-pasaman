import React from "react"
import Layout from "@components/Layout/Layout"
import GoodNotes from "@assets/GoodNotes.png"
import OakTree from "@assets/Oak Tree.png"
import Workflow from "@assets/Workflow.png"
import Reminders from "@assets/Reminders.png"
import TodoList from "@assets/Todo List.png"
import Task from "@assets/Task.png"
import OpenedFolder from "@assets/OpenedFolder.png"
import Notes from "@assets/Notes.png"
import { Link } from "react-router-dom"

const Kdh = () => {
    return (
        <Layout>
            <div className="bg-white h-auto dark:bg-gray-800 rounded-lg drop-shadow-xl py-1 px-3 w-full">
                <div className="dark:text-white">
                    <div className="flex flex-row items-center gap-3">
                        <div>
                            <img src={GoodNotes} alt="Perencanaan Kepala Daerah" className="object-contain" />
                        </div>
                        <div className="lg:text-lg font-bold text-teal-500 dark:text-white">Perencanaan Kepala Daerah Kabupaten Pasaman</div>
                    </div>
                </div>
            </div>
            <div className="bg-white h-auto dark:bg-gray-800 rounded-lg drop-shadow-xl py-1 sm:px-3 px-1 w-full flex lg:min-h-[35rem] lg:items-center">
                <div className="w-full grid lg:grid-cols-5 md:grid-cols-3 grid-cols-2 lg:gap-6 md:gap-4 gap-2 sm:p-4 py-4 px-2">
                    <Link to={'/perencanaan/kdh/pohonkinerja'} 
                        className="lg:h-40 h-32 bg-teal-300 dark:bg-teal-700 hover:bg-teal-400 dark:hover:bg-teal-800 rounded-lg drop-shadow-lg hover:drop-shadow-none flex flex-col justify-center items-center p-2">
                        <img src={OakTree} alt="" />
                        <div className="text-teal-500 text-sm lg:text-xl md:text-lg sm:text-base dark:text-white font-bold text-center">Pohon Kinerja</div>
                    </Link>
                    <Link to={'/perencanaan/kdh/cascading'} 
                        className="lg:h-40 h-32 bg-teal-300 dark:bg-teal-700 hover:bg-teal-400 dark:hover:bg-teal-800 rounded-lg drop-shadow-lg hover:drop-shadow-none flex flex-col justify-center items-center p-2">
                        <img src={Workflow} alt="" />
                        <div className="text-teal-500 text-sm lg:text-xl md:text-lg sm:text-base dark:text-white font-bold text-center">Cascading Kinerja</div>
                    </Link>
                    <Link to={'/perencanaan/kdh/rpjmd'} 
                        className="lg:h-40 h-32 bg-teal-300 dark:bg-teal-700 hover:bg-teal-400 dark:hover:bg-teal-800 rounded-lg drop-shadow-lg hover:drop-shadow-none flex flex-col justify-center items-center p-2">
                        <img src={Reminders} alt="" />
                        <div className="text-teal-500 text-sm lg:text-xl md:text-lg sm:text-base dark:text-white font-bold text-center">RPJMD</div>
                    </Link>
                    <Link to={'/perencanaan/kdh/iku'} 
                        className="lg:h-40 h-32 bg-teal-300 dark:bg-teal-700 hover:bg-teal-400 dark:hover:bg-teal-800 rounded-lg drop-shadow-lg hover:drop-shadow-none flex flex-col justify-center items-center p-2">
                        <img src={TodoList} alt="" />
                        <div className="text-teal-500 text-sm lg:text-xl md:text-lg sm:text-base dark:text-white font-bold text-center">IKU</div>
                    </Link>
                    <Link to={'/perencanaan/kdh/rkpd'} 
                        className="lg:h-40 h-32 bg-teal-300 dark:bg-teal-700 hover:bg-teal-400 dark:hover:bg-teal-800 rounded-lg drop-shadow-lg hover:drop-shadow-none flex flex-col justify-center items-center p-2">
                        <img src={Task} alt="" />
                        <div className="text-teal-500 text-sm lg:text-xl md:text-lg sm:text-base dark:text-white font-bold text-center">RKPD</div>
                    </Link>
                    <Link to={'/perencanaan/kdh/pk'} 
                        className="lg:h-40 h-32 bg-teal-300 dark:bg-teal-700 hover:bg-teal-400 dark:hover:bg-teal-800 rounded-lg drop-shadow-lg hover:drop-shadow-none flex flex-col justify-center items-center p-2">
                        <img src={OpenedFolder} alt="" />
                        <div className="text-teal-500 text-sm lg:text-xl md:text-lg sm:text-base dark:text-white font-bold text-center">Perjanjian Kinerja</div>
                    </Link>
                    <Link to={'/perencanaan/kdh/ra'} 
                        className="lg:h-40 h-32 bg-teal-300 dark:bg-teal-700 hover:bg-teal-400 dark:hover:bg-teal-800 rounded-lg drop-shadow-lg hover:drop-shadow-none flex flex-col justify-center items-center p-2">
                        <img src={Notes} alt="" />
                        <div className="text-teal-500 text-sm lg:text-xl md:text-lg sm:text-base dark:text-white font-bold text-center">Rencana Aksi</div>
                    </Link>
                    
                </div>
            </div>
        </Layout>
    )
}

export default Kdh