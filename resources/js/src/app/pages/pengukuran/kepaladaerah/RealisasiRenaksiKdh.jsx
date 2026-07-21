import Layout from '@/app/components/Layout/Layout'
import React from 'react'
import GoodNotes from "@assets/GoodNotes.png"
import { StaticTable } from '@/app/components/Table'
import { PencilSquareIcon, ArrowLeftCircleIcon } from '@heroicons/react/24/outline'
import MyModal from '@/app/components/Form/MyModal'
import MyInput from '@/app/components/Form/MyInput'
import PrimaryBtn from '@/app/components/Button/PrimaryBtn'
import PrimaryLinkBtn from '@/app/components/Button/PrimaryLinkBtn'
import { useDispatch, useSelector } from 'react-redux'
import { initFlowbite } from 'flowbite'
import Swal from 'sweetalert2'
import { Link } from 'react-router-dom'
import { RupiahFormatter } from '@/helper/common'
import MyTextarea from '@/app/components/Form/MyTextarea'
import { getListRealisasiRenaksiKdh, createRealisasiRenaksiKdh } from '@/redux/ducks/realisasirenaksikdh/action'

const RealisasiRenaksiKdh = () => {
    const realisasiRenaksiKdhState = useSelector((state) => state.realisasiRenaksiKdhState)
    const dispatch = useDispatch()
    const [formTitle, setFormTitle] = React.useState("Form Realisasi Rencana Aksi")
    const [openModal, setOpenModal] = React.useState(false)
    const [selectedYear, setSelectedYear] = React.useState(new Date().getFullYear());
    React.useEffect(() => {
        getDataTable(selectedYear)
    },[])
    const getDataTable = (year = "") => {
        dispatch(getListRealisasiRenaksiKdh({ tahun: year != "" ? year : selectedYear }))        
    }
    const [formContent, setFormContent] = React.useState({
        sasaranId: "",
        sasaran: "",
        indikatorId: "",
        indikator: "",
        rencana_aksi_id: "",
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
        hambatan: "",
        tindak_lanjut: ""
    })
    const handleYearChange = (event) => {
        setSelectedYear(event.target.value);
        getDataTable(event.target.value)
    };
    React.useEffect(() => {
        initFlowbite()
    },[realisasiRenaksiKdhState.list])
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
                <th scope="col" className="px-4 py-3 border w-[5%]" rowSpan="2">
                    <span className="sr-only">Actions</span>
                </th>
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
    const inputRealisasi = (data) => {
        if(data.rencana_aksi === null){
            Swal.fire({
                icon: 'warning',
                title: "tidak dapat mengisikan realisasi, karena target belum diinputkan",
                showConfirmButton: false,
                timer: 1500
            })
        }
        setFormContent({
            sasaranId: data.sasaranId ?? "",
            sasaran: data.sasaran ?? "",
            indikatorId: data.indikatorId ?? "",
            indikator: data.indikator ?? "",
            rencana_aksi_id: data.rencana_aksi.id ?? "",
            targettw1: data.rencana_aksi.target_tw1 ?? "",
            targettw2: data.rencana_aksi.target_tw2 ?? "",
            targettw3: data.rencana_aksi.target_tw3 ?? "",
            targettw4: data.rencana_aksi.target_tw4 ?? "",
            realisasitw1: data.rencana_aksi.realisasi_tw1,
            realisasitw2: data.rencana_aksi.realisasi_tw2,
            realisasitw3: data.rencana_aksi.realisasi_tw3,
            realisasitw4: data.rencana_aksi.realisasi_tw4,
            hambatan: data.rencana_aksi.hambatan,
            tindak_lanjut: data.rencana_aksi.tindak_lanjut,
        })
        setOpenModal(true)
    }

    const simpanData = async () => {
        let payload = {
            pohon_kinerja_sasaran_id: formContent.sasaranId,
            pohon_kinerja_indikator_id: formContent.indikatorId,
            realisasi_tw1: formContent.realisasitw1,
            realisasi_tw2: formContent.realisasitw2,
            realisasi_tw3: formContent.realisasitw3,
            realisasi_tw4: formContent.realisasitw4,
            hambatan: formContent.hambatan,
            tindak_lanjut: formContent.tindak_lanjut,
        }

        
        let response = await dispatch(createRealisasiRenaksiKdh(formContent.rencana_aksi_id,payload))
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
                            <img src={GoodNotes} alt="Pengukuran Kepala Daerah" className="object-contain" />
                        </div>
                        <div className="lg:text-lg font-bold text-teal-500 dark:text-white">
                            Pengukuran Kepala Daerah
                        </div>
                    </div>
                </div>
            </div>
            <div className="bg-white h-auto dark:bg-gray-800 rounded-lg drop-shadow-xl py-1 sm:px-3 px-1 w-full flex flex-col lg:min-h-[35rem]">
                <div className="block w-full p-4">
                    <h1 className="text-center font-semibold text-lg dark:text-white">Realisasi Rencana Aksi Kepala Daerah</h1>
                </div>
                <div className="w-full flex sm:justify-end px-6">
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
                                <td className="px-4 py-3 border text-center">
                                    <button id={`btn-${key}`} data-dropdown-toggle={`toggle-btn${key}`}
                                        className="inline-flex items-center p-0.5 text-sm font-medium text-center text-gray-500 hover:text-gray-800 rounded-lg focus:outline-none dark:text-gray-400 dark:hover:text-gray-100"
                                        type="button">
                                        <svg className="w-5 h-5" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM16 12a2 2 0 100-4 2 2 0 000 4z" />
                                        </svg>
                                    </button>
                                    <div id={`toggle-btn${key}`}
                                        className="hidden z-10 w-48 bg-gray-50 rounded divide-y divide-gray-100 drop-shadow-lg dark:bg-gray-700 dark:divide-gray-600">
                                        <ul className="py-1 text-sm text-gray-700 dark:text-gray-200"
                                            aria-labelledby={`btn-${key}`}>
                                            <li>
                                                <button onClick={() => inputRealisasi({
                                                    sasaranId:item.id,
                                                    sasaran:item.sasaran,
                                                    indikatorId: item.indikator[0]?.id ?? "",
                                                    indikator: item.indikator[0]?.indikator ?? "",
                                                    rencana_aksi: item.indikator[0]?.rencana_aksi
                                                })}
                                                    className="flex gap-1 py-2 px-4 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">
                                                    <PencilSquareIcon className='w-4 h-4' />
                                                    Input Realisasi
                                                </button>
                                            </li>
                                            <li>
                                                <Link to={`/pengukuran/kdh/realisasirenaksi/langkah?sasaranid=${item.id}&indikatorid=${item.indikator[0]?.id ?? "0"}&tahun=${selectedYear}`}
                                                    className="flex gap-1 py-2 px-4 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">
                                                    <PencilSquareIcon className='w-4 h-4' />
                                                    Input Langkah
                                                </Link>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
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
                                                    <td className="px-4 py-3 border text-center">
                                                        <button id={`btn-${val.id}`} data-dropdown-toggle={`toggle-btn${val.id}`}
                                                            className="inline-flex items-center p-0.5 text-sm font-medium text-center text-gray-500 hover:text-gray-800 rounded-lg focus:outline-none dark:text-gray-400 dark:hover:text-gray-100"
                                                            type="button">
                                                            <svg className="w-5 h-5" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20"
                                                                xmlns="http://www.w3.org/2000/svg">
                                                                <path
                                                                    d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM16 12a2 2 0 100-4 2 2 0 000 4z" />
                                                            </svg>
                                                        </button>
                                                        <div id={`toggle-btn${val.id}`}
                                                            className="hidden z-10 w-48 bg-gray-50 rounded divide-y divide-gray-100 drop-shadow-lg dark:bg-gray-700 dark:divide-gray-600">
                                                            <ul className="py-1 text-sm text-gray-700 dark:text-gray-200"
                                                                aria-labelledby={`btn-${val.id}`}>
                                                                <li>
                                                                    <button onClick={() => inputRealisasi({
                                                                        sasaranId:item.id,
                                                                        sasaran:item.sasaran,
                                                                        indikatorId: val.id ?? "",
                                                                        indikator: val.indikator ?? "",
                                                                        rencana_aksi: val.rencana_aksi
                                                                    })}
                                                                        className="flex gap-1 py-2 px-4 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">
                                                                        <PencilSquareIcon className='w-4 h-4' />
                                                                        Input Realisasi
                                                                    </button>
                                                                </li>
                                                                <li>
                                                                    <Link to={`/pengukuran/kdh/realisasirenaksi/langkah?sasaranid=${item.id}&indikatorid=${val.id}&tahun=${selectedYear}`}
                                                                        className="flex gap-1 py-2 px-4 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">
                                                                        <PencilSquareIcon className='w-4 h-4' />
                                                                        Input Langkah
                                                                    </Link>
                                                                </li>
                                                            </ul>
                                                        </div>
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
                    <MyModal  ModalTitle={formTitle} openModal={openModal} setOpenModal={setOpenModal} >
                        <div className="flex flex-col w-full p-4">
                            <MyInput id="sasaran" name="sasaran" label="Sasaran" value={formContent.sasaran} disabled />
                            <MyInput id="indikator" name="indikator" label="Indikator" value={formContent.indikator} disabled />
                            <div className="block w-full py-2">
                                <h1 className="font-bold dark:text-white">Target dan Realisasi per Triwulan (TW)</h1>
                            </div>
                            <div className="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-2 sm:gap-3 md:gap-5">
                                <MyInput id="tw1" name="tw1" 
                                    label="TW ke-1" 
                                    placeholder='Input target...'
                                    type='number'
                                    value={formContent.targettw1}
                                    disabled
                                />
                                <MyInput id="tw2" name="tw2" 
                                    label="TW ke-2" 
                                    placeholder='Input target...'
                                    type='number'
                                    value={formContent.targettw2}
                                    disabled
                                />
                                <MyInput id="tw3" name="tw3" 
                                    label="TW ke-3" 
                                    placeholder='Input target...'
                                    type='number'
                                    value={formContent.targettw3}
                                    disabled
                                />
                                <MyInput id="tw4" name="tw4" 
                                    label="TW ke-4" 
                                    placeholder='Input target...'
                                    type='number'
                                    value={formContent.targettw4}
                                    disabled
                                />
                            </div><div className="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-2 sm:gap-3 md:gap-5">
                                <MyInput id="realisasitw1" name="realisasitw1" 
                                    label="Realisasi TW ke-1" 
                                    placeholder='Input realiasi...'
                                    type='number'
                                    value={formContent.realisasitw1}
                                    onChange={(e) => setFormContent({...formContent, realisasitw1: e.target.value})}
                                />
                                <MyInput id="realisasitw2" name="realisasitw2" 
                                    label="Realisasi TW ke-2" 
                                    placeholder='Input realisasi...'
                                    type='number'
                                    value={formContent.realisasitw2}
                                    onChange={(e) => setFormContent({...formContent, realisasitw2: e.target.value})}
                                />
                                <MyInput id="realisasitw3" name="realisasitw3" 
                                    label="Realisasi TW ke-3" 
                                    placeholder='Input realisasi...'
                                    type='number'
                                    value={formContent.realisasitw3}
                                    onChange={(e) => setFormContent({...formContent, realisasitw3: e.target.value})}
                                />
                                <MyInput id="realisasitw4" name="realisasitw4" 
                                    label="Realisasi TW ke-4" 
                                    placeholder='Input realisasi...'
                                    type='number'
                                    value={formContent.realisasitw4}
                                    onChange={(e) => setFormContent({...formContent, realisasitw4: e.target.value})}
                                />
                            </div>
                            <div className="w-full py-2 block                                    ">
                                <MyTextarea 
                                    id="hambatan" 
                                    name="hambatan" 
                                    label="Hambatan yang dihadapi" 
                                    placeholder='Inputkan teks...'
                                    value={formContent.hambatan}
                                    onChange={(e) => setFormContent({...formContent, hambatan: e.target.value})}
                                />
                            </div>
                            <div className="w-full py-2 block                                    ">
                                <MyTextarea 
                                    id="tindaklanjut" 
                                    name="tindaklanjut" 
                                    label="Tindak lanjut yang harus dilakukan" 
                                    placeholder='Inputkan teks...'
                                    value={formContent.tindak_lanjut}
                                    onChange={(e) => setFormContent({...formContent, tindak_lanjut: e.target.value})}
                                />
                            </div>
                        </div>
                        
                        <div className="flex justify-center">
                            <PrimaryBtn loading={realisasiRenaksiKdhState.loading} onClick={() => simpanData()} >
                                Simpan Data
                            </PrimaryBtn>
                        </div>
                    </MyModal>
                </div>

            </div>
            
        </Layout>
    )
}

export default RealisasiRenaksiKdh