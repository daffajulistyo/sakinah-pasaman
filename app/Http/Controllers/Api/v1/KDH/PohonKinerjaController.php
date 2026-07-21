<?php

namespace App\Http\Controllers\Api\v1\KDH;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\v1\MASTER\BaseController;

use App\Models\Sakip\KDH\PohonKinerjaVisi;
use App\Models\Sakip\KDH\PohonKinerjaMisi;
use App\Models\Sakip\KDH\PohonKinerjaTujuan;
use App\Models\Sakip\KDH\PohonKinerjaSasaran;
use App\Models\Sakip\KDH\PohonKinerjaIndikator;


class PohonKinerjaController extends Controller
{
    public function showall1()
    {
        $visi = BaseController::getCurrentVisi();
        $misi =['asdas', 'asdasd'];
        
        $misi = PohonKinerjaMisi::where('pohon_kinerja_visi_id', '=', $visi->id)->get();
       

        $misi = $misi->map(function($item){  
            
            $tujuan = PohonKinerjaTujuan::where('pohon_kinerja_misi_id', '=', $item->id)->get();
            $tujuan = $tujuan->map(function($dt){

                    $indikator_tujuan = PohonKinerjaIndikator::where('pohon_kinerja_tujuan_id', '=', $dt->id)
                                             ->where('is_tujuan', '=', true)
                                             ->get();

                    $indikator_tujuan = $indikator_tujuan->map(function($it){
                        return [
                            "id" => $it->id,
                            "pohon_kinerja_tujuan_id" => $it->pohon_kinerja_tujuan_id,
                            "pohon_kinerja_sasaran_id" => $it->pohon_kinerja_sasaran_id,
                            "order" => $it->order,
                            "indikator" => $it->indikator,
                            "opd_pendukung" => $it->opd_pendukung,
                            "defenisi" => $it->defenisi,
                            "kegunaan" => $it->kegunaan,
                            "is_active" => $it->is_active,
                            'created_at'=>BaseController::format_tanggal($it->created_at),
                        ];
                    });

                    $sasaran = PohonKinerjaSasaran::where('pohon_kinerja_tujuan_id', '=', $dt->id)
                                             ->where('parent_id', '=', 0)
                                             ->get();

                    $sasaran = $sasaran->map(function($ds){
                        return [
                            "id" => $ds->id,
                            "pohon_kinerja_tujuan_id" => $ds->pohon_kinerja_tujuan_id,
                            "pohon_kinerja_sasaran_id" => $ds->pohon_kinerja_sasaran_id,
                            "order" => $ds->order,
                            "indikator" => $ds->indikator,
                            "opd_pendukung" => $ds->opd_pendukung,
                            "defenisi" => $ds->defenisi,
                            "kegunaan" => $ds->kegunaan,
                            "is_active" => $ds->is_active,
                            'created_at'=>BaseController::format_tanggal($ds->created_at),
                        ];
                    });
            
                return [
                    "id" => $dt->id,
                    "pohon_kinerja_misi_id" => $dt->pohon_kinerja_misi_id,
                    "order" => $dt->order,
                    "tujuan" => $dt->tujuan,
                    "is_active" => $dt->is_active,
                    'created_at'=>BaseController::format_tanggal($dt->created_at),
                    'indikator_tujuan' =>$indikator_tujuan,
                    'sasaran' =>$sasaran,
                ];
            });

            return [
                "id" => $item->id,
                "pohon_kinerja_visi_id" => $item->pohon_kinerja_visi_id,
                "order" => $item->order,
                "misi" => $item->misi,
                "is_active" => $item->is_active,
                'created_at'=>BaseController::format_tanggal($item->created_at),
                'tujuan'=> $tujuan
            ];
        });

        $pohon_kinerja = ['id'=>$visi->id, 
                          'period_starts'=>$visi->period_starts, 
                          'period_ends'=>$visi->period_ends,
                          'visi'=>$visi->visi,
                          'created_at'=>BaseController::format_tanggal($visi->created_at),
                          'misi' => $misi
                        ];
        return response()->json([
            'success' => true,
            'message' => 'Visi found.',
            'actions' => $pohon_kinerja,
        ]);
    }
    public function showall()
    {
        //
        $a = [];
        $actions = [];

        $visi = BaseController::getCurrentVisi();
        if (empty($visi)) {
            return response()->json([
                'success' => true,
                'message' => 'Data pohon kinerja belum tersedia.',
                'actions' => [],
            ]);
        }
        $visi_id = $visi->id;
        
        $rawActions = PohonKinerjaVisi::with(
            ['misi' => 
                function($query) {
                     $query->where('is_active', true);                 
                     $query->with([
                        'tujuan' => 
                        function($query) {
                            $query->where('is_active', true);                 
                            $query->with([
                               'sasaran' => 
                               function($query) {
                                   $query->where('is_active', true);                 
                                   $query->orderBy('order', 'asc');                 
                                   $query->with([
                                      'indikator_sasaran'=>
                                      function($query){
                                        $query->where('is_active', true); 
                                        $query->orderBy('order', 'asc');                 
                                        $query->with(['opd_pendukung'=>
                                                function($query){
                                                    $query->select('master_opd.id', 'master_opd.nama_opd');
                                                }
                                            ]);
                                      },
                                      'cascading' =>
                                      function($query){
                                        $query->orderBy('order', 'asc');                 
                                        $query->where('is_active', true); 
                                      }
                                   ]);
                               },

                               'indikator_tujuan' => 
                               function($query) {
                                    $query->orderBy('order', 'asc');                 
                                   $query->where('is_active', true);                                                   
                                   $query->where('is_tujuan', true);                                                   
                               }
                               
                               
                            ]);
                        } 
                     ]);
                }            
        ])->where('is_active', '=', true)
        ->where('id', $visi_id)
        ->get();

       
        return response()->json([
            'success' => true,
            'message' => 'Visi found.',
            'actions' => $rawActions,
        ]);
    }
}
