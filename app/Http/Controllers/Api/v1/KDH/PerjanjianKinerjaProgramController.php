<?php

namespace App\Http\Controllers\Api\v1\KDH;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

use App\Http\Controllers\Controller;

use App\Models\Sakip\KDH\PohonKinerjaIndikator;
use App\Models\Sakip\KDH\PohonKinerjaSasaran;
use App\Models\Sakip\KDH\OpdPendukungIndikator;
use App\Models\Sakip\KDH\PerjanjianKinerja;
use App\Models\Sakip\KDH\PerjanjianKinerjaProgram;

class PerjanjianKinerjaProgramController extends Controller
{
    public function create(Request $request)
    {
        try {

            // cek validasi jika id berformar uuid atau tidak
            if(!Str::isUuid($request->pohon_kinerja_sasaran_id)){
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid Id, Sasaran not Found',
                ], 422);
            }
            // cek existing sasaran
            $sasaran = PohonKinerjaSasaran::find($request->pohon_kinerja_sasaran_id);
            if (!$sasaran) {
                // jika data tidak ditamukan di dalam database
                return response()->json([
                    'success' => false,
                    'message' => 'Sasaran not found.',
                ], 404);
            }

            

            // cek existing target
            $target = PerjanjianKinerjaProgram::where('tahun', '=', $request->tahun)
                      ->where('pohon_kinerja_sasaran_id', '=',$request->pohon_kinerja_sasaran_id)
                      ->where('murni', '=', $request->murni)
                      ->get();
                         
            
            //validasi payload
            $form = $request->validate([
                "tahun" => "required|integer",
                "list_program" => "required|json",
                "anggaran" => "required",
                "murni" => "required|boolean"
            ]);

            if (count($target) <= 0) {
                    $form['pohon_kinerja_sasaran_id'] = $request->pohon_kinerja_sasaran_id;
                    $form['list_program'] = $request->list_program;

                    // create uuid and assign author
                    $form['id'] = Str::uuid();
                    $form['created_by'] = $request->attributes->get('payload')->username;
                    
                    // insert into table db
                    $data = PerjanjianKinerjaProgram::create($form);
            }
            else
            {
                $form['list_program'] = $request->list_program;
                $update = PerjanjianKinerjaProgram::where('tahun', '=', $request->tahun)
                ->where('pohon_kinerja_sasaran_id', '=',$request->pohon_kinerja_sasaran_id)
                ->where('murni', '=', $request->murni)
                ->update($form);
            }
            
            // return response json
            return response()->json([
                'success' => true,
                'message' => 'Successfully created Program Perjanjian Kinerja.',
                'data' => $form,
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

    public function read($id)
    {
        try {
            // cek validasi jika id berformar uuid atau tidak
            if(!Str::isUuid($id)){
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid Id, Program Perjanjian Kinerja not Found',
                ], 422);
            }
            // cek data ke database
            $detail = PerjanjianKinerjaProgram::find($id);
            if (!$detail) {
                // jika data tidak ditamukan di dalam database
                return response()->json([
                    'success' => false,
                    'message' => ' Program Perjanjian Kinerja not found.',
                ], 404);
            }
            // return data jika data ditemukan
            return response()->json([
                'success' => true,
                'message' => 'Program Perjanjian Kinerja found.',
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

             // cek validasi jika id berformar uuid atau tidak
             if(!Str::isUuid($request->pohon_kinerja_sasaran_id)){
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid Id, Sasaran not Found',
                ], 422);
            }
            // cek existing sasaran
            $sasaran = PohonKinerjaSasaran::find($request->pohon_kinerja_sasaran_id);
            if (!$sasaran) {
                // jika data tidak ditamukan di dalam database
                return response()->json([
                    'success' => false,
                    'message' => 'Sasaran not found.',
                ], 404);
            }


            // cek validasi jika id berformar uuid atau tidak
            if(!Str::isUuid($id)){
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid Id, PerjanjianKinerja not Found',
                ], 422);
            }
             // cek existing rkpd
            $program = PerjanjianKinerjaProgram::find($id);
            if (!$program) {
                return response()->json([
                    'success' => false,
                    'message' => 'PerjanjianKinerja not found.',
                ], 404);
            }

             // cek existing target
             $target = PerjanjianKinerjaProgram::where('tahun', $request->tahun)
                            ->where('id', '!=', $id)
                            ->where('pohon_kinerja_sasaran_id', $request->pohon_kinerja_sasaran_id)                                         
                            ->where('murni', $request->murni)
                            ->get();
                                
