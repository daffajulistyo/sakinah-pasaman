<?php

namespace App\Http\Controllers\Api\v1\MASTER;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
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
     public static function getSatuanByID($satuan_id)
     {    
           if(!Str::isUuid($satuan_id)){
               return "";
           }

           $satuan = MasterSatuan::where('id',  '=', $satuan_id)
                                   ->where('is_active', true)->first();
 
           return !empty($satuan) ? $satuan->satuan : '';                
     }


    public static function getCurrentVisi()
    {    
          $visi = PohonKinerjaVisi::where('is_active', true)->first();

          return $visi;                
    }

    public static function getMisiPemda()
    {    
          $pohon_kinerja = new BaseController;
          $dv = $pohon_kinerja->visi();
          $id_visi = !empty($dv[0]) ? $dv[0]['id'] : '';

           $misi = PohonKinerjaMisi::where('pohon_kinerja_visi_id',  '=', $id_visi)
                                    ->where('is_active', true)
                                    ->pluck('id')
                                    ->toArray();
          return $misi;                
    }

    public static function getTujuanPemda()
    {    
          $pohon_kinerja = new BaseController;
          $misi = $pohon_kinerja->getMisiPemda();

          $tujuan = PohonKinerjaTujuan::whereIn('pohon_kinerja_misi_id', $misi)
                                    ->where('is_active', true)     
                                    ->pluck('id')
                                    ->toArray();
          return $tujuan;                
    }

    public static function getIndikatorPemda()
    {    
          $pohon_kinerja = new BaseController;
          $tujuan = $pohon_kinerja->getTujuanPemda();

          $indikator = PohonKinerjaIndikator::whereIn('pohon_kinerja_tujuan_id', $tujuan)
                                    ->where('is_active', true)     
                                    ->pluck('id')
                                    ->toArray();
          return $indikator;                
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
           $dv = $pohon_kinerja->visi();
           $id_visi = !empty($dv[0]) ? $dv[0]['id'] : '';

           $misi = collect($pohon_kinerja->misi($id_visi));
           $tujuan = $pohon_kinerja->tujuan($misi);
           $sasaran = $pohon_kinerja->sasaranByOPDPendukung($tujuan, $master_opd_id);
           return $sasaran;
        }
        
   }

   public static function visi()
   {
        $visi = PohonKinerjaVisi::where('is_active', true)
                ->limit(1) 
                ->get();

        return $visi;
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

   /*-----------------------------------------Get Visi By Sasaran ID --------------------*/
   public static function getVisiBySasaranID($sasaran_id)
   {

     $visi = DB::table('pohon_kinerja_sasaran')
            ->where('pohon_kinerja_sasaran.id', '=', $sasaran_id)
             ->where('pohon_kinerja_sasaran.deleted_at', NULL)
            ->join('pohon_kinerja_tujuan', 'pohon_kinerja_tujuan.id', '=', 'pohon_kinerja_sasaran.pohon_kinerja_tujuan_id')
            ->join('pohon_kinerja_misi', 'pohon_kinerja_misi.id', '=', 'pohon_kinerja_tujuan.pohon_kinerja_misi_id')
            ->join('pohon_kinerja_visi', 'pohon_kinerja_visi.id', '=', 'pohon_kinerja_misi.pohon_kinerja_visi_id')
            ->select('pohon_kinerja_sasaran.id', 'pohon_kinerja_sasaran.sasaran', 'pohon_kinerja_visi.id as visi_id', 'pohon_kinerja_visi.visi')
            ->limit(1)
            ->get();

     return $visi;
   }

   public static function getVisiByTujuanOpdID($tujuan_opd_id)
   {

     $visi = DB::table('tujuan_opd')
            ->where('tujuan_opd.id', '=', $tujuan_opd_id)
            ->where('tujuan_opd.deleted_at', NULL)
            ->join('pohon_kinerja_sasaran', 'pohon_kinerja_sasaran.id', '=', 'tujuan_opd.pohon_kinerja_sasaran_id')
            ->join('pohon_kinerja_tujuan', 'pohon_kinerja_tujuan.id', '=', 'pohon_kinerja_sasaran.pohon_kinerja_tujuan_id')
            ->join('pohon_kinerja_misi', 'pohon_kinerja_misi.id', '=', 'pohon_kinerja_tujuan.pohon_kinerja_misi_id')
            ->join('pohon_kinerja_visi', 'pohon_kinerja_visi.id', '=', 'pohon_kinerja_misi.pohon_kinerja_visi_id')
            ->select('pohon_kinerja_sasaran.id', 'pohon_kinerja_sasaran.sasaran', 'pohon_kinerja_visi.id as visi_id', 'pohon_kinerja_visi.visi')
            ->limit(1)
            ->get();

     return $visi;
   }


   public static function getTujuanOPD($master_opd_id)
   { 
     $pohon_kinerja = new BaseController;
     $dv = $pohon_kinerja->visi();
     $id_visi = !empty($dv[0]) ? $dv[0]['id'] : '';


     $tujuan = DB::table('tujuan_opd')
            ->where('pohon_kinerja_visi_id', '=', $id_visi)
            ->where('master_opd_id', '=', $master_opd_id)
            ->where('deleted_at', '=', NULL)
            ->where('is_active', true)
            ->select('id')
            ->pluck('id')
            ->toArray();

     return $tujuan;
   }

   public static function getTujuanOPDMadani($master_opd_id)
   { 
     $id_visi = "2d0fb31c-3aca-4fa8-882a-626e8f5ae7d9";


     $tujuan = DB::table('tujuan_opd')
            ->where('pohon_kinerja_visi_id', '=', $id_visi)
            ->where('master_opd_id', '=', $master_opd_id)
            ->where('deleted_at', '=', NULL)
            ->where('is_active', true)
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
             ->where('deleted_at', '=', NULL)
            ->select('id')
            ->pluck('id')
            ->toArray();

     return $indikator_opd;
   }

   public static function format_tanggal($created_at){
     $jam = Carbon::parse($created_at)->diffInHours();
     if($jam > 24) 
         $created_at = Carbon::parse($created_at)->format('d M Y H:i');     
     else
         $created_at = Carbon::createFromFormat('Y-m-d H:i:s', $created_at)->diffForHumans();

     return $created_at;
   }

   
   /*-----------------------------------------Get Visi By Sasaran ID --------------------*/

}
