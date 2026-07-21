import React from 'react'
import brandLogo from '@assets/public_assets/brand_logo.png'
import PohonKinerjaIcon from '@assets/public_assets/pohon-kinerja-icon.png'
import RencanaKinerjaTahunanIcon from '@assets/public_assets/rencana-kinerja-tahunan-icon.png'
import PerjanjianKinerjaIcon from '@assets/public_assets/perjanjian-kinerja-icon.png'
import RencanaAksiIcon from '@assets/public_assets/rencana-aksi-icon.png'
import RealisasiRencanaAksiIcon from '@assets/public_assets/realisasi-rencana-aksi-icon.png'
import { Link } from 'react-router-dom'

const PublicNavbar = () => {
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
    const kinerjaOpdMenuItems = [
        {
            icon: PohonKinerjaIcon,
            title: 'Pohon Kinerja',
            url: '/opd/pohonkinerja'
        },
        {
            icon: RencanaKinerjaTahunanIcon,
            title: 'Rencana Strategis',
            url: '/opd/renstra'
        },
        {
            icon: PerjanjianKinerjaIcon,
            title: 'Perjanjian Kinerja',
            url: '/opd/pk'
        },
        {
            icon: RencanaAksiIcon,
            title: 'Rencana Aksi',
            url: '/opd/renaksi'
        },
        {
            icon: RealisasiRencanaAksiIcon,
            title: 'Realisasi Rencana Aksi',
            url: '/opd/realisasirenaksi'
        },
    ]
    return (
        <nav className="bg-white fixed w-full z-20 top-0 start-0 border-b drop-shadow-lg">
            <div className="max-w-screen-2xl flex flex-wrap items-center justify-between mx-auto p-4">
                <a href="/" className="flex items-center space-x-3 rtl:space-x-reverse">
                    <img src={brandLogo} className="h-10 object-contain" alt="Sakinah Logo" />
                </a>
                <button data-collapse-toggle="navbar-dropdown" type="button" className="inline-flex items-center p-2 w-10 h-10 justify-center text-sm text-body rounded-base md:hidden hover:bg-neutral-secondary-soft hover:text-heading focus:outline-none focus:ring-2 focus:ring-neutral-tertiary" aria-controls="navbar-dropdown" aria-expanded="false">
                    <span className="sr-only">Open main menu</span>
                    <svg className="w-6 h-6" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" strokeLinecap="round" strokeWidth="2" d="M5 7h14M5 12h14M5 17h14"/></svg>
                </button>
                <div className="hidden w-full md:block md:w-auto" id="navbar-dropdown">
                    <ul className="flex flex-col font-medium p-4 md:p-0 mt-4 border border-default rounded-base bg-neutral-secondary-soft md:space-x-8 rtl:space-x-reverse md:flex-row md:mt-0 md:border-0 md:bg-neutral-primary">
                        <li>
                        <a href="/" className="block md:mt-1.5 py-4 px-3 hover:text-primaryWebColor font-bold bg-brand rounded md:bg-transparent md:text-fg-brand md:p-0" aria-current="page">Beranda</a>
                        </li>
                        <li>
                            <button id="dropdownNvbarButton" data-dropdown-toggle="dropdownNavbar" className="flex items-center justify-between w-full md:mt-1.5 py-4 px-3 rounded font-bold text-heading md:w-auto hover:bg-neutral-tertiary md:hover:bg-transparent md:border-0 md:hover:text-fg-brand md:p-0 hover:text-primaryWebColor">
                            Kinerja Gubernur 
                            <svg className="w-4 h-4 ms-1.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="m19 9-7 7-7-7"/></svg>
                        </button>
                        <div id="dropdownNavbar" className="z-10 hidden bg-white border border-default-medium rounded-base shadow-lg w-44">
                            <ul className="p-2 text-sm text-body font-medium" aria-labelledby="dropdownNvbarButton">
                            {
                                kinerjaMenuItems.map((item, key) => (
                                <li>
                                    <Link to={item.url} className="inline-flex items-center w-full p-2 hover:bg-primaryWebColor hover:text-white hover:text-heading rounded">{item.title}</Link>
                                </li>
                                ))
                            }
                            </ul>
                        </div>
                        </li>
                        <li>
                            <button id="dropdownNvbarButton2" data-dropdown-toggle="dropdownNavbar2" className="flex items-center justify-between w-full md:mt-1.5 py-4 px-3 rounded font-bold text-heading md:w-auto hover:bg-neutral-tertiary md:hover:bg-transparent md:border-0 md:hover:text-fg-brand md:p-0 hover:text-primaryWebColor">
                                Kinerja Perangkat Daerah 
                                <svg className="w-4 h-4 ms-1.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="m19 9-7 7-7-7"/></svg>
                            </button>
                            <div id="dropdownNavbar2" className="z-10 hidden bg-white border border-default-medium rounded-base shadow-lg w-44">
                                <ul className="p-2 text-sm text-body font-medium" aria-labelledby="dropdownNvbarButton2">
                                {
                                    kinerjaOpdMenuItems.map((item, key) => (
                                    <li>
                                        <Link to={item.url} className="inline-flex items-center w-full p-2 hover:bg-primaryWebColor hover:text-white hover:text-heading rounded">{item.title}</Link>
                                    </li>
                                    ))
                                }
                                </ul>
                            </div>
                        </li>
                        <li>
                            <Link to="/authorization" className="rounded-md bg-primaryWebColor dark:bg-indigo-800 px-5 py-2 text-sm font-semibold text-white shadow-sm 
                                focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primaryWebColor 
                                dark:focus-visible:outline-indigo-900 flex items-center gap-1 hover:bg-indigo-500 dark:hover:bg-indigo-900 ">
                                Masuk
                            </Link>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    )
}

export default PublicNavbar