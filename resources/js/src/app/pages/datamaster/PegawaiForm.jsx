import Layout from '@/app/components/Layout/Layout'
import React from 'react'
import PrimaryBtn from '@/app/components/Button/PrimaryBtn'
import { useSelector, useDispatch } from 'react-redux'
import { useNavigate, useParams } from 'react-router-dom'
import { createPegawai, getPegawai, updatePegawai } from '@/redux/ducks/datamasterpegawai/action'
import Swal from 'sweetalert2'
import { useFormik } from 'formik'
import * as Yup from "yup"

const PegawaiForm = () => {
    const dispatch = useDispatch()
    const navigate = useNavigate()
    const { id } = useParams()
    const isEdit = Boolean(id)
    const pegawaiState = useSelector((state) => state.datamasterPegawaiState)

    const [refEselon, setRefEselon] = React.useState([])
    const [refGolongan, setRefGolongan] = React.useState([])
    const [refJenisJabatan, setRefJenisJabatan] = React.useState([])
    const [refJabatan, setRefJabatan] = React.useState([])
    const [opdList, setOpdList] = React.useState([])
    const [loading, setLoading] = React.useState(false)

    React.useEffect(() => {
        const loadRefs = async () => {
            const Api = (await import('@/api')).default
            const api = new Api()
            const [e, g, jj, opds] = await Promise.all([
                api.getRefEselon(), api.getRefGolongan(), api.getRefJenisJabatan(),
                api.getList_dmOpd({ page: 1, per_page: 999, search: '' })
            ])
            if (e.data) setRefEselon(e.data.data || [])
            if (g.data) setRefGolongan(g.data.data || [])
            if (jj.data) setRefJenisJabatan(jj.data.data || [])
            if (opds.data && opds.error === null) setOpdList(opds.data.data || [])
        }
        loadRefs()

        if (isEdit) {
            dispatch(getPegawai(id))
        }
    }, [])

    React.useEffect(() => {
        if (isEdit && pegawaiState.data) {
            const d = pegawaiState.data
            formik.setValues({
                nip: d.nip || '',
                nama: d.nama || '',
                password: '',
                gelar_depan: d.gelar_depan || '',
                gelar_belakang: d.gelar_belakang || '',
                tempat_lahir: d.tempat_lahir || '',
                tanggal_lahir: d.tanggal_lahir || '',
                jenis_kelamin: d.jenis_kelamin || '',
                alamat: d.alamat || '',
                no_hp: d.no_hp || '',
                email: d.email || '',
                master_opd_id: d.master_opd_id || '',
                ref_eselon_id: d.ref_eselon_id || '',
                ref_golongan_id: d.ref_golongan_id || '',
                ref_jenis_jabatan_id: d.ref_jenis_jabatan_id || '',
                ref_jabatan_id: d.ref_jabatan_id || '',
                jenjang: d.jenjang || '',
                sub_opd_nm: d.sub_opd_nm || '',
            })
        }
    }, [pegawaiState.data])

    const formik = useFormik({
        initialValues: {
            nip: '', nama: '', password: '', gelar_depan: '', gelar_belakang: '',
            tempat_lahir: '', tanggal_lahir: '', jenis_kelamin: '', alamat: '', no_hp: '',
            email: '', master_opd_id: '', ref_eselon_id: '', ref_golongan_id: '',
            ref_jenis_jabatan_id: '', ref_jabatan_id: '', jenjang: '', sub_opd_nm: '',
        },
        validationSchema: Yup.object({
            nip: Yup.string().required('NIP wajib diisi'),
            nama: Yup.string().required('Nama wajib diisi'),
            password: isEdit ? Yup.string() : Yup.string().min(6, 'Minimal 6 karakter').required('Password wajib diisi'),
        }),
        enableReinitialize: true,
        onSubmit: async (values) => {
            setLoading(true)
            const payload = { ...values }
            if (isEdit && !payload.password) delete payload.password

            const response = isEdit
                ? await dispatch(updatePegawai(id, payload))
                : await dispatch(createPegawai(payload))

            setLoading(false)
            if (response.error === null) {
                Swal.fire('Berhasil', isEdit ? 'Pegawai diperbarui' : 'Pegawai ditambahkan', 'success')
                navigate('/datamaster/pegawai')
            } else {
                Swal.fire('Gagal', response.error || 'Terjadi kesalahan', 'error')
            }
        }
    })

    const loadJabatan = async (jenisId) => {
        const Api = (await import('@/api')).default
        const api = new Api()
        const r = await api.getRefJabatan({ jenis_id: jenisId })
        if (r.data) setRefJabatan(r.data.data || [])
    }

    return (
        <Layout>
            <div className="p-4 max-w-3xl mx-auto">
                <h2 className="text-xl font-bold dark:text-white mb-4">
                    {isEdit ? 'Edit Pegawai' : 'Tambah Pegawai'}
                </h2>

                <form onSubmit={formik.handleSubmit} className="space-y-4">
                    <div className="grid grid-cols-2 gap-4">
                        <div>
                            <label className="block text-sm font-medium dark:text-white">NIP *</label>
                            <input type="text" name="nip" onChange={formik.handleChange} value={formik.values.nip}
                                className="w-full border rounded p-2 dark:bg-gray-700 dark:text-white" />
                            {formik.errors.nip && <span className="text-red-500 text-xs">{formik.errors.nip}</span>}
                        </div>
                        <div>
                            <label className="block text-sm font-medium dark:text-white">Nama *</label>
                            <input type="text" name="nama" onChange={formik.handleChange} value={formik.values.nama}
                                className="w-full border rounded p-2 dark:bg-gray-700 dark:text-white" />
                            {formik.errors.nama && <span className="text-red-500 text-xs">{formik.errors.nama}</span>}
                        </div>
                        <div>
                            <label className="block text-sm font-medium dark:text-white">Password {!isEdit && '*'}</label>
                            <input type="password" name="password" onChange={formik.handleChange} value={formik.values.password}
                                className="w-full border rounded p-2 dark:bg-gray-700 dark:text-white"
                                placeholder={isEdit ? 'Kosongkan jika tidak diubah' : ''} />
                            {formik.errors.password && <span className="text-red-500 text-xs">{formik.errors.password}</span>}
                        </div>
                        <div>
                            <label className="block text-sm font-medium dark:text-white">Jenis Kelamin</label>
                            <select name="jenis_kelamin" onChange={formik.handleChange} value={formik.values.jenis_kelamin}
                                className="w-full border rounded p-2 dark:bg-gray-700 dark:text-white">
                                <option value="">- Pilih -</option>
                                <option value="L">Laki-laki</option>
                                <option value="P">Perempuan</option>
                            </select>
                        </div>
                        <div>
                            <label className="block text-sm font-medium dark:text-white">OPD</label>
                            <select name="master_opd_id" onChange={formik.handleChange} value={formik.values.master_opd_id}
                                className="w-full border rounded p-2 dark:bg-gray-700 dark:text-white">
                                <option value="">- Pilih OPD -</option>
                                {opdList.map(o => (
                                    <option key={o.id} value={o.id}>{o.nama_opd}</option>
                                ))}
                            </select>
                        </div>
                        <div>
                            <label className="block text-sm font-medium dark:text-white">Sub OPD</label>
                            <input type="text" name="sub_opd_nm" onChange={formik.handleChange} value={formik.values.sub_opd_nm}
                                className="w-full border rounded p-2 dark:bg-gray-700 dark:text-white" />
                        </div>
                        <div>
                            <label className="block text-sm font-medium dark:text-white">Eselon</label>
                            <select name="ref_eselon_id" onChange={formik.handleChange} value={formik.values.ref_eselon_id}
                                className="w-full border rounded p-2 dark:bg-gray-700 dark:text-white">
                                <option value="">- Pilih -</option>
                                {refEselon.map(e => <option key={e.id} value={e.id}>{e.nama}</option>)}
                            </select>
                        </div>
                        <div>
                            <label className="block text-sm font-medium dark:text-white">Golongan</label>
                            <select name="ref_golongan_id" onChange={formik.handleChange} value={formik.values.ref_golongan_id}
                                className="w-full border rounded p-2 dark:bg-gray-700 dark:text-white">
                                <option value="">- Pilih -</option>
                                {refGolongan.map(g => <option key={g.id} value={g.id}>{g.golongan} - {g.pangkat}</option>)}
                            </select>
                        </div>
                        <div>
                            <label className="block text-sm font-medium dark:text-white">Jenis Jabatan</label>
                            <select name="ref_jenis_jabatan_id" onChange={(e) => { formik.handleChange(e); loadJabatan(e.target.value) }} value={formik.values.ref_jenis_jabatan_id}
                                className="w-full border rounded p-2 dark:bg-gray-700 dark:text-white">
                                <option value="">- Pilih -</option>
                                {refJenisJabatan.map(j => <option key={j.id} value={j.id}>{j.nama}</option>)}
                            </select>
                        </div>
                        <div>
                            <label className="block text-sm font-medium dark:text-white">Jabatan</label>
                            <select name="ref_jabatan_id" onChange={formik.handleChange} value={formik.values.ref_jabatan_id}
                                className="w-full border rounded p-2 dark:bg-gray-700 dark:text-white">
                                <option value="">- Pilih -</option>
                                {refJabatan.map(j => <option key={j.id} value={j.id}>{j.nama}</option>)}
                            </select>
                        </div>
                        <div>
                            <label className="block text-sm font-medium dark:text-white">Tempat Lahir</label>
                            <input type="text" name="tempat_lahir" onChange={formik.handleChange} value={formik.values.tempat_lahir}
                                className="w-full border rounded p-2 dark:bg-gray-700 dark:text-white" />
                        </div>
                        <div>
                            <label className="block text-sm font-medium dark:text-white">Tanggal Lahir</label>
                            <input type="date" name="tanggal_lahir" onChange={formik.handleChange} value={formik.values.tanggal_lahir}
                                className="w-full border rounded p-2 dark:bg-gray-700 dark:text-white" />
                        </div>
                        <div>
                            <label className="block text-sm font-medium dark:text-white">No HP</label>
                            <input type="text" name="no_hp" onChange={formik.handleChange} value={formik.values.no_hp}
                                className="w-full border rounded p-2 dark:bg-gray-700 dark:text-white" />
                        </div>
                        <div>
                            <label className="block text-sm font-medium dark:text-white">Email</label>
                            <input type="email" name="email" onChange={formik.handleChange} value={formik.values.email}
                                className="w-full border rounded p-2 dark:bg-gray-700 dark:text-white" />
                        </div>
                    </div>

                    <div className="flex gap-4 justify-end pt-4">
                        <button type="button" onClick={() => navigate('/datamaster/pegawai')}
                            className="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400 dark:bg-gray-600 dark:text-white">
                            Batal
                        </button>
                        <PrimaryBtn type="submit" disabled={loading}>
                            {loading ? 'Menyimpan...' : isEdit ? 'Update' : 'Simpan'}
                        </PrimaryBtn>
                    </div>
                </form>
            </div>
        </Layout>
    )
}

export default PegawaiForm
