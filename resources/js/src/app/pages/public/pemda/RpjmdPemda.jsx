import React from 'react'
import PublicLayout from '@/app/components/PublicLayout'
import { useDispatch, useSelector } from 'react-redux'
import { getPublicVisiPemda, getPublicRpjmdPemda } from '@/redux/ducks/public/action'
import { StaticTable } from '@/app/components/Table'

const RpjmdPemda = () => {
    const dispatch = useDispatch()
    const publicDataState = useSelector((state) => state.publicDataState)
    const [listTahun, setListTahun] = React.useState([])
    React.useEffect(() => { publicDataState.data_visi_pemda === null ? dispatch(getPublicVisiPemda()) : null }, [publicDataState.data_visi_pemda])
    const [tahunLabel, setTahunLabel] = React.useState('')
    React.useEffect(() => {
        if(publicDataState.data_visi_pemda !== null){
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

    React.useEffect(() => {
        publicDataState.data_rpjmd_pemda === null ?
        dispatch(getPublicRpjmdPemda()) : null
    },[])

    const dataRpjmd = () => {
        let data = []
        
        if(publicDataState.data_rpjmd_pemda?.misi){
            // rpjmdKdhState.data.map((item) => {
                // check if list misi exist
                if(publicDataState.data_rpjmd_pemda.misi.length > 0){
                    publicDataState.data_rpjmd_pemda.misi.map((m) => {

                        // check if list tujuan exist
                        if(m.tujuan.length > 0){
                            m.tujuan.map((t) => {

                                //check if list sasaran exist
                                if(t.sasaran.length > 0){
                                    t.sasaran.map((s) => {
                                        
                                        // check if list indikator
                                        if(s.indikator_sasaran.length > 0){
                                            s.indikator_sasaran.map((i) => {
                                                data.push({
                                                    id: i.id,
                                                    sasaran: s.sasaran,
                                                    indikator: i.indikator,
                                                    baseline: i.baseline,
                                                    target_1: i.target_1,
                                                    target_2: i.target_2,
                                                    target_3: i.target_3,
                                                    target_4: i.target_4,
                                                    target_5: i.target_5,
                                                    satuan: i.satuan ?? "-"
                                                })
                                            })
                                        }
                                    })
                                }
                            })
                        }
                    })
                }
            // })
        }
        
        return data
    }
    const renderTable = () => {
        return dataRpjmd().map((item, x) => (
            <tr key={x} className="border-b dark:border-gray-700 odd:bg-gray-100 dark:odd:bg-gray-900">
                <td className="px-4 py-3 border text-right">{x+1}</td>
                <td className="px-4 py-3 border">{item.sasaran}</td>
                <td className="px-4 py-3 border">{item.indikator}</td>
                <td className="px-4 py-3 border text-center">{item.satuan?.satuan ?? "-"}</td>
                <td className="px-4 py-3 border text-right">{item.baseline}</td>
                <td className="px-4 py-3 border text-right">{item.target_1}</td>
                <td className="px-4 py-3 border text-right">{item.target_2}</td>
                <td className="px-4 py-3 border text-right">{item.target_3}</td>
                <td className="px-4 py-3 border text-right">{item.target_4}</td>
                <td className="px-4 py-3 border text-right">{item.target_5}</td>
            </tr>
        ))
    }
    return (
        <PublicLayout loading={publicDataState.loading}>
            <div className="w-full px-4 md:py-6 py-2">
                <div className="w-full mx-auto max-w-screen-2xl">
                    <h1 className="font-bold md:text-2xl sm:text-xl text-lg text-primaryWebColor">RPJMD PEMERINTAH PROVINSI SUMATERA BARAT</h1>
                </div>
            </div>
            <div className="w-full max-w-screen-2xl min-h-screen bg-white mx-auto border p-4 rounded-lg">
                <h1 className="text-xl font-bold text-center mb-3"> RENCANA PEMBANGUNAN JANGKA MENENGAH <br /> {tahunLabel}</h1>
                

                <div className="w-full">
                    <StaticTable header={tableHeader()}>
                    {
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

export default RpjmdPemda