                if (count($target) > 0) {
                    // jika data tidak ditamukan di dalam database
                    return response()->json([
                        'success' => false,
                        'message' => 'Program '.$request->tahun.' sudah ada.',
                    ], 404);
                }


            $form = $request->validate([
                "tahun" => "required|integer",
                "list_program" => "required|json",
                "murni" => "required|boolean"
            ]);
            $form['pohon_kinerja_sasaran_id'] = $request->pohon_kinerja_sasaran_id;
            $form['updated_by'] = $request->attributes->get('payload')->username;

            $program->update($form);

            return response()->json([
                'success' => true,
                'message' => 'PerjanjianKinerja updated successfully.',
                'data' => $program,
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong!',
                'errors' => $th->getMessage()
            ], 500);
        }
    }

    public function delete($id)
    {
        try {
            if(!Str::isUuid($id)){
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid Id, Program PerjanjianKinerja not Found',
                ], 422);
            }
            $cek = PerjanjianKinerjaProgram::find($id);
            if (!$cek) {
                return response()->json([
                    'success' => false,
                    'message' => 'Program PerjanjianKinerja not found.',
                ], 404);
            }
            $cek->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Program PerjanjianKInerja deleted successfully.',
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
        $pohon_kinerja_sasaran_id = $request->get('pohon_kinerja_sasaran_id');
        $tahun = $request->get('tahun');
        $murni = $request->get('murni');


         // cek validasi jika id berformar uuid atau tidak
         if(!Str::isUuid($pohon_kinerja_sasaran_id)){
            return response()->json([
                'success' => false,
                'message' => 'Invalid Id, Sasaran not Found',
            ], 422);
        }
        // cek data ke database
        $sasaran = PohonKinerjaSasaran::find($pohon_kinerja_sasaran_id);
        if (!$sasaran) {
            // jika data tidak ditamukan di dalam database
            return response()->json([
                'success' => false,
                'message' => 'sasaran not found.',
            ], 404);
        }

        $query = PerjanjianKinerjaProgram::where('pohon_kinerja_sasaran_id', '=', $pohon_kinerja_sasaran_id)
                ->where('tahun', '=', $tahun)
                ->where('murni', '=', $murni)
                ->limit(1)
                ->get();     
      
        $opd = OpdPendukungIndikator::join('master_opd', 'opd_pendukung_indikator.master_opd_id', '=', 'master_opd.id')
                ->select('opd_pendukung_indikator.master_opd_id', 'opd_pendukung_indikator.master_opd_id', 'opd_pendukung_indikator.pohon_kinerja_sasaran_id', 'master_opd.kode_opd', 'master_opd.simpeg_opd_id', 'master_opd.ikd_opd_id', 'master_opd.nama_opd', 'master_opd.alias_opd', 'master_opd.opd_unit', 'master_opd.opd_unit_id', 'master_opd.simonev_opd_id')
                ->where('opd_pendukung_indikator.is_active', true)
                ->where('opd_pendukung_indikator.pohon_kinerja_sasaran_id', $pohon_kinerja_sasaran_id)
                ->distinct()
                ->get();

        $opd = $opd->map(function($item) use($request) {
            return [
                'pohon_kinerja_sasaran_id' => $item->pohon_kinerja_sasaran_id,
                'master_opd_id' => $item->master_opd_id,
                'kode_opd' => $item->kode_opd,
                'kode_sub_opd' => $item->kode_sub_opd ?? null,
                'nama_opd' => $item->nama_opd,
                'ikd_opd_id' => $item->ikd_opd_id,
                'simpeg_opd_id' => $item->simpeg_opd_id,                
                'simonev_opd_id' => $item->simonev_opd_id ?? null            
            ];      
        });

      
        if(count($query) > 0)
        {
            $list_prog = [
                'id' => $query[0]['id'],
                'pohon_kinerja_sasaran_id' => $query[0]['pohon_kinerja_sasaran_id'],
                'list_program' => json_decode($query[0]['list_program']),
                'tahun' => $query[0]['tahun'],
                'is_active' => $query[0]['is_active'],
                'murni' => $query[0]['murni'],
            ];
        }
        else
        $list_prog = array();

        $sasaran = [
            'sasaran' =>  $sasaran,
            'opd_pendukung' => $opd,
            'list_program' => $list_prog
        ];
                
        return response()->json([
            'success' => true,
            'message' => 'List of Kegiatan RKPD',
            'data' => $sasaran
        ]);
    }
}
