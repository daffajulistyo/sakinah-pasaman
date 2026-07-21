import React from 'react'
import PublicLayout from '@/app/components/PublicLayout'
import { useDispatch, useSelector } from 'react-redux'
import { getPublicVisiPemda, getPublicDaftarOpd, getPublicPkOpd } from '@/redux/ducks/public/action'
import MySelect2 from '@/app/components/Form/MySelect2'
import { StaticTable } from '@/app/components/Table'

const PerjanjianKinerjaOpd = () => {
    const [selectedOpd, setSelectedOpd] = React.useState(null)
    const [opdOptions, setOpdOptions] = React.useState([])
    const [selectedYear, setSelectedYear] = React.useState('')
    const [yearOptions, setYearOptions] = React.useState([])
    const dispatch = useDispatch()
    const publicDataState = useSelector((state) => state.publicDataState)
    React.useEffect(() => { publicDataState.data_visi_pemda === null ? dispatch(getPublicVisiPemda()) : null }, [publicDataState.data_visi_pemda])
    React.useEffect(() => { publicDataState.daftar_opd === null ? dispatch(getPublicDaftarOpd()) : null }, [publicDataState.daftar_opd])

    
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
    const onChangeSelectedOpd = (item) => { 
        setSelectedOpd(item)
    }
    React.useEffect(() => {
        if(publicDataState.daftar_opd !== null){
            if(publicDataState.daftar_opd.length > 0){
                let list = []
                publicDataState.daftar_opd.map((item, key) => {
                    list.push({ value: item.id, label: item.nama_opd })
                })
                setOpdOptions(list)
            }
        }
    },[publicDataState.daftar_opd])

    React.useEffect(() => {
        if(selectedOpd !== null && selectedYear !== '')
        {
            let payload = {
                tahun: selectedYear,
                murni: true,
                master_opd_id: selectedOpd.value
            }
            dispatch(getPublicPkOpd(payload))
        }
    },[selectedOpd, selectedYear])

    const tableHeader = () => (
        <tr>
            <th scope="col" className="px-4 py-3 border w-[3%]">No.</th>
            <th scope="col" className="px-4 py-3 border text-center">Sasaran</th>
            <th scope="col" className="px-4 py-3 border text-center">Indikator</th>
            <th scope="col" className="px-4 py-3 border text-center w-[5%]">Target <br /> N</th>
        </tr>
    )
    const renderTable = () => {
        return publicDataState.data_pk_opd.length > 0 ? publicDataState.data_pk_opd.map((item, x) => (
            <>
            <tr key={x} className="border-b dark:border-gray-700">
                <td className="px-4 py-3 border text-right" rowSpan={item.indikator_sasaran.length > 0 ? item.indikator_sasaran.length : 1}>{x+1}</td>
                <td className="px-4 py-3 border" rowSpan={item.indikator_sasaran.length > 0 ? item.indikator_sasaran.length : 1}>{item.sasaran}</td>
                <td className="px-4 py-3 border">{item.indikator_sasaran.length > 0 ? (`1. ${item.indikator_sasaran[0].indikator}`) : "-"}</td>
                <td className="px-4 py-3 border text-right">
                    {
                        item.indikator_sasaran.length > 0 ? (item.indikator_sasaran[0].perjanjian_kinerja?.target ?? "-") : "-"
                    }
                </td>
            </tr>
            {
                item.indikator_sasaran.length > 1 ? item.indikator_sasaran.map((i, n) => (
                    n > 0 ? 
                    <tr key={n} className="border-b dark:border-gray-700">
                        <td className="px-4 py-3 border">{`${n+1}. ${i.indikator}`}</td>
                        <td className="px-4 py-3 border text-right">
                            {
                                i.perjanjian_kinerja ? i.perjanjian_kinerja.target : "-"
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
                    <h1 className="font-bold md:text-2xl sm:text-xl text-lg text-primaryWebColor">PERJANJIAN KINERJA PERANGKAT DAERAH</h1>
                </div>
            </div>
            <div className="w-full max-w-screen-2xl min-h-screen bg-white mx-auto border p-4 rounded-lg">
                <div className="w-full flex justify-between py-2 gap-2">
                    <div className="w-full sm:w-1/2 py-2">
                        {/* <label htmlFor="" className="py-2 font-semibold dark:text-white">Perangkat Daerah</label> */}
                        <MySelect2
                            id="opd"
                            label=""
                            options={opdOptions}
                            value={selectedOpd}
                            onChange={onChangeSelectedOpd}
                            placeholder="Pilih Perangkat Daerah"
                        />
                    </div>
                    <div className="w-full md:w-1/4 sm:w-1/3 py-2">
                        {/* <label htmlFor="" className="py-2 font-semibold dark:text-white">Tahun</label> */}
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
                <h1 className="text-xl font-bold text-center mb-3"> 
                    PERJANJIAN KINERJA 
                    {selectedOpd?.label ? <><br />{selectedOpd.label}</> : null}
                    <br /> {selectedYear !== '' ? ('TAHUN '+selectedYear) : ''} 
                </h1>
                <div className="w-full">
                    <StaticTable header={tableHeader()}>
                    {
                        (publicDataState.data_pk_opd === null || selectedOpd === null || selectedYear === '') ? 
                        <tr className="border-b dark:border-gray-700">
                            <td className="px-4 py-3 border text-center" colSpan="100%">Pilih perangkat daerah dan tahun terlebih dahulu</td>
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

export default PerjanjianKinerjaOpd