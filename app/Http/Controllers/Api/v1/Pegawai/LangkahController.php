<?php

namespace App\Http\Controllers\Api\v1\Pegawai;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;

use App\Models\Sakip\MASTER\MasterSatuan;
use App\Models\Sakip\MASTER\MasterOpd;
use App\Http\Controllers\Api\v1\MASTER\BaseController;
use App\Models\Sakip\OPD\SasaranOpd;
use App\Models\Sakip\OPD\IndikatorOpd;
use App\Models\Sakip\OPD\Rencana;
use App\Models\Sakip\OPD\RencanaLangkah;
use App\Models\Sakip\OPD\Pengampu;

class LangkahController extends Controller
{
    public function create(Request $request)
    {
        try {

            $master_opd_id = $request->get('payload')->opd->id;
            $username = $request->get('payload')->username;
            $nip = $request->get('payload')->nip;
            $jabatan_id = $request->get('payload')->jabatan_id;


            // cek validasi jika id berformar uuid atau tidak
            if(!Str::isUuid($request->sasaran_opd_id)){
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid Id, Sasaran OPD not Found',
                ], 422);
            }
            // cek existing sasaran
            $sasaran = SasaranOpd::find($request->sasaran_opd_id);
            if (!$sasaran) {
                // jika data tidak ditamukan di dalam database
                return response()->json([
                    'success' => false,
                    'message' => 'sasaran not found.',
                ], 404);
            }


