<?php

namespace App\Http\Controllers\Api\v1\Dokumen;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

use App\Models\MASTER\MasterOpd;
use App\Models\Sakip\Dokumen\DataUploads;
use Illuminate\Support\Facades\Storage;

class UploadsController extends Controller
{
    /** service to create Sasaran */

    public function create(Request $request)
    {
        try {
                        
            //validasi payload
            $form = $request->validate([
                "title"         => "required|string",
                "tahun"         => "required|integer"
            ]);

            $tahun = !empty($request->tahun) ? $request->tahun : date('Y');
            if ($request->hasFile('dokumen')) {
                    
                    $request->validate([    
                        "dokumen" => "required|mimes:jpeg,png,jpg,pdf"
                    ]);
                    
                    $file = $request->file('dokumen');

                    // 'uploads' is the directory within your MinIO bucket
                    $path = Storage::disk('s3')->put('upload/'.date('Y'), $file); 
                    $form['dokumen']    = basename($path);
            }

            $slug = strtolower(preg_replace('/[ ",.]+/', '-', $request->title));
            $cek_slug = DataUploads::where('slug', $slug)->count(); 
            if($cek_slug > 0){
                 return response()->json([
                     'success' => false,
                     'message' => 'Slug Sudah Dipakai, Entri dengan Judul yang Lain',
                 ], 422);
             }


            // create uuid and assign author
            $form['id'] = Str::uuid();
            $form['master_opd_id'] = $request->attributes->get('payload')->opd->id;
            $form['slug'] = $slug;
            $form['type'] = 1;
            $form['tahun'] = $tahun;
            $form['created_by'] = $request->attributes->get('payload')->username;
            
            // insert into table db
            $data = DataUploads::create($form); 
            
           // return response json
            return response()->json([
                'success' => true,
                'message' => 'Successfully created Upload',
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
     public function read($id, Request $request)
     {
         try {
             // cek validasi jika id berformar uuid atau tidak
             if(!Str::isUuid($id)){
                 return response()->json([
                     'success' => false,
                     'message' => 'Invalid Id, Indikator not Found',
                 ], 422);
             }

             $username = $request->attributes->get('payload')->username;
             // cek data ke database
             $detail = DataUploads::join('master_opd', 'master_opd.id', '=', 'data_uploads.master_opd_id')
                        ->where('data_uploads.id', $id)
                        ->where('data_uploads.created_by', $username)
                        ->select('data_uploads.id', 'data_uploads.dokumen', 'data_uploads.title', 'data_uploads.slug','data_uploads.keterangan', 'data_uploads.tahun', 'master_opd.nama_opd')
                        ->get();
                         
             if (count($detail) <=0) {
                 // jika data tidak ditamukan di dalam database
                 return response()->json([
                     'success' => false,
                     'message' => 'Data not found.',
                 ], 404);
             }
             // return data jika data ditemukan
             return response()->json([
                 'success' => true,
                 'message' => 'Upload found.',
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
             // cek existing 
            $DataUploads = DataUploads::find($id);
            if (!$DataUploads) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data Upload not found.',
                ], 404);
            }              

            $form = $request->validate([
                "tahun"      => "required|integer",
                "title"     => "required|string"
            ]);

            $tahun = !empty($request->tahun) ? $request->tahun : date('Y');
            $slug = strtolower(preg_replace('/[ ",.]+/', '-', $request->title));
            $cek_slug = DataUploads::where('slug', $slug)->where('id', '!=', $id)->count(); 
            if($cek_slug > 0){
                 return response()->json([
                     'success' => false,
                     'message' => 'Slug Sudah Dipakai, Entri dengan Judul yang Lain',
                 ], 422);
             }



            if ($request->hasFile('dokumen')) {
                    //validasi payload
                    $request->validate([
                        "dokumen" => "required|mimes:jpeg,png,jpg,pdf"
                    ]);                    

                    $file = $request->file('dokumen');
                    $year = substr($DataUploads->created_at,0,4);

                    // 'uploads' is the directory within your MinIO bucket
                    $path = Storage::disk('s3')->put('upload/'.$year, $file); 
                
                    if(!empty($DataUploads->dokumen)){
                        if (Storage::disk('s3')->has('upload/'.$year.'/'.$DataUploads->dokumen)) {                               
                            Storage::disk('s3')->delete('upload/'.$year.'/'.$DataUploads->dokumen);
                        }
                    }
                    
                    $form['dokumen']    = basename($path);
            }
         
            $form['updated_by'] = $request->attributes->get('payload')->username;
            $form['tahun'] = $tahun;
            $form['slug'] = $slug;

            $DataUploads->update($form);

            return response()->json([
                'success' => true,
                'message' => 'Data Uploads updated successfully.',
                'data' => $form,
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
                    'message' => 'Invalid Id, Data not Found',
                ], 422);
            }
            $upload = DataUploads::find($id);
            if (!$upload) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data not found.',
                ], 404);
            }
                        
            $delete = $upload->delete();           

            if($delete){     
                    $year = substr($upload->created_at,0,4);     
                    $older_file = 'upload/'.$year.'/'.$upload->dokumen;
                    $trashed = 'upload/.trash/'.$year.'/'.$upload->dokumen;
                    
                    Storage::disk('s3')->move($older_file, $trashed);
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Dokumen deleted successfully.',
                'data' => $upload,
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
        $searchColumn = collect(['nama_opd', 'keterangan', 'tahun']);
        $currentPage = $request->get('page', 1);
        $perPage = $request->get('per_page', 10);
        $search = $request->get('search', '');

        $totalPage = 0;
        $totalRecord = 0;

        $master_opd_id = $request->attributes->get('payload')->opd->id;

        try {

           $query = DataUploads::join('master_opd', 'master_opd.id', '=', 'data_uploads.master_opd_id')->where('master_opd_id', $master_opd_id);
           
            if($search != ''){
                $searchColumn->map(function($item, $index) use($search, $query){
                    if($index == 0) $query->where($item, 'like', '%' . $search . '%');
                    else $query->orWhere($item, 'like', '%' . $search . '%');

                });
            }
            $query->select('data_uploads.id', 'data_uploads.title', 'data_uploads.slug', 'data_uploads.tahun', 'data_uploads.keterangan', 'data_uploads.created_at', 'master_opd.nama_opd');
            $query->orderBy('data_uploads.created_at', 'desc');
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
                    "title" => $item->title,
                    "slug" => $item->slug,
                    "dokumen" => $item->dokumen,
                    "nama_opd" => $item->nama_opd,
                    "tahun" => $item->tahun,
                    "keterangan" => $item->keterangan
                ];
                
                return $list;
            });

