<?php

namespace App\Http\Controllers\Api\v1\KDH;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use App\Models\Sakip\KDH\PohonKinerjaVisi;

use App\Models\Sakip\KDH\PohonKinerjaTujuan;
use App\Models\Sakip\KDH\PohonKinerjaSasaran;
use App\Models\Sakip\KDH\PohonKinerjaIndikator;

use App\Models\Sakip\MASTER\MasterSatuan;
use App\Models\MASTER\MasterOpd;
use Barryvdh\DomPDF\Facade\Pdf;

class RpjmdController extends Controller
{
    public function update($id, Request $request)
    {
        try {
            // cek validasi jika id berformar uuid atau tidak
            if(!Str::isUuid($id)){
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid Id, IKU not Found',
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

         
            // cek validasi jika id berformat uuid atau tidak
            if(!Str::isUuid($request->satuan_id)){
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid Id, Satuan not Found',
                ], 422);
            }
             // cek existing satuan
            $satuan = MasterSatuan::find($request->satuan_id);
            if (!$satuan) {
                return response()->json([
                    'success' => false,
                    'message' => $satuan.''.$request->satuan,
                ], 404);
            }

            $form = $request->validate([                
                "satuan_id"   => "required|string",               
                "target_1" => "required|string",
                "target_2" => "required|string",
                "target_3" => "required|string",
                "target_4" => "required|string",
                "target_5" => "required|string",
                "target_6" => "required|string"
            ]);

            $form['satuan_id'] = $request->satuan_id;            
            $form['target_1'] = $request->target_1;
            $form['target_2'] = $request->target_2;
            $form['target_3'] = $request->target_3;
            $form['target_4'] = $request->target_4;
            $form['target_5'] = $request->target_5;
            $form['target_6'] = $request->target_6;

            $form['updated_by'] = $request->get('payload')->username;

            $indikator->update($form);

            return response()->json([
                'success' => true,
                'message' => 'RPJMD updated successfully.',
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


    public function list()
    {
        $actions = [];
    
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
                                   $query->with([
                                      'indikator_sasaran' => function($query){
                                        $query->with(['satuan']);
                                      }
                                   ]);
                               }                               
                            ]);
                        } 
                     ]);
                }            
        ])->where('is_active', '=', true)
        ->get()
        ->first();

       
        return response()->json([
            'success' => true,
            'message' => 'Visi found.',
            'actions' => $rawActions,
        ]);
    }

    public function generate_pdf()
    {
        $actions = [];
    
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
                                   $query->with([
                                      'indikator_sasaran' => function($query){
                                        $query->with(['satuan']);
                                      }
                                   ]);
                               }                               
                            ]);
                        } 
                     ]);
                }            
        ])->where('is_active', '=', true)
        ->get()
        ->first();

       
       $data = [
            'generated_at' => now()->toDateTimeString(),
            'visi' => $rawActions
        ];
        

        $pdf = Pdf::loadView('report_template.kdh.rpjmd', compact('data'))
                        ->setPaper('Legal', 'portrait');
           return $pdf->download('RPJMD.pdf');
    }

     public function generate_pdf_iku()
    {
        $actions = [];
    
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
                                   $query->with([
                                      'indikator_sasaran' => function($query){
                                        $query->with(['satuan']);
                                      }
                                   ]);
                               }                               
                            ]);
                        } 
                     ]);
                }            
        ])->where('is_active', '=', true)
        ->get()
        ->first();

       
       $data = [
            'generated_at' => now()->toDateTimeString(),
            'visi' => $rawActions
        ];
        

        $pdf = Pdf::loadView('report_template.kdh.iku', compact('data'))
                        ->setPaper('Legal', 'portrait');
           return $pdf->download('iku.pdf');
    }
}
