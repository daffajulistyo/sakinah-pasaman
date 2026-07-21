import Dashboard from "./dashboard";
import Login from "./login";
import PageLoadingAuth from "./error/PageLoadingAuth";
import Authorization from "./login/Authorization";
import Kdh from "./perencanaan/kepaladaerah/Kdh";
import PohonKinerja from "./perencanaan/kepaladaerah/pohonkinerja/PohonKinerja";
import Visi from "./perencanaan/kepaladaerah/pohonkinerja/Visi";
import Misi from "./perencanaan/kepaladaerah/pohonkinerja/Misi";
import Tujuan from "./perencanaan/kepaladaerah/pohonkinerja/Tujuan";
import Sasaran from "./perencanaan/kepaladaerah/pohonkinerja/Sasaran";
import Indikator from "./perencanaan/kepaladaerah/pohonkinerja/Indikator";
import Satuan from "./datamaster/Satuan";
import Opd from "./datamaster/Opd";
import Pegawai from "./datamaster/Pegawai";
import PohonKinerjaView from "./perencanaan/kepaladaerah/pohonkinerja/PohonKinerjaView";
import CascadingKdh from "./perencanaan/kepaladaerah/cascading/CascadingKdh";
import AddCascadingKdh from "./perencanaan/kepaladaerah/cascading/AddCascadingKdh";
import TargetRpjmd from "./perencanaan/kepaladaerah/rpjmd/TargetRpjmd";
import IndikatorKinerjaUtama from "./perencanaan/kepaladaerah/iku/IndikatorKinerjaUtama";
import IkuEdit from "./perencanaan/kepaladaerah/iku/IkuEdit";
import RkpdDashboard from "./perencanaan/kepaladaerah/rkpd/RkpdDashboard";
import RkpdForm from "./perencanaan/kepaladaerah/rkpd/RkpdForm";
import RkpdProgramAnggaran from "./perencanaan/kepaladaerah/rkpd/RkpdProgramAnggaran";
import PkDashboard from "./perencanaan/kepaladaerah/pk/PkDashboard";
import PkForm from "./perencanaan/kepaladaerah/pk/PkForm";
import PkProgramAnggaran from "./perencanaan/kepaladaerah/pk/PkProgramAnggaran";
import RaPage from "./perencanaan/kepaladaerah/rencanaaksi/RaPage";
import LangkahRa from "./perencanaan/kepaladaerah/rencanaaksi/LangkahRa";
import PerangkatDaerah from "./perencanaan/perangkatdaerah/Opd";
import TujuanOpd from "./perencanaan/perangkatdaerah/pohonkinerja/TujuanOpd";
import SasaranOpd from "./perencanaan/perangkatdaerah/pohonkinerja/SasaranOpd";
import IndikatorOpd from "./perencanaan/perangkatdaerah/pohonkinerja/IndikatorOpd";
import CascadingOpd from "./perencanaan/perangkatdaerah/cascading/CascadingOpd";
import AddCascadingOpd from "./perencanaan/perangkatdaerah/cascading/AddCascadingOpd";
import TargetRenstra from "./perencanaan/perangkatdaerah/renstra/TargetRenstra";
import IkuOpd from "./perencanaan/perangkatdaerah/iku/IkuOpd";
import IkuOpdEdit from "./perencanaan/perangkatdaerah/iku/IkuOpdEdit";
import RenjaDashboard from "./perencanaan/perangkatdaerah/renja/RenjaDashboard";
import RenjaForm from "./perencanaan/perangkatdaerah/renja/RenjaForm";
import RenjaProgramAnggaran from "./perencanaan/perangkatdaerah/renja/RenjaProgramAnggaran";
import PkOpdDashboard from "./perencanaan/perangkatdaerah/pk/PkOpdDashboard";
import PkOpdForm from "./perencanaan/perangkatdaerah/pk/PkOpdForm";
import PkOpdProgramAnggaran from "./perencanaan/perangkatdaerah/pk/PkOpdProgramAnggaran";
import RaOpdPage from "./perencanaan/perangkatdaerah/rencanaaksi/RaOpdPage";
import LangkahRaOpd from "./perencanaan/perangkatdaerah/rencanaaksi/LangkahRaOpd";
import PohonKinerjaOpdView from "./perencanaan/perangkatdaerah/pohonkinerja/PohonKinerjaOpdView";
import RealisasiRenaksiKdh from "./pengukuran/kepaladaerah/RealisasiRenaksiKdh";
import LangkahRealisasiRenaksiKdh from "./pengukuran/kepaladaerah/LangkahRealisasiRenaksiKdh";
import RealisasiRenaksiOpd from "./pengukuran/opd/RealisasiRenaksiOpd";
import LangkahRealisasiRenaksiOpd from "./pengukuran/opd/LangkahRealisasiRenaksiOpd";
import LoginPegawai from "./login/LoginPegawai";
import PkPegawaiDashboard from "./pegawai/pk/PkPegawaiDashboard";
import PkPegawaiForm from "./pegawai/pk/PkPegawaiForm";
import PkPegawaiProgramAnggaran from "./pegawai/pk/PkPegawaiProgramAnggaran";
import RaPegawaiPage from "./pegawai/ra/RaPegawaiPage";
import LangkahRaPegawai from "./pegawai/ra/LangkahRaPegawai";
import RealisasiRenaksiPegawai from "./pegawai/realisasirenaksi/RealisasiRenaksiPegawai";
import LangkahRealisasiRenaksiPegawai from "./pegawai/realisasirenaksi/LangkahRealisasiRenaksiPegawai";
import DataKinerjaKdh from "./pelaporan/kepaladaerah/DataKinerjaKdh";
import CapaianKinerjaKdh from "./pelaporan/kepaladaerah/CapaianKinerjaKdh";
import AnalisisKinerjaKdh from "./pelaporan/kepaladaerah/AnalisisKinerjaKdh";
import EfisiensiAnggaranKdh from "./pelaporan/kepaladaerah/EfisiensiAnggaranKdh";
import DataKinerjaOpd from "./pelaporan/opd/DataKinerjaOpd";
import CapaianKinerjaOpd from "./pelaporan/opd/CapaianKinerjaOpd";
import AnalisisKinerjaOpd from "./pelaporan/opd/AnalisisKinerjaOpd";
import EfisiensiAnggaranOpd from "./pelaporan/opd/EfisiensiAnggaranOpd";
import SkpList from "./pegawai/skp/SkpList";
import SkpTarget from "./pegawai/skp/SkpTarget";
import SkpDetail from "./pegawai/skp/SkpDetail";
import SkpRenaksi from "./pegawai/skp/SkpRenaksi";
import Profile from "./accounts/Profile";
import SkpDetailRealisasi from "./pegawai/skp/SkpDetailRealisasi";
import SkpRenaksiRealisasi from "./pegawai/skp/SkpRenaksiRealisasi";
import DashboardOpd from "./monitoring/opd/DashboardOpd";
import PohonKinerjaOpd from "./monitoring/opd/PohonKinerjaOpd";

