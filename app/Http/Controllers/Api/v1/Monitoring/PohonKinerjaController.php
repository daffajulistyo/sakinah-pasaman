<?php

namespace App\Http\Controllers\Api\v1\Monitoring;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\v1\MASTER\BaseController;

use App\Models\Sakip\OPD\TujuanOpd;
use App\Models\Sakip\OPD\SasaranOpd;
use App\Models\Sakip\OPD\IndikatorOpd;
use App\Models\Sakip\MASTER\MasterOpd;

class PohonKinerjaController extends Controller
{   

    public function index(Request $request)
    {   
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


        $tujuan = TujuanOPD::where('master_opd_id', '=', $master_opd_id)->get();
        $tujuan = $tujuan->map(function($item)  use ($request, $master_opd_id)
        {
                $jam = Carbon::parse($item->created_at)->diffInHours();
                if($jam > 24) {
                    $created_at = Carbon::parse($item->created_at)->format('d M Y H:i');
                }
                else
                {
                    $created_at = Carbon::createFromFormat('Y-m-d H:i:s', $item->created_at)->diffForHumans();
                }

                $indikator_tujuan = IndikatorOPD::where('tujuan_opd_id', '=', $item->id)
                                    ->where('master_opd_id', '=', $master_opd_id)
                                    ->where('is_tujuan', '=', true)
                                    ->get();
                $indikator_tujuan = $indikator_tujuan->map(function($item)  use ($request)
                {
                    $jam = Carbon::parse($item->created_at)->diffInHours();
                    if($jam > 24) {
                        $created_at = Carbon::parse($item->created_at)->format('d M Y H:i');
                    }
                    else
                    {
                        $created_at = Carbon::createFromFormat('Y-m-d H:i:s', $item->created_at)->diffForHumans();
                    }               

                    return [
                        "id" => $item->id,
                        "tujuan_opd_id" => $item->tujuan_opd_id,
                        "sasaran_opd_id" => $item->sasaran_opd_id,
                        "order" => $item->order,
                        "indikator" => $item->indikator,
                        "is_active" => $item->is_active,
                        "created_at" => $created_at
                    ];
                });

                $sasaran = SasaranOPD::where('tujuan_opd_id', '=', $item->id)
                                    ->where('master_opd_id', '=', $master_opd_id)
                                    ->where('parent_id', '=', 0)
                                    ->get();

                $sasaran = $sasaran->map(function($item)  use ($request, $master_opd_id)
                {
                    $jam = Carbon::parse($item->created_at)->diffInHours();
                    if($jam > 24) {
                        $created_at = Carbon::parse($item->created_at)->format('d M Y H:i');
                    }
                    else
                    {
                        $created_at = Carbon::createFromFormat('Y-m-d H:i:s', $item->created_at)->diffForHumans();
                    }

                    $cek = SasaranOpd::where('parent_id', '=', $item->id)
                                        ->count();
                    
                    if($cek <=0 )
                        $sub = "";
                    else
                    $sub = $this->getSubSasaran($item->id, $request);


                    $indikator_sasaran = IndikatorOPD::where('sasaran_opd_id', '=', $item->id)
                                    ->where('master_opd_id', '=', $master_opd_id)
                                    ->where('is_tujuan', '=', false)
                                    ->get();
                    $indikator_sasaran = $indikator_sasaran->map(function($item)  use ($request)
                    {
                        $jam = Carbon::parse($item->created_at)->diffInHours();
                        if($jam > 24) {
                            $created_at = Carbon::parse($item->created_at)->format('d M Y H:i');
                        }
                        else
                        {
                            $created_at = Carbon::createFromFormat('Y-m-d H:i:s', $item->created_at)->diffForHumans();
                        }               

                        return [
                            "id" => $item->id,
                            "tujuan_opd_id" => $item->tujuan_opd_id,
                            "sasaran_opd_id" => $item->sasaran_opd_id,
                            "order" => $item->order,
                            "indikator" => $item->indikator,
                            "is_active" => $item->is_active,
                            "created_at" => $created_at
                        ];
                    });
                

                    return [
                        "id" => $item->id,
                        "tujuan_opd_id" => $item->tujuan_opd_id,
                        "parent_id" => $item->parent_id,
                        "order" => $item->order,
                        "sasaran" => $item->sasaran,
                        "is_active" => $item->is_active,
                        "created_at" => $created_at,
                        "sub_sasaran" => $sub,
                        "indikator_sasaran" => $indikator_sasaran
                    ];
                });

                return [
                    "id" => $item->id,
                    "pohon_kinerja_sasaran_id" => $item->pohon_kinerja_sasaran_id,
                    "master_opd_id" => $item->master_opd_id,
                    "order" => $item->order,                    
                    "tujuan" => $item->tujuan,
                    "is_direct" => $item->is_direct,
                    "is_active" => $item->is_active,
                    "created_at" => $created_at,
                    "sasaran" => $sasaran,
                    "indikator_tujuan" => $indikator_tujuan
                ];
        });

        return response()->json([
            'success' => true,
            'message' => 'Pohon Kinerja '.$opd->nama_opd.' ',
            'data' => $tujuan,
        ]);
    }

