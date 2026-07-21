<?php

namespace App\Http\Controllers\Api\v1\Monitoring;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;

use App\Models\Sakip\MASTER\MasterSatuan;
use App\Models\Sakip\MASTER\MasterOpd;
use App\Http\Controllers\Api\v1\MASTER\BaseController;
use App\Models\Sakip\OPD\SasaranOpd;
use App\Models\Sakip\OPD\TujuanOpd;
use App\Models\Sakip\OPD\IndikatorOpd;

use Illuminate\Support\Facades\DB;

class PelaporanController extends Controller
{
    public function data_kinerja(Request $request)
    {
         try {

            $master_opd_id = $request->master_opd_id;

            // cek existing opd
            $opd = MasterOpd::find($master_opd_id);
            if (!$opd) {
                // jika data tidak ditamukan di dalam database
                return response()->json([
                    'success' => false,
                    'message' => 'Perangkat Daerah not found.',
                ], 404);
            }

             $visi = BaseController::getCurrentVisi();
             
             $tahun_awal = $visi->period_starts;             
             $tahun_akhir = $visi->period_ends;

             $tujuan = TujuanOpd::where('pohon_kinerja_visi_id', '=', $visi->id)->where('master_opd_id', $master_opd_id)->get();

             $tujuan = $tujuan->map(function($item) use($request, $tahun_awal) {

                $indikator_tujuan = IndikatorOpd::where('tujuan_opd_id', '=', $item->id)
                                                    ->where('is_tujuan', true)->get();

                $indikator_tujuan = $indikator_tujuan->map(function($it) use($request, $tahun_awal) {
                    $list =  [
                        "id" => $it->id,
                        "tujuan_opd_id" => $it->tujuan_opd_id,
                        "sasaran_opd_id" => $it->sasaran_opd_id,
                        "indikator" => $it->indikator,
                        "order" => $it->order,
                        "target_1" => $it->target_1,
                        "target_2" => $it->target_2,
                        "target_3" => $it->target_3,
                        "target_4" => $it->target_4,
                        "target_5" => $it->target_5,
                        "target_6" => $it->target_6,
                        "realisasi_1" => $it->realisasi_1,
                        "realisasi_2" => $it->realisasi_2,
                        "realisasi_3" => $it->realisasi_3,
                        "realisasi_4" => $it->realisasi_4,
                        "realisasi_5" => $it->realisasi_5,
                        "realisasi_6" => $it->realisasi_6,
                        "capaian_1" => $it->capaian_1,
                        "capaian_2" => $it->capaian_2,
                        "capaian_3" => $it->capaian_3,
                        "capaian_4" => $it->capaian_4,
                        "capaian_5" => $it->capaian_5,
                        "capaian_6" => $it->capaian_6
                    ];
                    return $list;
                });

                $sasaran = SasaranOpd::where('tujuan_opd_id', '=', $item->id)->where('parent_id', '=', 0)->get();

                $sasaran = $sasaran->map(function($ds) use($request, $tahun_awal) {

                        $sasaran_id = $ds->id;
                        $indikator_sasaran = IndikatorOpd::where('sasaran_opd_id', '=', $ds->id)
                                                ->where('is_tujuan', false)->get();
                        

                        $indikator_sasaran = $indikator_sasaran->map(function($it) use($request, $tahun_awal, $sasaran_id) {

                            $indikator_id = $it->id;

                            return [
                            "id" => $it->id,
                            "tujuan_opd_id" => $it->tujuan_opd_id,
                            "sasaran_opd_id" => $it->sasaran_opd_id,
                            "indikator" => $it->indikator,
                            "order" => $it->order,
                            "target_1" => $it->target_1,
                            "target_2" => $it->target_2,
                            "target_3" => $it->target_3,
                            "target_4" => $it->target_4,
                            "target_5" => $it->target_5,
                            "target_6" => $it->target_6,
                            "realisasi_1" =>  $this->get_realisasi_opd($sasaran_id, $indikator_id, $tahun_awal)['real'],
                            "realisasi_2" =>  $this->get_realisasi_opd($sasaran_id, $indikator_id, $tahun_awal + 1)['real'],
                            "realisasi_3" =>  $this->get_realisasi_opd($sasaran_id, $indikator_id, $tahun_awal + 2)['real'],
                            "realisasi_4" =>  $this->get_realisasi_opd($sasaran_id, $indikator_id, $tahun_awal + 3)['real'],
                            "realisasi_5" =>  $this->get_realisasi_opd($sasaran_id, $indikator_id, $tahun_awal + 4)['real'],
                            "realisasi_6" =>  $this->get_realisasi_opd($sasaran_id, $indikator_id, $tahun_awal + 5)['real'],
                            "capaian_1" => $this->get_realisasi_opd($sasaran_id, $indikator_id, $tahun_awal)['capai'],
                            "capaian_2" => $this->get_realisasi_opd($sasaran_id, $indikator_id, $tahun_awal + 1)['capai'],
                            "capaian_3" => $this->get_realisasi_opd($sasaran_id, $indikator_id, $tahun_awal + 2)['capai'],
                            "capaian_4" => $this->get_realisasi_opd($sasaran_id, $indikator_id, $tahun_awal + 3)['capai'],
                            "capaian_5" => $this->get_realisasi_opd($sasaran_id, $indikator_id, $tahun_awal + 4)['capai'],
                            "capaian_6" => $this->get_realisasi_opd($sasaran_id, $indikator_id, $tahun_awal + 5)['capai']
                            ];
                    });
                    
                    return  [
                        "id" => $ds->id,
                        "tujuan_opd_id" => $ds->tujuan_opd_id,
                        "sasaran" => $ds->sasaran,
                        "order" => $ds->order,
                        "indikator_sasaran" => $indikator_sasaran
                    ];
                }); 

                
                return  [
                    "id" => $item->id,
                    "pohon_kinerja_sasaran_id" => $item->pohon_kinerja_sasaran_id,
                    "tujuan" => $item->tujuan,
                    "order" => $item->order,
                    "is_direct" => $item->is_direct,
                    "indikator_tujuan" =>$indikator_tujuan,
                    "sasaran" =>$sasaran,
                ];
            });

            return response()->json([
                'success' => true,
                'message' => 'List of Target dan Realisasi RENSTRA OPD '.$opd->nama_opd.' ',
                'data' => $tujuan
            ]);
            
         } catch (\Throwable $th) {
             //throw $th;
             return response()->json([
                 'success' => false,
                 'message' => 'List of Target dan Realisasi RENSTRA OPD '.$opd->nama_opd.' ',
                 'data' => [],
                 'errors' => $th->getMessage()
             ], 500);
         }
    }