            return response()->json([
                'success' => true,
                'message' => 'List of Dokumen Upload',
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
                'message' => 'List of Dokumen Upload',
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

    public function preview($id)
    {
        try {
            /*------------ cek validasi-------------------------------------*/
                // cek validasi jika id berformar uuid atau tidak
                if(!Str::isUuid($id)){
                    return response()->json([
                        'success' => false,
                        'message' => 'Invalid Id, Data Uploads not Found',
                    ], 422);
                }
                // cek existing tujuan
                $DataUploads = DataUploads::find($id);
                if (!$DataUploads) {
                    // jika data tidak ditamukan di dalam database
                    return response()->json([
                        'success' => false,
                        'message' => 'Data Uploads not found.',
                    ], 404);
                }
            /*------------ cek validasi-------------------------------------*/
            $year = substr($DataUploads->created_at,0,4);
            $objectName = 'upload/'.$year.'/'.$DataUploads->dokumen; // or image.jpg, video.mp4 die;
            

            if (!Storage::disk('s3')->exists($objectName)) {
                return response()->json([
                    'success' => true,
                    'message' => 'File Loaded unsuccessfully.',
                    'errors' => 'File Not Found',
                ]);
            }
            

            $path = !empty($DataUploads) ? $DataUploads->dokumen : '';
            $disk = Storage::disk('s3');

            if (!$disk->exists($objectName)) {
                abort(404);
            }

            $stream = $disk->get($objectName);
            $mime = $disk->mimeType($objectName);

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
