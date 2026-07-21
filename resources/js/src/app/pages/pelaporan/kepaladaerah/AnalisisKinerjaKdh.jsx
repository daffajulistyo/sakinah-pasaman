import React from 'react'
import { StaticTable } from '@/app/components/Table'
import Layout from '@/app/components/Layout/Layout'
import GoodNotes from "@assets/GoodNotes.png"
import PrimaryBtn from '@/app/components/Button/PrimaryBtn'
import { getListRealisasiRenaksiKdh } from '@/redux/ducks/realisasirenaksikdh/action'
import { useDispatch, useSelector } from 'react-redux'

const AnalisisKinerjaKdh = () => {
    const dispatch = useDispatch()
    const realisasiRenaksiKdhState = useSelector((state) => state.realisasiRenaksiKdhState)
    const [selectedYear, setSelectedYear] = React.useState(new Date().getFullYear());
    const handleYearChange = (event) => {
        setSelectedYear(event.target.value);
        getDataTable(event.target.value)
    };
    const getDataTable = (year = "") => {
        dispatch(getListRealisasiRenaksiKdh({ tahun: year != "" ? year : selectedYear }))        
    }
    React.useEffect(() => {
        getDataTable(selectedYear)
    },[])
    const tableHeader = () => (
        <>
        <tr>
            <th scope="col" className="px-4 py-3 border w-[3%]" rowSpan="2">No.</th>
            <th scope="col" className="px-4 py-3 border text-center" rowSpan="2">Tujuan/Sasaran</th>
            <th scope="col" className="px-4 py-3 border text-center" rowSpan="2">Indikator</th>
            <th scope="col" className="px-4 py-3 border text-center" colSpan="2">Aksi yang dilakukan</th>
            <th scope="col" className="px-4 py-3 border text-center" rowSpan="2">Hambatan yang dihadapi</th>
            <th scope="col" className="px-4 py-3 border text-center" rowSpan="2">Upaya di masa yang akan datang</th>
        </tr>
        <tr>
            <th scope="col" className="px-4 py-3 border text-center">Aksi</th>
            <th scope="col" className="px-4 py-3 border text-center">Realisasi</th>
        </tr>
        </>
    )
    return (
        <Layout loading={realisasiRenaksiKdhState.loading}>
            <div className="bg-white h-auto dark:bg-gray-800 rounded-lg drop-shadow-xl py-1 px-3 w-full">
                <div className="dark:text-white">
                    <div className="flex flex-row items-center gap-3">
                        <div>
                            <img src={GoodNotes} alt="Perencanaan Kepala Daerah" className="object-contain" />
                        </div>
                        <div className="lg:text-lg font-bold text-teal-500 dark:text-white">Pelaporan Kepala Daerah</div>
                    </div>
                </div>
            </div>
            <div className="bg-white h-auto dark:bg-gray-800 rounded-lg drop-shadow-xl py-1 sm:px-3 px-1 w-full flex flex-col lg:min-h-[35rem]">
                <div className="block w-full p-4">
                    <div className="text-center font-bold">
                        ANALISIS KINERJA <br />
                        BERISI UPAYA DAN HAMBATAN DAN UPAYA DI MASA YANG AKAN DATANG
                    </div>
                </div>
                <div className="w-full flex sm:justify-between px-6">
                    <div className="w-full md:w-1/4 sm:w-1/3 py-5">
                        <label htmlFor="" className="py-2 font-semibold dark:text-white">Tahun N</label>
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
                    
                    <div className="w-full flex justify-end items-end md:w-1/4 sm:w-1/3 py-5">
                        <PrimaryBtn>Export</PrimaryBtn>
                    </div>
                </div>
                <div className="block w-full p-4">
                    <StaticTable header={tableHeader()}>
                    {
                        realisasiRenaksiKdhState.loading ?
                        <tr className="border-b dark:border-gray-700">
                            <td className='px-4 py-3 border text-center' colSpan="100%">
                                Loading...
                            </td>
                        </tr> :
                        realisasiRenaksiKdhState.list.map((item, key) => (
                            <>
                            <tr key={key} className="border-b dark:border-gray-700">
                                <td 
                                    className="px-4 py-3 border text-right"
                                    rowSpan={item.indikator.length > 0 ? item.indikator.length : 1}
                                >
                                    {key+1}
                                </td>
                                <td 
                                    className="px-4 py-3 border"
                                    rowSpan={item.indikator.length > 0 ? item.indikator.length : 1}
                                >
                                    {item.sasaran}
                                </td>
                                <td className="px-4 py-3 border">{item.indikator[0]?.indikator ?? "(belum ada data)"}</td>
                                <td className="px-4 py-3 border">(belum ada data)</td>
                                <td className="px-4 py-3 border">(belum ada data)</td>
                                <td className="px-4 py-3 border">{item.indikator[0]?.rencana_aksi?.hambatan ?? '(No Data)'}</td>
                                <td className="px-4 py-3 border">{item.indikator[0]?.rencana_aksi?.tindak_lanjut ?? '(No Data)'}</td>
                            </tr>
                            {
                                item.indikator.length > 1 ? 
                                    item.indikator.map((val,x) => {
                                        if(x > 0){
                                            return (
                                                <tr key={x} className="border-b dark:border-gray-700">
                                                    <td className="px-4 py-3 border">{val.indikator}</td>
                                                    <td className="px-4 py-3 border">(belum ada data)</td>
                                                    <td className="px-4 py-3 border">(belum ada data)</td>
                                                    <td className="px-4 py-3 border">
                                                        {val.rencana_aksi?.hambatan ?? '(No Data)'}
                                                    </td>
                                                    <td className="px-4 py-3 border">
                                                        {val.rencana_aksi?.tindak_lanjut ?? '(No Data)'}
                                                    </td>
                                                </tr>
                                            )
                                        }
                                    })
                                : null
                            }
                            </>
                        ))
                    }
                    </StaticTable>
                </div>
            </div>
        </Layout>
    )
}

export default AnalisisKinerjaKdh