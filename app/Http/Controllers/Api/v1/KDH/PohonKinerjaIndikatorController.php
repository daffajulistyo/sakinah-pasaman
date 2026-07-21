<?php

namespace App\Http\Controllers\Api\v1\KDH;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

use App\Models\Sakip\KDH\PohonKinerjaTujuan;
use App\Models\Sakip\KDH\PohonKinerjaSasaran;
use App\Models\Sakip\KDH\PohonKinerjaIndikator;
use App\Models\Sakip\KDH\OpdPendukungIndikator;

use App\Models\Sakip\MASTER\MasterSatuan;

use App\Models\MASTER\MasterOpd;
use Illuminate\Support\Facades\Storage;

class PohonKinerjaIndikatorController extends Controller
{
    /** service to create Sasaran */

    public function create(Request $request)
    {
        try {
            /*------------ cek validasi Tujuan-------------------------------------*/
                // cek validasi jika id berformar uuid atau tidak
                if(!Str::isUuid($request->pohon_kinerja_tujuan_id)){
                    return response()->json([
                        'success' => false,
                        'message' => 'Invalid Id, Tujuan not Found',
                    ], 422);
                }
                // cek existing tujuan
                $tujuan = PohonKinerjaTujuan::find($request->pohon_kinerja_tujuan_id);
                if (!$tujuan) {
                    // jika data tidak ditamukan di dalam database
                    return response()->json([
                        'success' => false,
                        'message' => 'tujuan not found.',
                    ], 404);
                }
            /*------------ cek validasi Tujuan-------------------------------------*/
            /*------------ cek validasi Sasaran-------------------------------------*/
                
            if($request->pohon_kinerja_sasaran_id !='' OR $request->pohon_kinerja_sasaran_id !=null OR $request->pohon_kinerja_sasaran_id !=0)
            {
                // cek validasi jika id berformar uuid atau tidak
                if(!Str::isUuid($request->pohon_kinerja_sasaran_id)){
                    return response()->json([
                        'success' => false,
                        'message' => 'Invalid Id, Sasaran not Found '.$request->pohon_kinerja_sasaran_id.' ',
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
            }
            /*------------ cek validasi Sasaran-------------------------------------*/
            
           
            /*------------ cek validasi satuan-------------------------------------*/
            /*if(!Str::isUuid($request->pohon_kinerja_satuan_id)){
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid Id, satuan not Found',
                ], 422);
            }
            // cek existing tujuan
            $satuan = MasterSatuan::find($request->pohon_kinerja_satuan_id);
            if (!$satuan) {
                // jika data tidak ditamukan di dalam database
                return response()->json([
                    'success' => false,
                    'message' => 'satuan not found.',
                ], 404);
            }   
            /*------------ cek validasi satuan-------------------------------------*/        
            
            
            //validasi payload
            $form = $request->validate([
                "order"     => "required|integer",
                "indikator" => "required|string",
                "is_active" => "required|boolean"
            ]);

            if ($request->hasFile('formula_perhitungan')) {
                    
                    $request->validate([    
                        "formula_perhitungan" => "required|mimes:jpeg,png,jpg,pdf"
                    ]);
                    
                    $file = $request->file('formula_perhitungan');

                    // 'uploads' is the directory within your MinIO bucket
                    $path = Storage::disk('s3')->put('formula/kdh', $file); 
                    $form['formula_perhitungan']    = $path;
            }

            $form['pohon_kinerja_tujuan_id'] = $request->pohon_kinerja_tujuan_id;
            $form['pohon_kinerja_sasaran_id'] = $request->pohon_kinerja_sasaran_id;
            $form['pohon_kinerja_satuan_id'] = $request->satuan_id;
            $form['satuan_id'] = $request->satuan_id;
            $form['baseline'] = $request->baseline;
            $form['target_1'] = $request->target_1;
            $form['target_2'] = $request->target_2;
            $form['target_3'] = $request->target_3;
            $form['target_4'] = $request->target_4;
            $form['target_5'] = $request->target_5;
            $form['target_6'] = $request->target_6;
            $form['sumber_data'] = $request->sumber_data;
            $form['defenisi'] = $request->defenisi;
            $form['kegunaan'] = $request->kegunaan;
            $form['rilis']    = $request->rilis;
           

           
           


            // create uuid and assign author
            $form['id'] = Str::uuid();
            $form['is_tujuan'] = $request->is_tujuan;
            $form['parent_id'] = 0;
            $form['created_by'] = $request->get('payload')->username;
            
            // insert into table db
            $data = PohonKinerjaIndikator::create($form); 
            
           
            $opd = collect($request->opd_pendukung)->map(function($item) use($request){
                    return [
                        "id" => Str::uuid(),
                        "pohon_kinerja_sasaran_id" => $request->pohon_kinerja_sasaran_id,
                        "master_opd_id" => $item,
                        "created_by"=>$request->get('payload')->username,
                        "is_active" => true,
                    ];
                });
               
            $data->opd_pendukung()->attach($opd);


            // return response json
            return response()->json([
                'success' => true,
                'message' => 'Successfully created indikator pohon kinerja kepala daerah.',
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


     /** service to read Sasaran */
     public function read($id)
     {
         try {
             // cek validasi jika id berformar uuid atau tidak
             if(!Str::isUuid($id)){
                 return response()->json([
                     'success' => false,
                     'message' => 'Invalid Id, Indikator not Found',
                 ], 422);
             }
             // cek data ke database
             //$indikator = PohonKinerjaIndikator::find($id);
             $indikator = PohonKinerjaIndikator::with(['opd_pendukung' => function ($query) {
                $query->select('opd_pendukung_indikator.id as id', 'nama_opd', 'website', 'simpeg_opd_id', 'ikd_opd_id');
             }])->where('id', $id)->get();
             
             
             $satuan = MasterSatuan::where('id', $indikator[0]['satuan_id'])->limit(1)->get();
             $ss = ['satuan_id'=>1, 'satuan'=>'nama satuan'];
             
             if (!$indikator) {
                 // jika data tidak ditamukan di dalam database
                 return response()->json([
                     'success' => false,
                     'message' => 'indikator not found.',
                 ], 404);
             }
             // return data jika data ditemukan
             return response()->json([
                 'success' => true,
                 'message' => 'sasaran found.',
                 'data' => $indikator,
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

      /**
     * service for Update data indikator
     */
    public function update($id, Request $request)
    {
        try {
            // cek validasi jika id berformar uuid atau tidak
            if(!Str::isUuid($id)){
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid Id, Indikator not Found',
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

           
                /*------------ cek validasi Tujuan-------------------------------------*/
                if($request->pohon_kinerja_tujuan_id !='' OR $request->pohon_kinerja_tujuan_id !=null OR $request->pohon_kinerja_tujuan_id !=0)
                {
                    // cek validasi jika id berformar uuid atau tidak
                    if(!Str::isUuid($request->pohon_kinerja_tujuan_id)){
                        return response()->json([
                            'success' => false,
                            'message' => 'Invalid Id, Tujuan not Found',
                        ], 422);
                    }
                    // cek existing tujuan
                    $tujuan = PohonKinerjaTujuan::find($request->pohon_kinerja_tujuan_id);
                    if (!$tujuan) {
                        // jika data tidak ditamukan di dalam database
                        return response()->json([
                            'success' => false,
                            'message' => 'tujuan not found.',
                        ], 404);
                    }
                }
                /*------------ cek validasi Tujuan-------------------------------------*/
           
                /*------------ cek validasi Sasaran-------------------------------------
               
                    // cek validasi jika id berformar uuid atau tidak
                    if(!Str::isUuid($request->pohon_kinerja_sasaran_id)){
                        return response()->json([
                            'success' => false,
                            'message' => 'Invalid Id, Sasaran not Found uu',
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
                
                /*------------ cek validasi Sasaran-------------------------------------*/
                
                if($request->is_tujuan==false){
                      
                    $current_opd = OpdPendukungIndikator::where('pohon_kinerja_indikator_id', $request->id)->get();
                    $current_opd = $current_opd->pluck('master_opd_id');
                

                    $opd_pendukung = collect($request->opd_pendukung);

                    $new_opd = $opd_pendukung->diff($current_opd);
                    $old_opd = $new_opd->diff($opd_pendukung);
                    
                  
                    //delete old opd
                    OpdPendukungIndikator::whereIn('master_opd_id', $current_opd)
                                        ->where('pohon_kinerja_indikator_id', $request->id)
                                        ->delete();                    

                    $opd = collect($request->opd_pendukung)->map(function($item) use ($request) {
                        return [
                            "id" => Str::uuid(),
                            "pohon_kinerja_indikator_id" => $request->id,
                            "pohon_kinerja_sasaran_id" => $request->pohon_kinerja_sasaran_id,
                            "master_opd_id" => $item,
                            "updated_by" => $request->get('payload')->username,
                            "is_active" => true,
                        ];
                    });

                    OpdPendukungIndikator::insert($opd->all());
                }
            

            $form = $request->validate([
                "order" => "required|integer",
                "indikator" => "required|string",
                "is_active" => "required|boolean"
            ]);

            $form['parent_id'] = 0; $form['pohon_kinerja_tujuan_id'] = $request->pohon_kinerja_tujuan_id;
            $form['pohon_kinerja_sasaran_id'] = $request->pohon_kinerja_sasaran_id;
            $form['pohon_kinerja_satuan_id'] = $request->satuan_id;

            $form['satuan_id'] = $request->satuan_id;
            $form['baseline'] = $request->baseline;
            $form['target_1'] = $request->target_1;
            $form['target_2'] = $request->target_2;
            $form['target_3'] = $request->target_3;
            $form['target_4'] = $request->target_4;
            $form['target_5'] = $request->target_5;
            $form['target_6'] = $request->target_6;
            $form['sumber_data'] = $request->sumber_data;
            $form['defenisi'] = $request->defenisi;
            $form['kegunaan'] = $request->kegunaan;
            $form['rilis']    = $request->rilis;
           

            $form['updated_by'] = $request->get('payload')->username;

            $indikator->update($form);

            return response()->json([
                'success' => true,
                'message' => 'indikator updated successfully.',
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


    /**
     * service to Delete indikator
     */
    public function delete($id)
    {
        try {
            if(!Str::isUuid($id)){
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid Id, Indikator not Found',
                ], 422);
            }
            $indikator = PohonKinerjaIndikator::find($id);
            if (!$indikator) {
                return response()->json([
                    'success' => false,
                    'message' => 'indikator not found.',
                ], 404);
            }

             /*-------------- Cek Jika digunakan didata lain ----------------*/          

          
             $cek_pk = DB::table('perjanjian_kinerja')->where('pohon_kinerja_indikator_id', $id)->count();
            if ($cek_pk > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Indikator Tidak Bisa Dihapus karena sedang digunakan dalam data Perjanjian Kinerja',
                ], 404);
            }

            $cek_pk = DB::table('perjanjian_kinerja')->where('pohon_kinerja_indikator_id', $id)->count();
            if ($cek_pk > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Indikator Tidak Bisa Dihapus karena sedang digunakan dalam data Perjanjian Kinerja',
                ], 404);
            }


            $cek_ra = DB::table('rencana_aksi')->where('pohon_kinerja_indikator_id', $id)->count();
            if ($cek_ra > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Indikator Tidak Bisa Dihapus karena sedang digunakan dalam data Rencana Aksi',
                ], 404);
            }

            $cek_ra_langkah = DB::table('rencana_aksi_langkah')->where('pohon_kinerja_indikator_id', $id)->count();
            if ($cek_ra_langkah > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Indikator Tidak Bisa Dihapus karena sedang digunakan dalam data Program Rencana Aksi',
                ], 404);
            }

            $cek_rkpd = DB::table('rkpd')->where('pohon_kinerja_indikator_id', $id)->count();
            if ($cek_rkpd > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Indikator  Tidak Bisa Dihapus karena sedang digunakan dalam data RKPD',
                ], 404);
            }

            /*-------------- Cek Jika digunakan didata lain ----------------*/

            $indikator->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'indikator deleted successfully.',
                'data' => $indikator,
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
        $searchColumn = collect(['indikator']);
        $tujuan_id = $request->get('tujuan_id');
        $sasaran_id = $request->get('sasaran_id');
        $currentPage = $request->get('page', 1);
        $perPage = $request->get('per_page', 10);
        $search = $request->get('search', '');
        $tipe = $request->get('tipe');

        $totalPage = 0;
        $totalRecord = 0;
        try {

           // $query = PohonKinerjaIndikator::with(['opd_pendukung'])->get();

           $query = PohonKinerjaIndikator::with(['opd_pendukung'], function ($q) use ($request) {
                    $query();
            });
            
            //$query = PohonKinerjaIndikator::query()->toArray();
           
            if($search != ''){
                $searchColumn->map(function($item, $index) use($search, $query){
                    if($index == 0) $query->where($item, 'like', '%' . $search . '%');
                    else $query->orWhere($item, 'like', '%' . $search . '%');

                });
            }
            
            
            $query->where('pohon_kinerja_tujuan_id', "=", $tujuan_id);

            if(!empty($sasaran_id)){
                $query->where('pohon_kinerja_sasaran_id', "=", $sasaran_id);
                $query->where('is_tujuan', "=", false);
            }else{
                $query->where('is_tujuan', "=", true);
            }   


            $query->orderBy('order', 'asc');
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

                // $rpjmd='';
                // $iku='';
                // if($request->tipe=="RPJMD"){

                        // $satuan = MasterSatuan::where('id', $item->satuan_id)->limit(1)->get();
                //     //$satuan = $satuan->pluck('satuan');
                //     $rpjmd= [ 
                //         "baseline" => $item->baseline,
                //         "target_1" => $item->target_1,
                //         "target_2" => $item->target_2,
                //         "target_3" => $item->target_3,
                //         "target_4" => $item->target_4,
                //         "target_5" => $item->target_5,
                //         "target_6" => $item->target_6,
                //         "satuan_id" => $item->satuan_id,
                //         "satuan" => $satuan[0]['satuan']
                //     ];
                // }

                // if($request->tipe=="IKU"){

                //     $satuan = MasterSatuan::where('id', $item->satuan_id)->limit(1)->get();
                //     //$satuan = $satuan->pluck('satuan');
                //     $iku= [ 
                //         "baseline" => $item->baseline,
                //         "rilis" => $item->rilis,
                //         "sumber_data" => $item->sumber_data,
                //         "formula_perhitungan" => $item->formula_perhitungan
                //     ];
                // }
                $satuan = MasterSatuan::where('id', $item->satuan_id)->first();
                $list =  [
                    "id" => $item->id,
                    "pohon_kinerja_tujuan_id" => $item->pohon_kinerja_tujuan_id,
                    "pohon_kinerja_sasaran_id" => $item->pohon_kinerja_sasaran_id,
                    "order" => $item->order,
                    "indikator" => $item->indikator,
                    "opd_pendukung" => $item->opd_pendukung,
                    "defenisi" => $item->defenisi,
                    "rilis" => $item->rilis,
                    "kegunaan" => $item->kegunaan,
                    "is_active" => $item->is_active,
                    "created_at" => $created_at,
                    "baseline" => $item->baseline,
                    "target_1" => $item->target_1,
                    "target_2" => $item->target_2,
                    "target_3" => $item->target_3,
                    "target_4" => $item->target_4,
                    "target_5" => $item->target_5,
                    "target_6" => $item->target_6,
                    "satuan_id" => $item->satuan_id,
                    "satuan" => $satuan ? $satuan->satuan : null,
                    "rilis" => $item->rilis,
                    "sumber_data" => $item->sumber_data,
                    "formula_perhitungan" => $item->formula_perhitungan
                ];

                
                return $list;
            });

            return response()->json([
                'success' => true,
                'message' => 'List of Pohon Kinerja Indikator',
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
                'message' => 'List of Pohon Kinerja Sasaran',
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


    public function upload($id, Request $request)
    {
        try {
            /*------------ cek validasi Indikator-------------------------------------*/
                // cek validasi jika id berformar uuid atau tidak
                if(!Str::isUuid($id)){
                    return response()->json([
                        'success' => false,
                        'message' => 'Invalid Id, Indikator not Found',
                    ], 422);
                }
                // cek existing tujuan
                $indikator = PohonKinerjaIndikator::find($id);
                if (!$indikator) {
                    // jika data tidak ditamukan di dalam database
                    return response()->json([
                        'success' => false,
                        'message' => 'Indikator not found.',
                    ], 404);
                }
            /*------------ cek validasi Indikator-------------------------------------*/
            
            if ($request->hasFile('formula_perhitungan')) {
                    //validasi payload
                    $form = $request->validate([
                        "formula_perhitungan" => "required|mimes:jpeg,png,jpg,pdf"
                    ]);                    
                    $file = $request->file('formula_perhitungan');

                    // 'uploads' is the directory within your MinIO bucket
                    $path = Storage::disk('s3')->put('formula/kdh', $file); 
                
                    if(!empty($indikator->formula_perhitungan)){
                        if (Storage::disk('s3')->has($indikator->formula_perhitungan)) {                               
                            Storage::disk('s3')->delete($indikator->formula_perhitungan);
                        }
                    }
                    
                    $form['formula_perhitungan']    = $path;
            }
            else{
                return response()->json([
                    'success' => false,
                    'message' => 'Gambar updated unsuccessfully.',
                    'errors' => 'Gambar Wajib Diisi',
                ]);
            }

            $form['updated_by'] = $request->get('payload')->username;

            $indikator->where('id', $id)->update($form);

            return response()->json([
                'success' => true,
                'message' => 'indikator updated successfully.',
                'data' => $indikator,
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

    public function preview($id)
    {
        try {
            /*------------ cek validasi Indikator-------------------------------------*/
                // cek validasi jika id berformar uuid atau tidak
                if(!Str::isUuid($id)){
                    return response()->json([
                        'success' => false,
                        'message' => 'Invalid Id, Indikator not Found',
                    ], 422);
                }
                // cek existing tujuan
                $indikator = PohonKinerjaIndikator::find($id);
                if (!$indikator) {
                    // jika data tidak ditamukan di dalam database
                    return response()->json([
                        'success' => false,
                        'message' => 'Indikator not found.',
                    ], 404);
                }
            /*------------ cek validasi Indikator-------------------------------------*/
            
            $objectName = $indikator->formula_perhitungan; // or image.jpg, video.mp4
            

            if (!Storage::disk('s3')->exists($objectName)) {
                return response()->json([
                    'success' => true,
                    'message' => 'File Loaded unsuccessfully.',
                    'errors' => 'File Not Found',
                ]);
            }
            

            $path = !empty($indikator) ? $indikator->formula_perhitungan : '';
            $disk = Storage::disk('s3');

            if (!$disk->exists($path)) {
                abort(404);
            }

            $stream = $disk->get($path);
            $mime = $disk->mimeType($path);

            return response($stream, 200)->header('Content-Type', $mime);


            /* Generate a URL that is valid for 60 minutes
            $presignedUrl = Storage::disk('s3')->temporaryUrl(
                $objectName, now()->addMinutes(60)
            ); 
            
            return response()->json([
                'success' => true,
                'message' => 'File Loaded successfully.',
                'data' => $presignedUrl,
            ]); */

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
