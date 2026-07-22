<?php

namespace App\Http\Controllers\Api\v1\OPD;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

use App\Models\Sakip\MASTER\MasterSatuan;
use App\Models\Sakip\MASTER\MasterOpd;
use App\Http\Controllers\Api\v1\MASTER\BaseController;

use App\Models\Sakip\OPD\SasaranOpd;
use App\Models\Sakip\OPD\IndikatorOpd;
use App\Models\Sakip\OPD\CascadingOpd;

class CascadingOPDController extends Controller
{
    public function showall(Request $request)
    {
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

        $tujuan = BaseController::getTujuanOPD($master_opd_id);

        /*$rawActions = SasaranOPD::with(
                        ['program_pendukung' =>
                        function($query) use ($request) {
                            $master_opd_id = $request->attributes->get('payload')->opd->id;
                            $query->where('is_active', true);                 
                            $query->where('master_opd_id', $master_opd_id);         
                        }
                       ])
                      ->whereIn('tujuan_opd_id', $tujuan)
                      ->get(); */

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
                    $sub = $this->getSubSasaran($item->id, $request);


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

     private function getSubSasaran($parent_id, $request)
    {   
        $master_opd_id = $request->attributes->get('payload')->opd->id;

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
                $sub = $this->getSubSasaran($item->id, $request); 
            
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

    public function create(Request $request)
    {
        try {
           
            /*------------ cek validasi Sasaran-------------------------------------*/
            $test = collect($request);

            $insert =array();
            
            foreach (json_decode($test) as $req)
            {   
                 $master_opd_id = $request->attributes->get('payload')->opd->id;
                 $sasaran_opd_id = $req->sasaran_opd_id;

                 $cascading =  CascadingOpd::where('sasaran_opd_id', '=', $req->sasaran_opd_id)
                 ->where('master_opd_id', '=', $master_opd_id)
                 ->delete();

                $sasaran = SasaranOpd::where('id', '=', $req->sasaran_opd_id)
                           ->where('master_opd_id', '=', $master_opd_id)
                           ->count();
                
                if($sasaran <= 0){

                }
                
                                       
                        
                $form['master_opd_id'] = $master_opd_id;
                $form['sasaran_opd_id'] = $req->sasaran_opd_id;
                $form['tahun'] = $req->tahun;
                $form['id_program'] = $req->id_program;
                $form['kode_program'] = $req->kode_program;
                $form['nama_program'] = $req->nama_program;
                $form['id_skpd'] = $req->id_skpd;
                $form['order'] = 1;
                $form['is_active'] = true;

                $form['created_by'] = $request->attributes->get('payload')->username;  
                
                $insert[] = $form;
            }

             // insert into table db
            $data = CascadingOpd::insert($insert);                                            
               

            // return response json
            return response()->json([
                'success' => true,
                'message' => 'Successfully created cascading OPD.',
                'data' => $insert,
            ]);                            
            
        } catch (\Throwable $th) {

            // handdle error
            return response()->json([
                "success" => false,
                "message" => "Something went wrong!",
                "errors" => $th->getMessage()
            ],500);
        }
    }
}
