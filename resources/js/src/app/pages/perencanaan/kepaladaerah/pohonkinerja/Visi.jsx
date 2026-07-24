import React from 'react'
import Layout from '@/app/components/Layout/Layout'
import TabMenuPohonKinerja from '@/app/components/TabMenuPohonKinerja'
import HeaderPohonKinerja from '@/app/components/HeaderPohonKinerja'
import { PlusCircleIcon } from '@heroicons/react/24/outline'
import MyTextarea from '@/app/components/Form/MyTextarea'
import MyModal from '@/app/components/Form/MyModal'
import MySelect from '@/app/components/Form/MySelect'
import MyToggle from '@/app/components/Form/MyToggle'
import TableVisi from './TableVisi'
import { useSelector } from 'react-redux'
import { useDispatch } from 'react-redux'
import { getListVisiKDH, createVisiKdh, updateVisiKdh, deleteVisiKdh } from '@ducks/visikdh/action'
import PrimaryBtn from '@/app/components/Button/PrimaryBtn'
import { useFormik } from 'formik'
import * as Yup from "yup"
import Swal from 'sweetalert2'


const Visi = () => {
    const visiKdhState = useSelector((state) => state.visiKdhState)
    const dispatch = useDispatch()
    const [openModal, setOpenModal] = React.useState(false)
    const [formTitle, setFormTitle] = React.useState("FORM TAMBAH VISI")
    const [editId, setEditId] = React.useState("")
    const formik = useFormik({
        initialValues: {
            periodeVisi: undefined,
            visi: undefined,
            isActive: false
        },
        validationSchema: Yup.object({
            periodeVisi:    Yup.string().required("Silakan Pilih Tahun Periode Visi").min(3, "Silakan Pilih Tahun Periode Visi"), 
            visi:           Yup.string().required().strict(true),
            isActive:       Yup.boolean().required()
        }),
        enableReinitialize: true
    })
    const visiOptions = () => {
        const options = []
        const d = new Date()
        let year = d.getFullYear()
        let startpoint = parseInt(year) - 4
        let endpoint = startpoint + 10
        for(let x=startpoint; x<=endpoint; x++){ options.push({ value: x.toString(), name: x.toString() }) } 
        
        return options 
    }

    const getDataTable = async (page = 1, per_page = 10, search = "") => {
        const response = await dispatch(getListVisiKDH({ page, per_page, search }))
        if(response.error !== null){
            Swal.fire({
                icon: 'error',
                title: "something went wrong",
                showConfirmButton: false,
                timer: 1500
            })
        }
    }

    React.useEffect(() => {
        getDataTable()
        
    },[])
    
    const validationForm = async () => {
        //validation
        formik.setFieldTouched('periodeVisi', true, true)
        formik.setFieldTouched('visi', true, true)
        const errors = await formik.validateForm();

        return errors
    }

    const simpanVisi= async ()=> {
        
        const errors = await validationForm()
        if (Object.keys(errors).length === 0) {
            // Form is valid, do any success call, mapping payload before submit
            const form = formik.values
            const payload = {
                period_starts: form.periodeVisi,
                period_ends: (parseInt(form.periodeVisi) + 5).toString(),
                visi: form.visi,
                is_active: form.isActive
            }

            // submit payload with dispatch action redux
            let response = null
            if(editId !== "") response = await dispatch(updateVisiKdh(editId, payload))
            else response = await dispatch(createVisiKdh(payload));
            if(response.error === null){
                Swal.fire({
                    icon: 'success',
                    title: response.data.message,
                    showConfirmButton: false,
                    timer: 1500
                })
            
                setOpenModal(false)
                dispatch(getListVisiKDH())
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
        } else {
            // formik.setTouched(setNestedObjectValues<FormikTouched<FormValues>>(errors, true));
            Swal.fire({
                icon: 'warning',
                title: "periksa kembali form isian anda",
                showConfirmButton: false,
                timer: 1500
            })
            
        }
    }

    const openModalAction = () => {
        formik.resetForm();
        setEditId("")
        setFormTitle("FORM TAMBAH VISI")
        setOpenModal(true);
    }

    const editThisData = (data) => {
        
        formik.resetForm()
        formik.setFieldValue('visi', data.visi);
        formik.setFieldValue('periodeVisi', data.period_starts.toString())
        formik.setFieldValue('isActive', data.is_active)

        setEditId(data.id)
        
        setFormTitle("FORM EDIT VISI")
        setOpenModal(true);
    }

    const confirmDelete = (id) => {
        Swal.fire({
        title: 'Hapus data ini?',
        text: "data yang sudah dihapus tidak dapat dikembalikan!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes!'
        }).then(async (result) => {
            if (result.isConfirmed) {
                const response = await dispatch(deleteVisiKdh(id))
                
                if(response.error === null){
                    Swal.fire({
                        icon: 'success',
                        title: response.data.message,
                        showConfirmButton: false,
                        timer: 1500
                    })
                    dispatch(getListVisiKDH())
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
        })
    }

    return (
        <Layout>
            <HeaderPohonKinerja />

            <div className="bg-white h-auto dark:bg-gray-800 rounded-lg drop-shadow-xl py-5 px-4 w-full flex min-h-[35rem]">

                <TabMenuPohonKinerja active='visi' />
                <div className="flex flex-col w-full mt-14 p-3 border border-teal-500/30 dark:border-gray-600 rounded-lg">
                    <div className="w-full text-center text-lg text-teal-500 dark:text-white font-bold mb-3">VISI KEPALA DAERAH</div>
                    <div className="w-full flex">
                        <PrimaryBtn loading={visiKdhState.loading} 
                            onClick={()=> openModalAction()}>
                            <PlusCircleIcon className="w-5 h-5" />
                            Tambah Visi
                        </PrimaryBtn>
                    </div>
                    <TableVisi 
                        data={visiKdhState.list} 
                        loading={visiKdhState.loading} 
                        pagination={visiKdhState.pagination} 
                        getData={getDataTable}
                        editAction={editThisData}
                        deleteAction={confirmDelete}
                    />
                    <MyModal ModalTitle={formTitle} openModal={openModal} setOpenModal={setOpenModal}>
                        <div className="flex flex-col w-full p-4">
                            <MySelect 
                                id="periodeVisi" name="periodeVisi" label="Periode Visi" placeholder='Pilih periode tahun awal'
                                value={formik.values.periodeVisi} onChange={formik.handleChange} onBlur={formik.handleBlur}
                                error={(formik.errors.periodeVisi && formik.touched.periodeVisi) ? formik.errors.periodeVisi : ""} 
                                options={visiOptions()} 
                            />
                            <MyTextarea id="visi" name="visi" label="Visi" placeholder='Inputkan visi...' 
                                value={formik.values.visi} onChange={formik.handleChange} onBlur={formik.handleBlur}
                                error={(formik.errors.visi && formik.touched.visi) ? formik.errors.visi : ""}
                            />
                            <div className="flex w-fill justify-center">
                                <MyToggle id="isActive" name="isActive" label="Aktif" value={formik.values.isActive}
                                    error={formik.errors.isActive}
                                    onChange={formik.handleChange} />
                            </div>
                        </div>
                        <div className="mt-5 sm:mt-6 flex justify-center">
                            <PrimaryBtn onClick={()=> simpanVisi()} loading={visiKdhState.loading} >
                                Simpan Data
                            </PrimaryBtn>
                        </div>
                    </MyModal>





                </div>

            </div>
        </Layout>
    )
}

export default Visi