    private function getSubSasaran($parent_id, $request)
    {   
        $master_opd_id = $request->master_opd_id;

        $sasaran = SasaranOpd::where('parent_id', '=', $parent_id)
                            ->where('master_opd_id', '=', $master_opd_id)
                            ->get();

        
        $sasaran = $sasaran->map(function($item) use ($request, $master_opd_id)
        {
            $jam = Carbon::parse($item->created_at)->diffInHours();
            if($jam > 24) {
                $created_at = Carbon::parse($item->created_at)->format('d M Y H:i');
            }
            else
            {
                $created_at = Carbon::createFromFormat('Y-m-d H:i:s', $item->created_at)->diffForHumans();
            }

            $cek = SasaranOpd::where('parent_id', '=', $item->id)
                                ->count();
            
            if($cek <=0 )
                $sub = "";
            else
                $sub = $this->getSubSasaran($item->id, $request); 
            
            $indikator_sasaran = IndikatorOPD::where('sasaran_opd_id', '=', $item->id)
                                    ->where('master_opd_id', '=', $master_opd_id)
                                    ->where('is_tujuan', '=', false)
                                    ->get();
            $indikator_sasaran = $indikator_sasaran->map(function($item)  use ($request)
            {
                $jam = Carbon::parse($item->created_at)->diffInHours();
                if($jam > 24) {
                    $created_at = Carbon::parse($item->created_at)->format('d M Y H:i');
                }
                else
                {
                    $created_at = Carbon::createFromFormat('Y-m-d H:i:s', $item->created_at)->diffForHumans();
                }               

                return [
                    "id" => $item->id,
                    "tujuan_opd_id" => $item->tujuan_opd_id,
                    "sasaran_opd_id" => $item->sasaran_opd_id,
                    "order" => $item->order,
                    "indikator" => $item->indikator,
                    "is_active" => $item->is_active,
                    "created_at" => $created_at
                ];
            });

            return [
                "id" => $item->id,
                "tujuan_opd_id" => $item->tujuan_opd_id,
                "parent_id" => $item->parent_id,
                "order" => $item->order,
                "sasaran" => $item->sasaran,
                "is_active" => $item->is_active,
                "created_at" => $created_at,
                "sub_sasaran" => $sub,
                "indikator_sasaran" => $indikator_sasaran
            ];
        });

        return $sasaran;
    }


