import { PresentationIcon, MydocumentIcon, CalcIcon } from "../Icons"

const MenuAdminKdh = [
    {
        name: "Data Master",
        url: "",
        icon: MydocumentIcon,
        sub: [
            {
                name: "Satuan",
                url: "/datamaster/satuan"
            },
            {
                name: "OPD",
                url: "/datamaster/opd"
            },
            {
                name: "Pegawai",
                url: "/datamaster/pegawai"
            }
        ]
    },
    {
        name: "Perencanaan",
        url: "/perencanaan/kdh",
        icon: PresentationIcon
    },
    {
        name: "Pengukuran",
        url: "/pengukuran/kdh/realisasirenaksi",
        icon: CalcIcon
    },
    {
        name: "Pelaporan",
        url: "",
        icon: MydocumentIcon,
        sub: [
            {
                name: "Data Kinerja",
                url: "/pelaporan/kdh/kinerja"
            },
            {
                name: "Capaian Kinerja",
                url: "/pelaporan/kdh/capaiankinerja"
            },
            {
                name: "Analisis Kinerja",
                url: "/pelaporan/kdh/analisiskinerja"
            },
            {
                name: "Efisiensi Anggaran",
                url: "/pelaporan/kdh/efisiensianggaran"
            }
        ]
    },
    {
        name: "Monitoring",
        url: "/monitoring/opd",
        icon: PresentationIcon
    },
]

export default MenuAdminKdh