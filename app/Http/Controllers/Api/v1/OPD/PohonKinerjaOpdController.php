<?php

namespace App\Http\Controllers\Api\v1\OPD;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\v1\MASTER\BaseController;

use App\Models\Sakip\OPD\TujuanOpd;
use App\Models\Sakip\OPD\SasaranOpd;
use App\Models\Sakip\OPD\IndikatorOpd;
use App\Models\Sakip\MASTER\MasterOpd;

use App\Models\Sakip\KDH\PohonKinerjaVisi;

class PohonKinerjaOpdController extends Controller
{   

    public function index(Request $request)
    {   
        $master_opd_id = $request->get('payload')->opd->id;
        
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
        $tujuan = TujuanOPD::where('master_opd_id', '=', $master_opd_id)->where('pohon_kinerja_visi_id', '=', $visi_id)->get();

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
                                    ->where('is_sasaran_operasional', false)
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

                    $cek = SasaranOpd::where('parent_id', '=', $item->id)->where('is_sasaran_operasional', false)
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
        $master_opd_id = $request->get('payload')->opd->id;

        $sasaran = SasaranOpd::where('parent_id', '=', $parent_id)
                            ->where('master_opd_id', '=', $master_opd_id)
                            ->where('is_sasaran_operasional', false)
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

            $cek = SasaranOpd::where('parent_id', '=', $item->id)->where('is_sasaran_operasional', false)
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

    public function index2(Request $request)
    {   
        $master_opd_id = $request->get('payload')->opd->id;
        
         // cek existing opd
         $opd = MasterOpd::find($master_opd_id);
         if (!$opd) {
             // jika data tidak ditamukan di dalam database
             return response()->json([
                 'success' => false,
                 'message' => 'Perangkat Daerah not found.',
             ], 404);
         }


        $rawActions = TujuanOPD::with(
          ['sasaran' => 
                function($query) {
                    $query->where('is_active', true);                 
                    $query->with([
                        'indikator_sasaran' =>
                        function($query) {
                            $query->where('is_active', true);                                                   
                            $query->where('is_tujuan', false);                                                   
                        }   
                    ]);                    
                },

          'indikator_tujuan' => 
            function($query) {
                $query->where('is_active', true);                                                   
                $query->where('is_tujuan', true);                                                   
            }                                            
        ])
        ->where('master_opd_id', '=', $master_opd_id)
        ->get();
       
        return response()->json([
            'success' => true,
            'message' => 'Pohon Kinerja '.$opd->nama_opd.' ',
            'data' => $rawActions,
        ]);
    }


     public function tujuan_opd(Request $request)
    {   
        $master_opd_id = $request->get('payload')->opd->id;
        
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
        $visi_id = !empty($request->visi_id) ?  $request->visi_id :  $visi->id;

        if(!Str::isUuid($visi_id)){
            return response()->json([
                'success' => false,
                'message' => 'Invalid Id, Visi not Found',
            ], 404);
        }
        
        $cek_visi = PohonKinerjaVisi::find($visi_id);
        if (!$cek_visi) {
             // jika data tidak ditamukan di dalam database
             return response()->json([
                 'success' => false,
                 'message' => 'Visi Not Found',
             ], 404);
         }


        $tujuan = TujuanOPD::where('master_opd_id', '=', $master_opd_id)->where('pohon_kinerja_visi_id', '=', $visi_id)
                ->where('is_active', true)    
                ->get();

        $tujuan = $tujuan->map(function($item)  use ($request, $master_opd_id, $visi)
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
                                    ->where('is_tujuan', true)
                                    ->where('is_active', true)
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

                return [
                    "id" => $item->id,
                    "pohon_kinerja_visi_id" => $item->pohon_kinerja_visi_id,
                    "pohon_kinerja_sasaran_id" => $item->pohon_kinerja_sasaran_id,
                    "master_opd_id" => $item->master_opd_id,
                    "order" => $item->order,                    
                    "tujuan" => $item->tujuan,
                    "is_direct" => $item->is_direct,
                    "is_active" => $item->is_active,
                    "created_at" => $created_at,
                    "indikator_tujuan" => $indikator_tujuan
                ];
        });

        return response()->json([
            'success' => true,
            'message' => 'Pohon Kinerja '.$opd->nama_opd.' ',
            'data' => $tujuan,
            'visi' => $cek_visi
        ]);
    }


     public function sasaran_opd(Request $request)
     {   
        $master_opd_id = $request->get('payload')->opd->id;
        
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
            $visi_id = !empty($request->visi_id) ?  $request->visi_id :  $visi->id;
            
             if(!Str::isUuid($visi_id)){
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid Id, Visi not Found',
                ], 404);
            }
            
            $cek_visi = PohonKinerjaVisi::find($visi_id);
            if (!$cek_visi) {
                // jika data tidak ditamukan di dalam database
                return response()->json([
                    'success' => false,
                    'message' => 'Visi Not Found',
                ], 404);
            }

             $tujuan = TujuanOPD::where('master_opd_id', '=', $master_opd_id)
                    ->where('pohon_kinerja_visi_id', '=', $visi_id)
                    ->where('is_active', true)
                    ->get()->pluck('id');
      
                $sasaran = SasaranOPD::whereIn('tujuan_opd_id', $tujuan)
                                    ->where('master_opd_id', '=', $master_opd_id)
                                    ->where('parent_id', '=', 0)
                                    ->where('is_active', true)
                                    ->get();

                $sasaran = $sasaran->map(function($item)  use ($request, $master_opd_id, $visi)
                {
                    $jam = Carbon::parse($item->created_at)->diffInHours();
                    if($jam > 24) {
                        $created_at = Carbon::parse($item->created_at)->format('d M Y H:i');
                    }
                    else
                    {
                        $created_at = Carbon::createFromFormat('Y-m-d H:i:s', $item->created_at)->diffForHumans();
                    }
                 


                    $indikator_sasaran = IndikatorOPD::where('sasaran_opd_id', '=', $item->id)
                                    ->where('master_opd_id', '=', $master_opd_id)
                                    ->where('is_tujuan', '=', false)
                                    ->where('is_active', true)
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
                        "indikator_sasaran" => $indikator_sasaran
                    ];
                });            

            return response()->json([
                'success' => true,
                'message' => 'Pohon Kinerja '.$opd->nama_opd.' ',
                'data' => $sasaran,
                'visi' => $cek_visi
            ]);
    }


    public function list_visi(Request $request)
    {   
        $visi = PohonKinerjaVisi::get();

         // remap
        $visi = $visi->map(function($item){
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
                "period_starts" => $item->period_starts,
                "period_ends" => $item->period_ends,
                "visi" => $item->visi,
                "is_active" => $item->is_active,
                "created_at" => $created_at,
            ];
        });

        return response()->json([
            'success' => true,
            'message' => 'List of Pohon Kinerja Visi',
            'data' => $visi
        ]);

    }
}