    public function renstra(Request $request)
    {   
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
          $sasaran_kdh = BaseController::getSasaranByOPDPengampu($master_opd_id)->pluck('id');   

         $visi = BaseController::getCurrentVisi();
         $visi_id = !empty($visi->id) ?  $visi->id : '';

        $tujuan = tujuanOpd::where('master_opd_id', '=', $master_opd_id)
        ->where('pohon_kinerja_visi_id', '=', $visi_id)
        ->whereIn('pohon_kinerja_sasaran_id', $sasaran_kdh)
        ->get();

        $tujuan = $tujuan->map(function($item) use ($request)
        {    
            $master_opd_id = $request->master_opd_id;
            $sasaran = SasaranOpd::where('master_opd_id', '=', $master_opd_id)
                                ->where('tujuan_opd_id', '=', $item->id)
                                ->where('parent_id', '=', 0)
                                ->get(['id', 'sasaran', 'order', 'parent_id']);

            
            $indikator_opd = IndikatorOpd::where('master_opd_id', '=', $master_opd_id)
            ->where('tujuan_opd_id', '=', $item->id)
            ->where('is_tujuan', '=', true)
            ->get();

             $indikator_opd = $indikator_opd->map(function($is) use ($request)
                {   
                    $satuan = BaseController::getSatuanByID($is->satuan_id);

                    $visi_id = !empty($visi->id) ?  $visi->id : '';
                    return [
                        "id" => $is->id,
                        "tujuan_opd_id" => $is->id,
                        "sasaran_opd_id" => $is->id,
                        "indikator" => $is->indikator,
                        "order" => $is->order,
                        "defenisi"=> $is->defenisi,
                        "kegunaan"=> $is->kegunaan,
                        "rilis"=> $is->rilis,
                        "sumber_data"=> $is->sumber_data,
                        "satuan_id"=> $is->satuan_id,
                        "satuan"=> $satuan,
                        "baseline"=> $is->baseline,
                        "target_1"=> $is->target_1,
                        "target_2"=> $is->target_2,
                        "target_3"=> $is->target_3,
                        "target_4"=> $is->target_4,
                        "target_5"=> $is->target_5,
                        "target_6"=> $is->target_6,
                        "is_active"=> $is->is_active,
                        "is_indikator_kinerja_utama"=> $is->is_indikator_kinerja_utama,
                        "is_tujuan"=> $is->is_tujuan,
                        "pohon_kinerja_visi_id"=> $is->pohon_kinerja_visi_id,
                        "created_by"=> $is->created_by,
                        "created_at"=> $is->created_at,
                    ];                  
                });


            $sasaran = $sasaran->map(function($item) use ($request)
            {    
                $master_opd_id = $request->master_opd_id;
                            
                $indikator_sasaran = IndikatorOpd::where('sasaran_opd_id', $item->id) 
                                    ->where('master_opd_id', $master_opd_id)  
                                    ->distinct()
                                    ->get();

                $indikator_sasaran = $indikator_sasaran->map(function($is) use ($request)
                {   
                    $satuan = BaseController::getSatuanByID($is->satuan_id);

                    $visi_id = !empty($visi->id) ?  $visi->id : '';
                    return [
                        "id" => $is->id,
                        "tujuan_opd_id" => $is->id,
                        "sasaran_opd_id" => $is->id,
                        "indikator" => $is->indikator,
                        "order" => $is->order,
                        "defenisi"=> $is->defenisi,
                        "kegunaan"=> $is->kegunaan,
                        "rilis"=> $is->rilis,
                        "sumber_data"=> $is->sumber_data,
                        "satuan_id"=> $is->satuan_id,
                        "satuan"=> $satuan,
                        "baseline"=> $is->baseline,
                        "target_1"=> $is->target_1,
                        "target_2"=> $is->target_2,
                        "target_3"=> $is->target_3,
                        "target_4"=> $is->target_4,
                        "target_5"=> $is->target_5,
                        "target_6"=> $is->target_6,
                        "is_active"=> $is->is_active,
                        "is_indikator_kinerja_utama"=> $is->is_indikator_kinerja_utama,
                        "is_tujuan"=> $is->is_tujuan,
                        "pohon_kinerja_visi_id"=> $is->pohon_kinerja_visi_id,
                        "created_by"=> $is->created_by,
                        "created_at"=> $is->created_at,
                    ];                  
                });

                return [
                    "id" => $item->id,
                    "tujuan_opd_id" => $item->tujuan_opd_id,
                    "sasaran" => $item->sasaran,
                    "order" => $item->order,
                    "indikator_sasaran" => $indikator_sasaran,
                ];
            }); 

            return [
                "id" => $item->id,
                "tujuan" => $item->tujuan,
                "order" => $item->order,
                "sasaran" => $sasaran,
                "indikator_tujuan" => $indikator_opd
            ];   
        });
       
        return response()->json([
            'success' => true,
            'message' => 'Renstra '.$opd->nama_opd.' ',
            'data' => $tujuan,
        ]);
    }
    
