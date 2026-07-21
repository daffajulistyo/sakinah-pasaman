import React from 'react'
import { initFlowbite } from 'flowbite'
import BannerWeb from '@assets/public_assets/banner-web.png'
import PohonKinerjaIcon from '@assets/public_assets/pohon-kinerja-icon.png'
import RencanaKinerjaTahunanIcon from '@assets/public_assets/rencana-kinerja-tahunan-icon.png'
import PerjanjianKinerjaIcon from '@assets/public_assets/perjanjian-kinerja-icon.png'
import RencanaAksiIcon from '@assets/public_assets/rencana-aksi-icon.png'
import RealisasiRencanaAksiIcon from '@assets/public_assets/realisasi-rencana-aksi-icon.png'
import { Link } from 'react-router-dom'
import SecondaryBanner from '@assets/public_assets/secondary-banner.png'
import PublicLayout from '@/app/components/PublicLayout'

const Frontpage = () => {
    React.useEffect(() => {
        initFlowbite()
    },[])
    const kinerjaMenuItems = [
        {
            icon: PohonKinerjaIcon,
            title: 'Pohon Kinerja',
            url: '/pemda/pohonkinerja'
        },
        {
            icon: RencanaKinerjaTahunanIcon,
            title: 'RPJMD',
            url: '/pemda/rpjmd'
        },
        {
            icon: PerjanjianKinerjaIcon,
            title: 'Perjanjian Kinerja',
            url: '/pemda/pk'
        },
        {
            icon: RencanaAksiIcon,
            title: 'Rencana Aksi',
            url: '/pemda/renaksi'
        },
        {
            icon: RealisasiRencanaAksiIcon,
            title: 'Realisasi Rencana Aksi',
            url: '/pemda/realisasirenaksi'
        },
    ]
    return (
        <PublicLayout loading={false}>
                {/* Main Banner  */}
                <div className="mx-auto w-full h-full flex justify-center items-center">
                    <img src={BannerWeb} alt="Banner Web" className="w-full h-full object-cover" />
                </div>
                {/* Kinerja Menu  */}
                <div className="bg-white mx-auto px-4 w-full h-full flex flex-col md:py-24 py-10 justify-center items-center">
                    <div className="w-full h-full flex flex-col justify-center items-center mb-10">
                        <h1 className="md:text-4xl sm:text-2xl text-xl text-primaryWebColor font-bold mb-4">Kinerja</h1>
                        <p className="text-xs md:text-base text-center">Pengendalian kinerja dalam rangka meningkatkan akuntabilitas dan kinerja unit kerja</p>
                    </div>
                    <div className="max-w-screen-xl mx-auto w-full h-full grid lg:grid-cols-5 md:grid-cols-3 grid-cols-2 md:gap-8 gap-4 justify-center items-center">
                        {
                            kinerjaMenuItems.map((item, index) => (
                                <Link to={item.url} key={index} className="bg-white shadow-lg border border-default-medium py-14 md:h-52 h-40 rounded-lg flex flex-col gap-2 justify-center hover:bg-gray-200 hover:text-white hover:text-heading hover:border-primaryWebColor   transition-all duration-300 cursor-pointer">
                                    <img src={item.icon} alt="Icon" className="md:w-28 w-14 mx-auto object-cover" />
                                    <h3 className="md:text-md sm:text-sm text-xs font-bold text-center text-primaryWebColor mx-12">{item.title}</h3>
                                </Link>
                            ))
                        }
                    </div>
                </div>
                {/* Secondary Banner  */}
                <div className="w-full h-full bg-[#EFF9FF] flex justify-center items-center">
                    <img src={SecondaryBanner} alt="Secondary Banner" className="max-w-screen-xl w-full h-full object-cover" />
                </div>
        </PublicLayout>
    )
}

export default Frontpage