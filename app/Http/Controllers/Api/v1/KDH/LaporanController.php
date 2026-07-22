<?php

namespace App\Http\Controllers\Api\v1\KDH;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;

use App\Models\Sakip\KDH\PohonKinerjaSasaran;
use App\Models\Sakip\KDH\PohonKinerjaTujuan;
use App\Models\Sakip\KDH\PohonKinerjaIndikator;
use App\Models\Sakip\KDH\OpdPendukungIndikator;

use App\Models\Sakip\MASTER\MasterSatuan;
use App\Models\MASTER\MasterOpd;
use App\Http\Controllers\Api\v1\MASTER\BaseController;

use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanController extends Controller
{
    public function data_kinerja(Request $request)
    {
         try {

             $misi = BaseController ::getMisiPemda();
             $tujuan = PohonKinerjaTujuan::whereIn('pohon_kinerja_misi_id', $misi)->get();

             $tujuan = $tujuan->map(function($item) use($request) {

                $indikator_tujuan = PohonKinerjaIndikator::where('pohon_kinerja_tujuan_id', '=', $item->id)
                                                         ->where('is_tujuan', true)->get();

                $indikator_tujuan = $indikator_tujuan->map(function($it) use($request) {
                    $list =  [
                        "id" => $it->id,
                        "pohon_kinerja_tujuan_id" => $it->pohon_kinerja_tujuan_id,
                        "pohon_kinerja_sasaran_id" => $it->pohon_kinerja_sasaran_id,
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

                $sasaran = PohonKinerjaSasaran::where('pohon_kinerja_tujuan_id', '=', $item->id)->get();

                $sasaran = $sasaran->map(function($ds) use($request) {

                        $sasaran_id = $ds->id;
                        $indikator_sasaran = PohonKinerjaIndikator::where('pohon_kinerja_sasaran_id', '=', $ds->id)
                                                ->where('is_tujuan', false)->get();

                        $indikator_sasaran = $indikator_sasaran->map(function($it) use($request, $sasaran_id) {
                             $indikator_id = $it->id;
                             $visi = BaseController::getCurrentVisi();             
                             $tahun_awal = $visi->period_starts;             
                             $tahun_akhir = $visi->period_ends;


                            return [
                            "id" => $it->id,
                            "pohon_kinerja_tujuan_id" => $it->pohon_kinerja_tujuan_id,
                            "pohon_kinerja_sasaran_id" => $it->pohon_kinerja_sasaran_id,
                            "indikator" => $it->indikator,
                            "order" => $it->order,
                            "target_1" => $it->target_1,
                            "target_2" => $it->target_2,
                            "target_3" => $it->target_3,
                            "target_4" => $it->target_4,
                            "target_5" => $it->target_5,
                            "target_6" => $it->target_6,
                            "realisasi_1" => $this->get_realisasi_pemda($sasaran_id, $indikator_id, $tahun_awal)['real'],
                            "realisasi_2" => $this->get_realisasi_pemda($sasaran_id, $indikator_id, $tahun_awal + 1)['real'],
                            "realisasi_3" => $this->get_realisasi_pemda($sasaran_id, $indikator_id, $tahun_awal + 2)['real'],
                            "realisasi_4" => $this->get_realisasi_pemda($sasaran_id, $indikator_id, $tahun_awal + 3)['real'],
                            "realisasi_5" => $this->get_realisasi_pemda($sasaran_id, $indikator_id, $tahun_awal + 4)['real'],
                            "realisasi_6" => $this->get_realisasi_pemda($sasaran_id, $indikator_id, $tahun_awal + 5)['real'],
                            "capaian_1" => $this->get_realisasi_pemda($sasaran_id, $indikator_id, $tahun_awal)['capai'],
                            "capaian_2" => $this->get_realisasi_pemda($sasaran_id, $indikator_id, $tahun_awal + 1)['capai'],
                            "capaian_3" => $this->get_realisasi_pemda($sasaran_id, $indikator_id, $tahun_awal + 2)['capai'],
                            "capaian_4" => $this->get_realisasi_pemda($sasaran_id, $indikator_id, $tahun_awal + 3)['capai'],
                            "capaian_5" => $this->get_realisasi_pemda($sasaran_id, $indikator_id, $tahun_awal + 4)['capai'],
                            "capaian_6" => $this->get_realisasi_pemda($sasaran_id, $indikator_id, $tahun_awal + 5)['capai']
                            ];
                    });

                    return  [
                        "id" => $ds->id,
                        "pohon_kinerja_tujuan_id" => $ds->pohon_kinerja_tujuan_id,
                        "sasaran" => $ds->sasaran,
                        "order" => $ds->order,
                        "indikator_sasaran" => $indikator_sasaran
                    ];
                });

                
                $list =  [
                    "id" => $item->id,
                    "pohon_kinerja_misi_id" => $item->pohon_kinerja_sasaran_id,
                    "tujuan" => $item->tujuan,
                    "order" => $item->order,
                    "indikator_tujuan" =>$indikator_tujuan,
                    "sasaran" =>$sasaran,
                ];
                return $list;
            });

            return response()->json([
                'success' => true,
                'message' => 'List of Target dan Realisasi RPJMD ',
                'data' => $tujuan
            ]);
            
         } catch (\Throwable $th) {
             //throw $th;
             return response()->json([
                 'success' => false,
                 'message' => 'List of Target dan Realisasi RPJMD',
                 'data' => [],
                 'errors' => $th->getMessage()
             ], 500);
         }
    }

    public function data_kinerja_cetak(Request $request)
    {
         try {

             $misi = BaseController ::getMisiPemda();
             $tujuan = PohonKinerjaTujuan::whereIn('pohon_kinerja_misi_id', $misi)->get();

             $tujuan = $tujuan->map(function($item) use($request) {

                $indikator_tujuan = PohonKinerjaIndikator::where('pohon_kinerja_tujuan_id', '=', $item->id)
                                                         ->where('is_tujuan', true)->get();

                $indikator_tujuan = $indikator_tujuan->map(function($it) use($request) {
                    $list =  [
                        "id" => $it->id,
                        "pohon_kinerja_tujuan_id" => $it->pohon_kinerja_tujuan_id,
                        "pohon_kinerja_sasaran_id" => $it->pohon_kinerja_sasaran_id,
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

                $sasaran = PohonKinerjaSasaran::where('pohon_kinerja_tujuan_id', '=', $item->id)->get();

                $sasaran = $sasaran->map(function($ds) use($request) {

                        $sasaran_id = $ds->id;
                        $indikator_sasaran = PohonKinerjaIndikator::where('pohon_kinerja_sasaran_id', '=', $ds->id)
                                                ->where('is_tujuan', false)->get();

                        $indikator_sasaran = $indikator_sasaran->map(function($it) use($request, $sasaran_id) {
                             $indikator_id = $it->id;
                             $visi = BaseController::getCurrentVisi();             
                             $tahun_awal = $visi->period_starts;             
                             $tahun_akhir = $visi->period_ends;


                            return [
                            "id" => $it->id,
                            "pohon_kinerja_tujuan_id" => $it->pohon_kinerja_tujuan_id,
                            "pohon_kinerja_sasaran_id" => $it->pohon_kinerja_sasaran_id,
                            "indikator" => $it->indikator,
                            "order" => $it->order,
                            "target_1" => $it->target_1,
                            "target_2" => $it->target_2,
                            "target_3" => $it->target_3,
                            "target_4" => $it->target_4,
                            "target_5" => $it->target_5,
                            "target_6" => $it->target_6,
                            "realisasi_1" => $this->get_realisasi_pemda($sasaran_id, $indikator_id, $tahun_awal)['real'],
                            "realisasi_2" => $this->get_realisasi_pemda($sasaran_id, $indikator_id, $tahun_awal + 1)['real'],
                            "realisasi_3" => $this->get_realisasi_pemda($sasaran_id, $indikator_id, $tahun_awal + 2)['real'],
                            "realisasi_4" => $this->get_realisasi_pemda($sasaran_id, $indikator_id, $tahun_awal + 3)['real'],
                            "realisasi_5" => $this->get_realisasi_pemda($sasaran_id, $indikator_id, $tahun_awal + 4)['real'],
                            "realisasi_6" => $this->get_realisasi_pemda($sasaran_id, $indikator_id, $tahun_awal + 5)['real'],
                            "capaian_1" => $this->get_realisasi_pemda($sasaran_id, $indikator_id, $tahun_awal)['capai'],
                            "capaian_2" => $this->get_realisasi_pemda($sasaran_id, $indikator_id, $tahun_awal + 1)['capai'],
                            "capaian_3" => $this->get_realisasi_pemda($sasaran_id, $indikator_id, $tahun_awal + 2)['capai'],
                            "capaian_4" => $this->get_realisasi_pemda($sasaran_id, $indikator_id, $tahun_awal + 3)['capai'],
                            "capaian_5" => $this->get_realisasi_pemda($sasaran_id, $indikator_id, $tahun_awal + 4)['capai'],
                            "capaian_6" => $this->get_realisasi_pemda($sasaran_id, $indikator_id, $tahun_awal + 5)['capai']
                            ];
                    });

                    return  [
                        "id" => $ds->id,
                        "pohon_kinerja_tujuan_id" => $ds->pohon_kinerja_tujuan_id,
                        "sasaran" => $ds->sasaran,
                        "order" => $ds->order,
                        "indikator_sasaran" => $indikator_sasaran
                    ];
                });

                
                $list =  [
                    "id" => $item->id,
                    "pohon_kinerja_misi_id" => $item->pohon_kinerja_sasaran_id,
                    "tujuan" => $item->tujuan,
                    "order" => $item->order,
                    "indikator_tujuan" =>$indikator_tujuan,
                    "sasaran" =>$sasaran,
                ];
                return $list;
            });

            $data = [
                'generated_at' => now()->toDateTimeString(),
                'tujuan' => $tujuan,
                'visi' => BaseController::getCurrentVisi()
            ];
            $pdf = Pdf::loadView('report_template.kdh.kinerja', compact('data'))->setPaper('Legal', 'portrait');;
            return $pdf->download('Data Kinerja.pdf');
            
         } catch (\Throwable $th) {
             //throw $th;
             return response()->json([
                 'success' => false,
                 'message' => 'List of Target dan Realisasi RPJMD',
                 'data' => [],
                 'errors' => $th->getMessage()
             ], 500);
         }
    }

     private function get_realisasi_pemda($sasaran_id, $indikator_id, $tahun_awal){

        $realisasi = DB::table('rencana_aksi')
                    ->where('pohon_kinerja_sasaran_id', '=', $sasaran_id)
                    ->where('pohon_kinerja_indikator_id', '=', $indikator_id)
                    ->where('tahun', '=', $tahun_awal)
                    ->where('deleted_at', '=', NULL)
                    ->first();

        return array('real'=>!empty($realisasi) ? $realisasi->realisasi_tw4 : '', 
                    'capai'=>!empty($realisasi) ? $realisasi->capaian_tw4 : '');
    }

    public function update_realisasi($id, Request $request)
    {
        try {
            // cek validasi jika id berformar uuid atau tidak
            if(!Str::isUuid($id)){
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid Id, Indikator not Found',
                ], 422);
            }
             // cek existing indikator
            $indikator = PohonKinerjaIndikator::find($id);
            if (!$indikator) {
                return response()->json([
                    'success' => false,
                    'message' => 'indikator not found.',
                ], 404);
            }


            $form = $request->validate([                  
                "realisasi_1" => "required|string",
                "realisasi_2" => "required|string",
                "realisasi_3" => "required|string",
                "realisasi_4" => "required|string",
                "realisasi_5" => "required|string",
                "realisasi_6" => "required|string"
            ]);

            $target_1 = $indikator->target_1;
            $target_2 = $indikator->target_2;
            $target_3 = $indikator->target_3;
            $target_4 = $indikator->target_4;
            $target_5 = $indikator->target_5;
            $target_6 = $indikator->target_6;

            $realisasi_1 = $indikator->realisasi_1;
            $realisasi_2 = $indikator->realisasi_2;
            $realisasi_3 = $indikator->realisasi_3;
            $realisasi_4 = $indikator->realisasi_4;
            $realisasi_5 = $indikator->realisasi_5;
            $realisasi_6 = $indikator->realisasi_6;

            if($target_1==0) $capaian_1=0; else $capaian_1 = $realisasi_1/$target_1 * 100;
            if($target_2==0) $capaian_1=0; else $capaian_2 = $realisasi_1/$target_2 * 100;
            if($target_3==0) $capaian_1=0; else $capaian_3 = $realisasi_1/$target_3 * 100;
            if($target_4==0) $capaian_1=0; else $capaian_4 = $realisasi_1/$target_4 * 100;
            if($target_5==0) $capaian_1=0; else $capaian_5 = $realisasi_1/$target_5 * 100;
            if($target_6==0) $capaian_1=0; else $capaian_6 = $realisasi_1/$target_6 * 100;
            
                
            $form['realisasi_1'] = $request->realisasi_1;
            $form['realisasi_2'] = $request->realisasi_2;
            $form['realisasi_3'] = $request->realisasi_3;
            $form['realisasi_4'] = $request->realisasi_4;
            $form['realisasi_5'] = $request->realisasi_5;
            $form['realisasi_6'] = $request->realisasi_6;

            $form['updated_by'] = $request->attributes->get('payload')->username;

            $indikator->update($form);

            return response()->json([
                'success' => true,
                'message' => 'Realisasi RPJMD updated successfully.',
                'data' => $indikator,
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong!',
                'errors' => $th->getMessage()
            ], 500);
        }
    }


    public function capaian(Request $request)
    {
         try {

             $tahun = $request->tahun;
             $visi = BaseController ::getCurrentVisi();

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
          

             $misi = BaseController ::getMisiPemda();
             $tujuan = PohonKinerjaTujuan::whereIn('pohon_kinerja_misi_id', $misi)->get();

             $tujuan = $tujuan->map(function($item) use($request, $target_skrg, $realisasi_skrg, $capaian_skrg,  $target_lalu,  $realisasi_lalu ,  $capaian_lalu,  $tahun_akhir ) {

                $indikator_tujuan = PohonKinerjaIndikator::where('pohon_kinerja_tujuan_id', '=', $item->id)
                                                         ->where('is_tujuan', true)->get();

                $indikator_tujuan = $indikator_tujuan->map(function($it) use($request, $target_skrg, $realisasi_skrg, $capaian_skrg,  $target_lalu,  $realisasi_lalu ,  $capaian_lalu ) {
                 
                    $list =  [
                        "id" => $it->id,
                        "pohon_kinerja_tujuan_id" => $it->pohon_kinerja_tujuan_id,
                        "pohon_kinerja_sasaran_id" => $it->pohon_kinerja_sasaran_id,
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

                $sasaran = PohonKinerjaSasaran::where('pohon_kinerja_tujuan_id', '=', $item->id)->get();

                $sasaran = $sasaran->map(function($ds) use($request,  $target_skrg, $realisasi_skrg, $capaian_skrg,  $target_lalu,  $realisasi_lalu ,  $capaian_lalu,  $tahun_akhir) {

                        $sasaran_id = $ds->id;

                        $indikator_sasaran = PohonKinerjaIndikator::where('pohon_kinerja_sasaran_id', '=', $ds->id)
                                                ->where('is_tujuan', false)->get();

                        $indikator_sasaran = $indikator_sasaran->map(function($it) use($request,  $target_skrg, $realisasi_skrg, $capaian_skrg,  $target_lalu,  $realisasi_lalu ,  $capaian_lalu, $sasaran_id, $tahun_akhir) {

                            $indikator_id = $it->id;

                            $tahun_ini = date('Y');
                            $tahun_lalu = $tahun_ini - 1;

                            $realisasi_tahun_ini = DB::table('rencana_aksi')
                                ->where('pohon_kinerja_sasaran_id', '=', $sasaran_id)
                                ->where('pohon_kinerja_indikator_id', '=', $indikator_id)
                                ->where('tahun', '=', $tahun_ini)
                                ->where('deleted_at', '=', NULL)
                                ->first();

                            $realisasi_tahun_lalu = DB::table('rencana_aksi')
                                ->where('pohon_kinerja_sasaran_id', '=', $sasaran_id)
                                ->where('pohon_kinerja_indikator_id', '=', $indikator_id)
                                ->where('tahun', '=', $tahun_ini - 1)
                                ->where('deleted_at', '=', NULL)
                                ->first();

                            $realisasi_tahun_akhir = DB::table('rencana_aksi')
                                ->where('pohon_kinerja_sasaran_id', '=', $sasaran_id)
                                ->where('pohon_kinerja_indikator_id', '=', $indikator_id)
                                ->where('tahun', '=', $tahun_akhir)
                                ->where('deleted_at', '=', NULL)
                                ->first();

                            return [
                            "id" => $it->id,
                            "pohon_kinerja_tujuan_id" => $it->pohon_kinerja_tujuan_id,
                            "pohon_kinerja_sasaran_id" => $it->pohon_kinerja_sasaran_id,
                            "indikator" => $it->indikator,
                            "order" => $it->order,
                            "target_tahun_sekarang" => $it->$target_skrg,
                            "realisasi_tahun_sekarang" => !empty($realisasi_tahun_ini) ? $realisasi_tahun_ini->realisasi_tw4 : '',
                            "capaian_tahun_sekarang" => !empty($realisasi_tahun_ini) ? $realisasi_tahun_ini->capaian_tw4 : '',

                            "target_tahun_lalu" => ($target_lalu !=0) ? $it->$target_lalu : "Target Tahun Lalu Not Found",
                            "realisasi_tahun_lalu" => !empty($realisasi_tahun_lalu) ? $realisasi_tahun_lalu->capaian_tw4 : '',
                            "capaian_tahun_lalu" => !empty($realisasi_tahun_lalu) ? $realisasi_tahun_lalu->capaian_tw4 : '',

                            "target_tahun_terakhir" => $it->target_5,
                            "realisasi_tahun_terakhir" => !empty($realisasi_tahun_akhir) ? $realisasi_tahun_akhir->capaian_tw4 : '',
                            "capaian_tahun_terakhir" => !empty($realisasi_tahun_akhir) ? $realisasi_tahun_akhir->capaian_tw4 : ''
                            ];
                    });

                    return  [
                        "id" => $ds->id,
                        "pohon_kinerja_tujuan_id" => $ds->pohon_kinerja_tujuan_id,
                        "sasaran" => $ds->sasaran,
                        "order" => $ds->order,
                        "indikator_sasaran" => $indikator_sasaran
                    ];
                });

                
                $list =  [
                    "id" => $item->id,
                    "pohon_kinerja_misi_id" => $item->pohon_kinerja_sasaran_id,
                    "tujuan" => $item->tujuan,
                    "order" => $item->order,
                    "indikator_tujuan" =>$indikator_tujuan,
                    "sasaran" =>$sasaran,
                ];
                return $list;
            });

            return response()->json([
                'success' => true,
                'message' => 'List of Target dan Realisasi RPJMD ',
                'data' => $tujuan
            ]);
            
         } catch (\Throwable $th) {
             //throw $th;
             return response()->json([
                 'success' => false,
                 'message' => 'List of Target dan Realisasi RPJMD',
                 'data' => [],
                 'errors' => $th->getMessage()
             ], 500);
         }
    }

    public function capaian_cetak(Request $request)
    {
         try {

             $tahun = $request->tahun;
             $visi = BaseController ::getCurrentVisi();

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
          

             $misi = BaseController ::getMisiPemda();
             $tujuan = PohonKinerjaTujuan::whereIn('pohon_kinerja_misi_id', $misi)->get();

             $tujuan = $tujuan->map(function($item) use($request, $target_skrg, $realisasi_skrg, $capaian_skrg,  $target_lalu,  $realisasi_lalu ,  $capaian_lalu,  $tahun_akhir ) {

                $indikator_tujuan = PohonKinerjaIndikator::where('pohon_kinerja_tujuan_id', '=', $item->id)
                                                         ->where('is_tujuan', true)->get();

                $indikator_tujuan = $indikator_tujuan->map(function($it) use($request, $target_skrg, $realisasi_skrg, $capaian_skrg,  $target_lalu,  $realisasi_lalu ,  $capaian_lalu ) {
                 
                    $list =  [
                        "id" => $it->id,
                        "pohon_kinerja_tujuan_id" => $it->pohon_kinerja_tujuan_id,
                        "pohon_kinerja_sasaran_id" => $it->pohon_kinerja_sasaran_id,
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

                $sasaran = PohonKinerjaSasaran::where('pohon_kinerja_tujuan_id', '=', $item->id)->get();

                $sasaran = $sasaran->map(function($ds) use($request,  $target_skrg, $realisasi_skrg, $capaian_skrg,  $target_lalu,  $realisasi_lalu ,  $capaian_lalu,  $tahun_akhir) {

                        $sasaran_id = $ds->id;

                        $indikator_sasaran = PohonKinerjaIndikator::where('pohon_kinerja_sasaran_id', '=', $ds->id)
                                                ->where('is_tujuan', false)->get();

                        $indikator_sasaran = $indikator_sasaran->map(function($it) use($request,  $target_skrg, $realisasi_skrg, $capaian_skrg,  $target_lalu,  $realisasi_lalu ,  $capaian_lalu, $sasaran_id, $tahun_akhir) {

                            $indikator_id = $it->id;

                            $tahun_ini = date('Y');
                            $tahun_lalu = $tahun_ini - 1;

                            $realisasi_tahun_ini = DB::table('rencana_aksi')
                                ->where('pohon_kinerja_sasaran_id', '=', $sasaran_id)
                                ->where('pohon_kinerja_indikator_id', '=', $indikator_id)
                                ->where('tahun', '=', $tahun_ini)
                                ->where('deleted_at', '=', NULL)
                                ->first();

                            $realisasi_tahun_lalu = DB::table('rencana_aksi')
                                ->where('pohon_kinerja_sasaran_id', '=', $sasaran_id)
                                ->where('pohon_kinerja_indikator_id', '=', $indikator_id)
                                ->where('tahun', '=', $tahun_ini - 1)
                                ->where('deleted_at', '=', NULL)
                                ->first();

                            $realisasi_tahun_akhir = DB::table('rencana_aksi')
                                ->where('pohon_kinerja_sasaran_id', '=', $sasaran_id)
                                ->where('pohon_kinerja_indikator_id', '=', $indikator_id)
                                ->where('tahun', '=', $tahun_akhir)
                                ->where('deleted_at', '=', NULL)
                                ->first();

                            return [
                            "id" => $it->id,
                            "pohon_kinerja_tujuan_id" => $it->pohon_kinerja_tujuan_id,
                            "pohon_kinerja_sasaran_id" => $it->pohon_kinerja_sasaran_id,
                            "indikator" => $it->indikator,
                            "order" => $it->order,
                            "target_tahun_sekarang" => $it->$target_skrg,
                            "realisasi_tahun_sekarang" => !empty($realisasi_tahun_ini) ? $realisasi_tahun_ini->realisasi_tw4 : '',
                            "capaian_tahun_sekarang" => !empty($realisasi_tahun_ini) ? $realisasi_tahun_ini->capaian_tw4 : '',

                            "target_tahun_lalu" => ($target_lalu !=0) ? $it->$target_lalu : "Target Tahun Lalu Not Found",
                            "realisasi_tahun_lalu" => !empty($realisasi_tahun_lalu) ? $realisasi_tahun_lalu->capaian_tw4 : '',
                            "capaian_tahun_lalu" => !empty($realisasi_tahun_lalu) ? $realisasi_tahun_lalu->capaian_tw4 : '',

                            "target_tahun_terakhir" => $it->target_5,
                            "realisasi_tahun_terakhir" => !empty($realisasi_tahun_akhir) ? $realisasi_tahun_akhir->capaian_tw4 : '',
                            "capaian_tahun_terakhir" => !empty($realisasi_tahun_akhir) ? $realisasi_tahun_akhir->capaian_tw4 : ''
                            ];
                    });

                    return  [
                        "id" => $ds->id,
                        "pohon_kinerja_tujuan_id" => $ds->pohon_kinerja_tujuan_id,
                        "sasaran" => $ds->sasaran,
                        "order" => $ds->order,
                        "indikator_sasaran" => $indikator_sasaran
                    ];
                });

                
                $list =  [
                    "id" => $item->id,
                    "pohon_kinerja_misi_id" => $item->pohon_kinerja_sasaran_id,
                    "tujuan" => $item->tujuan,
                    "order" => $item->order,
                    "indikator_tujuan" =>$indikator_tujuan,
                    "sasaran" =>$sasaran,
                ];
                return $list;
            });

            $data = [
                'generated_at' => now()->toDateTimeString(),
                'tujuan' => $tujuan,
                'tahun' => $tahun,
                'visi' => BaseController::getCurrentVisi()
            ];
            $pdf = Pdf::loadView('report_template.kdh.capaian', compact('data'))->setPaper('Legal', 'portrait');;
            return $pdf->download('Data Capaian.pdf');
            
         } catch (\Throwable $th) {
             //throw $th;
             return response()->json([
                 'success' => false,
                 'message' => 'List of Target dan Realisasi RPJMD',
                 'data' => [],
                 'errors' => $th->getMessage()
             ], 500);
         }
    }


    public function analisis(Request $request)
    {

    }

    public function efisiensi(Request $request)
    {

    }
}
