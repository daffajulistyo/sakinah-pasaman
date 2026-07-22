<?php

namespace App\Http\Controllers\Api\v1\OPD;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

use App\Http\Controllers\Controller;

use App\Models\Sakip\OPD\TujuanOpd;
use App\Models\Sakip\OPD\SasaranOpd;
use App\Models\Sakip\OPD\IndikatorOpd;
use App\Models\Sakip\MASTER\MasterSatuan;
use App\Models\Sakip\MASTER\MasterOpd;
use App\Http\Controllers\Api\v1\MASTER\BaseController;
use Barryvdh\DomPDF\Facade\Pdf;

class IkuController extends Controller
{
    public function update($id, Request $request)
    {
        try {

            $master_opd_id = $request->attributes->get('payload')->opd->id;

            // cek validasi jika id berformar uuid atau tidak
            if(!Str::isUuid($id)){
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid Id, IKU not Found',
                ], 422);
            }
             // cek existing indikator
            $indikator = IndikatorOpd::find($id);
            if (!$indikator) {
                return response()->json([
                    'success' => false,
                    'message' => 'indikator not found.',
                ], 404);
            }

            $cek_indikator = IndikatorOpd::where('master_opd_id', '=', $master_opd_id)
                ->where('id', '=', $id)
                ->count();

            if ($cek_indikator <= 0 ) {
                    // jika data tidak ditamukan di dalam database
                    return response()->json([
                        'success' => false,
                        'message' => ' Tujuan Bukan Di Ampu Oleh OPD Terkait',
                ], 404);
            }

            $form = $request->validate([                
                "baseline" => "required",
                "formula_perhitungan" => "required|string",
                "sumber_data" => "required|string"
            ]);

            $form['formula_perhitungan'] = $request->formula_perhitungan;
            $form['sumber_data'] = $request->sumber_data;
            $form['defenisi'] = $request->defenisi;
            $form['kegunaan'] = $request->kegunaan;
            $form['rilis'] = $request->rilis;
            $form['updated_by'] = $request->attributes->get('payload')->username;

            $indikator->update($form);

            return response()->json([
                'success' => true,
                'message' => 'IKU OPD updated successfully.',
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

    public function list(Request $request)
    {
        try {

            $master_opd_id = $request->attributes->get('payload')->opd->id;

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
            $visi_id = !empty($visi->id) ?  $visi->id : '';
             $sasaran_kdh = BaseController::getSasaranByOPDPengampu($master_opd_id)->pluck('id'); 
            
            $tujuan = TujuanOPD::where('pohon_kinerja_visi_id', '=', $visi_id)
                                ->where('master_opd_id', '=', $master_opd_id)
                                ->whereIn('pohon_kinerja_sasaran_id', $sasaran_kdh);
            $tujuan = $tujuan->pluck('id');
        
            $indikator = IndikatorOpd::whereIn('tujuan_opd_id', $tujuan)
                        ->where('is_active', true)
                        ->where('is_tujuan', true)
                        ->where('is_indikator_kinerja_utama', true)
                        ->orderBy('order', 'ASC')
                        ->get();

            $sasaran = SasaranOpd::whereIn('tujuan_opd_id', $tujuan)
                        ->where('is_active', '=', true)
                        ->where('parent_id', '=', 0)
                        ->pluck('id');

            $indikator_sasaran = IndikatorOpd::whereIn('sasaran_opd_id', $sasaran)
                        ->where('is_active', '=', true)
                        ->where('is_tujuan', '=', false)     
                        ->where('is_indikator_kinerja_utama', true)                   
                        ->orderBy('order', 'ASC')
                        ->get();

             $mergedIndikator = $indikator->merge($indikator_sasaran);

            return response()->json([
                'success' => true,
                'message' => 'Daftar Indikator Utama OPD (Indikator Tujuan & Sasaran) - '.$opd->nama_opd.' ',
                'data' => $mergedIndikator
            ]);
        }
        catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong!',
                'errors' => $th->getMessage()
            ], 500);
        }

    }

    public function create(Request $request)
    {
        try {            
            $indikator_id = $request->indikator_id;
            
            if($indikator_id==""){
                return response()->json([
                    'success' => false,
                    'message' => 'Something went wrong!',
                    'errors' => "Indikator Wajib Diisi"
                ], 500);
            }

            $master_opd_id = $request->attributes->get('payload')->opd->id;

            $current_indikator = BaseController::getIndikatorOPD($master_opd_id);

            $update = DB::table('indikator_opd')
                         ->whereIn('id', $current_indikator)
                         ->update(array('is_indikator_kinerja_utama' => false));  
                       
            
            
            foreach ($indikator_id as $item)
            { 
                $indikator = IndikatorOpd::find($item);
                if (!$indikator) {
                    return response()->json([
                        'success' => false,
                        'message' => 'indikator not found.',
                    ], 404);
                }

                $update_indikator = DB::table('indikator_opd')
                         ->where('id', $item)
                         ->where('master_opd_id', $master_opd_id)
                         ->update(array('is_indikator_kinerja_utama' => true));  
            }           

            return response()->json([
                'success' => true,
                'message' => 'IKU Created successfully.',
                'data' => $indikator_id,
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong!',
                'errors' => $th->getMessage()
            ], 500);
        }
    }

    public function generate_pdf(Request $request)
    {
        try {

            $master_opd_id = $request->attributes->get('payload')->opd->id;

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
            $visi_id = !empty($visi->id) ?  $visi->id : '';
            
             $sasaran_kdh = BaseController::getSasaranByOPDPengampu($master_opd_id)->pluck('id'); 
            
            $tujuan = TujuanOPD::where('pohon_kinerja_visi_id', '=', $visi_id)
                                ->where('master_opd_id', '=', $master_opd_id)
                                ->whereIn('pohon_kinerja_sasaran_id', $sasaran_kdh);
            $tujuan = $tujuan->pluck('id');
        
            $indikator = IndikatorOpd::whereIn('tujuan_opd_id', $tujuan)
                        ->where('is_active', true)
                        ->where('is_tujuan', true)
                        ->where('is_indikator_kinerja_utama', true)
                        ->orderBy('order', 'ASC')
                        ->get();

            $sasaran = SasaranOpd::whereIn('tujuan_opd_id', $tujuan)
                        ->where('is_active', '=', true)
                        ->where('parent_id', '=', 0)
                        ->pluck('id');

            $indikator_sasaran = IndikatorOpd::whereIn('sasaran_opd_id', $sasaran)
                        ->where('is_active', '=', true)
                        ->where('is_tujuan', '=', false) 
                        ->where('is_indikator_kinerja_utama', true)                       
                        ->orderBy('order', 'ASC')
                        ->get();

             $mergedIndikator = $indikator->merge($indikator_sasaran);

            $data = [
                'generated_at' => now()->toDateTimeString(),
                'opd' => $opd,
                'visi' => $visi,
                'indikator' => $mergedIndikator
            ];
            $pdf = Pdf::loadView('report_template.opd.iku_opd', compact('data'));
            return $pdf->download('IKU_'.str_replace($opd->nama_opd,' ','_').'.pdf');
        }
        catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong!',
                'errors' => $th->getMessage()
            ], 500);
        }
    }
}
