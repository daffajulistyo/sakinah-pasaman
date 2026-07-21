import React from 'react'
import Layout from "@components/Layout/Layout"
import { useSelector, useDispatch } from "react-redux"
import IconBtn from '@/app/components/Button/IconBtn'
import { Cog8ToothIcon } from '@heroicons/react/24/solid'
import MyModal from '@/app/components/Form/MyModal'
import { getListPegawaiPengampuIndikatorOpd } from '@/redux/ducks/pengampuindikatoropd/actions'
import { getListAtasanPegawai, createAtasanPegawai } from '@/redux/ducks/atasan/actions'
import MySelect2 from '@/app/components/Form/MySelect2'
import PrimaryBtn from '@/app/components/Button/PrimaryBtn'
import Swal from 'sweetalert2'
import { getMyRoles_act, changeMyRole_act } from '@/redux/ducks/auth/action'
import { useNavigate } from 'react-router-dom'

const Profile = () => {
    const navigate = useNavigate()
    const dispatch = useDispatch()
    const authState = useSelector((state) => state.authState)
    const atasanState = useSelector((state) => state.atasanState)
    const [openModal, setOpenModal] = React.useState(false)
    const formTitle = "Setting Atasan Langsung"
    const pengampuIndikatorOpdState = useSelector((state) => state.pengampuIndikatorOpdState)
    const [selectedPegawai, setSelectedPegawai] = React.useState(null)
    const listPegawaiOnchange = (item) => {
        setSelectedPegawai(item.value)
        
    }

    React.useEffect(() => {
        if(authState.myroles.length == 0)
        {
            dispatch(getMyRoles_act())
        }
    },[authState.myroles])

    const optionRoles = () => (
        authState.myroles.length > 0 ?
            authState.myroles.map((item) => ({ label: item.role_name, value: item.role_id, roleplay_id: item.id })) : []
    )

    const [currentRole, setCurrentRole] = React.useState({})

    React.useEffect(() => {
        if(authState.myroles.length > 0)
        {
            authState.myroles.map((item) => {
                if(item.role_id === authState.biodata.role){
                    
                    setCurrentRole({
                        label: item.role_name,
                        value: item.role_id,
                        roleplay_id: item.id
                    })
                }
            })
        }
    },[authState.biodata.role, authState.myroles])

    const changeRoleAction = async () => {
        const preparedPayload = {
            role_id: currentRole.value,
            roleplay_id: currentRole.roleplay_id
        }
        if(currentRole.id !== authState.biodata.role){

            const response = await dispatch(changeMyRole_act(preparedPayload))
            if(response.code === 200)
            {
                navigate('/dashboard')
            }
        }
    }

    
    React.useEffect(() => {
        if(authState.biodata.opd?.simpeg_opd_id && authState.biodata.level === "Pegawai"){
            dispatch(getListPegawaiPengampuIndikatorOpd(authState.biodata.opd?.simpeg_opd_id || 0))
        }
    },[])

    React.useEffect(() => {
        if(authState.biodata.level === "Pegawai"){
            dispatch(getListAtasanPegawai())
        }
    },[])

    const daftarPegawai = () => {
        let data = []
        // if(pengampuIndikatorOpdState.list_pegawai.length > 0){
        //     data = pengampuIndikatorOpdState.list_pegawai.map((item) => ({
        //         label: item.jabatan_nm.toUpperCase() + " - " + item.nip + " - " + item.nama_pns, //renderOptionItem(item.jabatan_nm, item.nama_pns, item.nip),
        //         value: item
        //     }))
        // }
        return data
    }
    const simpanData = () => {
        Swal.fire({
        title: 'Apakah anda yakin?',
        text: "Menetapkan pegawai ini sebagai atasan langsung!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes!'
        }).then(async (result) => {
            if (result.isConfirmed) {
                if(selectedPegawai === null){
                    Swal.fire({
                        icon: 'error',
                        title: "Pegawai belum dipilih",
                        showConfirmButton: false,
                        timer: 1500
                    })
                }
                else{
                    let payload = {
                        nip_atasan: selectedPegawai.nip || "-",
                        nama_atasan: selectedPegawai.nama_pns || "-",
                        jabatan_atasan: selectedPegawai.jabatan_nm || "-",
                        unit_kerja_atasan: selectedPegawai.sub_opd_nm || "-"
                    }
                    let response = await dispatch(createAtasanPegawai(payload))
                    if(response.status !== "failed"){
                        setOpenModal(false)
                        dispatch(getListAtasanPegawai())
                        Swal.fire({
                            icon: 'success',
                            title: response.data.message,
                            showConfirmButton: false,
                            timer: 1500
                        })
                    }
                    else{
                        Swal.fire({
                            icon: 'error',
                            title: "something went wrong",
                            showConfirmButton: false,
                            timer: 1500
                        })
                    }
                }
            }
        })
    }
    let loading = false
    return (
        <Layout>
            <div className="bg-white h-auto dark:bg-gray-800 rounded-lg drop-shadow-xl p-3 w-full">
                <div className="text-teal-500 dark:text-teal-200 font-bold italic my-10 border-b border-gray-300 dark:border-gray-700">
                    Profile
                </div>
                <div className="w-full grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-1 sm:gap-4 my-4">
                    <div className="w-full rounded-lg p-2 flex flex-col justify-top">
                        <span className="text-xs text-teal-500 dark:text-teal-200 font-bold italic">Nama</span>
                        <span className="text-sm text-gray-900 dark:text-white">{authState.biodata.name}</span>
                    </div>
                    <div className="w-full rounded-lg p-2 flex flex-col justify-top">
                        <span className="text-xs text-teal-500 dark:text-teal-200 font-bold italic">NIP</span>
                        <span className="text-sm text-gray-900 dark:text-white">{authState.biodata.nip || "-"}</span>
                    </div>
                    <div className="w-full rounded-lg p-2 flex flex-col justify-top">
                        <span className="text-xs text-teal-500 dark:text-teal-200 font-bold italic">Status</span>
                        <span className="text-sm text-gray-900 dark:text-white">{authState.biodata.level || "-"}</span>
                    </div>
                    <div className="w-full rounded-lg p-2 flex flex-col justify-top">
                        <span className="text-xs text-teal-500 dark:text-teal-200 font-bold italic">OPD</span>
                        <span className="text-sm text-gray-900 dark:text-white">{authState.biodata.opd?.nama_opd || "-"}</span>
                    </div>
                    <div className="w-full rounded-lg p-2 flex flex-col justify-top">
                        <span className="text-xs text-teal-500 dark:text-teal-200 font-bold italic">Jabatan</span>
                        <span className="text-sm text-gray-900 dark:text-white">{authState.biodata.jabatan_nm || "-"}</span>
                    </div>
                    <div className="w-full rounded-lg p-2 flex flex-col justify-top">
                        <span className="text-xs text-teal-500 dark:text-teal-200 font-bold italic">Jenis Jabatan</span>
                        <span className="text-sm text-gray-900 dark:text-white">{authState.biodata.jns_jbtn_nm || "-"}</span>
                    </div>
                    <div className="w-full rounded-lg p-2 flex flex-col justify-top">
                        <span className="text-xs text-teal-500 dark:text-teal-200 font-bold italic">Esselon</span>
                        <span className="text-sm text-gray-900 dark:text-white">{authState.biodata.eselon_nm || "-"}</span>
                    </div>
                </div>
                <div className="text-teal-500 dark:text-teal-200 font-bold italic my-10 border-b border-gray-300 dark:border-gray-700">
                    Akses
                </div>
                <div className="w-full flex flex-col">
                    {/* <div className="w-full">Anda mengakses SAKINAH sebagai :</div> */}
                    <div className="w-full sm:w-1/2 md:w-1/3">

                        <MySelect2
                            id="role"
                            label="Anda mengakses SAKINAH sebagai :"
                            options={optionRoles()}
                            value={currentRole}
                            onChange={setCurrentRole}
                        />

                        <PrimaryBtn onClick={()=> changeRoleAction()} loading={authState.loading} >
                            Ganti
                        </PrimaryBtn>
                    </div>
                </div>
                {
                    authState.biodata?.level !== "Pegawai" ? null : (
                        <>
                        {/* <div className="w-full font-bold text-teal-500 dark:text-teal-200 italic border-b border-gray-300 dark:border-gray-700 mt-10">
                            Atasan Langsung &nbsp; <IconBtn onClick={()=> setOpenModal(true)} ><Cog8ToothIcon className='w-3 h-3' /></IconBtn>
                        </div> */}
                        <div className="w-full grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 sm:gap-4 gap-1 my-4">
                            <div className="w-full rounded-lg p-2 flex flex-col justify-top">
                                <span className="text-xs text-teal-500 dark:text-teal-200 font-bold italic">Nama Atasan</span>
                                <span className="text-sm text-gray-900 dark:text-white">{atasanState.data?.atasan?.nama_atasan || "-"}</span>
                            </div>
                            <div className="w-full rounded-lg p-2 flex flex-col justify-top">
                                <span className="text-xs text-teal-500 dark:text-teal-200 font-bold italic">NIP Atasan</span>
                                <span className="text-sm text-gray-900 dark:text-white">{atasanState.data?.atasan?.nip_atasan || "-"}</span>
                            </div>
                            <div className="w-full rounded-lg p-2 flex flex-col justify-top">
                                <span className="text-xs text-teal-500 dark:text-teal-200 font-bold italic">Jabatan</span>
                                <span className="text-sm text-gray-900 dark:text-white">{atasanState.data?.atasan?.jabatan_atasan || "-"}</span>
                            </div>
                            <div className="w-full rounded-lg p-2 flex flex-col justify-top">
                                <span className="text-xs text-teal-500 dark:text-teal-200 font-bold italic">Unit Kerja</span>
                                <span className="text-sm text-gray-900 dark:text-white">{atasanState.data?.atasan?.unit_kerja_atasan || "-"}</span>
                            </div>
                        </div>
                        </>
                    )
                }
                
            </div>
            <MyModal ModalTitle={formTitle} openModal={openModal} setOpenModal={setOpenModal} >
                <div className="flex flex-col w-full p-4">
                    <div className="w-full rounded-lg p-2 flex flex-col justify-top">
                        <span className="text-xs text-teal-500 dark:text-teal-200 font-bold italic">Unit Kerja</span>
                        <span className="text-sm text-gray-900 dark:text-white">{authState.biodata.opd?.nama_opd || "-"}</span>
                    </div>
                    <MySelect2
                        id="pengampu"
                        label="Pegawai"
                        options={daftarPegawai()}
                        onChange={listPegawaiOnchange}
                    />
                    <div className="mt-5 sm:mt-6 flex justify-end">
                        <PrimaryBtn onClick={()=> simpanData()} loading={loading} >
                            Simpan
                        </PrimaryBtn>
                    </div>
                </div>
            </MyModal>
        </Layout>
    )
}

export default Profile