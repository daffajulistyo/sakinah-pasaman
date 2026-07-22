import Layout from '@/app/components/Layout/Layout'
import React from 'react'
import GoodNotes from "@assets/GoodNotes.png"
import PrimaryBtn from '@/app/components/Button/PrimaryBtn'
import { useSelector, useDispatch } from 'react-redux'
import { PlusCircleIcon, PencilSquareIcon, TrashIcon } from '@heroicons/react/24/outline'
import { getListPegawai, createPegawai, updatePegawai, deletePegawai } from '@/redux/ducks/datamasterpegawai/action'
import { MyTable, TableHeader, TableSection, TableBody } from '@/app/components/Table'
import Swal from 'sweetalert2'
import { PacmanLoader } from 'react-spinners'
import { initFlowbite } from 'flowbite'
import { useFormik } from 'formik'
import * as Yup from "yup"
import MyModal from '@/app/components/Form/MyModal'
import MyInput from '@/app/components/Form/MyInput'
import MyToggle from '@/app/components/Form/MyToggle'
import MyMultiSelect from '@/app/components/Form/MyMultiSelect'

const Pegawai = () => {
    const pegawaiState = useSelector((state) => state.datamasterPegawaiState)
    const dispatch = useDispatch()
    const [openModal, setOpenModal] = React.useState(false)
    const [formTitle, setFormTitle] = React.useState("FORM TAMBAH DATA PEGAWAI")
    const [editId, setEditId] = React.useState("")
    const [opdList, setOpdList] = React.useState([])
    const [refEselon, setRefEselon] = React.useState([])
    const [refGolongan, setRefGolongan] = React.useState([])
    const [refJenisJabatan, setRefJenisJabatan] = React.useState([])
    const [refJabatan, setRefJabatan] = React.useState([])
    const [refRoles, setRefRoles] = React.useState([])
    const [selectedRoles, setSelectedRoles] = React.useState([])

    const getDataTable = async (page = 1, per_page = 10, search = "") => {
        const response = await dispatch(getListPegawai({ page, per_page, search }))
        if (response.error !== null) {
            Swal.fire({ icon: 'error', title: "something went wrong", showConfirmButton: true, confirmButtonText: 'Refresh Halaman', timer: 1500 })
                .then(async (result) => { if (result.isConfirmed) window.location.reload() })
        }
    }

    React.useEffect(() => { initFlowbite() }, [pegawaiState])
    React.useEffect(() => {
        getDataTable()
        const loadData = async () => {
            const Api = (await import('@/api')).default
            const api = new Api()
            const [rOpd, rEselon, rGol, rJnsJab, rRoles] = await Promise.all([
                api.getList_dmOpd({ page: 1, per_page: 999, search: '' }),
                api.getRefEselon(), api.getRefGolongan(), api.getRefJenisJabatan(), api.getRefRoles(),
            ])
            if (rOpd.data && rOpd.error === null) setOpdList(rOpd.data.data || [])
            if (rEselon.data) setRefEselon(rEselon.data.data || [])
            if (rGol.data) setRefGolongan(rGol.data.data || [])
            if (rJnsJab.data) setRefJenisJabatan(rJnsJab.data.data || [])
            if (rRoles.data) setRefRoles(rRoles.data.data || [])
        }
        loadData()
    }, [])

    const loadJabatan = async (jenisId) => {
        if (!jenisId) { setRefJabatan([]); return }
        const Api = (await import('@/api')).default
        const api = new Api()
        const r = await api.getRefJabatan({ jenis_id: jenisId })
        if (r.data) setRefJabatan(r.data.data || [])
    }

    const formik = useFormik({
        initialValues: {
            nip: '', nama: '', password: '', gelar_depan: '', gelar_belakang: '',
            tempat_lahir: '', tanggal_lahir: '', jenis_kelamin: '', alamat: '', no_hp: '',
            email: '', master_opd_id: '', sub_opd_nm: '', ref_eselon_id: '', ref_golongan_id: '',
            ref_jenis_jabatan_id: '', ref_jabatan_id: '', jenjang: '', role_ids: [], isActive: true
        },
        validationSchema: Yup.object({
            nip: Yup.string().required(),
            nama: Yup.string().required(),
            password: Yup.string().when('editId', { is: '', then: s => s.required().min(6) }),
        }),
        enableReinitialize: true
    })

    const openModalAction = () => {
        formik.resetForm()
        setEditId("")
        setRefJabatan([])
        setSelectedRoles([])
        setFormTitle("FORM TAMBAH DATA PEGAWAI")
        setOpenModal(true)
    }

    const validationForm = async () => {
        formik.setFieldTouched('nip', true, true)
        formik.setFieldTouched('nama', true, true)
        if (!editId) formik.setFieldTouched('password', true, true)
        return await formik.validateForm()
    }

    const simpanData = async () => {
        const errors = await validationForm()
        if (Object.keys(errors).length === 0) {
            const payload = {
                nip: formik.values.nip,
                nama: formik.values.nama,
                gelar_depan: formik.values.gelar_depan || undefined,
                gelar_belakang: formik.values.gelar_belakang || undefined,
                tempat_lahir: formik.values.tempat_lahir || undefined,
                tanggal_lahir: formik.values.tanggal_lahir || undefined,
                jenis_kelamin: formik.values.jenis_kelamin || undefined,
                alamat: formik.values.alamat || undefined,
                no_hp: formik.values.no_hp || undefined,
                email: formik.values.email || undefined,
                master_opd_id: formik.values.master_opd_id || undefined,
                sub_opd_nm: formik.values.sub_opd_nm || undefined,
                ref_eselon_id: formik.values.ref_eselon_id || undefined,
                ref_golongan_id: formik.values.ref_golongan_id || undefined,
                ref_jenis_jabatan_id: formik.values.ref_jenis_jabatan_id || undefined,
                ref_jabatan_id: formik.values.ref_jabatan_id || undefined,
                jenjang: formik.values.jenjang || undefined,
                role_ids: (formik.values.role_ids && formik.values.role_ids.length) ? formik.values.role_ids : undefined,
                is_active: formik.values.isActive,
            }
            if (formik.values.password) payload.password = formik.values.password

            let response = null
            if (editId !== "") response = await dispatch(updatePegawai(editId, payload))
            else response = await dispatch(createPegawai(payload))

            if (response.error === null) {
                Swal.fire({ icon: 'success', title: 'Berhasil', showConfirmButton: false, timer: 1500 })
                setOpenModal(false); getDataTable()
            } else {
                Swal.fire({ icon: 'error', title: "Gagal menyimpan", showConfirmButton: false, timer: 1500 })
            }
        } else {
            Swal.fire({ icon: 'warning', title: "periksa kembali form isian anda", showConfirmButton: false, timer: 1500 })
        }
    }

    const editAction = async (data) => {
        const Api = (await import('@/api')).default
        const api = new Api()
        const r = await api.getPegawai(data.id)
        const d = r.error === null ? (r.data?.data || r.data) : data

        formik.resetForm()
        formik.setFieldValue('nip', d.nip || '')
        formik.setFieldValue('nama', d.nama || '')
        formik.setFieldValue('gelar_depan', d.gelar_depan || '')
        formik.setFieldValue('gelar_belakang', d.gelar_belakang || '')
        formik.setFieldValue('tempat_lahir', d.tempat_lahir || '')
        formik.setFieldValue('tanggal_lahir', d.tanggal_lahir || '')
        formik.setFieldValue('jenis_kelamin', d.jenis_kelamin || '')
        formik.setFieldValue('alamat', d.alamat || '')
        formik.setFieldValue('no_hp', d.no_hp || '')
        formik.setFieldValue('email', d.email || '')
        formik.setFieldValue('master_opd_id', d.master_opd_id || '')
        formik.setFieldValue('sub_opd_nm', d.sub_opd_nm || '')
        formik.setFieldValue('ref_eselon_id', d.ref_eselon_id || '')
        formik.setFieldValue('ref_golongan_id', d.ref_golongan_id || '')
        formik.setFieldValue('ref_jenis_jabatan_id', d.ref_jenis_jabatan_id || '')
        formik.setFieldValue('ref_jabatan_id', d.ref_jabatan_id || '')
        formik.setFieldValue('jenjang', d.jenjang || '')
        formik.setFieldValue('isActive', d.is_active ?? true)
        const rids = d.role_ids || (d.role_id ? [d.role_id] : [])
        formik.setFieldValue('role_ids', rids)
        setSelectedRoles(rids.map(rid => {
            const r = refRoles.find(rr => rr.id === rid)
            return r ? { label: r.name, value: r.id } : null
        }).filter(Boolean))
        if (d.role_ids && d.role_ids.length > 0) {
            // refresh roles list just in case
            const Api2 = (await import('@/api')).default
            const api2 = new Api2()
            const rr = await api2.getRefRoles()
            if (rr.data) {
                setRefRoles(rr.data.data || [])
                setSelectedRoles(d.role_ids.map(rid => {
                    const role = (rr.data.data || []).find(rr => rr.id === rid)
                    return role ? { label: role.name, value: role.id } : null
                }).filter(Boolean))
            }
        }

        if (d.ref_jenis_jabatan_id) loadJabatan(d.ref_jenis_jabatan_id)

        setEditId(d.id)
        setFormTitle("FORM EDIT DATA PEGAWAI")
        setOpenModal(true)
    }

    const deleteAction = (id) => {
        Swal.fire({
            title: 'Hapus data ini?', text: "data yang sudah dihapus tidak dapat dikembalikan!",
            icon: 'warning', showCancelButton: true, confirmButtonColor: '#3085d6', cancelButtonColor: '#d33', confirmButtonText: 'Yes!'
        }).then(async (result) => {
            if (result.isConfirmed) {
                const response = await dispatch(deletePegawai(id))
                if (response.error === null) { Swal.fire({ icon: 'success', title: 'Berhasil', showConfirmButton: false, timer: 1500 }); getDataTable() }
                else { Swal.fire({ icon: 'error', title: "something went wrong", showConfirmButton: false, timer: 1500 }) }
            }
        })
    }

    return (
        <Layout>
            <div className="bg-white h-auto dark:bg-gray-800 rounded-lg drop-shadow-xl py-1 px-3 w-full">
                <div className="dark:text-white flex w-full justify-between">
                    <div className="flex flex-row items-center gap-3">
                        <div><img src={GoodNotes} alt="Data Master" className="object-contain" /></div>
                        <div className="lg:text-lg font-bold text-teal-500 dark:text-white">Data Master Pegawai</div>
                    </div>
                </div>
            </div>
            <div className="bg-white h-auto dark:bg-gray-800 rounded-lg drop-shadow-xl py-5 px-4 w-full flex min-h-[35rem]">
                <div className="flex flex-col w-full p-3 border border-teal-500/30 dark:border-gray-600 rounded-lg">
                    <div className="w-full flex">
                        <PrimaryBtn loading={pegawaiState.loading} onClick={() => openModalAction()}>
                            <PlusCircleIcon className="w-5 h-5" /> Tambah Data Pegawai
                        </PrimaryBtn>
                    </div>

                    <TableSection getDataAction={getDataTable} pagination={pegawaiState.pagination}>
                        <MyTable>
                            <TableHeader>
                                <tr>
                                    <th scope="col" className="px-4 py-3 w-[3%]">No.</th>
                                    <th scope="col" className="px-4 py-3">NIP</th>
                                    <th scope="col" className="px-4 py-3">Nama</th>
                                    <th scope="col" className="px-4 py-3">OPD</th>
                                    <th scope="col" className="px-4 py-3">Golongan</th>
                                    <th scope="col" className="px-4 py-3">Jabatan</th>
                                    <th scope="col" className="px-4 py-3 w-[10%]">Active</th>
                                    <th scope="col" className="px-4 py-3 w-[10%]"><span className="sr-only">Actions</span></th>
                                </tr>
                            </TableHeader>
                            <TableBody>
                                {pegawaiState.loading ? (
                                    <tr className="border-b dark:border-gray-700">
                                        <td scope="row" className="px-4 py-5 bg-gray-100 dark:bg-gray-800 dark:text-white" colSpan="100%">
                                            <div className="flex flex-row justify-center w-full gap-12">
                                                <PacmanLoader size={10} color='gray' /> Please Wait...
                                            </div>
                                        </td>
                                    </tr>
                                ) : (pegawaiState.list.length > 0 ? (
                                    pegawaiState.list.map((item, key) => (
                                        <tr key={item.id} className="border-b dark:border-gray-700">
                                            <th scope="row" className="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">{key + 1}</th>
                                            <td className="px-4 py-3">{item.nip || '-'}</td>
                                            <td className="px-4 py-3">{item.nama || '-'}</td>
                                            <td className="px-4 py-3">{item.master_opd?.nama_opd || '-'}</td>
                                            <td className="px-4 py-3">{item.ref_golongan?.golongan || '-'}</td>
                                            <td className="px-4 py-3">{item.ref_jabatan?.nama || '-'}</td>
                                            <td className="px-4 py-3">{item.is_active ? "Aktif" : "Non-Aktif"}</td>
                                            <td className="px-4 py-3 flex items-center justify-end">
                                                <button id={`btn-peg-${key}`} data-dropdown-toggle={`toggle-peg-${key}`}
                                                    className="inline-flex items-center p-0.5 text-sm font-medium text-center text-gray-500 hover:text-gray-800 rounded-lg focus:outline-none dark:text-gray-400 dark:hover:text-gray-100" type="button">
                                                    <svg className="w-5 h-5" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM16 12a2 2 0 100-4 2 2 0 000 4z" />
                                                    </svg>
                                                </button>
                                                <div id={`toggle-peg-${key}`}
                                                    className="hidden z-10 w-44 bg-gray-50 rounded divide-y divide-gray-100 drop-shadow-lg dark:bg-gray-700 dark:divide-gray-600">
                                                    <ul className="py-1 text-sm text-gray-700 dark:text-gray-200" aria-labelledby={`btn-peg-${key}`}>
                                                        <li>
                                                            <a href="#" onClick={() => editAction(item)}
                                                                className="flex gap-1 py-2 px-4 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">
                                                                <PencilSquareIcon className='w-5 h-5' /> Edit
                                                            </a>
                                                        </li>
                                                    </ul>
                                                    <div className="py-1">
                                                        <a href="#" onClick={() => deleteAction(item.id)}
                                                            className="flex gap-1 py-2 px-4 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">
                                                            <TrashIcon className='w-5 h-5' /> Hapus
                                                        </a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    ))
                                ) : (
                                    <tr className="border-b dark:border-gray-700">
                                        <td scope="row" className="px-4 py-3 text-center" colSpan="100%">No Data</td>
                                    </tr>
                                ))}
                            </TableBody>
                        </MyTable>
                    </TableSection>

                    <MyModal ModalTitle={formTitle} openModal={openModal} setOpenModal={setOpenModal}>
                        <div className="flex flex-col w-full p-4 gap-3" style={{ maxHeight: '70vh', overflowY: 'auto' }}>
                            <MyInput id="nip" name="nip" label="NIP *" type="text" placeholder="19800101 200604 1 001"
                                value={formik.values.nip} onChange={formik.handleChange} onBlur={formik.handleBlur}
                                error={(formik.errors.nip && formik.touched.nip) ? formik.errors.nip : ""} />
                            <MyInput id="nama" name="nama" label="Nama *" type="text" placeholder="Nama lengkap"
                                value={formik.values.nama} onChange={formik.handleChange} onBlur={formik.handleBlur}
                                error={(formik.errors.nama && formik.touched.nama) ? formik.errors.nama : ""} />
                            <MyInput id="password" name="password" label={editId ? "Password (kosongkan jika tidak diubah)" : "Password *"} type="password"
                                value={formik.values.password} onChange={formik.handleChange} onBlur={formik.handleBlur}
                                error={(formik.errors.password && formik.touched.password) ? formik.errors.password : ""} />
                            <div className="grid grid-cols-2 gap-3">
                                <MyInput id="gelar_depan" name="gelar_depan" label="Gelar Depan" type="text" placeholder="Dr."
                                    value={formik.values.gelar_depan} onChange={formik.handleChange} />
                                <MyInput id="gelar_belakang" name="gelar_belakang" label="Gelar Belakang" type="text" placeholder="S.E., M.M."
                                    value={formik.values.gelar_belakang} onChange={formik.handleChange} />
                            </div>
                            <MyInput id="tempat_lahir" name="tempat_lahir" label="Tempat Lahir" type="text"
                                value={formik.values.tempat_lahir} onChange={formik.handleChange} />
                            <MyInput id="tanggal_lahir" name="tanggal_lahir" label="Tanggal Lahir" type="date"
                                value={formik.values.tanggal_lahir} onChange={formik.handleChange} />
                            <div className="flex flex-col gap-1">
                                <label className="text-sm font-medium text-gray-900 dark:text-white">Jenis Kelamin</label>
                                <select name="jenis_kelamin" value={formik.values.jenis_kelamin} onChange={formik.handleChange}
                                    className="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                    <option value="">- Pilih -</option>
                                    <option value="L">Laki-laki</option>
                                    <option value="P">Perempuan</option>
                                </select>
                            </div>
                            <div className="flex flex-col gap-1">
                                <label className="text-sm font-medium text-gray-900 dark:text-white">OPD</label>
                                <select name="master_opd_id" value={formik.values.master_opd_id} onChange={formik.handleChange}
                                    className="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                    <option value="">- Pilih OPD -</option>
                                    {opdList.map(o => <option key={o.id} value={o.id}>{o.nama_opd}</option>)}
                                </select>
                            </div>
                            <MyInput id="sub_opd_nm" name="sub_opd_nm" label="Sub OPD / Unit Kerja" type="text"
                                value={formik.values.sub_opd_nm} onChange={formik.handleChange} />
                            <div className="flex flex-col gap-1">
                                <label className="text-sm font-medium text-gray-900 dark:text-white">Eselon</label>
                                <select name="ref_eselon_id" value={formik.values.ref_eselon_id} onChange={formik.handleChange}
                                    className="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                    <option value="">- Pilih Eselon -</option>
                                    {refEselon.map(e => <option key={e.id} value={e.id}>{e.nama}</option>)}
                                </select>
                            </div>
                            <div className="flex flex-col gap-1">
                                <label className="text-sm font-medium text-gray-900 dark:text-white">Golongan / Pangkat</label>
                                <select name="ref_golongan_id" value={formik.values.ref_golongan_id} onChange={formik.handleChange}
                                    className="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                    <option value="">- Pilih Golongan -</option>
                                    {refGolongan.map(g => <option key={g.id} value={g.id}>{g.golongan} — {g.pangkat}</option>)}
                                </select>
                            </div>
                            <div className="flex flex-col gap-1">
                                <label className="text-sm font-medium text-gray-900 dark:text-white">Jenis Jabatan</label>
                                <select name="ref_jenis_jabatan_id" value={formik.values.ref_jenis_jabatan_id}
                                    onChange={(e) => { formik.handleChange(e); loadJabatan(e.target.value) }}
                                    className="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                    <option value="">- Pilih Jenis Jabatan -</option>
                                    {refJenisJabatan.map(j => <option key={j.id} value={j.id}>{j.nama}</option>)}
                                </select>
                            </div>
                            <div className="flex flex-col gap-1">
                                <label className="text-sm font-medium text-gray-900 dark:text-white">Jabatan</label>
                                <select name="ref_jabatan_id" value={formik.values.ref_jabatan_id} onChange={formik.handleChange}
                                    className="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                    <option value="">- Pilih Jabatan -</option>
                                    {refJabatan.map(j => <option key={j.id} value={j.id}>{j.nama}</option>)}
                                </select>
                            </div>
                            <MyInput id="jenjang" name="jenjang" label="Jenjang" type="text" placeholder="S1 / S2 / S3"
                                value={formik.values.jenjang} onChange={formik.handleChange} />
                            <MyInput id="no_hp" name="no_hp" label="No HP" type="text"
                                value={formik.values.no_hp} onChange={formik.handleChange} />
                            <MyInput id="email" name="email" label="Email" type="email"
                                value={formik.values.email} onChange={formik.handleChange} />
                            <div className="flex flex-col gap-1">
                                <MyMultiSelect id="role_ids" label="Role"
                                    options={refRoles.map(r => ({ label: r.name, value: r.id }))}
                                    value={selectedRoles}
                                    onChange={(val) => {
                                        setSelectedRoles(val)
                                        formik.setFieldValue('role_ids', val.map(v => v.value))
                                    }} />
                            </div>
                            <div className="flex w-fill justify-center">
                                <MyToggle id="isActive" name="isActive" label="Aktif" value={formik.values.isActive}
                                    error={formik.errors.isActive} onChange={formik.handleChange} />
                            </div>
                        </div>
                        <div className="mt-5 sm:mt-6 flex justify-center">
                            <PrimaryBtn onClick={() => simpanData()} loading={pegawaiState.loading}>Simpan Data</PrimaryBtn>
                        </div>
                    </MyModal>
                </div>
            </div>
        </Layout>
    )
}

export default Pegawai
