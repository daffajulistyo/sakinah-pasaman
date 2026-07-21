import React from 'react'
import PublicLayout from '@/app/components/PublicLayout'
import { useDispatch, useSelector } from 'react-redux'
import { getPublicVisiPemda, getPublicDaftarOpd, getPublicRenaksiOpd } from '@/redux/ducks/public/action'
import MySelect2 from '@/app/components/Form/MySelect2'
import { StaticTable } from '@/app/components/Table'
import { RupiahFormatter } from '@/helper/common'
import IconBtn from '@/app/components/Button/IconBtn'
import { MagnifyingGlassIcon } from '@heroicons/react/24/outline'
import MyModal from '@/app/components/Form/MyModal'
import PrimaryBtn from '@/app/components/Button/PrimaryBtn'

const RencanaAksiOpd = () => {
    const [selectedOpd, setSelectedOpd] = React.useState(null)
    const [opdOptions, setOpdOptions] = React.useState([])
    const [selectedYear, setSelectedYear] = React.useState('')
    const [yearOptions, setYearOptions] = React.useState([])
    const [openModal, setOpenModal] = React.useState(false)
    const [viewLangkah, setViewLangkah] = React.useState([])
    const [selectedIndikator, setSelectedIndikator] = React.useState('')
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
            dispatch(getPublicRenaksiOpd(payload))
        }
    },[selectedOpd, selectedYear])

    const tableHeader = () => (
        <>
            <tr>
                <th scope="col" className="px-4 py-3 border w-[3%]" rowSpan="2">No.</th>
                <th scope="col" className="px-4 py-3 border text-center" rowSpan="2">Tujuan/Sasaran</th>
                <th scope="col" className="px-4 py-3 border text-center" rowSpan="2">Indikator Kinerja</th>
                <th scope="col" className="px-4 py-3 border text-center" colSpan="4">Target Kinerja per Triwulan</th>
                <th scope="col" className="px-4 py-3 border text-center w-1/6" rowSpan="2">Langkah-langkah pencapaian target</th>
                <th scope="col" className="px-4 py-3 border text-center w-[10%]" rowSpan="2">Anggaran</th>
                <th scope="col" className="px-4 py-3 border text-center" rowSpan="2">&nbsp;</th>
            </tr>
            <tr>
                <th scope="col" className="px-4 py-3 border text-center w-[2%]">I</th>
                <th scope="col" className="px-4 py-3 border text-center w-[2%]">II</th>
                <th scope="col" className="px-4 py-3 border text-center w-[2%]">III</th>
                <th scope="col" className="px-4 py-3 border text-center w-[2%]">VI</th>
            </tr>
        </>
    )
    const renderTable = () => {
        return publicDataState.data_renaksi_opd.length > 0 ?
        publicDataState.data_renaksi_opd.map((item, key) => (
            <>
            <tr key={key} className="border-b dark:border-gray-700">
                <td 
                    className="px-4 py-3 border text-right align-top nowrap"
                    rowSpan={item.indikator_sasaran.length > 0 ? item.indikator_sasaran.length : 1}
                >
                    {key+1}
                </td>
                <td 
                    className="px-4 py-3 border align-top"
                    rowSpan={item.indikator_sasaran.length > 0 ? item.indikator_sasaran.length : 1}
                >
                    {item.sasaran}
                </td>
                <td className="px-4 py-3 border align-top">{item.indikator_sasaran[0]?.indikator ?? "(belum ada data)"}</td>
                <td className="px-4 py-3 border text-right align-top">{item.indikator_sasaran[0]?.rencana_aksi?.target_tw1 ?? ""}</td>
                <td className="px-4 py-3 border text-right align-top">{item.indikator_sasaran[0]?.rencana_aksi?.target_tw2 ?? ""}</td>
                <td className="px-4 py-3 border text-right align-top">{item.indikator_sasaran[0]?.rencana_aksi?.target_tw3 ?? ""}</td>
                <td className="px-4 py-3 border text-right align-top">{item.indikator_sasaran[0]?.rencana_aksi?.target_tw4 ?? ""}</td>
                <td className="px-4 py-3 border align-top">
                    {
                        item.indikator_sasaran[0]?.langkah?.length > 0 ? 
                        <ul className='list-disc px-5'>
                            {
                                item.indikator_sasaran[0].langkah.map((i) => (
                                    <li>{i.langkah}</li>
                                ))
                            }
                        </ul> : null
                    }
                </td>
                <td className="px-4 py-3 border text-right align-top" rowSpan={item.indikator_sasaran.length > 0 ? item.indikator_sasaran.length : 1}>
                    {
                        item.anggaran_perjanjian_kinerja.perubahan > 0 ? RupiahFormatter(item.anggaran_perjanjian_kinerja.perubahan) :
                        RupiahFormatter(item.anggaran_perjanjian_kinerja.murni)
                    }
                </td>
                <td className="px-4 py-3 border text-center align-top h-full">
                    <IconBtn onClick={() => openRenaksi({ indikator: item.indikator_sasaran[0]?.indikator ?? "(belum ada data)", langkah: item.indikator_sasaran[0].langkah })}>
                        <MagnifyingGlassIcon className="w-4 h-4" />
                    </IconBtn>
                </td>
            </tr>
            {
                item.indikator_sasaran.length > 1 ? 
                    item.indikator_sasaran.map((val,x) => {
                        if(x > 0){
                            return (
                                <tr key={x} className="border-b dark:border-gray-700">
                                    <td className="px-4 py-3 border align-top">{val.indikator}</td>
                                    <td className="px-4 py-3 border text-right align-top">{val.rencana_aksi?.target_tw1 ?? ""}</td>
                                    <td className="px-4 py-3 border text-right align-top">{val.rencana_aksi?.target_tw2 ?? ""}</td>
                                    <td className="px-4 py-3 border text-right align-top">{val.rencana_aksi?.target_tw3 ?? ""}</td>
                                    <td className="px-4 py-3 border text-right align-top">{val.rencana_aksi?.target_tw4 ?? ""}</td>
                                    <td className="px-4 py-3 border align-top">
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
                                    <td className="px-4 py-3 border text-center align-top h-full">
                                        <IconBtn onClick={() => openRenaksi({ indikator: val.indikator, langkah: val.langkah })}>
                                            <MagnifyingGlassIcon className="w-4 h-4" />
                                        </IconBtn>
                                    </td>
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
    const langkahTableHeader = () => (
        <>
            <tr>
                <th scope="col" className="px-4 py-3 border w-[3%]" rowSpan="2">No.</th>
                <th scope="col" className="px-4 py-3 border text-center" rowSpan="2">Rencana Aksi /Langkah-langkah</th>
                <th scope="col" className="px-4 py-3 border text-center" colSpan="4">Target</th>
            </tr>
            <tr>
                <th scope="col" className="px-4 py-3 border text-center">TW I</th>
                <th scope="col" className="px-4 py-3 border text-center">TW II</th>
                <th scope="col" className="px-4 py-3 border text-center">TW III</th>
                <th scope="col" className="px-4 py-3 border text-center">TW IV</th>
            </tr>
        </>
    )
    const openRenaksi = (data) => {
        setViewLangkah(data.langkah)
        setSelectedIndikator(data.indikator)
        setOpenModal(true)
    }
    return (
        
        <PublicLayout loading={publicDataState.loading}>
            <div className="w-full px-4 md:py-6 py-2">
                <div className="w-full mx-auto max-w-screen-2xl">
                    <h1 className="font-bold md:text-2xl sm:text-xl text-lg text-primaryWebColor">RENCANA AKSI PERANGKAT DAERAH</h1>
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
                    RENCANA AKSI
                    {selectedOpd?.label ? <><br />{selectedOpd.label}</> : null}
                    <br /> {selectedYear !== '' ? ('TAHUN '+selectedYear) : ''} 
                </h1>

                <div className="w-full">
                    <StaticTable header={tableHeader()}>
                    {
                        (publicDataState.data_renaksi_opd === null || selectedOpd === null || selectedYear === '') ? 
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
            <MyModal ModalTitle={"Rencana Aksi"} openModal={openModal} setOpenModal={setOpenModal} >
                <div className="flex flex-col w-full p-4">
                    <div className="w-full py-2 ">
                        <h1 className="text-center font-semibold italic">Indikator : " { selectedIndikator } "</h1>
                    </div>
                    <StaticTable header={langkahTableHeader()}>
                        {
                            viewLangkah.length > 0 ?
                            viewLangkah.map((item, x) => (
                                <tr key={x} className="border-b dark:border-gray-700">
                                    <td className="px-4 py-3 border align-right">{x+1}</td>
                                    <td className="px-4 py-3 border align-top">{item.langkah}</td>
                                    <td className="px-4 py-3 border text-right align-top">{item.target_tw1 ?? ""}</td>
                                    <td className="px-4 py-3 border text-right align-top">{item.target_tw2 ?? ""}</td>
                                    <td className="px-4 py-3 border text-right align-top">{item.target_tw3 ?? ""}</td>
                                    <td className="px-4 py-3 border text-right align-top">{item.target_tw4 ?? ""}</td>
                                </tr>
                            ))
                            :
                            <tr className="border-b dark:border-gray-700">
                                <td className="px-4 py-3 border text-center" colSpan="100%">No Data</td>
                            </tr>
                        }
                    </StaticTable>
                </div>
                
                <div className="mt-5 sm:mt-6 flex justify-center">
                    <PrimaryBtn onClick={()=> setOpenModal(false)} loading={false} >
                        Tutup
                    </PrimaryBtn>
                </div>
            </MyModal>
        </PublicLayout>
    )
}

export default RencanaAksiOpd