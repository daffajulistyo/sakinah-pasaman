import React from 'react'
import GoodNotes from "@assets/GoodNotes.png"
import OakTree from "@assets/Oak Tree.png"
import { Link } from 'react-router-dom'

const HeaderPohonKinerja = () => {
    
    
    return (
        <div className="bg-white h-auto dark:bg-gray-800 rounded-lg drop-shadow-xl py-1 px-3 w-full">
            <div className="dark:text-white flex w-full justify-between">
                <div className="flex flex-row items-center gap-3">
                    <div>
                        <img src={GoodNotes} alt="Perencanaan Kepala Daerah" className="object-contain" />
                    </div>
                    <div className="lg:text-lg font-bold text-teal-500 dark:text-white">Pohon Kinerja KDH</div>
                </div>
                <div className="flex p-2">
                    <Link to={'/perencanaan/kdh/pohonkinerja/view'} className="flex flex-row items-center gap-3 bg-lime-600 dark:bg-lime-800 sm:px-5 px-1.5 sm:p-0.5 p-1 rounded-lg hover:bg-lime-700 dark:hover:bg-lime-900">
                        <div>
                            <img src={OakTree} alt="Perencanaan Kepala Daerah" className="object-contain sm:h-10 h-5 " />
                        </div>
                        <div className="text-sm sm:block hidden font-bold text-white">Lihat Pohon Kinerja</div>
                    </Link>
                </div>

            </div>
        </div>
    )
}

export default HeaderPohonKinerja