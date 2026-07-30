import React from 'react'
import PublicLayout from '@/app/components/PublicLayout'
import { useDispatch, useSelector } from 'react-redux'
import { getPublicVisiPemda, getPublicPkPemda } from '@/redux/ducks/public/action'
import { StaticTable } from '@/app/components/Table'

const PerjanjianKinerjaPemda = () => {
    const dispatch = useDispatch()
    const publicDataState = useSelector((state) => state.publicDataState)
    React.useEffect(() => { publicDataState.data_visi_pemda === null ? dispatch(getPublicVisiPemda()) : null }, [publicDataState.data_visi_pemda])
    const [selectedYear, setSelectedYear] = React.useState('')
    const [yearOptions, setYearOptions] = React.useState([])
    React.useEffect(() => {
        if(publicDataState.data_visi_pemda !== null){
            let starts = publicDataState.data_visi_pemda?.period_starts ?? ""
            let ends = publicDataState.data_visi_pemda?.period_ends ?? ""
            if(starts !== "" && ends !== "" && ends > starts)
            {
                let yearlist = []
                for(let n=starts; n<=ends; n++)
                {
                    yearlist.push(n)
                }
                setYearOptions(yearlist)
            }
        }
    },[publicDataState.data_visi_pemda])
    const tableHeader = () => (
        <tr>
            <th scope="col" className="px-4 py-3 border w-[3%]">No.</th>
            <th scope="col" className="px-4 py-3 border text-center">Sasaran</th>
            <th scope="col" className="px-4 py-3 border w-1/2 text-center">Indikator</th>
            <th scope="col" className="px-4 py-3 border text-center w-[5%]">Target</th>
        </tr>
    )
    React.useEffect(() => {
        if(selectedYear !== ''){
            dispatch(getPublicPkPemda({ tahun: selectedYear, murni:true }))
        }
    },[selectedYear])

    const renderTable = () => {
        return publicDataState.data_pk_pemda.length > 0 ? publicDataState.data_pk_pemda.map((item, x) => (
            <>
            <tr key={x} className="border-b dark:border-gray-700 odd:bg-gray-100 dark:odd:bg-gray-900">
                <td className="px-4 py-3 border text-right" rowSpan={item.indikator.length > 0 ? item.indikator.length : 1}>{x+1}</td>
                <td className="px-4 py-3 border" rowSpan={item.indikator.length > 0 ? item.indikator.length : 1}>{item.sasaran}</td>
                <td className="px-4 py-3 border">{item.indikator.length > 0 ? (`1. ${item.indikator[0].indikator}`) : "-"}</td>
                <td className="px-4 py-3 border text-right">
                    {
                        item.indikator.length > 0 ? 
                            (item.indikator[0].perjanjian_kinerja?.target ?? "-") : "-"
                    }
                </td>
                
            </tr>
            {
                item.indikator.length > 1 ? item.indikator.map((i, n) => (
                    n > 0 ? 
                    <tr key={n} className="border-b dark:border-gray-700 odd:bg-gray-100 dark:odd:bg-gray-900">
                        <td className="px-4 py-3 border">{`${n+1}. ${i.indikator}`}</td>
                        <td className="px-4 py-3 border text-right">
                            {
                                i.perjanjian_kinerja?.target ?? "-"
                            }
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

    return (
        
        <PublicLayout loading={publicDataState.loading}>
            <div className="w-full px-4 md:py-6 py-2">
                <div className="w-full mx-auto max-w-screen-2xl">
                    <h1 className="font-bold md:text-2xl sm:text-xl text-lg text-primaryWebColor">PERJANJIAN KINERJA PEMERINTAH KABUPATEN PASAMAN</h1>
                </div>
            </div>
            <div className="w-full max-w-screen-2xl min-h-screen bg-white mx-auto border p-4 rounded-lg">
                <h1 className="text-xl font-bold text-center mb-3"> PERJANJIAN KINERJA BUPATI {selectedYear !== '' ? ('TAHUN '+selectedYear) : ''}</h1>
                <div className="w-full flex justify-end py-2">
                    <div className="w-full md:w-1/4 sm:w-1/3 md:py-5 py-2">
                        <label htmlFor="" className="py-2 font-semibold dark:text-white">Tahun</label>
                        <select 
                            value={selectedYear} 
                            onChange={(e) => setSelectedYear(e.target.value)}
                            className="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        >
                            <option value="">Pilih Tahun</option>
                            {
                                yearOptions.length > 0 ?
                                yearOptions.map((item) => (<option key={item} value={item}>{item}</option>))
                                : null
                            }
                        </select>
                    </div>
                </div>

                <div className="w-full">
                    <StaticTable header={tableHeader()}>
                    {
                        (publicDataState.data_pk_pemda === null || selectedYear === '') ? 
                        <tr className="border-b dark:border-gray-700">
                            <td className="px-4 py-3 border text-center" colSpan="100%">Pilih tahun terlebih dahulu</td>
                        </tr>
                        :
                        !publicDataState.loading ? renderTable() :
                        <tr className="border-b dark:border-gray-700">
                            <td className="px-4 py-3 border text-center" colSpan="100%">Loading...</td>
                        </tr>
                    }
                    </StaticTable>
                </div>
            </div>
        </PublicLayout>
    )
}

export default PerjanjianKinerjaPemda