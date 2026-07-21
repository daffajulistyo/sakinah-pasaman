<?php

namespace App\Http\Controllers\Api\v1\KDH;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use App\Models\Sakip\KDH\PohonKinerjaIndikator;
use App\Models\Sakip\KDH\PohonKinerjaSasaran;
use App\Models\Sakip\KDH\Cascading;
use App\Models\Sakip\KDH\Rkpd;
use App\Models\Sakip\KDH\RkpdKegiatan;
use App\Models\Sakip\KDH\OpdPendukungIndikator;

class RkpdKegiatanController extends Controller
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
            // cek existing rkpd
            $sasaran = PohonKinerjaSasaran::find($request->pohon_kinerja_sasaran_id);
            if (!$sasaran) {
                // jika data tidak ditamukan di dalam database
                return response()->json([
                    'success' => false,
                    'message' => 'sasaran not found.',
                ], 404);
            }


            // cek existing target
            $target = RkpdKegiatan::where('pohon_kinerja_sasaran_id', $request->pohon_kinerja_sasaran_id)
                      ->where('tahun', '=', $request->tahun)
                      ->where('murni', '=', $request->murni)
                      ->limit(1)
                      ->get();

                //validasi payload
            $form = $request->validate([
                "tahun" => "required",
                "anggaran" => "required",
                "list_kegiatan" => "required|json",
                "is_active" => "required|boolean",
                "murni" => "required|boolean"
            ]);

            if (count($target) <= 0) 
            {   
                $form['pohon_kinerja_sasaran_id'] = $request->pohon_kinerja_sasaran_id;
                $form['tahun'] = $request->tahun;
                $form['anggaran'] = $request->anggaran;
                $form['murni'] = $request->murni;

                // create uuid and assign author
                $form['id'] = Str::uuid();
                $form['created_by'] = $request->get('payload')->username;
                
                // insert into table db
                $data = RkpdKegiatan::create($form);
            
            }
            else
            {
                $form['list_kegiatan'] = $request->list_kegiatan;
                $form['anggaran'] = $request->anggaran;

                $update = RkpdKegiatan::where('pohon_kinerja_sasaran_id', $request->pohon_kinerja_sasaran_id)
                ->where('tahun', '=', $request->tahun)
                ->where('murni', '=', $request->murni)
                ->update($form);            
            }
                      

            // return response json
            return response()->json([
                'success' => true,
                'message' => 'Successfully created Kegiatan RKPD.',
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
                    'message' => 'Invalid Id, Kegiatan RKPD not Found',
                ], 422);
            }
            // cek data ke database
            $rkpd = RkpdKegiatan::find($id);
            if (!$rkpd) {
                // jika data tidak ditamukan di dalam database
                return response()->json([
                    'success' => false,
                    'message' => 'Kegiatan RKPD not found.',
                ], 404);
            }
            // return data jika data ditemukan
            return response()->json([
                'success' => true,
                'message' => 'rkpd found.',
                'data' => $rkpd,
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
            if(!Str::isUuid($id)){
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid Id, RKPD not Found',
                ], 422);
            }
             // cek existing rkpd
            $rkpd = RkpdKegiatan::find($id);
            if (!$rkpd) {
                return response()->json([
                    'success' => false,
                    'message' => 'rkpd not found.',
                ], 404);
            }

             // cek existing target
             $target = RkpdKegiatan::where('pohon_kinerja_sasaran_id', '!=', $request->pohon_kinerja_sasaran_id)
                ->where('tahun', $request->tahun)               
                ->where('murni', $request->murni)               
                ->get();
                                
                if (count($target) > 0) {
                    // jika data tidak ditamukan di dalam database
                    return response()->json([
                        'success' => false,
                        'message' => 'data '.$request->tahun.' sudah ada.',
                    ], 404);
                }


                $form = $request->validate([
                    "tahun" => "required|integer",
                    "target" => "required",
                    "murni" => "required|boolean"
                ]);
                $form['pohon_kinerja_sasaran_id'] = $request->pohon_kinerja_sasaran_id;
                $form['tahun'] = $request->tahun;
                $form['anggaran'] = $request->anggaran;
                $form['updated_by'] = $request->get('payload')->username;

                $rkpd->update($form);

                return response()->json([
                    'success' => true,
                    'message' => 'rkpd updated successfully.',
                    'data' => $rkpd,
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
                    'message' => 'Invalid Id, RKPD not Found',
                ], 422);
            }
            $rkpd = RkpdKegiatan::find($id);
            if (!$rkpd) {
                return response()->json([
                    'success' => false,
                    'message' => 'rkpd not found.',
                ], 404);
            }
            $rkpd->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'rkpd kegiatan deleted successfully.',
                'data' => $rkpd,
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

        $query = RkpdKegiatan::where('pohon_kinerja_sasaran_id', '=', $pohon_kinerja_sasaran_id)
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
                'nama_opd' => $item->nama_opd,
                'kode_opd' => $item->kode_opd,
                'kode_sub_opd' => $item->kode_sub_opd ?? null,
                'ikd_opd_id' => $item->ikd_opd_id,
                'simpeg_opd_id' => $item->simpeg_opd_id,                
                'simonev_opd_id' => $item->simonev_opd_id ?? null         
            ];      
        });

      
        if(count($query) > 0)
        {
            $rkpd = [
                'id' => $query[0]['id'],
                'pohon_kinerja_sasaran_id' => $query[0]['pohon_kinerja_sasaran_id'],
                'list_kegiatan' => json_decode($query[0]['list_kegiatan']),
                'tahun' => $query[0]['tahun'],
                'anggaran' => $query[0]['anggaran'],
                'is_active' => $query[0]['is_active'],
                'murni' => $query[0]['murni'],
            ];
        }
        else
        $rkpd = array();

        $sasaran = [
            'sasaran' =>  $sasaran,
            'opd_pendukung' => $opd,
            'program_rkpd' => $rkpd
        ];
                
        return response()->json([
            'success' => true,
            'message' => 'List of Kegiatan RKPD',
            'data' => $sasaran
        ]);
    }
}
