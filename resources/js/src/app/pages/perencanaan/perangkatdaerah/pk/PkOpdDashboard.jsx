import React from 'react'
import Layout from '@/app/components/Layout/Layout'
import GoodNotes from "@assets/GoodNotes.png"
import { Link } from 'react-router-dom'
import Bookmarks from '@/assets/Bookmarks.png'
import Bookmarked from '@/assets/Bookmarked.png'

const PkOpdDashboard = () => {
    const [selectedYear, setSelectedYear] = React.useState(new Date().getFullYear());

    const handleYearChange = (event) => {
        setSelectedYear(event.target.value);
    };
    return (
        <Layout>
            <div className="bg-white h-auto dark:bg-gray-800 rounded-lg drop-shadow-xl py-1 px-3 w-full">
                <div className="dark:text-white">
                    <div className="flex flex-row items-center gap-3">
                        <div>
                            <img src={GoodNotes} alt="Perencanaan Perangkat Daerah" className="object-contain" />
                        </div>
                        <div className="lg:text-lg font-bold text-teal-500 dark:text-white">Perencanaan Perjanjian Kinerja Perangkat Daerah</div>
                    </div>
                </div>
            </div>
            
            <div className="bg-white h-auto dark:bg-gray-800 rounded-lg drop-shadow-xl py-1 sm:px-3 px-1 w-full flex lg:min-h-[35rem] lg:items-center flex-col">
                <div className="w-full flex px-6">
                    <div className="w-full md:w-1/4 sm:w-1/3 py-5">
                        <label htmlFor="" className="py-2 font-semibold dark:text-white">Tahun</label>
                        <select 
                            value={selectedYear} 
                            onChange={handleYearChange} 
                            className="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        >
                            {[...Array(10)].map((_, index) => {
                                const year = new Date().getFullYear() - index;
                                return <option key={year} value={year}>{year}</option>;
                            })}
                        </select>
                    </div>
                </div>
                <div className="w-full grid lg:grid-cols-5 md:grid-cols-3 grid-cols-2 lg:gap-6 md:gap-4 gap-2 sm:p-4 py-4 px-2">
                    <Link to={`/perencanaan/opd/pk/${selectedYear}/murni`} 
                        className="lg:h-40 h-32 bg-teal-300 dark:bg-teal-700 hover:bg-teal-400 dark:hover:bg-teal-800 rounded-lg drop-shadow-lg hover:drop-shadow-none flex flex-col justify-center items-center p-2">
                        <img src={Bookmarks} alt="" />
                        <div className="text-teal-500 text-sm lg:text-xl md:text-lg sm:text-base dark:text-white font-bold text-center">PK Murni</div>
                    </Link>
                    <Link to={`/perencanaan/opd/pk/${selectedYear}/perubahan`} 
                        className="lg:h-40 h-32 bg-teal-300 dark:bg-teal-700 hover:bg-teal-400 dark:hover:bg-teal-800 rounded-lg drop-shadow-lg hover:drop-shadow-none flex flex-col justify-center items-center p-2">
                        <img src={Bookmarked} alt="" />
                        <div className="text-teal-500 text-sm lg:text-xl md:text-lg sm:text-base dark:text-white font-bold text-center">PK Perubahan</div>
                    </Link>
                </div>
            </div>
        </Layout>
    )
}

export default PkOpdDashboard