    public function iku(Request $request)
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

    public function cascading(Request $request)
    {
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

        $tujuan = BaseController::getTujuanOPD($master_opd_id);
            $sasaran = SasaranOPD::whereIn('tujuan_opd_id', $tujuan)
                    ->where('master_opd_id', '=', $master_opd_id)
                    ->where('parent_id', '=', 0)
                    ->with(
                        ['program_pendukung' =>
                        function($query) use ($request, $master_opd_id) {
                            $query->where('is_active', true);                 
                            $query->where('master_opd_id', $master_opd_id);         
                        }
                       ])
                    ->orderBy('order', 'ASC')
                    ->get();
        
            $sasaran = $sasaran->map(function($item)  use ($request, $master_opd_id)
            {
                    $jam = Carbon::parse($item->created_at)->diffInHours();
                    if($jam > 24) {
                        $created_at = Carbon::parse($item->created_at)->format('d M Y H:i');
                    }
                    else
                    {
                        $created_at = Carbon::createFromFormat('Y-m-d H:i:s', $item->created_at)->diffForHumans();
                    }

                    $cek = SasaranOpd::where('parent_id', '=', $item->id)
                                        ->count();
                    
                    if($cek <=0 )
                        $sub = "";
                    else
                    $sub = $this->getSubSasaran($item->id, $request);


                    $indikator_sasaran = IndikatorOPD::where('sasaran_opd_id', '=', $item->id)
                                    ->where('master_opd_id', '=', $master_opd_id)
                                    ->where('is_tujuan', '=', false)
                                    ->get();
                    $indikator_sasaran = $indikator_sasaran->map(function($item)  use ($request)
                    {
                        $jam = Carbon::parse($item->created_at)->diffInHours();
                        if($jam > 24) {
                            $created_at = Carbon::parse($item->created_at)->format('d M Y H:i');
                        }
                        else
                        {
                            $created_at = Carbon::createFromFormat('Y-m-d H:i:s', $item->created_at)->diffForHumans();
                        }               

                        return [
                            "id" => $item->id,
                            "tujuan_opd_id" => $item->tujuan_opd_id,
                            "sasaran_opd_id" => $item->sasaran_opd_id,
                            "order" => $item->order,
                            "indikator" => $item->indikator,
                            "is_active" => $item->is_active,
                            "created_at" => $created_at
                        ];
                    });

                    return [
                        "id" => $item->id,
                        "tujuan_opd_id" => $item->tujuan_opd_id,
                        "parent_id" => $item->parent_id,
                        "order" => $item->order,
                        "sasaran" => $item->sasaran,
                        "is_active" => $item->is_active,
                        "created_at" => $created_at,
                        "sub_sasaran" => $sub,
                        "program_pendukung" => $item->program_pendukung,
                        "indikator_sasaran" => $indikator_sasaran
                    ];
            });

            return response()->json([
                'success' => true,
                'message' => 'Cascading OPD '.$opd->nama_opd.' ',
                'data' => $sasaran,
            ]);
    }
}