    private function get_realisasi_opd($sasaran_id, $indikator_id, $tahun_awal){

        $realisasi = DB::table('rencana_opd')
                    ->where('sasaran_opd_id', '=', $sasaran_id)
                    ->where('indikator_opd_id', '=', $indikator_id)
                    ->where('tahun', '=', $tahun_awal)
                    ->where('deleted_at', '=', NULL)
                    ->first();

        return array('real'=>!empty($realisasi) ? $realisasi->realisasi_tw4 : '', 
                    'capai'=>!empty($realisasi) ? $realisasi->capaian_tw4 : '');
    }

   
    public function capaian(Request $request)
    {
         try {

            $tahun = $request->tahun;
            $master_opd_id = $request->master_opd_id;

            // cek existing opd
            $opd = MasterOpd::find($master_opd_id);
            if (!$opd) {
                // jika data tidak ditamukan di dalam database
                return response()->json([
                    'success' => false,
                    'message' => 'Perangkat Daerah not found.',
                ], 404);
            }

             $visi = BaseController::getCurrentVisi();           

             $tahun_awal = $visi->period_starts;             
             $tahun_akhir = $visi->period_ends;
             
             $periode = array();
             $key=0;
             for ($x = $tahun_awal; $x <= $tahun_akhir; $x++) {
                $key++;
                $periode[$key] = $x; 
             }

             if(!in_array($tahun, $periode)){
                return response()->json([
                    'success' => false,
                    'message' => 'Tahun tidak Sesuai dengan Periode Visi',
                ], 404);
             }


             
             $tahun_sekarang =  array_search($tahun, $periode);
             $tahun_lalu = $tahun_sekarang - 1;

             if(array_key_exists($tahun_lalu, $periode)){
                $target_skrg     = "target_$tahun_sekarang";
                $realisasi_skrg     = "realisasi_$tahun_sekarang";
                $capaian_skrg     = "capaian_$tahun_sekarang";

                $target_lalu     = "target_$tahun_lalu";
                $realisasi_lalu     = "realisasi_$tahun_lalu";
                $capaian_lalu     = "capaian_$tahun_lalu";
             }else{
                $target_skrg=0; $realisasi_skrg=0; $capaian_skrg=0;
                $target_lalu=0; $realisasi_lalu=0; $capaian_lalu=0;
             }

             $tujuan = TujuanOpd::where('pohon_kinerja_visi_id', '=', $visi->id)->where('master_opd_id', $master_opd_id)->get();

             $tujuan = $tujuan->map(function($item) use($request , $target_skrg, $realisasi_skrg, $capaian_skrg,  $target_lalu,  $realisasi_lalu ,  $capaian_lalu, $tahun_akhir) {

                $indikator_tujuan = IndikatorOpd::where('tujuan_opd_id', '=', $item->id)
                                                    ->where('is_tujuan', true)->get();

                $indikator_tujuan = $indikator_tujuan->map(function($it) use($request, $target_skrg, $realisasi_skrg, $capaian_skrg,  $target_lalu,  $realisasi_lalu ,  $capaian_lalu) {
                    
                    $list =  [
                        "id" => $it->id,
                        "tujuan_opd_id" => $it->tujuan_opd_id,
                        "sasaran_opd_id" => $it->sasaran_opd_id,
                        "indikator" => $it->indikator,
                        "order" => $it->order,
                        "target_tahun_sekarang" => $it->$target_skrg,
                        "realisasi_tahun_sekarang" => $it->$realisasi_skrg,
                        "capaian_tahun_sekarang" => $it->$capaian_skrg,

                        "target_tahun_lalu" => ($target_lalu !=0) ? $it->$target_lalu : "Target Tahun Lalu Not Found",
                        "realisasi_tahun_lalu" => ($target_lalu !=0) ? $it->$target_lalu : "Target Tahun Lalu Not Found",
                        "capaian_tahun_lalu" => ($target_lalu !=0) ? $it->$target_lalu : "Target Tahun Lalu Not Found",

                        "target_tahun_terakhir" => $it->target_6,
                        "realisasi_tahun_terakhir" => $it->realisasi_6,
                        "capaian_tahun_terakhir" => $it->capaian_6 
                    ];
                    return $list;
                });

                $sasaran = SasaranOpd::where('tujuan_opd_id', '=', $item->id)->where('parent_id', '=', 0)->get();

                $sasaran = $sasaran->map(function($ds) use($request, $target_skrg, $realisasi_skrg, $capaian_skrg,  $target_lalu,  $realisasi_lalu ,  $capaian_lalu, $tahun_akhir) {

                    $sasaran_id = $ds->id;
                        $indikator_sasaran = IndikatorOpd::where('sasaran_opd_id', '=', $ds->id)
                                                ->where('is_tujuan', false)->get();

                        $indikator_sasaran = $indikator_sasaran->map(function($it) use($request, $target_skrg, $realisasi_skrg, $capaian_skrg,  $target_lalu,  $realisasi_lalu ,  $capaian_lalu, $sasaran_id, $tahun_akhir) {

                            $indikator_id = $it->id;

                            $tahun_ini = date('Y');
                            $tahun_lalu = $tahun_ini - 1;

                            $realisasi = DB::table('rencana_opd')
                                ->where('sasaran_opd_id', '=', $sasaran_id)
                                ->where('indikator_opd_id', '=', $indikator_id)
                                ->where('tahun', '=', $tahun_ini)
                                ->where('deleted_at', '=', NULL)
                                ->first();

                            $realisasi_tahun_lalu = DB::table('rencana_opd')
                                ->where('sasaran_opd_id', '=', $sasaran_id)
                                ->where('indikator_opd_id', '=', $indikator_id)
                                ->where('tahun', '=', $tahun_lalu)
                                ->where('deleted_at', '=', NULL)
                                ->first();

                            $realisasi_tahun_akhir = DB::table('rencana_opd')
                                ->where('sasaran_opd_id', '=', $sasaran_id)
                                ->where('indikator_opd_id', '=', $indikator_id)
                                ->where('tahun', '=', $tahun_akhir)
                                ->where('deleted_at', '=', NULL)
                                ->first();

                            return [
                            "id" => $it->id,
                            "tujuan_opd_id" => $it->tujuan_opd_id,
                            "sasaran_opd_id" => $it->sasaran_opd_id,
                            "indikator" => $it->indikator,
                            "order" => $it->order,
                            "target_tahun_sekarang" => $it->$target_skrg,
                            "realisasi_tahun_sekarang" =>!empty($realisasi) ? $realisasi->realisasi_tw4 : '',
                            "capaian_tahun_sekarang" =>!empty($realisasi) ? $realisasi->capaian_tw4 : '',

                            "target_tahun_lalu" => ($target_lalu !=0) ? $it->$target_lalu : "Target Tahun Lalu Not Found",
                            "realisasi_tahun_lalu" =>!empty($realisasi_tahun_lalu) ? $realisasi_tahun_lalu->realisasi_tw4 : '',
                            "capaian_tahun_lalu" =>!empty($realisasi_tahun_lalu) ? $realisasi_tahun_lalu->capaian_tw4 : '',

                            "target_tahun_terakhir" => $it->target_5,
                            "realisasi_tahun_akhir" =>!empty($realisasi_tahun_akhir) ? $realisasi_tahun_akhir->realisasi_tw4 : '',
                            "capaian_tahun_akhir" =>!empty($realisasi_tahun_akhir) ? $realisasi_tahun_akhir->capaian_tw4 : ''
                                ];
                        });
                    
                    return  [
                        "id" => $ds->id,
                        "tujuan_opd_id" => $ds->tujuan_opd_id,
                        "sasaran" => $ds->sasaran,
                        "order" => $ds->order,
                        "indikator_sasaran" => $indikator_sasaran
                    ];
                }); 

                
                return  [
                    "id" => $item->id,
                    "pohon_kinerja_sasaran_id" => $item->pohon_kinerja_sasaran_id,
                    "tujuan" => $item->tujuan,
                    "order" => $item->order,
                    "is_direct" => $item->is_direct,
                    "indikator_tujuan" =>$indikator_tujuan,
                    "sasaran" =>$sasaran,
                ];
            });

            return response()->json([
                'success' => true,
                'message' => 'List of Target dan Realisasi RENSTRA OPD '.$opd->nama_opd.' ',
                'data' => $tujuan
            ]);
            
         } catch (\Throwable $th) {
             //throw $th;
             return response()->json([
                 'success' => false,
                 'message' => 'List of Target dan Realisasi RENSTRA OPD '.$opd->nama_opd.' ',
                 'data' => [],
                 'errors' => $th->getMessage()
             ], 500);
         }
    }
}