            // cek validasi jika id berformar uuid atau tidak
            if(!Str::isUuid($request->satuan_id)){
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid Id, Satuan not Found',
                ], 422);
            }
            // cek existing satuan
            $satuan = MasterSatuan::find($request->satuan_id);
            if (!$satuan) {
                // jika data tidak ditamukan di dalam database
                return response()->json([
                    'success' => false,
                    'message' => 'Satuan not found.',
                ], 404);
            }

             // cek validasi jika id berformar uuid atau tidak
            if(!Str::isUuid($request->indikator_opd_id)){
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid Id, Indikator not Found',
                ], 422);
            }
            // cek existing indikator
            $indikator = IndikatorOpd::find($request->indikator_opd_id);
            if (!$indikator) {
                // jika data tidak ditamukan di dalam database
                return response()->json([
                    'success' => false,
                    'message' => 'indikator not found.',
                ], 404);
            }


            $opd = MasterOpd::find($master_opd_id);
            if (!$opd) {
                // jika data tidak ditamukan di dalam database
                return response()->json([
                    'success' => false,
                    'message' => 'Perangkat Daerah not found.',
                ], 404);
            }


            $cek_pengampu = Pengampu::where('sasaran_opd_id', '=', $request->sasaran_opd_id)
                        ->where('indikator_opd_id', '=', $request->indikator_opd_id)
                        ->where('nip', '=', $nip)
                        ->where('jabatan_id', '=', $jabatan_id)
                        ->count();

            if ($cek_pengampu <= 0) {
                // jika data tidak ditamukan di dalam database
                return response()->json([
                'success' => false,
                'message' => 'indikator tidak diampu oleh pegawai terkait.',
                ], 404);
            }

               //validasi payload
            $form = $request->validate([
                "langkah" => "required|string",
                "tahun" => "required",
                "target_tw1" => "required",
                "target_tw2" => "required",
                "target_tw3" => "required",
                "target_tw4" => "required"
            ]);

            $form['sasaran_opd_id'] = $request->sasaran_opd_id;
            $form['indikator_opd_id'] = $request->indikator_opd_id;
            $form['master_opd_id'] = $master_opd_id;
            $form['langkah'] = $request->langkah;
            $form['tahun'] = $request->tahun;
            $form['satuan_id'] = $request->satuan_id;

            // create uuid and assign author
            $form['id'] = Str::uuid();
            $form['created_by'] = $request->get('payload')->username;
            
            // insert into table db
            $data = RencanaLangkah::create($form);
            
            // return response json
            return response()->json([
                'success' => true,
                'message' => 'Successfully created Langkah Rencana Aksi.',
                'data' => $data,
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

    public function read($id, Request $request)
    {
        try {

            $username = $request->get('payload')->username;
            $nip = $request->get('payload')->nip;
            $jabatan_id = $request->get('payload')->jabatan_id;

            // cek validasi jika id berformar uuid atau tidak
            if(!Str::isUuid($id)){
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid Id, Langkah Aksi not Found',
                ], 422);
            }

            $cek_pengampu = RencanaLangkah::where('id', '=', $id)
                        ->where('created_by', '=', $username)
                        ->count();

            if ($cek_pengampu <= 0) {
                // jika data tidak ditamukan di dalam database
                return response()->json([
                'success' => false,
                'message' => 'Langkah indikator tidak diampu oleh pegawai terkait.',
                ], 404);
            }

            // cek data ke database
            $detail = RencanaLangkah::find($id);
            if (!$detail) {
                // jika data tidak ditamukan di dalam database
                return response()->json([
                    'success' => false,
                    'message' => 'Langkah Aksi not found.',
                ], 404);
            }
            // return data jika data ditemukan
            return response()->json([
                'success' => true,
                'message' => 'Langkah Aksi found.',
                'data' => $detail,
            ]);
        } catch (\Throwable $th) {
            // handle error
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong!',
                'errors' => $th->getMessage()
            ], 500);
        }
    }


    public function update($id, Request $request)
    {
        try {

            $username = $request->get('payload')->username;
            $nip = $request->get('payload')->nip;
            $jabatan_id = $request->get('payload')->jabatan_id;

            // cek validasi jika id berformar uuid atau tidak
            if(!Str::isUuid($id)){
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid Id, Rencana Aksi not Found',
                ], 422);
            }
             // cek existing rkpd
            $langkah = RencanaLangkah::find($id);
            if (!$langkah) {
                return response()->json([
                    'success' => false,
                    'message' => 'langkah RencanaAksi not found.',
                ], 404);
            }


            $cek_pengampu = RencanaLangkah::where('id', '=', $id)
                        ->where('created_by', '=', $username)
                        ->count();

            if ($cek_pengampu <= 0) {
                // jika data tidak ditamukan di dalam database
                return response()->json([
                'success' => false,
                'message' => 'Langkah indikator tidak diampu oleh pegawai terkait.',
                ], 404);
            }

            $form = $request->validate([                
                "tahun" => "required",
                "langkah" => "required|string",
                "target_tw1" => "required",
                "target_tw2" => "required",
                "target_tw3" => "required",
                "target_tw4" => "required"
            ]);
            
            $form['satuan_id'] = $request->satuan_id;
            $form['updated_by'] = $request->get('payload')->username;

            $langkah->update($form);

            return response()->json([
                'success' => true,
                'message' => ' langkah RencanaAksi updated successfully.',
                'data' => $langkah,
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong!',
                'errors' => $th->getMessage()
            ], 500);
        }
    }

    public function delete($id, Request $request)
    {
        try {

            $username = $request->get('payload')->username;
            $nip = $request->get('payload')->nip;
            $jabatan_id = $request->get('payload')->jabatan_id;

            $cek_pengampu = RencanaLangkah::where('id', '=', $id)
                        ->where('created_by', '=', $username)
                        ->count();

            if ($cek_pengampu <= 0) {
                // jika data tidak ditamukan di dalam database
                return response()->json([
                'success' => false,
                'message' => 'Langkah indikator tidak diampu oleh pegawai terkait.',
                ], 404);
            }

            if(!Str::isUuid($id)){
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid Id, RencanaLangkah not Found',
                ], 422);
            }

            

            $cek = RencanaLangkah::find($id);
            if (!$cek) {
                return response()->json([
                    'success' => false,
                    'message' => 'Langkah not found.',
                ], 404);
            }
            $cek->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Langkah deleted successfully.',
                'data' => $cek,
            ]);

        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong!',
                'errors' => $th->getMessage()
            ], 500);
        }
    }

    public function list(Request $request)
    {
     
        $sasaran_id = $request->get('sasaran_opd_id');
        $indikator_id = $request->get('indikator_opd_id');
        $tahun = $request->get('tahun');
        $master_opd_id = $request->get('payload')->opd->id;
       
        try {

            $username = $request->get('payload')->username;
            $nip = $request->get('payload')->nip;
            $jabatan_id = $request->get('payload')->jabatan_id;

            $cek_pengampu = Pengampu::where('sasaran_opd_id', '=', $sasaran_id)
                        ->where('indikator_opd_id', '=', $indikator_id)
                        ->where('nip', '=', $nip)
                        ->where('jabatan_id', '=', $jabatan_id)
                        ->count();

            if ($cek_pengampu <= 0) {
                // jika data tidak ditamukan di dalam database
                return response()->json([
                'success' => false,
                'message' => 'Indikator tidak diampu oleh pegawai terkait.',
                ], 404);
            }

            $query = RencanaLangkah::query();
            
            $query->where('sasaran_opd_id', $sasaran_id);
            $query->where('indikator_opd_id', $indikator_id);            
            $query->where('tahun', $tahun);            
            $query->where('created_by', '=', $username);          
            $query->orderBy('created_at', 'desc');
            $objData = $query->get();

            // remap
            $objData = $objData->map(function($item) use($request) {
                $jam = Carbon::parse($item->created_at)->diffInHours();
                if($jam > 24) {
                    $created_at = Carbon::parse($item->created_at)->format('d M Y H:i');
                }
                else
                {
                    $created_at = Carbon::createFromFormat('Y-m-d H:i:s', $item->created_at)->diffForHumans();
                }
                
               
                $list =  [
                    "id" => $item->id,
                    "langkah" => $item->langkah,
                    "target_tw1" => $item->target_tw1,
                    "target_tw2" => $item->target_tw2,
                    "target_tw3" => $item->target_tw3,
                    "target_tw4" => $item->target_tw4,
                    "tahun" => $item->tahun,
                    "created_by" => $item->created_by,
                    "created_at" => $item->created_at,
                ];
                
                return $list;
            });

            return response()->json([
                'success' => true,
                'message' => 'List of Rencana Aksi',
                'data' => $objData
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'success' => false,
                'message' => 'List of Rencana Aksi',
                'data' => [],
                'errors' => $th->getMessage()
            ], 500);
        }
    }
}
