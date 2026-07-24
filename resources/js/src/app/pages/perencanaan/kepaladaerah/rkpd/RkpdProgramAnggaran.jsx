import Layout from '@/app/components/Layout/Layout'
import React from 'react'
import GoodNotes from "@assets/GoodNotes.png"
import MySelect2 from '@/app/components/Form/MySelect2'
import PrimaryLinkBtn from '@/app/components/Button/PrimaryLinkBtn'
import { ArrowLeftCircleIcon, ExclamationTriangleIcon } from '@heroicons/react/24/outline'
import MyInput from '@/app/components/Form/MyInput'
import { useParams, useNavigate, useSearchParams } from 'react-router-dom'
import { useSelector, useDispatch } from 'react-redux'
import { getListProgramAnggaran } from '@/redux/ducks/programanggaran/action' 
import { createProgramRkpdKdh, getListRkpdKdhProgram } from '@/redux/ducks/rkpdkdh/action'
import TablePA from '@/app/components/ProgramAnggaran/TableProgramAnggaran'
import Swal from 'sweetalert2'

const RkpdProgramAnggaran = () => {
    const { type, period } = useParams()
    const dispatch = useDispatch()
    const [searchParams] = useSearchParams()
    const pohon_kinerja_sasaran_id = searchParams.get('id')
    const programAnggaranState = useSelector((state) => state.programAnggaranState)
    const rkpdKdhState = useSelector((state) => state.rkpdKdhState)
    const navigate = useNavigate()
    React.useEffect(() => {
        if (type !== 'murni' && type !== 'perubahan') {
            navigate('/perencanaan/kdh/rkpd')
            return
        }
        let payload = {
            pohon_kinerja_sasaran_id: pohon_kinerja_sasaran_id,
            tahun: period,
            murni: type === 'murni'
        }
        dispatch(getListRkpdKdhProgram(payload))
    },[])
    const [programExisted,setProgramExisted] = React.useState(null)
    React.useEffect(() => {
        if(rkpdKdhState.sasaran !== null){
            if(rkpdKdhState.sasaran.program_rkpd && typeof rkpdKdhState.sasaran.program_rkpd === 'object' && Object.keys(rkpdKdhState.sasaran.program_rkpd).length > 0){
                if(rkpdKdhState.sasaran.program_rkpd.list_kegiatan){
                    var existedProgram = rkpdKdhState.sasaran.program_rkpd.list_kegiatan
                    setProgramExisted(existedProgram)
                }
            }
        }
    },[rkpdKdhState])
    const existedProgramList = () => {
        
        if(selectedOpd.value){
            if(programExisted !== null){
                if(('opd_id_'+selectedOpd.value) in programExisted) return programExisted['opd_id_'+selectedOpd.value]
                else return []
            }
            return []
        }
        return []
    }

    const [selectedOpd, setSelectedOpd] = React.useState({})
    const opdOnChange = (val) => {
        setSelectedOpd(val)
        let payload = {
            idskpd : val.value,
            year : period,
            periode: type
        }
        dispatch(getListProgramAnggaran(payload))
    }
    const opdOptions = () => {
        let opdPendukung = rkpdKdhState.sasaran?.opd_pendukung ?? null
        if(opdPendukung){
            if(opdPendukung.length > 0){
                return opdPendukung.map((item) => ({
                    value: item.kode_opd,
                    label: item.nama_opd
                }))
            }
            return []
        }
        return []
    }

    const mixedLoading = () => (programAnggaranState.loading || rkpdKdhState.loading)

    const isError = () => (rkpdKdhState.error) && !rkpdKdhState.loading

    const simpanData = (programAnggaranData) => {
        // hitung total anggaran
        let list_kegiatan = {}

        if(programExisted !== null){
            for(var key in programExisted){
                list_kegiatan[key] = programExisted[key]
            }
        }

        list_kegiatan['opd_id_'+selectedOpd.value] = programAnggaranData
        let tAnggaran = 0
        for(var xk in list_kegiatan){
            let existedPA = list_kegiatan[xk]
                if(existedPA.length > 0){
                    existedPA.map((item) => {
                        tAnggaran += item.uAnggaran ?? 0
                    })
                }
        }
        
        let payload = {
            pohon_kinerja_sasaran_id: pohon_kinerja_sasaran_id, // sesuaikan dengan sasaran yang dipilih
            tahun: period,
            murni: type === "murni",
            list_kegiatan: JSON.stringify(list_kegiatan), // mapping ulang menggunakan kode opd sebagai objectkey
            anggaran: tAnggaran,
            is_active: true
        }
        Swal.fire({
            title: 'Apakah anda yakin?',
            text: "pastikan data program anggaran sudah sesuai dan benar",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes!'
        }).then(async (result) => {
            if (result.isConfirmed) {
                const response = await dispatch(createProgramRkpdKdh(payload))
            
                if(response.error === null){
                    Swal.fire({
                        icon: 'success',
                        title: response.data.message,
                        showConfirmButton: false,
                        timer: 1500
                    })
                    navigate(`/perencanaan/kdh/rkpd/${period}/${type}`)
                }
                else{
                    Swal.fire({
                        icon: 'error',
                        title: typeof response.error === 'string' ? response.error : "something went wrong",
                        showConfirmButton: false,
                        timer: 1500
                    })
                }
                
            }
        })
    }

    return (
        <Layout>
            <div className="bg-white h-auto dark:bg-gray-800 rounded-lg drop-shadow-xl py-1 px-3 w-full">
                <div className="dark:text-white">
                    <div className="flex flex-row items-center gap-3">
                        <div>
                            <img src={GoodNotes} alt="Perencanaan Kepala Daerah" className="object-contain" />
                        </div>
                        <div className="lg:text-lg font-bold text-teal-500 dark:text-white">Perencanaan RKPD KDH</div>
                    </div>
                </div>
            </div>
            <div className="bg-white h-auto dark:bg-gray-800 rounded-lg drop-shadow-xl py-1 sm:px-3 px-1 w-full flex flex-col lg:min-h-[35rem]">
                <div className="block w-full p-4">
                    <h2 className="font-bold text-blue-500 w-full text-center">
                        Pilih Program/Kegiatan/Subkegiatan <br /> 
                        RKPD {type.toUpperCase()} <br />
                        {period}
                    </h2>
                    <div className="flex">
                        <PrimaryLinkBtn to={`/perencanaan/kdh/rkpd/${period}/${type}`}>
                            <ArrowLeftCircleIcon className='w-5 h-5' />
                            Kembali
                        </PrimaryLinkBtn>
                    </div>
                </div>
                {isError() ? (
                    <div className="block w-full p-4">
                        <div className="flex items-center gap-2 text-red-500 bg-red-50 dark:bg-red-900/20 p-4 rounded-lg">
                            <ExclamationTriangleIcon className="w-6 h-6" />
                            <span>Gagal memuat data sasaran. Silakan coba kembali.</span>
                        </div>
                    </div>
                ) : <>
                <div className="block w-full p-4">
                    
                    <MyInput
                        id="sasaranSelector"
                        label="Sasaran"
                        value={rkpdKdhState.sasaran?.sasaran?.sasaran ?? ""}
                        disabled
                    />
                    <MySelect2
                        id="opdSelector"
                        label="OPD Pengampu"
                        options={opdOptions()}
                        value={selectedOpd}
                        onChange={opdOnChange}
                    />
                </div>
                <div className="block w-full p-4">
                    <TablePA 
                        programAnggaranData={selectedOpd.value ? programAnggaranState.data : []} 
                        existingData={existedProgramList()}
                        loading={mixedLoading()}
                        action={simpanData}
                    />
                </div>
                </>}
            </div>
        </Layout>
    )
}

export default RkpdProgramAnggaran