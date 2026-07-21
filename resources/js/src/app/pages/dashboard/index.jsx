import React from "react"
import Layout from "@components/Layout/Layout"
import { useSelector } from "react-redux"

const Dashboard = () => {
    
    const authState = useSelector((state) => state.authState)
    return (
        <Layout>
            <div className="bg-white h-auto dark:bg-gray-800 rounded-lg drop-shadow-xl p-3 w-full">
                <div className="text-gray-900 dark:text-white text-lg text-center font-semibold my-10">
                    <span className="text-3xl font-bold">Aplikasi SAKINAH</span> <br />
                    <span className="text-md italic font-light">Sistem Akuntabilitas Kinerja Instansi Pemerintah</span> <br />
                    Selamat Datang, {authState.biodata.name} <br />
                    Anda Login sebagai {authState.biodata.level}
                </div>
            </div>
            <div className="bg-white h-auto dark:bg-gray-800 rounded-lg drop-shadow-xl p-3 w-full">
                <div className="text-gray-900 dark:text-white p-4">
                SAKINAH (Sistem Akuntabilitas Kinerja Instansi Pemerintah) adalah sistem manajemen kinerja yang diterapkan di instansi pemerintah untuk memastikan kinerja yang terukur dan akuntabel. SAKINAH merupakan rangkaian sistematis dari aktivitas, alat, dan prosedur yang dirancang untuk perencanaan, pengukuran, pengumpulan data, pelaporan kinerja, dan pertanggungjawaban. 
                </div>
                <div className="text-gray-900 dark:text-white p-4">
                Secara lebih detail, SAKINAH mencakup:
                    <ul className="list-disc list-inside leading-5">
                        <li className="py-2">
                            <span className="font-bold">Perencanaan:</span>&nbsp;
                            Penetapan tujuan, sasaran, dan indikator kinerja yang jelas.
                        </li>
                        <li className="py-2">
                            <span className="font-bold">Pengukuran:</span>&nbsp;
                            Pengumpulan dan analisis data kinerja untuk mengukur pencapaian tujuan.
                        </li>
                        <li className="py-2">
                            <span className="font-bold">Pelaporan:</span>&nbsp;
                            Penyusunan laporan kinerja yang menggambarkan pencapaian tujuan dan indikator kinerja.
                        </li>
                        <li className="py-2">
                            <span className="font-bold">Evaluasi:</span>&nbsp;
                            Peningkatan kinerja dan akuntabilitas terhadap masyarakat. 
                        </li>
                    </ul>
                </div>
            </div>
        </Layout>
    )
}

export default Dashboard