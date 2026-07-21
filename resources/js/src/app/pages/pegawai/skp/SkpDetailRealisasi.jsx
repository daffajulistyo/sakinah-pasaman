import React from 'react'
import Layout from '@/app/components/Layout/Layout'
import GoodNotes from "@assets/GoodNotes.png"
import { useSelector, useDispatch } from 'react-redux'
import { getListPeriodeSkp } from '@/redux/ducks/skp/action'
import { indonesianDate } from '@/app/helper/Common'
import StaticTable from '@/app/components/Table/StaticTable'
import { getListSkpRealisasi, updateSkpRealisasi } from '@/redux/ducks/skp/action'
import { PacmanLoader } from 'react-spinners'
import { PencilSquareIcon } from '@heroicons/react/24/outline'
import { initFlowbite } from 'flowbite'
import MyModal from '@/app/components/Form/MyModal'
import MyInput from '@/app/components/Form/MyInput'
import MyTextarea from '@/app/components/Form/MyTextarea'
import PrimaryBtn from '@/app/components/Button/PrimaryBtn'
import Swal from 'sweetalert2'
import { useNavigate } from 'react-router-dom'

const SkpDetailRealisasi = () => {
    const [openModal, setOpenModal] = React.useState(false)
    const [formTitle, setFormTitle] = React.useState("FORM REALISASI SKP")
    const [selectedPeriod, setSelectedPeriod] = React.useState("");
    const dispatch = useDispatch()
    const skpState = useSelector((state) => state.skpState)
    const navigate = useNavigate()
    const [initFormData, setInitFormData] = React.useState({
        id: "",
        sasaran_atasan: "",
        sasaran_skp: "",
        indikator_skp: "",
        satuan: "",
        targettw1: 0,
        targettw2: 0,
        targettw3: 0,
        targettw4: 0,
        realisasitw1: 0,
        realisasitw2: 0,
        realisasitw3: 0,
        realisasitw4: 0,
        capaiantw1: 0,
        capaiantw2: 0,
        capaiantw3: 0,
        capaiantw4: 0,
        capaian: 0,
        hambatan: "",
        tindak_lanjut: ""
    })

    let loading = skpState.loading

    React.useEffect(() => {
        dispatch(getListPeriodeSkp())
    },[])

    React.useEffect(() => {
        initFlowbite()
    },[skpState.list_skp_realisasi?.sasaran_atasan])
    
    const listPeriode = () => {
        let list = skpState.list_periode.length > 0 ? skpState.list_periode.map((item, key) => (
                <option key={item.id} value={item.id}>{indonesianDate(item.periode_awal)} - {indonesianDate(item.periode_akhir)}</option>
            )) : []
        list.unshift(<option value="">Pilih Periode</option>)
        return list
    }

    const handlePeriodChange = (event) => {
        setSelectedPeriod(event.target.value);
        let reponse = event.target.value !== "" ? dispatch(getListSkpRealisasi(event.target.value)) : null
    };

    const getDataTable = () => {
        let response = dispatch(getListSkpRealisasi(selectedPeriod))
    }

    const tableHeader = () => (
        <>
            <tr>
                <th rowSpan="2" scope="col" className="px-4 py-3 border w-[3%]">No.</th>
                <th rowSpan="2" scope="col" className="px-4 py-3 border text-center">Sasaran Kerja Pimpinan Yang Diintervensi</th>
                <th rowSpan="2" scope="col" className="px-4 py-3 border text-center">Sasaran Kerja Pegawai</th>
                <th rowSpan="2" scope="col" className="px-4 py-3 border text-center">Indikator Kinerja Individu</th>
                <th rowSpan="2" scope="col" className="px-4 py-3 border text-center">Satuan</th>
                <th colSpan="3" scope="col" className="px-4 py-3 border text-center">TW I</th>
                <th colSpan="3" scope="col" className="px-4 py-3 border text-center">TW II</th>
                <th colSpan="3" scope="col" className="px-4 py-3 border text-center">TW III</th>
                <th colSpan="3" scope="col" className="px-4 py-3 border text-center">TW IV</th>
                <th rowSpan="2" scope="col" className="px-4 py-3 border text-center w-1/6">Hambatan</th>
                <th rowSpan="2" scope="col" className="px-4 py-3 border text-center w-1/6">Tindak Lanjut</th>
                <th rowSpan="2" scope="col" className="px-4 py-3 border w-[5%]">
                    <span className="sr-only">Actions</span>
                </th>
            </tr>
            <tr>
                <th scope="col" className="px-4 py-3 border w-[5%]">T</th>
                <th scope="col" className="px-4 py-3 border w-[5%]">R</th>
                <th scope="col" className="px-4 py-3 border w-[5%]">C</th>
                <th scope="col" className="px-4 py-3 border w-[5%]">T</th>
                <th scope="col" className="px-4 py-3 border w-[5%]">R</th>
                <th scope="col" className="px-4 py-3 border w-[5%]">C</th>
                <th scope="col" className="px-4 py-3 border w-[5%]">T</th>
                <th scope="col" className="px-4 py-3 border w-[5%]">R</th>
                <th scope="col" className="px-4 py-3 border w-[5%]">C</th>
                <th scope="col" className="px-4 py-3 border w-[5%]">T</th>
                <th scope="col" className="px-4 py-3 border w-[5%]">R</th>
                <th scope="col" className="px-4 py-3 border w-[5%]">C</th>
            </tr>
        </>
    )
    const dataTable = () => {
        let data = skpState.list_skp_realisasi?.sasaran_atasan ?? []
        let raw = data.length > 0 ? data.map((item,a1) => {
            let rowspan_atasan = 0
            let sasaran_pegawai = item.sasaran_pegawai.length > 0 ? item.sasaran_pegawai.map((item2,a2) => {
                rowspan_atasan = rowspan_atasan + item2.indikator.length
                return {
                    ...item2,
                    rowspan: item2.indikator.length
                }
            }) : []
            return {
                ...item,
                rowspan: rowspan_atasan,
                sasaran_pegawai: sasaran_pegawai
            }

        }) : []
        console.log(raw);
        
        return raw
    }
    const renderTable = () => {
        const data = dataTable();
        let no = 1;
        return (
            loading ? 
                    <tr className="border-b dark:border-gray-700">
                        <td scope="row" className="px-4 py-5 bg-gray-100 dark:bg-gray-800 dark:text-white" colSpan="100%">
                            <div className="flex flex-row justify-center w-full gap-12">
                                <PacmanLoader size={10} color='gray' />
                                Please Wait...
                            </div>
                        </td>
                    </tr> : 
                    (data.length > 0 && selectedPeriod !== "") ? data.map((item) => {
                        return item.sasaran_pegawai?.map((item1, key1) => {
                            return item1.indikator?.map((item2, key2) => {
                                return (
                                    <tr key={item2.id} className="border-b align-top">
                
                                        {/* LEVEL 1 (ITEM) */}
                                        {key1 === 0 && key2 === 0 && (
                                            <>
                                                <td rowSpan={item.rowspan} className="border px-4 py-3">
                                                    {no++}
                                                </td>
                
                                                {/* kalau ada nama parent */}
                                                <td rowSpan={item.rowspan} className="border px-4 py-3">
                                                    {item.sasaran ?? '-'}
                                                </td>
                                            </>
                                        )}
                
                                        {/* LEVEL 2 (SASARAN) */}
                                        {key2 === 0 && (
                                            <td rowSpan={item1.rowspan} className="border px-4 py-3">
                                                {item1.sasaran}
                                            </td>
                                        )}
                
                                        {/* LEVEL 3 (INDIKATOR) */}
                                        <td className="border px-4 py-3">{item2.indikator}</td>
                                        <td className="border px-4 py-3">{item2.satuan}</td>
                                        <td className="border px-4 py-3">{item2.target_tw1}</td>
                                        <td className="border px-4 py-3">{item2.realisasi_tw1}</td>
                                        <td className="border px-4 py-3">{item2.capaian_tw1}</td>
                                        <td className="border px-4 py-3">{item2.target_tw2}</td>
                                        <td className="border px-4 py-3">{item2.realisasi_tw2}</td>
                                        <td className="border px-4 py-3">{item2.capaian_tw2}</td>
                                        <td className="border px-4 py-3">{item2.target_tw3}</td>
                                        <td className="border px-4 py-3">{item2.realisasi_tw3}</td>
                                        <td className="border px-4 py-3">{item2.capaian_tw3}</td>
                                        <td className="border px-4 py-3">{item2.target_tw4}</td>
                                        <td className="border px-4 py-3">{item2.realisasi_tw4}</td>
                                        <td className="border px-4 py-3">{item2.capaian_tw4}</td>
                                        <td className="border px-4 py-3">{item2.hambatan}</td>
                                        <td className="border px-4 py-3">{item2.tindak_lanjut}</td>
                
                                        {/* AKSI */}
                                        <td className="border px-4 py-3 text-center">
                                            <button id={`btn-${item2.id}`} data-dropdown-toggle={`toggle-btn${item2.id}`}
                                                className="inline-flex items-center p-0.5 text-sm h-full font-medium text-center text-gray-500 hover:text-gray-800 rounded-lg focus:outline-none dark:text-gray-400 dark:hover:text-gray-100"
                                                type="button">
                                                <svg className="w-5 h-5" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <path
                                                        d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM16 12a2 2 0 100-4 2 2 0 000 4z" />
                                                </svg>
                                            </button>
                                            <div id={`toggle-btn${item2.id}`}
                                                className="hidden z-10 w-44 bg-gray-50 rounded divide-y divide-gray-100 drop-shadow-lg dark:bg-gray-700 dark:divide-gray-600">
                                                <ul className="py-1 text-sm text-gray-700 dark:text-gray-200"
                                                    aria-labelledby={`btn-${item2.id}`}>
                                                    <li>
                                                        <a href="#" onClick={() => openModalAction({
                                                            id: item2.id,
                                                            sasaran_atasan:item.sasaran,
                                                            sasaran_skp:item1.sasaran,
                                                            indikator_skp:item2.indikator,
                                                            satuan:item2.satuan,
                                                            target_tw1:item2.target_tw1,
                                                            target_tw2:item2.target_tw2,
                                                            target_tw3:item2.target_tw3,
                                                            target_tw4:item2.target_tw4,
                                                            realisasi_tw1:item2.realisasi_tw1,
                                                            realisasi_tw2:item2.realisasi_tw2,
                                                            realisasi_tw3:item2.realisasi_tw3,
                                                            realisasi_tw4:item2.realisasi_tw4,
                                                            hambatan:item2.hambatan,
                                                            tindak_lanjut:item2.tindak_lanjut
                                                        })}
                                                            className="flex gap-1 py-2 px-4 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">
                                                            <PencilSquareIcon className='w-5 h-5' />
                                                            Input Realisasi
                                                        </a>
                                                    </li>
                                                </ul>
                                                <div className="py-1">
                                                    <a href="#" onClick={() => navigate(`/pegawai/realisasiskp/${selectedPeriod}/rencana-aksi/${item2.id}`)}
                                                        className="flex gap-1 py-2 px-4 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">
                                                        <PencilSquareIcon className='w-5 h-5' />
                                                        Realisasi Renaksi
                                                    </a>
                                                </div>
                                            </div>
            
                                        </td>
                                    </tr>
                                );
                            });
                        });
                    }) 
                    
                        :
                    <tr className="border-b dark:border-gray-700">
                        <td scope="row" className="px-4 py-3 text-center" colSpan="100%">No Data</td>
                    </tr>
                
        )
    }
    
    const openModalAction = (data) => {
        setFormTitle("FORM REALISASI SKP")
        setInitFormData(
            {
                id: data?.id ?? null,
                sasaran_atasan: data?.sasaran_atasan ?? "(no data)",
                sasaran_skp: data?.sasaran_skp ?? "(no data)",
                indikator_skp: data?.indikator_skp ?? "(no data)",
                satuan: data?.satuan ?? "(no data)",
                targettw1: data?.target_tw1 ?? 0,
                targettw2: data?.target_tw2 ?? 0,
                targettw3: data?.target_tw3 ?? 0,
                targettw4: data?.target_tw4 ?? 0,
                realisasitw1: data?.realisasi_tw1 ?? 0,
                realisasitw2: data?.realisasi_tw2 ?? 0,
                realisasitw3: data?.realisasi_tw3 ?? 0,
                realisasitw4: data?.realisasi_tw4 ?? 0,
                hambatan: data?.hambatan,
                tindak_lanjut: data?.tindak_lanjut
            }
        )
        
        setOpenModal(true);
    }
    const simpanData = async () => {
        let payload = {
            realisasi_tw1: initFormData.realisasitw1.toString(),
            realisasi_tw2: initFormData.realisasitw2.toString(),
            realisasi_tw3: initFormData.realisasitw3.toString(),
            realisasi_tw4: initFormData.realisasitw4.toString(),
            hambatan: initFormData.hambatan,
            tindak_lanjut: initFormData.tindak_lanjut,
        }
        let response = await dispatch(updateSkpRealisasi(initFormData.id,payload))
        if(response.status !== "failed"){
            Swal.fire({
                icon: 'success',
                title: response.data.message,
                showConfirmButton: false,
                timer: 1500
            })
        
            setOpenModal(false)
            getDataTable()
        }
        else{
            Swal.fire({
                icon: 'error',
                title: "something went wrong",
                showConfirmButton: false,
                timer: 1500
            })
        
            setOpenModal(false)
        }
    }
    
    return (
        <Layout>
            <div className="bg-white h-auto dark:bg-gray-800 rounded-lg drop-shadow-xl py-1 px-3 w-full">
                <div className="dark:text-white">
                    <div className="flex flex-row items-center gap-3">
                        <div>
                            <img src={GoodNotes} alt="Pengukuran Pegawai" className="object-contain" />
                        </div>
                        <div className="lg:text-lg font-bold text-teal-500 dark:text-white">
                            Realisasi SKP Pegawai
                        </div>
                    </div>
                </div>
            </div>
            <div className="bg-white h-auto dark:bg-gray-800 rounded-lg drop-shadow-xl py-1 sm:px-3 px-1 w-full flex flex-col lg:min-h-[35rem]">
                <div className="block w-full p-4">
                    <h1 className="text-center font-semibold text-lg dark:text-white">Realisasi SKP Pegawai</h1>
                </div>
                <div className="w-full flex sm:justify-end px-6">
                    <div className="w-full md:w-1/4 sm:w-1/3 py-5">
                        <label htmlFor="" className="py-2 font-semibold dark:text-white">Periode</label>
                        <select 
                            value={selectedPeriod} 
                            onChange={handlePeriodChange} 
                            className="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        >
                            {listPeriode()}
                        </select>
                    </div>
                </div>
                <div className="block w-full p-4">
                <StaticTable header={tableHeader()}>
                    {
                        renderTable()
                    }
                </StaticTable>
                <MyModal ModalTitle={formTitle} openModal={openModal} setOpenModal={setOpenModal} >
                    <div className="flex flex-col w-full p-4">
                        <MyInput id="sasaran_atasan" name="sasaran_atasan" label="Sasaran Atasan Pegawai"
                            placeholder="pilih sasaran..."
                            value={initFormData.sasaran_atasan} disabled  />
                        <MyInput id="sasaran_diampu" name="sasaran_diampu" label="Sasaran Kinerja Pegawai"
                            placeholder="pilih sasaran..."
                            value={initFormData.sasaran_skp} disabled />
                        <MyInput id="indikator_skp" name="indikator_skp" label="Indikator Kinerja Individu"
                            placeholder="pilih indikator..."
                            value={initFormData.indikator_skp} disabled />
                        
                        <MyInput id="satuan" name="satuan" label="Satuan"
                            type="text"
                            value={initFormData.satuan}
                            disabled
                            />
                        <div className="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-2 sm:gap-3 md:gap-5">
                            <MyInput id="tw1" name="tw1" 
                                label="TW ke-1" 
                                placeholder='Input target...'
                                type='number'
                                value={initFormData.targettw1}
                                disabled
                            />
                            <MyInput id="tw2" name="tw2" 
                                label="TW ke-2" 
                                placeholder='Input target...'
                                type='number'
                                value={initFormData.targettw2}
                                disabled
                            />
                            <MyInput id="tw3" name="tw3" 
                                label="TW ke-3" 
                                placeholder='Input target...'
                                type='number'
                                value={initFormData.targettw3}
                                disabled
                            />
                            <MyInput id="tw4" name="tw4" 
                                label="TW ke-4" 
                                placeholder='Input target...'
                                type='number'
                                value={initFormData.targettw4}
                                disabled
                            />
                        </div>
                        <div className="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-2 sm:gap-3 md:gap-5">
                            <MyInput id="realisasitw1" name="realisasitw1" 
                                label="Realisasi TW ke-1" 
                                placeholder='Input realiasi...'
                                type='number'
                                value={initFormData.realisasitw1}
                                onChange={(e) => setInitFormData({...initFormData, realisasitw1: e.target.value})}
                            />
                            <MyInput id="realisasitw2" name="realisasitw2" 
                                label="Realisasi TW ke-2" 
                                placeholder='Input realisasi...'
                                type='number'
                                value={initFormData.realisasitw2}
                                onChange={(e) => setInitFormData({...initFormData, realisasitw2: e.target.value})}
                            />
                            <MyInput id="realisasitw3" name="realisasitw3" 
                                label="Realisasi TW ke-3" 
                                placeholder='Input realisasi...'
                                type='number'
                                value={initFormData.realisasitw3}
                                onChange={(e) => setInitFormData({...initFormData, realisasitw3: e.target.value})}
                            />
                            <MyInput id="realisasitw4" name="realisasitw4" 
                                label="Realisasi TW ke-4" 
                                placeholder='Input realisasi...'
                                type='number'
                                value={initFormData.realisasitw4}
                                onChange={(e) => setInitFormData({...initFormData, realisasitw4: e.target.value})}
                            />
                        </div>
                        <MyTextarea 
                            id="hambatan" 
                            name="hambatan" 
                            label="Hambatan yang dihadapi" 
                            placeholder='Inputkan teks...'
                            value={initFormData.hambatan}
                            onChange={(e) => setInitFormData({...initFormData, hambatan: e.target.value})}
                        />
                        <MyTextarea 
                            id="tindak_lanjut" 
                            name="tindak_lanjut" 
                            label="Tindak Lanjut yang harus dilakukan" 
                            placeholder='Inputkan teks...'
                            value={initFormData.tindak_lanjut}
                            onChange={(e) => setInitFormData({...initFormData, tindak_lanjut: e.target.value})}
                        />

                    </div>
                    
                    <div className="mt-5 sm:mt-6 flex justify-center">
                        <PrimaryBtn onClick={()=> simpanData()} loading={loading} >
                            Simpan Data
                        </PrimaryBtn>
                    </div>
                </MyModal>
                </div>
            </div>
        </Layout>
    )
}

export default SkpDetailRealisasi