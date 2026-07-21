import Layout from '@/app/components/Layout/Layout'
import React from 'react'
import GoodNotes from "@assets/GoodNotes.png"
import { StaticTable } from '@/app/components/Table'
import PrimaryBtn from '@/app/components/Button/PrimaryBtn'
import { PencilSquareIcon } from '@heroicons/react/24/outline'
import { Link } from 'react-router-dom'
import { getListRpjmdKdh } from '@/redux/ducks/rpjmdkdh/action'
import { useSelector, useDispatch } from 'react-redux'

const IndikatorKinerjaUtama = () => {
    const dispatch = useDispatch()
    const rpjmdKdhState = useSelector((state) => state.rpjmdKdhState)
    React.useEffect(() => {
        dispatch(getListRpjmdKdh())
    },[])
    const dataIndikator = () => {
        let data = []
        if(rpjmdKdhState.data?.misi){
            // rpjmdKdhState.data.map((item) => {
                // check if list misi exist
                if(rpjmdKdhState.data.misi.length > 0){
                    rpjmdKdhState.data.misi.map((m) => {

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
                                                    satuan: i.satuan?.satuan ?? "-",
                                                    rilis: i.rilis ?? "-",
                                                    sumber_data: i.sumber_data ?? "-",
                                   
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

    const tableHeader = () => (
        <tr>
            <th scope="col" className="px-4 py-3 border w-[3%]">No.</th>
            <th scope="col" className="px-4 py-3 border">Indikator</th>
            <th scope="col" className="px-4 py-3 border">Baseline</th>
            <th scope="col" className="px-4 py-3 border">Rilis</th>
            <th scope="col" className="px-4 py-3 border">Sumber Data</th>
            <th scope="col" className="px-4 py-3 border w-[5%]">
                <span className="sr-only">Actions</span>
            </th>
        </tr>
    )
    return (
        <Layout>
            <div className="bg-white h-auto dark:bg-gray-800 rounded-lg drop-shadow-xl py-1 px-3 w-full">
                <div className="dark:text-white">
                    <div className="flex flex-row items-center gap-3">
                        <div>
                            <img src={GoodNotes} alt="Perencanaan Kepala Daerah" className="object-contain" />
                        </div>
                        <div className="lg:text-lg font-bold text-teal-500 dark:text-white">Perencanaan IKU KDH</div>
                    </div>
                </div>
            </div>
            <div className="bg-white h-auto dark:bg-gray-800 rounded-lg drop-shadow-xl py-1 sm:px-3 px-1 w-full flex flex-col lg:min-h-[35rem]">
                <div className="block w-full p-4">
                    <h1 className="text-center font-semibold text-lg dark:text-white">Indikator Kinerja Utama</h1>
                </div>
                <div className="block w-full p-4">
                    <StaticTable header={tableHeader()}>
                    {
                        !rpjmdKdhState.loading ?
                        dataIndikator().map((item, x) => (
                            <tr key={x} className="border-b dark:border-gray-700 odd:bg-gray-100 dark:odd:bg-gray-900">
                                <td className="px-4 py-3 border text-right">{x+1}</td>
                                <td className="px-4 py-3 border">{item.indikator}</td>
                                <td className="px-4 py-3 border">{item.baseline}</td>
                                <td className="px-4 py-3 border">{item.rilis}</td>
                                <td className="px-4 py-3 border">{item.sumber_data}</td>
                                <td className="px-4 py-3 border flex justify-center">
                                    <Link to={`/perencanaan/kdh/iku/${item.id}`}>
                                        <PencilSquareIcon className='w-5 h-5' />
                                    </Link>                                        
                                </td>
                            </tr>
                        )) : 
                        <tr className="border-b dark:border-gray-700">
                            <td className="px-4 py-3 border text-center" colSpan="100%">Loading...</td>
                        </tr>
                    }
                    </StaticTable>
                </div>
            </div>
        </Layout>
    )
}

export default IndikatorKinerjaUtama