import React from 'react'
import { initFlowbite } from 'flowbite'
import LoadingOverlay from "react-loading-overlay"
import PohonKinerjaIcon from '@assets/public_assets/pohon-kinerja-icon.png'
import RencanaKinerjaTahunanIcon from '@assets/public_assets/rencana-kinerja-tahunan-icon.png'
import PerjanjianKinerjaIcon from '@assets/public_assets/perjanjian-kinerja-icon.png'
import RencanaAksiIcon from '@assets/public_assets/rencana-aksi-icon.png'
import RealisasiRencanaAksiIcon from '@assets/public_assets/realisasi-rencana-aksi-icon.png'
import { Link } from 'react-router-dom'
import PublicNavbar from './PublicNavbar'
import PublicFooter from './PublicFooter'

const PublicLayout = ({ children, loading = false, }) => {
    React.useEffect(() => {
        initFlowbite()
    },[])
    const kinerjaMenuItems = [
        {
            icon: PohonKinerjaIcon,
            title: 'Pohon Kinerja',
            url: '#'
        },
        {
            icon: RencanaKinerjaTahunanIcon,
            title: 'Rencana Strategis',
            url: '#'
        },
        {
            icon: PerjanjianKinerjaIcon,
            title: 'Perjanjian Kinerja',
            url: '#'
        },
        {
            icon: RencanaAksiIcon,
            title: 'Rencana Aksi',
            url: '#'
        },
        {
            icon: RealisasiRencanaAksiIcon,
            title: 'Realisasi Rencana Aksi',
            url: '#'
        },
    ]
    return (
        <LoadingOverlay
            spinner
            active={loading}
            text="Mohon menunggu..."
        >
            <div className="w-screen min-h-screen font-poppins bg-gray-100 dark:bg-gray-700 flex flex-col">
                <PublicNavbar />

                    <div className="w-full flex flex-col pt-[4.6rem]">
                        { children }
                    </div>
                <PublicFooter />
            </div>
        </LoadingOverlay>
    )
}

export default PublicLayout