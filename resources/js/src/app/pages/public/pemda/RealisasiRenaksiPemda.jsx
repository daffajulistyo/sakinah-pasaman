import React from 'react'
import PublicLayout from '@/app/components/PublicLayout'
import { useDispatch, useSelector } from 'react-redux'
import { getPublicVisiPemda, getPublicRealisasiRenaksiPemda } from '@/redux/ducks/public/action'
import { StaticTable } from '@/app/components/Table'
import { RupiahFormatter } from '@/helper/common'

const RealisasiRenaksiPemda = () => {const dispatch = useDispatch()
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
        <>
            <tr>
                <th scope="col" className="px-4 py-3 border w-[3%]" rowSpan="2">No.</th>
                <th scope="col" className="px-4 py-3 border text-center" rowSpan="2">Tujuan/Sasaran</th>
                <th scope="col" className="px-4 py-3 border text-center" rowSpan="2">Indikator Kinerja</th>
                <th scope="col" className="px-4 py-3 border text-center" colSpan="3">TW 1</th>
                <th scope="col" className="px-4 py-3 border text-center" colSpan="3">TW 2</th>
                <th scope="col" className="px-4 py-3 border text-center" colSpan="3">TW 3</th>
                <th scope="col" className="px-4 py-3 border text-center" colSpan="3">TW 4</th>
                <th scope="col" className="px-4 py-3 border text-center w-1/6" rowSpan="2">Langkah-langkah pencapaian target</th>
                <th scope="col" className="px-4 py-3 border text-center w-[10%]" rowSpan="2">Anggaran</th>
                <th scope="col" className="px-4 py-3 border text-center w-[10%]" rowSpan="2">Hambatan</th>
                <th scope="col" className="px-4 py-3 border text-center w-[10%]" rowSpan="2">Tindak Lanjut</th>
            </tr>
            <tr>
                <th scope="col" className="px-4 py-3 border text-center w-[2%]">T</th>
                <th scope="col" className="px-4 py-3 border text-center w-[2%]">R</th>
                <th scope="col" className="px-4 py-3 border text-center w-[2%]">C</th>
                <th scope="col" className="px-4 py-3 border text-center w-[2%]">T</th>
                <th scope="col" className="px-4 py-3 border text-center w-[2%]">R</th>
                <th scope="col" className="px-4 py-3 border text-center w-[2%]">C</th>
                <th scope="col" className="px-4 py-3 border text-center w-[2%]">T</th>
                <th scope="col" className="px-4 py-3 border text-center w-[2%]">R</th>
                <th scope="col" className="px-4 py-3 border text-center w-[2%]">C</th>
                <th scope="col" className="px-4 py-3 border text-center w-[2%]">T</th>
                <th scope="col" className="px-4 py-3 border text-center w-[2%]">R</th>
                <th scope="col" className="px-4 py-3 border text-center w-[2%]">C</th>
            </tr>
        </>
    )

    React.useEffect(() => {
        if(selectedYear !== ''){
            dispatch(getPublicRealisasiRenaksiPemda({ tahun: selectedYear, murni:true }))
        }
    },[selectedYear])
    const renderTable = () => {
        return publicDataState.data_realisasirenaksi_pemda.length > 0 ?
        publicDataState.data_realisasirenaksi_pemda.map((item, key) => (
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
                <td className="px-4 py-3 border text-right">{item.indikator[0]?.rencana_aksi?.target_tw1 ?? ""}</td>
                <td className="px-4 py-3 border text-right">{item.indikator[0]?.rencana_aksi?.realisasi_tw1 ?? ""}</td>
                <td className="px-4 py-3 border text-right">{item.indikator[0]?.rencana_aksi?.capaian_tw1 ?? ""}</td>
                <td className="px-4 py-3 border text-right">{item.indikator[0]?.rencana_aksi?.target_tw2 ?? ""}</td>
                <td className="px-4 py-3 border text-right">{item.indikator[0]?.rencana_aksi?.realisasi_tw2 ?? ""}</td>
                <td className="px-4 py-3 border text-right">{item.indikator[0]?.rencana_aksi?.capaian_tw2 ?? ""}</td>
                <td className="px-4 py-3 border text-right">{item.indikator[0]?.rencana_aksi?.target_tw3 ?? ""}</td>
                <td className="px-4 py-3 border text-right">{item.indikator[0]?.rencana_aksi?.realisasi_tw3 ?? ""}</td>
                <td className="px-4 py-3 border text-right">{item.indikator[0]?.rencana_aksi?.capaian_tw3 ?? ""}</td>
                <td className="px-4 py-3 border text-right">{item.indikator[0]?.rencana_aksi?.target_tw4 ?? ""}</td>
                <td className="px-4 py-3 border text-right">{item.indikator[0]?.rencana_aksi?.realisasi_tw4 ?? ""}</td>
                <td className="px-4 py-3 border text-right">{item.indikator[0]?.rencana_aksi?.capaian_tw4 ?? ""}</td>
                <td className="px-4 py-3 border">
                    {
                        item.indikator[0]?.langkah?.length > 0 ? 
                        <ul className='list-disc px-5'>
                            {
                                item.indikator[0].langkah.map((i) => (
                                    <li>{i.langkah}</li>
                                ))
                            }
                        </ul> : null
                    }
                </td>
                <td className="px-4 py-3 border text-right" rowSpan={item.indikator.length > 0 ? item.indikator.length : 1}>{
                    item.anggaran_perjanjian_kinerja.perubahan > 0 ? RupiahFormatter(item.anggaran_perjanjian_kinerja.perubahan) :
                    RupiahFormatter(item.anggaran_perjanjian_kinerja.murni)
                }</td>
                <td className="px-4 py-3 border text-right">{item.indikator[0]?.rencana_aksi?.hambatan ?? ""}</td>
                <td className="px-4 py-3 border text-right">{item.indikator[0]?.rencana_aksi?.tindak_lanjut ?? ""}</td>
            </tr>
            {
                item.indikator.length > 1 ? 
                    item.indikator.map((val,x) => {
                        if(x > 0){
                            return (
                                <tr key={x} className="border-b dark:border-gray-700">
                                    <td className="px-4 py-3 border">{val.indikator}</td>
                                    <td className="px-4 py-3 border text-right">{val.rencana_aksi?.target_tw1 ?? ""}</td>
                                    <td className="px-4 py-3 border text-right">{val.rencana_aksi?.realisasi_tw1 ?? ""}</td>
                                    <td className="px-4 py-3 border text-right">{val.rencana_aksi?.capaian_tw1 ?? ""}</td>
                                    <td className="px-4 py-3 border text-right">{val.rencana_aksi?.target_tw2 ?? ""}</td>
                                    <td className="px-4 py-3 border text-right">{val.rencana_aksi?.realisasi_tw2 ?? ""}</td>
                                    <td className="px-4 py-3 border text-right">{val.rencana_aksi?.capaian_tw2 ?? ""}</td>
                                    <td className="px-4 py-3 border text-right">{val.rencana_aksi?.target_tw3 ?? ""}</td>
                                    <td className="px-4 py-3 border text-right">{val.rencana_aksi?.realisasi_tw3 ?? ""}</td>
                                    <td className="px-4 py-3 border text-right">{val.rencana_aksi?.capaian_tw3 ?? ""}</td>
                                    <td className="px-4 py-3 border text-right">{val.rencana_aksi?.target_tw4 ?? ""}</td>
                                    <td className="px-4 py-3 border text-right">{val.rencana_aksi?.realisasi_tw4 ?? ""}</td>
                                    <td className="px-4 py-3 border text-right">{val.rencana_aksi?.capaian_tw4 ?? ""}</td>
                                    <td className="px-4 py-3 border">
                                    {
                                        val.langkah?.length > 0 ? 
                                        <ul className='list-disc px-5'>
                                            {
                                                val.langkah.map((i) => (
                                                    <li>{i.langkah}</li>
                                                ))
                                            }
                                        </ul> : null
                                    }
                                    </td>
                                    <td className="px-4 py-3 border text-right">{item.rencana_aksi?.hambatan ?? ""}</td>
                                    <td className="px-4 py-3 border text-right">{item.rencana_aksi?.tindak_lanjut ?? ""}</td>
                                </tr>
                            )
                        }
                    })
                : null
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
                    <h1 className="font-bold md:text-2xl sm:text-xl text-lg text-primaryWebColor">REALISASI RENCANA AKSI PEMERINTAH PROVINSI SUMATERA BARAT</h1>
                </div>
            </div>
            <div className="w-full max-w-screen-2xl min-h-screen bg-white mx-auto border p-4 rounded-lg">
                <h1 className="text-xl font-bold text-center mb-3">REALISASI RENCANA AKSI GUBERNUR {selectedYear !== '' ? ('TAHUN '+selectedYear) : ''}</h1>
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
                        (publicDataState.data_realisasirenaksi_pemda === null || selectedYear === '') ? 
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

export default RealisasiRenaksiPemda