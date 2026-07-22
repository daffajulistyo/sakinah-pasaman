<?php

namespace App\Http\Controllers\Api\v1\KDH;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;

use App\Models\Sakip\KDH\PohonKinerjaSasaran;
use App\Models\Sakip\KDH\PohonKinerjaIndikator;
use App\Models\Sakip\KDH\Rkpd;
use App\Models\Sakip\KDH\PerjanjianKinerja;
use App\Models\Sakip\KDH\RencanaAksi;
use App\Models\Sakip\KDH\RencanaAksiLangkah;

class RencanaAksiLangkahController extends Controller
{
    public function create(Request $request)
    {
        try {
            // cek validasi jika id berformar uuid atau tidak
            if(!Str::isUuid($request->pohon_kinerja_sasaran_id)){
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid Id, PerjanjianKinerja not Found',
                ], 422);
            }
            // cek existing sasaran
            $sasaran = PohonKinerjaSasaran::find($request->pohon_kinerja_sasaran_id);
            if (!$sasaran) {
                // jika data tidak ditamukan di dalam database
                return response()->json([
                    'success' => false,
                    'message' => 'sasaran not found.',
                ], 404);
            }


            // cek validasi jika id berformar uuid atau tidak
            if(!Str::isUuid($request->pohon_kinerja_indikator_id)){
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid Id, Indikator not Found',
                ], 422);
            }
            // cek existing indikator
            $indikator = PohonKinerjaIndikator::find($request->pohon_kinerja_indikator_id);
            if (!$indikator) {
                // jika data tidak ditamukan di dalam database
                return response()->json([
                    'success' => false,
                    'message' => 'indikator not found.',
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

            $form['pohon_kinerja_sasaran_id'] = $request->pohon_kinerja_sasaran_id;
            $form['pohon_kinerja_indikator_id'] = $request->pohon_kinerja_indikator_id;
            $form['langkah'] = $request->langkah;
            $form['tahun'] = $request->tahun;

            // create uuid and assign author
            $form['id'] = Str::uuid();
            $form['created_by'] = $request->attributes->get('payload')->username;
            
            // insert into table db
            $data = RencanaAksiLangkah::create($form);
            
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

    public function read($id)
    {
        try {
            // cek validasi jika id berformar uuid atau tidak
            if(!Str::isUuid($id)){
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid Id, Aksi not Found',
                ], 422);
            }
            // cek data ke database
            $detail = RencanaAksiLangkah::find($id);
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
            // cek validasi jika id berformar uuid atau tidak
            if(!Str::isUuid($id)){
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid Id, Rencana Aksi not Found',
                ], 422);
            }
             // cek existing rkpd
            $langkah = RencanaAksiLangkah::find($id);
            if (!$langkah) {
                return response()->json([
                    'success' => false,
                    'message' => 'langkah RencanaAksi not found.',
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
            
            $form['updated_by'] = $request->attributes->get('payload')->username;

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

    public function delete($id)
    {
        try {
            if(!Str::isUuid($id)){
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid Id, RencanaAksiLangkah not Found',
                ], 422);
            }
            $cek = RencanaAksiLangkah::find($id);
            if (!$cek) {
                return response()->json([
                    'success' => false,
                    'message' => 'RencanaAksiLangkah not found.',
                ], 404);
            }
            $cek->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'RencanaAksiLangkah deleted successfully.',
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
        //
        $searchColumn = collect(['sasaran']);
        $sasaran_id = $request->get('sasaran_id');
        $indikator_id = $request->get('indikator_id');
        $tahun = $request->get('tahun');

        $currentPage = $request->get('page', 1);
        $perPage = $request->get('per_page', 10);
        $search = $request->get('search', '');

        $totalPage = 0;
        $totalRecord = 0;
        try {

            $query = RencanaAksiLangkah::query();
            
            $query->where('pohon_kinerja_sasaran_id', $sasaran_id);
            $query->where('pohon_kinerja_indikator_id', $indikator_id);
            $query->where('tahun', $tahun);

            if($search != ''){
                $searchColumn->map(function($item, $index) use($search, $query){
                    if($index == 0) $query->where($item, 'like', '%' . $search . '%');
                    else $query->orWhere($item, 'like', '%' . $search . '%');

                });
            }
            
            $query->orderBy('created_at', 'desc');
            $objData = $query->paginate($perPage);
            $totalPage = $objData->lastPage();
            $totalRecord = $objData->total();

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
                    "tahun" => $item->tahun
                ];
                
                return $list;
            });

            return response()->json([
                'success' => true,
                'message' => 'List of Rencana Aksi',
                'data' => $objData,
                'pagination' => [
                    'page' => $currentPage,
                    'per_page' => $perPage,
                    'total_records' => $totalRecord,
                    'total_page' => $totalPage,
                    'search' => $search
                ]
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'success' => false,
                'message' => 'List of Rencana Aksi',
                'data' => [],
                'pagination' => [
                    'page' => $currentPage,
                    'per_page' => $perPage,
                    'total_records' => $totalRecord,
                    'total_page' => $totalPage,
                    'search' => $search
                ],
                'errors' => $th->getMessage()
            ], 500);
        }
    }
}
