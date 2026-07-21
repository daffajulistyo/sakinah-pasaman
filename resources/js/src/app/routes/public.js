import {
    PageLoadingAuth,
    Login, LoginPegawai,
    Frontpage, PohonKinerjaPemda, RencanaKinerjaPemda, RpjmdPemda,
    PerjanjianKinerjaPemda, RencanaAksiPemda, RealisasiRenaksiPemda,

    PublicPohonKinerjaOpd,
    PublicRencanaKinerjaOpd,
    PublicRenstraOpd,
    PublicPerjanjianKinerjaOpd,
    PublicRencanaAksiOpd,
    PublicRealisasiRenaksiOpd
} from "@pages"

const routes = [
    {
        key: 'login-sakip',
        name: 'login-sakip',
        Component: Login,
        path: '/admin',
    },
    {
        key: 'login-pegawai-sakip',
        name: 'login-pegawai-sakip',
        Component: LoginPegawai,
        path: '/pegawai',
    },
    {
        key: 'loading-auth',
        name: 'loading-auth',
        Component: PageLoadingAuth,
        path: '/authenticating',
    },
    {
        key: 'frontpage',
        name: 'frontpage',
        Component: Frontpage,
        path: '/',
    },
    {
        key: 'public-pohon-kinerja-pemda',
        name: 'public-pohon-kinerja-pemda',
        Component: PohonKinerjaPemda,
        path: '/pemda/pohonkinerja',
    },
    {
        key: 'public-rencana-kinerja-pemda',
        name: 'public-rencana-kinerja-pemda',
        Component: RencanaKinerjaPemda,
        path: '/pemda/rencanakinerja',
    },
    {
        key: 'public-perjanjian-kinerja-pemda',
        name: 'public-perjanjian-kinerja-pemda',
        Component: PerjanjianKinerjaPemda,
        path: '/pemda/pk',
    },
    {
        key: 'public-rencana-aksi-pemda',
        name: 'public-rencana-aksi-pemda',
        Component: RencanaAksiPemda,
        path: '/pemda/renaksi',
    },
    {
        key: 'public-rpjmd-pemda',
        name: 'public-rpjmd-pemda',
        Component: RpjmdPemda,
        path: '/pemda/rpjmd',
    },
    {
        key: 'public-realisasi-rencana-aksi-pemda',
        name: 'public-realisasi-rencana-aksi-pemda',
        Component: RealisasiRenaksiPemda,
        path: '/pemda/realisasirenaksi',
    },
    {
        key: 'public-pohon-kinerja-opd',
        name: 'public-pohon-kinerja-opd',
        Component: PublicPohonKinerjaOpd,
        path: '/opd/pohonkinerja',
    },
    {
        key: 'public-rencana-kinerja-opd',
        name: 'public-rencana-kinerja-opd',
        Component: PublicRencanaKinerjaOpd,
        path: '/opd/rencanakinerja',
    },
    {
        key: 'public-renstra-opd',
        name: 'public-renstra-opd',
        Component: PublicRenstraOpd,
        path: '/opd/renstra',
    },
    {
        key: 'public-perjanjian-kinerja-opd',
        name: 'public-perjanjian-kinerja-opd',
        Component: PublicPerjanjianKinerjaOpd,
        path: '/opd/pk',
    },
    {
        key: 'public-rencana-aksi-opd',
        name: 'public-rencana-aksi-opd',
        Component: PublicRencanaAksiOpd,
        path: '/opd/renaksi',
    },
    {
        key: 'public-realisasi-rencana-aksi-opd',
        name: 'public-realisasi-rencana-aksi-opd',
        Component: PublicRealisasiRenaksiOpd,
        path: '/opd/realisasirenaksi',
    }
]

export default routes