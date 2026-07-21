<?php

namespace App\Http\Controllers\Api\v1\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

use App\Models\Sakip\MASTER\MasterOpd;
use App\Models\Sakip\MASTER\MasterSatuan;
use App\Models\Sakip\OPD\TujuanOpd;
use App\Models\Sakip\OPD\SasaranOpd;
use App\Models\Sakip\OPD\IndikatorOpd;

use App\Models\Sakip\OPD\Renja;
use App\Models\Sakip\OPD\RenjaProgram;

use App\Models\Sakip\OPD\PerjanjianKinerja;
use App\Models\Sakip\OPD\PerjanjianKinerjaProgram;

use App\Models\Sakip\OPD\Rencana;
use App\Models\Sakip\OPD\RencanaLangkah;

use App\Http\Controllers\Api\v1\Dashboard\BaseController;

class OpdController extends BaseController
{
    public function list(){
        try 
        {
            $opd = MasterOpd::where('is_active', true)
                                ->get(['id', 'kode_opd', 'nama_opd', 'alias_opd']);

            return response()->json([
                'success' => true,
                'message' => 'opd Aktif',
                'data' => $opd,
            ]);

        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'success' => false,
                'message' => 'List of Sasaran dan OPD Pendukung',
                'data' => [],
                'errors' => $th->getMessage()
            ], 500);
        }
    }


    public function pohonkinerja(Request $request)
    {   
        $master_opd_id = $request->get('master_opd_id');
        
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
        $master_opd_id = $request->get('master_opd_id');

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


    public function cascading(Request $request)
    {
        $master_opd_id = $request->get('master_opd_id');

         // cek existing opd
         $opd = MasterOpd::find($master_opd_id);
         if (!$opd) {
             // jika data tidak ditamukan di dalam database
             return response()->json([
                 'success' => false,
                 'message' => 'Perangkat Daerah not found.',
             ], 404);
         }

        $tujuan = $this->getTujuanOPD($master_opd_id);

            $sasaran = SasaranOPD::whereIn('tujuan_opd_id', $tujuan)
                    ->where('master_opd_id', '=', $master_opd_id)
                    ->where('parent_id', '=', 0)
                    ->where('is_active', true)
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
                    $sub = $this->getSubSasaranCascading($item->id, $request);


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
                        "sub_sasaran" => $sub,
                        "program_pendukung" => $item->program_pendukung,
                        "indikator_sasaran" => $indikator_sasaran
                    ];
            });

            return response()->json([
                'success' => true,
                'message' => 'Cascading OPD ',
                'data' => $sasaran,
            ]);
    }

     private function getSubSasaranCascading($parent_id, $request)
    {   
        $master_opd_id = $request->get('master_opd_id');

        $sasaran = SasaranOpd::where('parent_id', '=', $parent_id)
                            ->where('master_opd_id', '=', $master_opd_id)
                            ->where('is_active', true)
                            ->with(
                                ['program_pendukung' =>
                                function($query) use ($request, $master_opd_id) {
                                    $query->where('is_active', true);                 
                                    $query->where('master_opd_id', $master_opd_id);         
                                }
                            ])
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
                $sub = $this->getSubSasaranCascading($item->id, $request); 
            
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
                "sub_sasaran" => $sub,
                "program_pendukung" => $item->program_pendukung,
                "indikator_sasaran" => $indikator_sasaran
            ];
        });

        return $sasaran;
    }

    public function renja(Request $request)
    {
        
        $master_opd_id = $request->get('master_opd_id');
         // cek existing opd
         $opd = MasterOpd::find($master_opd_id);
         if (!$opd) {
             // jika data tidak ditamukan di dalam database
             return response()->json([
                 'success' => false,
                 'message' => 'Perangkat Daerah not found.',
             ], 404);
         }

         
        $tujuan = $this->getTujuanOPD($master_opd_id);
        

        $sasaran = SasaranOpd::whereIn('tujuan_opd_id', $tujuan)
                        ->where('parent_id', '=', 0)
                        ->where('master_opd_id', '=', $master_opd_id)
                        ->where('is_active', true)
                        ->orderBy('order', 'asc')
                        ->distinct()
                        ->get(['id', 'sasaran', 'order', 'parent_id', 'tujuan_opd_id']);

      

        $sasaran = $sasaran->map(function($item) use ($request)
        {    
            $master_opd_id = $request->get('master_opd_id');

            $anggaran = RenjaProgram::where('sasaran_opd_id', '=', $item->id)
                            ->where('master_opd_id', '=',  $master_opd_id)
                            ->where('tahun', '=',          $request->tahun)
                            ->where('murni', '=',       $request->murni)
                            ->sum('anggaran');

            $indikator_sasaran = IndikatorOpd::where('sasaran_opd_id', $item->id) 
                                ->where('master_opd_id', $master_opd_id) 
                                ->where('is_active', true)
                                ->orderBy('order', 'asc')
                                ->select('id','indikator', 'order')
                                ->distinct()
                                ->get();

            $indikator_sasaran = $indikator_sasaran->map(function($is) use ($request)
            {   
                $master_opd_id = $request->get('master_opd_id');

                $renja = Renja::where('indikator_opd_id', $is->id) 
                ->where('master_opd_id', $master_opd_id) 
                ->where('tahun', $request->tahun)            
                ->where('murni', $request->murni)            
                ->get()
                ->first();

                return [
                    "id" => $is->id,
                    "indikator" => $is->indikator,
                    "order" => $is->order,
                    "renja"=> $renja
                ];                  
            }); 

            return [
                "id" => $item->id,
                "tujuan_opd_id" => $item->tujuan_opd_id,
                "sasaran" => $item->sasaran,
                "order" => $item->order,
                "indikator_sasaran" => $indikator_sasaran,
                "anggaran" => $anggaran
            ];
        });

        
        $periode = ($request->murni=="true") ? 'Murni' : 'Perubahan';
        return response()->json([
            'success' => true,
            'message' => 'Daftar Target Renja  '.$periode.' Tahun '.$request->tahun.'  ',
            'data' => $sasaran,
        ]);
    }

    public function perjanjiankinerja(Request $request)
    {
        $master_opd_id = $request->get('master_opd_id');

        $eselon = !empty($request->eselon) ? $request->eselon : 'II';

        if($eselon=="II") 
            $level =0;
        else if($eselon=="III")
            $level =1;
        else
            $level =2;


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

        $sasaran = SasaranOpd::whereIn('tujuan_opd_id', $tujuan)
                        ->where('level', $level)
                        ->where('parent_id', '=', 0)
                        ->where('is_active', true)
                        ->orderBy('order', 'asc')
                        ->distinct()
                        ->get(['id', 'sasaran', 'order', 'tujuan_opd_id', 'is_active']);

        $sasaran = $sasaran->map(function($item) use ($request)
        {    
            $master_opd_id = $request->get('master_opd_id');
            $anggaran = PerjanjianKinerjaProgram::where('sasaran_opd_id', '=', $item->id)
                            ->where('master_opd_id', '=',  $master_opd_id)
                            ->where('tahun', '=',          $request->tahun)
                            ->where('murni', '=',       $request->murni)
                            ->sum('anggaran');

         
            $indikator_sasaran = IndikatorOpd::where('sasaran_opd_id', $item->id) 
                                ->where('master_opd_id', $master_opd_id)                                 
                                ->where('is_active', true)
                                ->orderBy('order', 'asc')
                                ->select('id','indikator', 'order', 'is_active')
                                ->orderBy('order', 'ASC')
                                ->distinct()
                                ->get();

            $indikator_sasaran = $indikator_sasaran->map(function($is) use ($request)
            {   
                $master_opd_id = $request->get('master_opd_id');

                $perjanjian_kinerja = PerjanjianKinerja::where('indikator_opd_id', $is->id) 
                ->where('master_opd_id', $master_opd_id) 
                ->where('tahun', $request->tahun)            
                ->where('murni', $request->murni) 
                ->first();

                return [
                    "id" => $is->id,
                    "indikator" => $is->indikator,
                    "order" => $is->order,
                    "is_active" => $is->is_active,
                    "perjanjian_kinerja"=> $perjanjian_kinerja
                ];                  
            });

            return [
                "id" => $item->id,
                "tujuan_opd_id" => $item->tujuan_opd_id,
                "sasaran" => $item->sasaran,
                "order" => $item->order,
                "is_active" => $item->is_active,
                "indikator_sasaran" => $indikator_sasaran,
                "anggaran" => $anggaran
            ];
        });

        $periode = ($request->murni=="true") ? 'Murni' : 'Perubahan';

        return response()->json([
            'success' => true,
            'message' => 'Daftar Target Perjanjian Kinerja Eselon '.$eselon.'  '.$periode.' Tahun '.$request->tahun.'  ',
            'data' => $sasaran,
        ]);
    }


     public function rencana(Request $request)
    {
        $master_opd_id = $request->get('master_opd_id');

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

       
        $sasaran = SasaranOpd::join('pk_opd', 'pk_opd.sasaran_opd_id', '=', 'sasaran_opd.id')
                        ->whereIn('sasaran_opd.tujuan_opd_id', $tujuan)
                        ->where('sasaran_opd.parent_id', 0)
                        ->where('pk_opd.tahun', $request->tahun)
                        ->where('sasaran_opd.is_active', true)
                        ->orderBy('sasaran_opd.order', 'asc')
                        ->distinct()
                        ->get(['sasaran_opd.id', 'sasaran_opd.sasaran', 'sasaran_opd.order', 'sasaran_opd.tujuan_opd_id']);

        $sasaran = $sasaran->map(function($item) use ($request)
        {    
            $master_opd_id = $request->get('master_opd_id');

            $indikator_sasaran = IndikatorOpd::join('pk_opd', 'pk_opd.indikator_opd_id', '=', 'indikator_opd.id') 
                                ->where('indikator_opd.sasaran_opd_id', $item->id) 
                                ->where('pk_opd.master_opd_id', $master_opd_id) 
                                ->where('pk_opd.tahun', $request->tahun) 
                                ->where('indikator_opd.is_active', true)
                                ->orderBy('indikator_opd.order', 'asc')
                                ->select('indikator_opd.id','indikator_opd.indikator', 'indikator_opd.order')
                                ->distinct()
                                ->get(); 

            $anggaran_murni = PerjanjianKinerjaProgram::where('sasaran_opd_id', '=', $item->id)
            ->where('tahun', '=',$request->tahun)
            ->where('master_opd_id', $master_opd_id) 
            ->where('murni', '=',true)
            ->sum('anggaran');

            $anggaran_perubahan = PerjanjianKinerjaProgram::where('sasaran_opd_id', '=', $item->id)
            ->where('tahun', '=',$request->tahun)
            ->where('master_opd_id', $master_opd_id) 
            ->where('murni', '=',false)
            ->sum('anggaran');

            $indikator_sasaran = $indikator_sasaran->map(function($is) use ($request)
            {   
                $master_opd_id = $request->get('master_opd_id');

                $rencana_aksi = Rencana::where('indikator_opd_id', $is->id) 
                ->where('master_opd_id', $master_opd_id) 
                ->where('tahun', $request->tahun)
                ->select('target_tw1','target_tw2', 'target_tw3', 'target_tw4')                
                ->get()
                ->first();


                $langkah = RencanaLangkah::where('indikator_opd_id', '=', $is->id)
                               ->where('tahun', '=', $request->tahun)
                               ->where('master_opd_id', '=',$master_opd_id)
                               ->get();  

                $langkah = $langkah->map(function($dl) use($request) 
                {
                    return [
                        "id"            => $dl->id,
                        "langkah"       => $dl->langkah,
                        "satuan"       => $dl->satuan,
                        "keterangan"       => $dl->keterangan,
                        "tahun"       => $dl->tahun,
                        "target_tw1"       => $dl->target_tw1,
                        "target_tw2"       => $dl->target_tw2,
                        "target_tw3"       => $dl->target_tw3,
                        "target_tw4"       => $dl->target_tw4
                    ];
                });                

                $target_pk = PerjanjianKinerja::where('indikator_opd_id', '=', $is->id)
                ->where('tahun', '=',$request->tahun)
                ->where('master_opd_id', '=', $master_opd_id)
                ->get(['target', 'tahun', 'murni']); 
                
                return [
                    "id" => $is->id,
                    "indikator" => $is->indikator,
                    "order" => $is->order,
                    "rencana_aksi"=> $rencana_aksi,
                    "langkah" => $langkah,
                    "target_perjanjian_kinerja" => $target_pk
                ];                  
            }); 

            return [
                "id" => $item->id,
                "tujuan_opd_id" => $item->tujuan_opd_id,
                "sasaran" => $item->sasaran,
                "order" => $item->order,
                "indikator_sasaran" => $indikator_sasaran,
                "anggaran_perjanjian_kinerja" => ['murni'=> $anggaran_murni, 'perubahan'=>$anggaran_perubahan],
            ];
        });

        return response()->json([
            'success' => true,
            'message' => 'Daftar Target Rencana Aksi Tahun '.$request->tahun.'  ',
            'data' => $sasaran,
        ]);
    }


    public function realisasi(Request $request)
    {
        $master_opd_id = $request->get('master_opd_id');

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

       
        $sasaran = SasaranOpd::join('pk_opd', 'pk_opd.sasaran_opd_id', '=', 'sasaran_opd.id')
                        ->whereIn('sasaran_opd.tujuan_opd_id', $tujuan)
                        ->where('sasaran_opd.parent_id', 0)
                        ->where('pk_opd.tahun', $request->tahun)
                        ->where('sasaran_opd.is_active', true)
                        ->orderBy('sasaran_opd.order', 'asc')
                        ->distinct()
                        ->get(['sasaran_opd.id', 'sasaran_opd.sasaran', 'sasaran_opd.order', 'sasaran_opd.tujuan_opd_id']);

        $sasaran = $sasaran->map(function($item) use ($request)
        {    
            $master_opd_id = $request->get('master_opd_id');

            $indikator_sasaran = IndikatorOpd::join('pk_opd', 'pk_opd.indikator_opd_id', '=', 'indikator_opd.id') 
                                ->where('indikator_opd.sasaran_opd_id', $item->id) 
                                ->where('pk_opd.master_opd_id', $master_opd_id) 
                                ->where('pk_opd.tahun', $request->tahun) 
                                ->where('indikator_opd.is_active', true)
                                ->orderBy('indikator_opd.order', 'asc')
                                ->select('indikator_opd.id','indikator_opd.indikator', 'indikator_opd.order')
                                ->distinct()
                                ->get(); 

            $anggaran_murni = PerjanjianKinerjaProgram::where('sasaran_opd_id', '=', $item->id)
            ->where('tahun', '=',$request->tahun)
            ->where('master_opd_id', $master_opd_id) 
            ->where('murni', '=',true)
            ->sum('anggaran');

            $anggaran_perubahan = PerjanjianKinerjaProgram::where('sasaran_opd_id', '=', $item->id)
            ->where('tahun', '=',$request->tahun)
            ->where('master_opd_id', $master_opd_id) 
            ->where('murni', '=',false)
            ->sum('anggaran');

            $indikator_sasaran = $indikator_sasaran->map(function($is) use ($request)
            {   
                $master_opd_id = $request->get('master_opd_id');

                $rencana_aksi = Rencana::where('indikator_opd_id', $is->id) 
                ->where('master_opd_id', $master_opd_id) 
                ->where('tahun', $request->tahun)
                ->select('id','target_tw1','target_tw2', 'target_tw3', 'target_tw4', 'realisasi_tw1', 'realisasi_tw2', 'realisasi_tw3','realisasi_tw4', 'capaian_tw1', 'capaian_tw2', 'capaian_tw3', 'capaian_tw4', 'hambatan', 'tindak_lanjut')  
                ->first();


                $langkah = RencanaLangkah::where('indikator_opd_id', '=', $is->id)
                               ->where('tahun', '=', $request->tahun)
                               ->where('master_opd_id', '=', $master_opd_id)
                               ->get();  

                $langkah = $langkah->map(function($dl) use($request) 
                {
                    return [
                        "id"            => $dl->id,
                        "langkah"       => $dl->langkah,
                        "satuan"       => $dl->satuan,
                        "keterangan"       => $dl->keterangan,
                        "tahun"       => $dl->tahun,
                        "target_tw1"       => $dl->target_tw1,
                        "target_tw2"       => $dl->target_tw3,
                        "target_tw3"       => $dl->target_tw3,
                        "target_tw4"       => $dl->target_tw4,
                        "realisasi_tw1"       => $dl->realisasi_tw1,
                        "realisasi_tw2"       => $dl->realisasi_tw2,
                        "realisasi_tw3"       => $dl->realisasi_tw3,
                        "realisasi_tw4"       => $dl->realisasi_tw4,
                        "capaian_tw1"       => $dl->capaian_tw1,
                        "capaian_tw2"       => $dl->capaian_tw2,
                        "capaian_tw3"       => $dl->capaian_tw3,
                        "capaian_tw4"       => $dl->capaian_tw4
                    ];
                });                

                $target_pk = PerjanjianKinerja::where('indikator_opd_id', '=', $is->id)
                ->where('tahun', '=',$request->tahun)
                ->where('master_opd_id', '=',$request->master_opd_id)
                ->get(['target', 'tahun', 'murni']); 
                
                return [
                    "id" => $is->id,
                    "indikator" => $is->indikator,
                    "order" => $is->order,
                    "rencana_aksi"=> $rencana_aksi,
                    "langkah" => $langkah,
                    "target_perjanjian_kinerja" => $target_pk
                ];                  
            }); 

            return [
                "id" => $item->id,
                "tujuan_opd_id" => $item->tujuan_opd_id,
                "sasaran" => $item->sasaran,
                "order" => $item->order,
                "indikator_sasaran" => $indikator_sasaran,
                "anggaran_perjanjian_kinerja" => ['murni'=> $anggaran_murni, 'perubahan'=>$anggaran_perubahan],
            ];
        });

        return response()->json([
            'success' => true,
            'message' => 'Daftar Target & Realisasi Rencana Aksi  '.$opd->nama_opd.' Tahun '.$request->tahun.'  ',
            'data' => $sasaran,
        ]);
    }

    public function renstra(Request $request)
    {   
        $master_opd_id = $request->get('master_opd_id');
        $sasaran_kdh = BaseController::getSasaranByOPDPengampu($master_opd_id)->pluck('id');        
        
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

        $tujuan = tujuanOpd::where('master_opd_id', '=', $master_opd_id)
        ->where('pohon_kinerja_visi_id', '=', $visi_id)
        ->whereIn('pohon_kinerja_sasaran_id', $sasaran_kdh)
        ->where('is_active', true)
        ->orderBy('order', 'ASC')
        ->get();

        $tujuan = $tujuan->map(function($item) use ($request)
        {    
            $master_opd_id = $request->get('master_opd_id');
            $sasaran = SasaranOpd::where('master_opd_id', '=', $master_opd_id)
                                ->where('tujuan_opd_id', '=', $item->id)
                                ->where('parent_id', '=', 0)
                                ->where('is_active', true)
                                ->orderBy('order', 'ASC')
                                ->get(['id', 'sasaran', 'order', 'parent_id']);

            
            $indikator_opd = IndikatorOpd::where('master_opd_id', '=', $master_opd_id)
            ->where('tujuan_opd_id', '=', $item->id)
            ->where('is_tujuan', '=', true)
            ->where('is_active', true)
            ->orderBy('order', 'ASC')
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
                $master_opd_id = $request->get('master_opd_id');
                            
                $indikator_sasaran = IndikatorOpd::where('sasaran_opd_id', $item->id) 
                                    ->where('master_opd_id', $master_opd_id)  
                                    ->where('is_active', true)
                                    ->orderBy('order', 'ASC')
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
            'message' => 'Renstra OPD',
            'data' => $tujuan,
        ]);
    }
}
