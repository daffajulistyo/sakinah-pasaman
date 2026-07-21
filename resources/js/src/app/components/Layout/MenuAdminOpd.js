import { PresentationIcon, CalcIcon, MydocumentIcon } from "../Icons"


const MenuAdminOpd = 
        [
            {
                name: "Perencanaan",
                url: "/perencanaan/opd",
                icon: PresentationIcon,
                external: false
            },
            {
                name: "Pengukuran",
                url: "/pengukuran/opd/realisasirenaksi",
                icon: CalcIcon,
                external: false
            },
            {
                name: "Pelaporan",
                url: "",
                icon: MydocumentIcon,
                sub: [
                    {
                        name: "Data Kinerja",
                        url: "/pelaporan/opd/kinerja",
                        external: false
                    },
                    {
                        name: "Capaian Kinerja",
                        url: "/pelaporan/opd/capaiankinerja",
                        external: false
                    },
                    {
                        name: "Analisis Kinerja",
                        url: "/pelaporan/opd/analisiskinerja",
                        external: false
                    },
                    {
                        name: "Efisiensi Anggaran",
                        url: "/pelaporan/opd/efisiensianggaran",
                        external: false
                    }
                ]
            }
        ]


export default MenuAdminOpd