import SasaranOperasionalOpd from "./perencanaan/perangkatdaerah/sasaran_operasional/SasaranOperasionalOpd";
import IndikatorOperasionalOpd from "./perencanaan/perangkatdaerah/sasaran_operasional/IndikatorOperasionalOpd";

import MonitorPohonKinerjaOpdView from "./monitoring/opd/MonitorPohonKinerjaOpd";
import MonitorCascadingOpd from "./monitoring/opd/MonitorCascadingOpd";
import MonitorRenstraOpd from "./monitoring/opd/MonitorRenstraOpd";
import MonitorIkuOpd from "./monitoring/opd/MonitorIkuOpd";
import MonitorRenjaOpd from "./monitoring/opd/MonitorRenjaOpd";
import MonitorPkOpd from "./monitoring/opd/MonitorPkOpd";
import MonitorRencanaAksiOpd from "./monitoring/opd/MonitorRencanaAksiOpd";
import MonitorRealisasiRenaksiOpd from "./monitoring/opd/MonitorRealisasiRenaksiOpd";
import MonitorDataKinerjaOpd from "./monitoring/opd/MonitorDataKinerjaOpd";
import MonitorCapaianKinerjaOpd from "./monitoring/opd/MonitorCapaianKinerjaOpd";




import Frontpage from "./public/Frontpage";
import PohonKinerjaPemda from "./public/pemda/PohonKinerjaPemda";
import RencanaKinerjaPemda from "./public/pemda/RencanaKinerjaPemda";
import RpjmdPemda from "./public/pemda/RpjmdPemda";
import PerjanjianKinerjaPemda from "./public/pemda/PerjanjianKinerjaPemda";
import RencanaAksiPemda from "./public/pemda/RencanaAksiPemda";
import RealisasiRenaksiPemda from "./public/pemda/RealisasiRenaksiPemda";

