<?php

namespace App\Http\Controllers\Api\v1\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

use App\Models\Sakip\KDH\PohonKinerjaVisi;
use App\Models\Sakip\KDH\PohonKinerjaMisi;
use App\Models\Sakip\KDH\PohonKinerjaTujuan;
use App\Models\Sakip\KDH\PohonKinerjaSasaran;
use App\Models\Sakip\KDH\PohonKinerjaIndikator;
use App\Models\Sakip\MASTER\MasterOpd;
use App\Models\Sakip\MASTER\MasterSatuan;
use Illuminate\Support\Facades\DB;



class BaseController extends Controller
{
    public static function getCurrentVisi()
    {    
          $visi = PohonKinerjaVisi::where('is_active',  '=', 'true')->first();

          return $visi;                
    }

    public static function getMisiPemda()
    {    
          $pohon_kinerja = new BaseController;
          $dv = $pohon_kinerja->getCurrentVisi();
          $id_visi = $dv->id;

           $misi = PohonKinerjaMisi::where('pohon_kinerja_visi_id',  '=', $id_visi)
                                    ->where('is_active',  '=', 'true')     
                                    ->pluck('id')
                                    ->toArray();
          return $misi;                
    }

    public static function getTujuanPemda()
    {    
          $pohon_kinerja = new BaseController;
          $misi = $pohon_kinerja->getMisiPemda();

          $tujuan = PohonKinerjaTujuan::whereIn('pohon_kinerja_misi_id', $misi)
                                    ->where('is_active',  '=', 'true')     
                                    ->pluck('id')
                                    ->toArray();
          return $tujuan;                
    }

    public static function getTujuanOPD($master_opd_id)
   { 
     $pohon_kinerja = new BaseController;
     $dv = $pohon_kinerja->getCurrentVisi();
     $id_visi = !empty($dv->id) ? $dv->id : '';


     $tujuan = DB::table('tujuan_opd')
            ->where('pohon_kinerja_visi_id', '=', $id_visi)
            ->where('master_opd_id', '=', $master_opd_id)
            ->where('deleted_at', NULL)
            ->select('id')
            ->pluck('id')
            ->toArray();

     return $tujuan;
   }

   public static function getIndikatorOPD($master_opd_id)
   { 
     $pohon_kinerja = new BaseController;
     $tujuan_opd = $pohon_kinerja->getTujuanOPD($master_opd_id);


     $indikator_opd = DB::table('indikator_opd')
            ->where('tujuan_opd_id', '=', $tujuan_opd)
             ->where('deleted_at', NULL)
            ->select('id')
            ->pluck('id')
            ->toArray();

     return $indikator_opd;
   }


    public static function getSasaranByOPDPengampu($master_opd_id)
   {    
        $opd = MasterOpd::find($master_opd_id);

        if(!Str::isUuid($master_opd_id) OR !$opd){
            return "OPD Tidak Sesuai";
        }
        else
        {
           $pohon_kinerja = new BaseController;
           $dv = $pohon_kinerja->getCurrentVisi();
           $id_visi = !empty($dv) ? $dv->id : '';

           $misi = collect($pohon_kinerja->misi($id_visi));
           $tujuan = $pohon_kinerja->tujuan($misi);
           $sasaran = $pohon_kinerja->sasaranByOPDPendukung($tujuan, $master_opd_id);
           return $sasaran;
        }
        
   }

   public  function misi($id_visi)
   {
        $misi = PohonKinerjaMisi::where('pohon_kinerja_visi_id', '=', $id_visi)->get(['id']);

        return $misi;
   }

   public  function tujuan($misi)
   {
        $tujuan = PohonKinerjaTujuan::whereIn('pohon_kinerja_misi_id',  $misi)->get(['id']);

        return $tujuan;
   }

   public  function sasaranByOPDPendukung($tujuan, $master_opd_id)
   {
        $sasaran = PohonKinerjaSasaran::join('opd_pendukung_indikator', 'opd_pendukung_indikator.pohon_kinerja_sasaran_id' ,'=', 'pohon_kinerja_sasaran.id')
                ->where('opd_pendukung_indikator.master_opd_id', '=', $master_opd_id)
                 ->whereIn('pohon_kinerja_tujuan_id',  $tujuan)
                 ->distinct()
                 ->get(['pohon_kinerja_sasaran.id', 'sasaran', 'order', 'master_opd_id']);

        return $sasaran;
   }

    public static function getSatuanByID($satuan_id)
     {    
           if(!Str::isUuid($satuan_id)){
               return "";
           }

           $satuan = MasterSatuan::where('id',  '=', $satuan_id)
                                   ->where('is_active',  '=', 'true')->first();
 
           return !empty($satuan) ? $satuan->satuan : '';                
     }
}
