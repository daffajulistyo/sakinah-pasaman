import React from 'react'
import FooterLogo from '@assets/public_assets/footer-logo.png'
import PseBadge from '@assets/pse_badge.png'
import { Link } from 'react-router-dom'

const PublicFooter = () => {
    const kinerjaMenuItems = [
        {
            title: 'Pohon Kinerja',
            url: '#'
        },
        {
            title: 'Rencana Strategis',
            url: '#'
        },
        {
            title: 'Perjanjian Kinerja',
            url: '#'
        },
        {
            title: 'Rencana Aksi',
            url: '#'
        },
        {
            title: 'Realisasi Rencana Aksi',
            url: '#'
        },
    ]
    return (
        <div className="w-full static bottom-0 left-0 flex flex-col">
            <div className="bg-primaryWebColor static bottom-0 left-0 right-0 py-4 flex justify-center items-center min-h-64">
                <div className="max-w-screen-2xl mx-auto w-full h-full grid md:grid-cols-4 sm:grid-cols-2 grid-cols-1 md:gap-4 gap-2 justify-center items-center">
                    <div className="flex flex-col justify-center items-center gap-4">
                        <img src={FooterLogo} alt="Icon" className="object-cover mdd:w-64 w-40" />
                        <img src={PseBadge} alt="Icon" className="object-cover mdd:w-64" />
                    </div>
                    <div className="flex flex-col gap-2 justify-center px-4">
                        <h3 className="text-white md:text-sm text-xs font-bold underline md:mb-2">Kinerja</h3>
                        <ul className="flex flex-col md:gap-2">
                            {
                                kinerjaMenuItems.map((item, index) => (
                                    <li key={index}>
                                        <Link to={item.url} className="text-white hover:font-bold md:text-sm text-xs">{item.title}</Link>
                                    </li>
                                ))
                            }
                        </ul>
                    </div>
                    <div className="flex flex-col gap-2 justify-center px-4">
                        <h3 className="text-white md:text-sm text-xs font-bold underline md:mb-2">Kontak</h3>
                        <ul className="flex flex-col md:gap-2">
                            <li>
                                <p className="text-white md:text-sm text-xs">biroorganisasi.sumbarprov.go.id</p>
                            </li>
                            <li>
                                <p className="text-white md:text-sm text-xs">Kode Pos 25129</p>
                            </li>
                            <li>
                                <p className="text-white md:text-sm text-xs">Jalan Jenderal Sudirman No. 51, Kota Padang, Sumatera Barat</p>
                            </li>
                                
                        </ul>
                    </div>
                    <div className="flex flex-col gap-2 justify-center px-4">
                        <h3 className="text-white md:text-sm text-xs font-bold underline mb-2">Sosial</h3>
                        <div className="flex gap-4">
                            <a href="#" className="p-1 rounded-full cursor-pointer hover:bg-white text-white hover:text-primaryWebColor">
                                <svg class="w-10 h-10" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                <path fill-rule="evenodd" d="M13.135 6H15V3h-1.865a4.147 4.147 0 0 0-4.142 4.142V9H7v3h2v9.938h3V12h2.021l.592-3H12V6.591A.6.6 0 0 1 12.592 6h.543Z" clip-rule="evenodd"/>
                                </svg>
                            </a>
                            <a href="#" className="p-1 rounded-full cursor-pointer hover:bg-white text-white hover:text-primaryWebColor">
                                <svg class="w-10 h-10" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                <path fill="currentColor" fill-rule="evenodd" d="M3 8a5 5 0 0 1 5-5h8a5 5 0 0 1 5 5v8a5 5 0 0 1-5 5H8a5 5 0 0 1-5-5V8Zm5-3a3 3 0 0 0-3 3v8a3 3 0 0 0 3 3h8a3 3 0 0 0 3-3V8a3 3 0 0 0-3-3H8Zm7.597 2.214a1 1 0 0 1 1-1h.01a1 1 0 1 1 0 2h-.01a1 1 0 0 1-1-1ZM12 9a3 3 0 1 0 0 6 3 3 0 0 0 0-6Zm-5 3a5 5 0 1 1 10 0 5 5 0 0 1-10 0Z" clip-rule="evenodd"/>
                                </svg>

                            </a>
                            <a href="#" className="p-1 rounded-full cursor-pointer hover:bg-white text-white hover:text-primaryWebColor">
                                <svg class="w-10 h-10" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                <path fill-rule="evenodd" d="M21.7 8.037a4.26 4.26 0 0 0-.789-1.964 2.84 2.84 0 0 0-1.984-.839c-2.767-.2-6.926-.2-6.926-.2s-4.157 0-6.928.2a2.836 2.836 0 0 0-1.983.839 4.225 4.225 0 0 0-.79 1.965 30.146 30.146 0 0 0-.2 3.206v1.5a30.12 30.12 0 0 0 .2 3.206c.094.712.364 1.39.784 1.972.604.536 1.38.837 2.187.848 1.583.151 6.731.2 6.731.2s4.161 0 6.928-.2a2.844 2.844 0 0 0 1.985-.84 4.27 4.27 0 0 0 .787-1.965 30.12 30.12 0 0 0 .2-3.206v-1.516a30.672 30.672 0 0 0-.202-3.206Zm-11.692 6.554v-5.62l5.4 2.819-5.4 2.801Z" clip-rule="evenodd"/>
                                </svg>

                            </a>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    )
}

export default PublicFooter