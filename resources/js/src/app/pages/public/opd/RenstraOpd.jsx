import React from 'react'
import PublicLayout from '@/app/components/PublicLayout'
import { useDispatch, useSelector } from 'react-redux'
import { getPublicRenstraOpd, getPublicVisiPemda, getPublicDaftarOpd } from '@/redux/ducks/public/action'
import MySelect2 from '@/app/components/Form/MySelect2'
import { StaticTable } from '@/app/components/Table'

const RenstraOpd = () => {
    const [tahunLabel, setTahunLabel] = React.useState('')
    const [selectedOpd, setSelectedOpd] = React.useState(null)
    const [opdOptions, setOpdOptions] = React.useState([])
    const [listTahun, setListTahun] = React.useState([])
    const dispatch = useDispatch()
    const publicDataState = useSelector((state) => state.publicDataState)
    React.useEffect(() => { publicDataState.data_visi_pemda === null ? dispatch(getPublicVisiPemda()) : null }, [publicDataState.data_visi_pemda])
    React.useEffect(() => { publicDataState.daftar_opd === null ? dispatch(getPublicDaftarOpd()) : null }, [publicDataState.daftar_opd])

    React.useEffect(() => {
        if(publicDataState.data_visi_pemda !== null)
        {
            let starts = publicDataState.data_visi_pemda?.period_starts ?? ""
            let ends = publicDataState.data_visi_pemda?.period_ends ?? ""
            if(starts !== "" && ends !== ""){
                setTahunLabel('PERIODE '+starts+' - '+ends)
                let listTahun = []
                for(let n=(starts+1); n<=ends; n++){
                    listTahun.push(n)
                }
                setListTahun(listTahun)
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
        if(selectedOpd !== null)
        {
            let payload = {
                master_opd_id: selectedOpd?.value ?? "0"
            }
            dispatch(getPublicRenstraOpd(payload))
        }
    },[selectedOpd])
    const tableHeader = () => (
        <>
            <tr>
                <th scope="col" className="px-4 py-3 border w-[3%]" rowSpan="2">No.</th>
                <th scope="col" className="px-4 py-3 border text-center" rowSpan="2">Sasaran</th>
                <th scope="col" className="px-4 py-3 border text-center" rowSpan="2">Indikator</th>
                <th scope="col" className="px-4 py-3 border text-center" rowSpan="2">Satuan</th>
                <th scope="col" className="px-4 py-3 border text-center" rowSpan="2">Baseline</th>
                <th scope="col" className="px-4 py-3 border text-center" colSpan={listTahun.length ?? 1}>Target</th>
            </tr>
            <tr>
                {
                    listTahun.map((item, x) => (
                        <th scope="col" className="px-4 py-3 border text-center" key={x}>{item}</th>
                    ))
                }
            </tr>
        </>
    )
    const dataRenstra = () => {
        let data = []
        if(publicDataState.data_renstra_opd !== null){
            publicDataState.data_renstra_opd.map((item) => {
                //check if list sasaran exist
                if(item.sasaran.length > 0){
                    item.sasaran.map((s) => {
                        
                        // check if list indikator
                        if(s.indikator_sasaran.length > 0){
                            s.indikator_sasaran.map((i) => {
                                data.push({
                                    id: i.id,
                                    sasaran: s.sasaran,
                                    indikator: i.indikator,
                                    satuan: i.satuan,
                                    baseline: i.baseline,
                                    target_1: i.target_1,
                                    target_2: i.target_2,
                                    target_3: i.target_3,
                                    target_4: i.target_4,
                                    target_5: i.target_5,
                                })
                            })
                        }
                    })
                }
            })
        }
        return data
    }

    const renderTable = () => {
        return dataRenstra().length > 0 ? dataRenstra().map((item, x) => (
            <tr key={x} className="border-b dark:border-gray-700">
                <td className="px-4 py-3 border text-right">{x+1}</td>
                <td className="px-4 py-3 border">{item.sasaran}</td>
                <td className="px-4 py-3 border">{item.indikator}</td>
                <td className="px-4 py-3 border">{item.satuan}</td>
                <td className="px-4 py-3 border text-right">{item.baseline}</td>
                <td className="px-4 py-3 border text-right">{item.target_1}</td>
                <td className="px-4 py-3 border text-right">{item.target_2}</td>
                <td className="px-4 py-3 border text-right">{item.target_3}</td>
                <td className="px-4 py-3 border text-right">{item.target_4}</td>
                <td className="px-4 py-3 border text-right">{item.target_5}</td>
            </tr>
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
                    <h1 className="font-bold md:text-2xl sm:text-xl text-lg text-primaryWebColor">RENSTRA PERANGKAT DAERAH</h1>
                </div>
            </div>
            <div className="w-full max-w-screen-2xl min-h-screen bg-white mx-auto border p-4 rounded-lg">
                <div className="w-full flex justify-end py-2">
                    <div className="w-full sm:w-1/2 md:py-5 py-2">
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
                </div>
                <h1 className="text-xl font-bold text-center mb-3"> 
                    RENCANA STRATEGIS 
                    {selectedOpd?.label ? <><br />{selectedOpd.label}</> : null}
                    <br /> {tahunLabel} 
                </h1>
                <div className="w-full">
                    <StaticTable header={tableHeader()}>
                    {
                        (publicDataState.data_renstra_opd === null || selectedOpd === null) ? 
                        <tr className="border-b dark:border-gray-700">
                            <td className="px-4 py-3 border text-center" colSpan="100%">Pilih perangkat daerah terlebih dahulu</td>
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

export default RenstraOpd