import PublicPohonKinerjaOpd from "./public/opd/PohonKinerjaOpd";
import PublicRencanaKinerjaOpd from "./public/opd/RencanaKinerjaOpd";
import PublicRenstraOpd from "./public/opd/RenstraOpd";
import PublicPerjanjianKinerjaOpd from "./public/opd/PerjanjianKinerjaOpd";
import PublicRencanaAksiOpd from "./public/opd/RencanaAksiOpd";
import PublicRealisasiRenaksiOpd from "./public/opd/RealisasiRenaksiOpd";


export {
    Dashboard,
    Login, LoginPegawai,
    PageLoadingAuth, 
    Authorization,
    Satuan, Opd,
    Pegawai,
    Kdh,
    PohonKinerja, PohonKinerjaView,
    Visi, Misi, Tujuan, Sasaran, Indikator,
    CascadingKdh, AddCascadingKdh,
    TargetRpjmd, IndikatorKinerjaUtama, IkuEdit,
    RkpdDashboard, RkpdForm, RkpdProgramAnggaran,
    PkDashboard, PkForm, PkProgramAnggaran,
    RaPage, LangkahRa,

    PerangkatDaerah,
    TujuanOpd, SasaranOpd, IndikatorOpd,
    CascadingOpd, AddCascadingOpd,
    TargetRenstra,
    IkuOpd, IkuOpdEdit,
    RenjaDashboard, RenjaForm, RenjaProgramAnggaran,
    PkOpdDashboard, PkOpdForm, PkOpdProgramAnggaran,
    RaOpdPage, LangkahRaOpd,
    PohonKinerjaOpdView,

    SasaranOperasionalOpd,
    IndikatorOperasionalOpd,

    RealisasiRenaksiKdh,
    LangkahRealisasiRenaksiKdh,

    RealisasiRenaksiOpd,
    LangkahRealisasiRenaksiOpd,

    PkPegawaiDashboard, PkPegawaiForm, PkPegawaiProgramAnggaran,

    RaPegawaiPage, LangkahRaPegawai,

    RealisasiRenaksiPegawai, LangkahRealisasiRenaksiPegawai,

    DataKinerjaKdh, CapaianKinerjaKdh, AnalisisKinerjaKdh, EfisiensiAnggaranKdh,

    DataKinerjaOpd, CapaianKinerjaOpd, AnalisisKinerjaOpd, EfisiensiAnggaranOpd,

    SkpList, SkpTarget, SkpDetail, SkpRenaksi, SkpDetailRealisasi, SkpRenaksiRealisasi,

    Profile,

    
    DashboardOpd, 
    PohonKinerjaOpd,
    MonitorPohonKinerjaOpdView,
    MonitorCascadingOpd,
    MonitorRenstraOpd,
    MonitorIkuOpd,
    MonitorRenjaOpd,
    MonitorPkOpd,
    MonitorRencanaAksiOpd,
    MonitorRealisasiRenaksiOpd,
    MonitorDataKinerjaOpd,
    MonitorCapaianKinerjaOpd,





    Frontpage,
    PohonKinerjaPemda,
    RencanaKinerjaPemda,
    RpjmdPemda,
    PerjanjianKinerjaPemda,
    RencanaAksiPemda,
    RealisasiRenaksiPemda,

    PublicPohonKinerjaOpd,
    PublicRencanaKinerjaOpd,
    PublicRenstraOpd,
    PublicPerjanjianKinerjaOpd, 
    PublicRencanaAksiOpd,
    PublicRealisasiRenaksiOpd
}