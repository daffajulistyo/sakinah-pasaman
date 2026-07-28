<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/auth',[App\Http\Controllers\Api\v1\AuthController::class, 'authenticate']);
Route::post('/v1/pegawai/auth', [App\Http\Controllers\Api\v1\PegawaiAuthController::class, 'login']);

Route::prefix('v1')
->middleware('JwtAuthorization') // protected by jwt
->group(function() {

    Route::get('/me',[App\Http\Controllers\Api\v1\HomeController::class, 'me']);
    Route::get('/getmyroles',[App\Http\Controllers\Api\v1\HomeController::class, 'getMyRoles']);
    Route::put('/changemyrole',[App\Http\Controllers\Api\v1\HomeController::class, 'changeMyRole']);

    // modul kepala daerah (KDH)
    Route::prefix('kdh')
    ->middleware('role:Admin_KDH')
    ->group(function(){
       
        // untuk sub modul pohon kinerja
        Route::prefix('pohonkinerja')->group(function(){

            // untuk crud visi
            Route::get('/visi/list', [App\Http\Controllers\Api\v1\KDH\PohonKinerjaVisiController::class, 'list']);
            Route::post('/visi', [App\Http\Controllers\Api\v1\KDH\PohonKinerjaVisiController::class, 'create']);
            Route::get('/visi/{id}', [App\Http\Controllers\Api\v1\KDH\PohonKinerjaVisiController::class, 'read']);
            Route::put('/visi/{id}', [App\Http\Controllers\Api\v1\KDH\PohonKinerjaVisiController::class, 'update']);
            Route::delete('/visi/{id}', [App\Http\Controllers\Api\v1\KDH\PohonKinerjaVisiController::class, 'delete']);
            Route::get('/showall', [App\Http\Controllers\Api\v1\KDH\PohonKinerjaController::class, 'showall']);

            // untuk crud misi
            Route::get('/misi/list', [App\Http\Controllers\Api\v1\KDH\PohonKinerjaMisiController::class, 'list']);
            Route::post('/misi', [App\Http\Controllers\Api\v1\KDH\PohonKinerjaMisiController::class, 'create']);
            Route::get('/misi/{id}', [App\Http\Controllers\Api\v1\KDH\PohonKinerjaMisiController::class, 'read']);
            Route::put('/misi/{id}', [App\Http\Controllers\Api\v1\KDH\PohonKinerjaMisiController::class, 'update']);
            Route::delete('/misi/{id}', [App\Http\Controllers\Api\v1\KDH\PohonKinerjaMisiController::class, 'delete']);


            // untuk crud tujuan
            Route::get('/tujuan/list', [App\Http\Controllers\Api\v1\KDH\PohonKinerjaTujuanController::class, 'list']);
            Route::post('/tujuan', [App\Http\Controllers\Api\v1\KDH\PohonKinerjaTujuanController::class, 'create']);
            Route::get('/tujuan/{id}', [App\Http\Controllers\Api\v1\KDH\PohonKinerjaTujuanController::class, 'read']);
            Route::put('/tujuan/{id}', [App\Http\Controllers\Api\v1\KDH\PohonKinerjaTujuanController::class, 'update']);
            Route::delete('/tujuan/{id}', [App\Http\Controllers\Api\v1\KDH\PohonKinerjaTujuanController::class, 'delete']);


            // untuk crud sasaran
            Route::get('/sasaran/list', [App\Http\Controllers\Api\v1\KDH\PohonKinerjaSasaranController::class, 'list']);
            Route::post('/sasaran', [App\Http\Controllers\Api\v1\KDH\PohonKinerjaSasaranController::class, 'create']);
            Route::get('/sasaran/{id}', [App\Http\Controllers\Api\v1\KDH\PohonKinerjaSasaranController::class, 'read']);
            Route::put('/sasaran/{id}', [App\Http\Controllers\Api\v1\KDH\PohonKinerjaSasaranController::class, 'update']);
            Route::delete('/sasaran/{id}', [App\Http\Controllers\Api\v1\KDH\PohonKinerjaSasaranController::class, 'delete']);
            

             // untuk crud indikator
             Route::get('/indikator/list', [App\Http\Controllers\Api\v1\KDH\PohonKinerjaIndikatorController::class, 'list']);
             Route::post('/indikator', [App\Http\Controllers\Api\v1\KDH\PohonKinerjaIndikatorController::class, 'create']);
             Route::get('/indikator/{id}', [App\Http\Controllers\Api\v1\KDH\PohonKinerjaIndikatorController::class, 'read']);
             Route::put('/indikator/{id}', [App\Http\Controllers\Api\v1\KDH\PohonKinerjaIndikatorController::class, 'update']);
             Route::post('/indikator/upload/{id}', [App\Http\Controllers\Api\v1\KDH\PohonKinerjaIndikatorController::class, 'upload']);
             Route::get('/indikator/upload/{id}', [App\Http\Controllers\Api\v1\KDH\PohonKinerjaIndikatorController::class, 'preview']);
             Route::post('/indikator/{id}', [App\Http\Controllers\Api\v1\KDH\PohonKinerjaIndikatorController::class, 'update']);


             // untuk crud cascading
             Route::get('/cascading/list', [App\Http\Controllers\Api\v1\KDH\CascadingController::class, 'list']);
             Route::post('/cascading', [App\Http\Controllers\Api\v1\KDH\CascadingController::class, 'create']);
             Route::get('/cascading/{id}', [App\Http\Controllers\Api\v1\KDH\CascadingController::class, 'read']);
             Route::put('/cascading/{id}', [App\Http\Controllers\Api\v1\KDH\CascadingController::class, 'update']);
             Route::delete('/cascading/{id}', [App\Http\Controllers\Api\v1\KDH\CascadingController::class, 'delete']);
             Route::get('/showallsasaran', [App\Http\Controllers\Api\v1\KDH\CascadingController::class, 'showallsasaran']);

        });

         // untuk sub modul IKU
         Route::prefix('indikatorkinerjautama')->group(function(){
            Route::put('/update/{id}', [App\Http\Controllers\Api\v1\KDH\IndikatorKinerjaUtamaController::class, 'update']);
            Route::post('/create', [App\Http\Controllers\Api\v1\KDH\IndikatorKinerjaUtamaController::class, 'create']);          
            Route::post('/update/{id}', [App\Http\Controllers\Api\v1\KDH\IndikatorKinerjaUtamaController::class, 'update']);
            Route::get('/cetak', [App\Http\Controllers\Api\v1\KDH\RpjmdController::class, 'generate_pdf_iku']);
         });

         // untuk sub modul RPJMD
         Route::prefix('rpjmd')->group(function(){
            Route::put('/update/{id}', [App\Http\Controllers\Api\v1\KDH\RpjmdController::class, 'update']);
         });

         // untuk sub modul RPJMD
         Route::prefix('rpjmd')->group(function(){
            Route::get('/list', [App\Http\Controllers\Api\v1\KDH\RpjmdController::class, 'list']);
            Route::get('/cetak', [App\Http\Controllers\Api\v1\KDH\RpjmdController::class, 'generate_pdf']);
         });


         // untuk sub modul RKPD         
         Route::get('/rkpd/list', [App\Http\Controllers\Api\v1\KDH\RkpdController::class, 'list']);
         Route::post('/rkpd', [App\Http\Controllers\Api\v1\KDH\RkpdController::class, 'create']);
         //Route::get('/rkpd/{id}', [App\Http\Controllers\Api\v1\KDH\RkpdController::class, 'read']);
         //Route::put('/rkpd/{id}', [App\Http\Controllers\Api\v1\KDH\RkpdController::class, 'update']);
         Route::delete('/rkpd/{id}', [App\Http\Controllers\Api\v1\KDH\RkpdController::class, 'delete']);
         Route::get('/rkpd/cetak', [App\Http\Controllers\Api\v1\KDH\RkpdController::class, 'generate_pdf']);


         // untuk sub modul RKPD         
         Route::get('/rkpd-kegiatan/list', [App\Http\Controllers\Api\v1\KDH\RkpdKegiatanController::class, 'list']);
         Route::post('/rkpd-kegiatan', [App\Http\Controllers\Api\v1\KDH\RkpdKegiatanController::class, 'create']);
         Route::get('/rkpd-kegiatan/{id}', [App\Http\Controllers\Api\v1\KDH\RkpdKegiatanController::class, 'read']);
         Route::put('/rkpd-kegiatan/{id}', [App\Http\Controllers\Api\v1\KDH\RkpdKegiatanController::class, 'update']);
         Route::delete('/rkpd-kegiatan/{id}', [App\Http\Controllers\Api\v1\KDH\RkpdKegiatanController::class, 'delete']);


         // untuk sub modul perjanjian kinerja         
         Route::get('/perjanjian-kinerja/list', [App\Http\Controllers\Api\v1\KDH\PerjanjianKinerjaController::class, 'list']);
         Route::post('/perjanjian-kinerja', [App\Http\Controllers\Api\v1\KDH\PerjanjianKinerjaController::class, 'create']);
         Route::get('/perjanjian-kinerja/{id}', [App\Http\Controllers\Api\v1\KDH\PerjanjianKinerjaController::class, 'read']);
         Route::put('/perjanjian-kinerja/{id}', [App\Http\Controllers\Api\v1\KDH\PerjanjianKinerjaController::class, 'update']);
         Route::delete('/perjanjian-kinerja/{id}', [App\Http\Controllers\Api\v1\KDH\PerjanjianKinerjaController::class, 'delete']);
         Route::get('/perjanjian-kinerja-cetak', [App\Http\Controllers\Api\v1\KDH\PerjanjianKinerjaController::class, 'generate_pdf']);


          // untuk sub modul program perjanjian kinerja         
          Route::get('/perjanjian-kinerja-program/list', [App\Http\Controllers\Api\v1\KDH\PerjanjianKinerjaProgramController::class, 'list']);
          Route::post('/perjanjian-kinerja-program', [App\Http\Controllers\Api\v1\KDH\PerjanjianKinerjaProgramController::class, 'create']);
          Route::get('/perjanjian-kinerja-program/{id}', [App\Http\Controllers\Api\v1\KDH\PerjanjianKinerjaProgramController::class, 'read']);
          Route::put('/perjanjian-kinerja-program/{id}', [App\Http\Controllers\Api\v1\KDH\PerjanjianKinerjaProgramController::class, 'update']);
          Route::delete('/perjanjian-kinerja-program/{id}', [App\Http\Controllers\Api\v1\KDH\PerjanjianKinerjaProgramController::class, 'delete']);

         //Route untuk rencana aksi
         Route::put('/rencana/{id}', [App\Http\Controllers\Api\v1\KDH\PerjanjianKinerjaController::class, 'updateTargetRencanaAksi']);
                 
         Route::get('/aksi/list', [App\Http\Controllers\Api\v1\KDH\RencanaAksiController::class, 'list']);
         Route::post('/aksi', [App\Http\Controllers\Api\v1\KDH\RencanaAksiController::class, 'create']);
         Route::get('/aksi/{id}', [App\Http\Controllers\Api\v1\KDH\RencanaAksiController::class, 'read']);
         Route::put('/aksi/{id}', [App\Http\Controllers\Api\v1\KDH\RencanaAksiController::class, 'update']);
         Route::delete('/aksi/{id}', [App\Http\Controllers\Api\v1\KDH\RencanaAksiController::class, 'delete']);

         Route::get('/aksi-cetak', [App\Http\Controllers\Api\v1\KDH\RencanaAksiController::class, 'generate_pdf']);

         //Route untuk rencana aksi
         Route::put('/rencana/{id}', [App\Http\Controllers\Api\v1\KDH\PerjanjianKinerjaController::class, 'updateTargetRencanaAksi']);
                 
         Route::get('/langkah-aksi/list', [App\Http\Controllers\Api\v1\KDH\RencanaAksiLangkahController::class, 'list']);
         Route::post('/langkah-aksi', [App\Http\Controllers\Api\v1\KDH\RencanaAksiLangkahController::class, 'create']);
         Route::get('/langkah-aksi/{id}', [App\Http\Controllers\Api\v1\KDH\RencanaAksiLangkahController::class, 'read']);
         Route::put('/langkah-aksi/{id}', [App\Http\Controllers\Api\v1\KDH\RencanaAksiLangkahController::class, 'update']);
         Route::delete('/langkah-aksi/{id}', [App\Http\Controllers\Api\v1\KDH\RencanaAksiLangkahController::class, 'delete']);
         
         
         Route::prefix('realisasi')->group(function(){
            Route::get('/list', [App\Http\Controllers\Api\v1\KDH\RealisasiController::class, 'list']);
            Route::put('/update/{id}', [App\Http\Controllers\Api\v1\KDH\RealisasiController::class, 'update']);
            
            Route::get('/langkah/list', [App\Http\Controllers\Api\v1\KDH\RealisasiLangkahController::class, 'list']);
            Route::put('/langkah/{id}', [App\Http\Controllers\Api\v1\KDH\RealisasiLangkahController::class, 'update']);
        });


        Route::prefix('pelaporan')->group(function(){
            Route::get('/data_kinerja', [App\Http\Controllers\Api\v1\KDH\LaporanController::class, 'data_kinerja']);
            Route::get('/data_kinerja_cetak', [App\Http\Controllers\Api\v1\KDH\LaporanController::class, 'data_kinerja_cetak']);
            Route::put('/update_realisasi/{id}', [App\Http\Controllers\Api\v1\KDH\LaporanController::class, 'update_realisasi']);
            Route::get('/capaian', [App\Http\Controllers\Api\v1\KDH\LaporanController::class, 'capaian']);
            Route::get('/capaian_cetak', [App\Http\Controllers\Api\v1\KDH\LaporanController::class, 'capaian_cetak']);

        });

    });

    //Modul data master
    Route::prefix('master')->group(function(){

        // CRUD Pegawai
        Route::prefix('pegawai')->group(function(){
            Route::get('/list', [App\Http\Controllers\Api\v1\MASTER\PegawaiController::class, 'list']);
            Route::post('/', [App\Http\Controllers\Api\v1\MASTER\PegawaiController::class, 'create']);
            Route::get('/{id}', [App\Http\Controllers\Api\v1\MASTER\PegawaiController::class, 'read']);
            Route::put('/{id}', [App\Http\Controllers\Api\v1\MASTER\PegawaiController::class, 'update']);
            Route::delete('/{id}', [App\Http\Controllers\Api\v1\MASTER\PegawaiController::class, 'delete']);
        });

        // Referensi
        Route::prefix('ref')->group(function(){
            Route::get('/eselon', [App\Http\Controllers\Api\v1\MASTER\RefController::class, 'eselon']);
            Route::get('/golongan', [App\Http\Controllers\Api\v1\MASTER\RefController::class, 'golongan']);
            Route::get('/jenis-jabatan', [App\Http\Controllers\Api\v1\MASTER\RefController::class, 'jenisJabatan']);
            Route::get('/jabatan', [App\Http\Controllers\Api\v1\MASTER\RefController::class, 'jabatan']);
            Route::get('/sub-opd', [App\Http\Controllers\Api\v1\MASTER\RefController::class, 'subOpd']);
            Route::get('/roles', [App\Http\Controllers\Api\v1\MASTER\RefController::class, 'roles']);
            Route::get('/program', [App\Http\Controllers\Api\v1\MASTER\RefController::class, 'program']);
            Route::get('/kegiatan', [App\Http\Controllers\Api\v1\MASTER\RefController::class, 'kegiatan']);
            Route::get('/sub-kegiatan', [App\Http\Controllers\Api\v1\MASTER\RefController::class, 'subKegiatan']);
        });

        // untuk sub modul pohon kinerja
        Route::prefix('data')->group(function(){

            // untuk crud satuan
            Route::get('/satuan/list', [App\Http\Controllers\Api\v1\MASTER\MasterSatuanController::class, 'list']);
            Route::post('/satuan', [App\Http\Controllers\Api\v1\MASTER\MasterSatuanController::class, 'create']);
            Route::get('/satuan/{id}', [App\Http\Controllers\Api\v1\MASTER\MasterSatuanController::class, 'read']);
            Route::put('/satuan/{id}', [App\Http\Controllers\Api\v1\MASTER\MasterSatuanController::class, 'update']);
            Route::delete('/satuan/{id}', [App\Http\Controllers\Api\v1\MASTER\MasterSatuanController::class, 'delete']);

            // untuk crud opd
            Route::get('/opd/list', [App\Http\Controllers\Api\v1\MASTER\MasterOpdController::class, 'list']);
            Route::post('/opd', [App\Http\Controllers\Api\v1\MASTER\MasterOpdController::class, 'create']);
            Route::get('/opd/{id}', [App\Http\Controllers\Api\v1\MASTER\MasterOpdController::class, 'read']);
            Route::put('/opd/{id}', [App\Http\Controllers\Api\v1\MASTER\MasterOpdController::class, 'update']);
            Route::delete('/opd/{id}', [App\Http\Controllers\Api\v1\MASTER\MasterOpdController::class, 'delete']);

            // untuk crud program
            Route::get('/program/list', [App\Http\Controllers\Api\v1\MASTER\MasterProgramController::class, 'list']);
            Route::post('/program', [App\Http\Controllers\Api\v1\MASTER\MasterProgramController::class, 'create']);
            Route::get('/program/{id}', [App\Http\Controllers\Api\v1\MASTER\MasterProgramController::class, 'read']);
            Route::put('/program/{id}', [App\Http\Controllers\Api\v1\MASTER\MasterProgramController::class, 'update']);
            Route::delete('/program/{id}', [App\Http\Controllers\Api\v1\MASTER\MasterProgramController::class, 'delete']);

            // untuk crud kegiatan
            Route::get('/kegiatan/list', [App\Http\Controllers\Api\v1\MASTER\MasterKegiatanController::class, 'list']);
            Route::post('/kegiatan', [App\Http\Controllers\Api\v1\MASTER\MasterKegiatanController::class, 'create']);
            Route::get('/kegiatan/{id}', [App\Http\Controllers\Api\v1\MASTER\MasterKegiatanController::class, 'read']);
            Route::put('/kegiatan/{id}', [App\Http\Controllers\Api\v1\MASTER\MasterKegiatanController::class, 'update']);
            Route::delete('/kegiatan/{id}', [App\Http\Controllers\Api\v1\MASTER\MasterKegiatanController::class, 'delete']);

            // untuk crud sub kegiatan
            Route::get('/sub-kegiatan/list', [App\Http\Controllers\Api\v1\MASTER\MasterSubKegiatanController::class, 'list']);
            Route::post('/sub-kegiatan', [App\Http\Controllers\Api\v1\MASTER\MasterSubKegiatanController::class, 'create']);
            Route::get('/sub-kegiatan/{id}', [App\Http\Controllers\Api\v1\MASTER\MasterSubKegiatanController::class, 'read']);
            Route::put('/sub-kegiatan/{id}', [App\Http\Controllers\Api\v1\MASTER\MasterSubKegiatanController::class, 'update']);
            Route::delete('/sub-kegiatan/{id}', [App\Http\Controllers\Api\v1\MASTER\MasterSubKegiatanController::class, 'delete']);

        });
    });

     //Modul data master
     Route::prefix('opd')
        ->middleware('role:Admin_OPD')
        ->group(function(){ 
        // untuk sub modul pohon kinerja
        Route::prefix('pohonkinerja')->group(function(){

            // untuk view pohon kinerja
            Route::get('/showall', [App\Http\Controllers\Api\v1\OPD\PohonKinerjaOpdController::class, 'index']);

            Route::get('/tujuan_opd', [App\Http\Controllers\Api\v1\OPD\PohonKinerjaOpdController::class, 'tujuan_opd']);
            Route::get('/sasaran_opd', [App\Http\Controllers\Api\v1\OPD\PohonKinerjaOpdController::class, 'sasaran_opd']);
            Route::get('/visi', [App\Http\Controllers\Api\v1\OPD\PohonKinerjaOpdController::class, 'list_visi']);


            // untuk crud tujuan
            Route::get('/tujuan/list', [App\Http\Controllers\Api\v1\OPD\TujuanOpdController::class, 'list']);
            Route::post('/tujuan', [App\Http\Controllers\Api\v1\OPD\TujuanOpdController::class, 'create']);
            Route::get('/tujuan/{id}', [App\Http\Controllers\Api\v1\OPD\TujuanOpdController::class, 'read']);
            Route::put('/tujuan/{id}', [App\Http\Controllers\Api\v1\OPD\TujuanOpdController::class, 'update']);
            Route::delete('/tujuan/{id}', [App\Http\Controllers\Api\v1\OPD\TujuanOpdController::class, 'delete']);

            Route::get('/getSasaranKDH', [App\Http\Controllers\Api\v1\OPD\TujuanOpdController::class, 'getSasaranKDH']);

            // untuk crud sasaran
            Route::get('/sasaran/list', [App\Http\Controllers\Api\v1\OPD\SasaranOpdController::class, 'list']);
            Route::post('/sasaran', [App\Http\Controllers\Api\v1\OPD\SasaranOpdController::class, 'create']);
            Route::get('/sasaran/{id}', [App\Http\Controllers\Api\v1\OPD\SasaranOpdController::class, 'read']);
            Route::put('/sasaran/{id}', [App\Http\Controllers\Api\v1\OPD\SasaranOpdController::class, 'update']);
            Route::delete('/sasaran/{id}', [App\Http\Controllers\Api\v1\OPD\SasaranOpdController::class, 'delete']);

            // untuk crud indikator
            Route::get('/indikator/list', [App\Http\Controllers\Api\v1\OPD\IndikatorOpdController::class, 'list']);
            Route::post('/indikator', [App\Http\Controllers\Api\v1\OPD\IndikatorOpdController::class, 'create']);
            Route::get('/indikator/{id}', [App\Http\Controllers\Api\v1\OPD\IndikatorOpdController::class, 'read']);
            Route::put('/indikator/{id}', [App\Http\Controllers\Api\v1\OPD\IndikatorOpdController::class, 'update']);
            Route::delete('/indikator/{id}', [App\Http\Controllers\Api\v1\OPD\IndikatorOpdController::class, 'delete']);

            Route::post('/indikator/upload/{id}', [App\Http\Controllers\Api\v1\OPD\IndikatorOpdController::class, 'upload']);
            Route::get('/indikator/preview/{id}', [App\Http\Controllers\Api\v1\OPD\IndikatorOpdController::class, 'preview']);

            // untuk crud indikator
            Route::get('/pengampu/list', [App\Http\Controllers\Api\v1\OPD\PengampuController::class, 'list']);
            Route::post('/pengampu', [App\Http\Controllers\Api\v1\OPD\PengampuController::class, 'create']);
            Route::get('/pengampu/{id}', [App\Http\Controllers\Api\v1\OPD\PengampuController::class, 'read']);
            Route::put('/pengampu/{id}', [App\Http\Controllers\Api\v1\OPD\PengampuController::class, 'update']);
            Route::delete('/pengampu/{id}', [App\Http\Controllers\Api\v1\OPD\PengampuController::class, 'delete']);
             
        });

        // untuk crud cascading
        Route::prefix('cascading')->group(function(){
            Route::get('/showall', [App\Http\Controllers\Api\v1\OPD\CascadingOPDController::class, 'showall']);
            Route::post('/create', [App\Http\Controllers\Api\v1\OPD\CascadingOPDController::class, 'create']);
        });

        // untuk crud cascading
        Route::prefix('renja')->group(function(){
            Route::get('/showall', [App\Http\Controllers\Api\v1\OPD\RenjaController::class, 'showall']);
            Route::post('/create', [App\Http\Controllers\Api\v1\OPD\RenjaController::class, 'create']);
            Route::post('/create-program', [App\Http\Controllers\Api\v1\OPD\RenjaController::class, 'createProgram']);
            Route::get('/list-program', [App\Http\Controllers\Api\v1\OPD\RenjaController::class, 'listProgram']);
            Route::get('/cetak', [App\Http\Controllers\Api\v1\OPD\RenjaController::class, 'generate_pdf']);
        });

        // untuk crud renstra
        Route::prefix('renstra')->group(function(){
            Route::put('/update/{id}', [App\Http\Controllers\Api\v1\OPD\RenstraController::class, 'update']);
            Route::get('/list-indikator', [App\Http\Controllers\Api\v1\OPD\RenstraController::class, 'list']);
            Route::get('/cetak', [App\Http\Controllers\Api\v1\OPD\RenstraController::class, 'generate_pdf']);
        });

        // untuk crud renstra
        Route::prefix('indikatorkinerjautama')->group(function(){
            Route::put('/update/{id}', [App\Http\Controllers\Api\v1\OPD\IkuController::class, 'update']);
            Route::get('/list', [App\Http\Controllers\Api\v1\OPD\IkuController::class, 'list']);
            Route::get('/cetak', [App\Http\Controllers\Api\v1\OPD\IkuController::class, 'generate_pdf']);
        });

        // untuk crud cascading
        Route::prefix('perjanjiankinerja')->group(function(){
            Route::get('/showall', [App\Http\Controllers\Api\v1\OPD\PerjanjianKinerjaController::class, 'showall']);
            Route::post('/create', [App\Http\Controllers\Api\v1\OPD\PerjanjianKinerjaController::class, 'create']);
            Route::post('/create-program', [App\Http\Controllers\Api\v1\OPD\PerjanjianKinerjaController::class, 'createProgram']);
            Route::get('/list-program', [App\Http\Controllers\Api\v1\OPD\PerjanjianKinerjaController::class, 'listProgram']);
            Route::get('/cetak', [App\Http\Controllers\Api\v1\OPD\PerjanjianKinerjaController::class, 'generate_pdf']);
        });

        // untuk crud target rencana aksi
        Route::prefix('aksi')->group(function(){
            Route::get('/list', [App\Http\Controllers\Api\v1\OPD\RencanaAksiController::class, 'list']);
            Route::post('/create', [App\Http\Controllers\Api\v1\OPD\RencanaAksiController::class, 'create']);
            Route::get('/cetak', [App\Http\Controllers\Api\v1\OPD\RencanaAksiController::class, 'generate_pdf']);
            
            Route::get('/langkah/list', [App\Http\Controllers\Api\v1\OPD\LangkahController::class, 'list']);
            Route::post('/langkah', [App\Http\Controllers\Api\v1\OPD\LangkahController::class, 'create']);
            Route::get('/langkah/{id}', [App\Http\Controllers\Api\v1\OPD\LangkahController::class, 'read']);
            Route::put('/langkah/{id}', [App\Http\Controllers\Api\v1\OPD\LangkahController::class, 'update']);
            Route::delete('/langkah/{id}', [App\Http\Controllers\Api\v1\OPD\LangkahController::class, 'delete']);
        });


         // untuk crud target rencana aksi
         Route::prefix('realisasi')->group(function(){
            Route::get('/list', [App\Http\Controllers\Api\v1\OPD\RealisasiController::class, 'list']);
            Route::put('/update/{id}', [App\Http\Controllers\Api\v1\OPD\RealisasiController::class, 'update']);
            
            Route::get('/langkah/list', [App\Http\Controllers\Api\v1\OPD\RealisasiLangkahController::class, 'list']);
            Route::put('/langkah/{id}', [App\Http\Controllers\Api\v1\OPD\RealisasiLangkahController::class, 'update']);
            Route::get('/cetak', [App\Http\Controllers\Api\v1\OPD\RealisasiController::class, 'generate_pdf']);
        });

        
        Route::prefix('pelaporan')->group(function(){
            Route::get('/data_kinerja', [App\Http\Controllers\Api\v1\OPD\LaporanController::class, 'data_kinerja']);
            Route::get('/cetak_data_kinerja', [App\Http\Controllers\Api\v1\OPD\LaporanController::class, 'cetak_data_kinerja']);
            Route::put('/update_realisasi/{id}', [App\Http\Controllers\Api\v1\OPD\LaporanController::class, 'update_realisasi']);
            Route::get('/capaian', [App\Http\Controllers\Api\v1\OPD\LaporanController::class, 'capaian']);

        });


         Route::prefix('operasional')->group(function(){

            // untuk crud sasaran
            Route::get('/sasaran/ref', [App\Http\Controllers\Api\v1\OPD\SasaranOperasionalController::class, 'sasaran_operasional_ref']);
            Route::get('/sasaran/list', [App\Http\Controllers\Api\v1\OPD\SasaranOperasionalController::class, 'list']);
            Route::post('/sasaran', [App\Http\Controllers\Api\v1\OPD\SasaranOperasionalController::class, 'create']);
            Route::get('/sasaran/{id}', [App\Http\Controllers\Api\v1\OPD\SasaranOperasionalController::class, 'read']);
            Route::put('/sasaran/{id}', [App\Http\Controllers\Api\v1\OPD\SasaranOperasionalController::class, 'update']);
            Route::delete('/sasaran/{id}', [App\Http\Controllers\Api\v1\OPD\SasaranOperasionalController::class, 'delete']);

            // untuk crud indikator
            Route::get('/indikator/ref/{id}', [App\Http\Controllers\Api\v1\OPD\IndikatorOperasionalController::class, 'indikator_operasional_ref']);
            Route::get('/indikator/list', [App\Http\Controllers\Api\v1\OPD\IndikatorOperasionalController::class, 'list']);
            Route::post('/indikator', [App\Http\Controllers\Api\v1\OPD\IndikatorOperasionalController::class, 'create']);
            Route::get('/indikator/{id}', [App\Http\Controllers\Api\v1\OPD\IndikatorOperasionalController::class, 'read']);
            Route::put('/indikator/{id}', [App\Http\Controllers\Api\v1\OPD\IndikatorOperasionalController::class, 'update']);
            Route::delete('/indikator/{id}', [App\Http\Controllers\Api\v1\OPD\IndikatorOperasionalController::class, 'delete']);

            // untuk crud indikator
            Route::get('/pengampu/list', [App\Http\Controllers\Api\v1\OPD\PengampuOperasionalController::class, 'list']);
            Route::post('/pengampu', [App\Http\Controllers\Api\v1\OPD\PengampuOperasionalController::class, 'create']);
            Route::get('/pengampu/{id}', [App\Http\Controllers\Api\v1\OPD\PengampuOperasionalController::class, 'read']);
            Route::put('/pengampu/{id}', [App\Http\Controllers\Api\v1\OPD\PengampuOperasionalController::class, 'update']);
            Route::delete('/pengampu/{id}', [App\Http\Controllers\Api\v1\OPD\PengampuOperasionalController::class, 'delete']);
             
        });

    });

    // modul service integrated
    Route::prefix('integrated')->group(function(){

        // integrated with IKD BPKAD
        Route::prefix('ikd-bpkad')->group(function(){
            Route::get('/anggaran/skpd-program/{idskpd}/{year}', [App\Http\Controllers\Api\v1\Services\IkdIntegratedController::class, 'getProgramAnggaranSkpd']);
        });

        // integrated with SIMONEV Bappeda 
        Route::prefix('simonev-bappeda')->group(function(){
            Route::get('/anggaran/skpd-program/{idskpd}/{year}', [App\Http\Controllers\Api\v1\Services\SimonevIntegratedController::class, 'getProgramAnggaranSkpd']);
        });

        // integrated with SIMPEG
        Route::prefix('simpeg')
        ->middleware('role:Admin_OPD,Pegawai')
        ->group(function(){
            Route::get('/pegawai/{idskpd}', [App\Http\Controllers\Api\v1\Services\SimpegController::class, 'getPegawaiOpd']);            
        });

        Route::prefix('program')
        ->middleware('role:Admin_KDH,Admin_OPD,Pegawai')
        ->group(function(){
            Route::get('/anggaran/{tahun}/{periode}', [App\Http\Controllers\Api\v1\Services\AnggaranController::class, 'index']);            
        });

         Route::prefix('program')
        ->middleware('role:Admin_KDH')
        ->group(function(){
            Route::get('/anggaran_opd/{tahun}/{periode}/{kode_skpd}', [App\Http\Controllers\Api\v1\Services\AnggaranController::class, 'getAnggaranOpd']);            
        });

    });

    /*--------------------------------Pegawai---------------------------*/
    Route::prefix('pegawai')
        ->middleware('IsPegawai')
        ->group(function(){
        // untuk perjanjian kinerja
        Route::prefix('perjanjiankinerja')->group(function(){
            Route::get('/showall', [App\Http\Controllers\Api\v1\Pegawai\PerjanjianController::class, 'showall']);
            Route::post('/create', [App\Http\Controllers\Api\v1\Pegawai\PerjanjianController::class, 'create']);
            Route::post('/create-program', [App\Http\Controllers\Api\v1\Pegawai\PerjanjianController::class, 'createProgram']);
            Route::get('/list-program', [App\Http\Controllers\Api\v1\Pegawai\PerjanjianController::class, 'listProgram']);
            Route::get('/cetak', [App\Http\Controllers\Api\v1\Pegawai\PerjanjianController::class, 'generate_pdf']);
        });

        // untuk crud target rencana aksi
        Route::prefix('aksi')->group(function(){
            Route::get('/list', [App\Http\Controllers\Api\v1\Pegawai\RencanaController::class, 'list']);
            Route::post('/create', [App\Http\Controllers\Api\v1\Pegawai\RencanaController::class, 'create']);
            
            Route::get('/langkah/list', [App\Http\Controllers\Api\v1\Pegawai\LangkahController::class, 'list']);
            Route::post('/langkah', [App\Http\Controllers\Api\v1\Pegawai\LangkahController::class, 'create']);
            Route::get('/langkah/{id}', [App\Http\Controllers\Api\v1\Pegawai\LangkahController::class, 'read']);
            Route::put('/langkah/{id}', [App\Http\Controllers\Api\v1\Pegawai\LangkahController::class, 'update']);
            Route::delete('/langkah/{id}', [App\Http\Controllers\Api\v1\Pegawai\LangkahController::class, 'delete']);

            Route::get('/cetak', [App\Http\Controllers\Api\v1\Pegawai\RencanaController::class, 'generate_pdf']);
        });


        // untuk crud target rencana aksi
        Route::prefix('realisasi')->group(function(){
            Route::get('/list', [App\Http\Controllers\Api\v1\Pegawai\RealisasiController::class, 'list']);
            Route::put('/update/{id}', [App\Http\Controllers\Api\v1\Pegawai\RealisasiController::class, 'update']);
            
            Route::get('/langkah/list', [App\Http\Controllers\Api\v1\Pegawai\RealisasiLangkahController::class, 'list']);
            Route::put('/langkah/{id}', [App\Http\Controllers\Api\v1\Pegawai\RealisasiLangkahController::class, 'update']);

            Route::get('/cetak', [App\Http\Controllers\Api\v1\Pegawai\RealisasiController::class, 'generate_pdf']);
        });


        // untuk crud SKP
        Route::prefix('skp')->group(function(){
            Route::get('/list', [App\Http\Controllers\Api\v1\Pegawai\SkpController::class, 'list']);
            Route::post('/create', [App\Http\Controllers\Api\v1\Pegawai\SkpController::class, 'create']);
            Route::get('/read/{id}', [App\Http\Controllers\Api\v1\Pegawai\SkpController::class, 'read']);
             
             Route::post('/indikator', [App\Http\Controllers\Api\v1\Pegawai\IndikatorSkpController::class, 'create']);
             Route::put('/indikator/{id}', [App\Http\Controllers\Api\v1\Pegawai\IndikatorSkpController::class, 'update']);
             Route::delete('/indikator/{id}', [App\Http\Controllers\Api\v1\Pegawai\IndikatorSkpController::class, 'delete']);
             Route::get('/getSasaranPegawai', [App\Http\Controllers\Api\v1\Pegawai\IndikatorSkpController::class, 'getSasaranPegawai']);
             Route::get('/indikator/list/{id}', [App\Http\Controllers\Api\v1\Pegawai\IndikatorSkpController::class, 'list']);
             Route::put('/indikator/realisasi/{id}', [App\Http\Controllers\Api\v1\Pegawai\IndikatorSkpController::class, 'realisasi']);
             Route::get('/indikator/{id}', [App\Http\Controllers\Api\v1\Pegawai\IndikatorSkpController::class, 'read']);

             Route::get('/langkah', [App\Http\Controllers\Api\v1\Pegawai\LangkahSkpController::class, 'list']);
             Route::post('/langkah', [App\Http\Controllers\Api\v1\Pegawai\LangkahSkpController::class, 'create']);
             Route::put('/langkah/{id}', [App\Http\Controllers\Api\v1\Pegawai\LangkahSkpController::class, 'update']);
             Route::delete('/langkah/{id}', [App\Http\Controllers\Api\v1\Pegawai\LangkahSkpController::class, 'delete']);
             Route::get('/langkah/realisasi', [App\Http\Controllers\Api\v1\Pegawai\LangkahSkpController::class, 'list_realisasi']);
             Route::put('/langkah/realisasi/{id}', [App\Http\Controllers\Api\v1\Pegawai\LangkahSkpController::class, 'update_realisasi']);

             Route::get('/cetak/{id}', [App\Http\Controllers\Api\v1\Pegawai\SkpController::class, 'generate_pdf']);
             Route::get('/cetak_realisasi/{id}', [App\Http\Controllers\Api\v1\Pegawai\SkpController::class, 'generate_pdf_realisasi']);
        });


        // untuk crud SKP
        Route::prefix('atasan')->group(function(){
            Route::get('/list', [App\Http\Controllers\Api\v1\Pegawai\AtasanController::class, 'list']);
            Route::post('/create', [App\Http\Controllers\Api\v1\Pegawai\AtasanController::class, 'create']);
        });

        Route::get('/profil', [App\Http\Controllers\Api\v1\Pegawai\ProfilController::class, 'index']);
        Route::post('/profil', [App\Http\Controllers\Api\v1\Pegawai\ProfilController::class, 'create']);
        

        

    });

    /*--------------------------------Pegawai---------------------------*/

    /*--------------------------------Monitoring---------------------------*/
    Route::prefix('monitoring')
    ->middleware('role:Admin_KDH')
    ->group(function(){
       
        // untuk sub modul pohon kinerja
        Route::prefix('opd')->group(function(){
            Route::get('/pohonkinerja', [App\Http\Controllers\Api\v1\Monitoring\PohonKinerjaController::class, 'index']);           
             Route::get('/renstra', [App\Http\Controllers\Api\v1\Monitoring\PohonKinerjaController::class, 'renstra']);           
             Route::get('/indikatorkinerjautama', [App\Http\Controllers\Api\v1\Monitoring\PohonKinerjaController::class, 'iku']);           
             Route::get('/cascading', [App\Http\Controllers\Api\v1\Monitoring\PohonKinerjaController::class, 'cascading']);           

             Route::get('/renja', [App\Http\Controllers\Api\v1\Monitoring\PerencanaanController::class, 'renja']);           
             Route::get('/perjanjian_kinerja', [App\Http\Controllers\Api\v1\Monitoring\PerencanaanController::class, 'perjanjian_kinerja']);           
             Route::get('/perjanjian_kinerja/cetak', [App\Http\Controllers\Api\v1\Monitoring\PerencanaanController::class, 'generate_pdf_pk']);           

             Route::get('/aksi', [App\Http\Controllers\Api\v1\Monitoring\PerencanaanController::class, 'rencana_aksi']);           
             Route::get('/aksi/cetak', [App\Http\Controllers\Api\v1\Monitoring\PerencanaanController::class, 'generate_pdf_aksi']);           

             Route::get('/realisasi', [App\Http\Controllers\Api\v1\Monitoring\PerencanaanController::class, 'realisasi']);           
             Route::get('/realisasi/cetak', [App\Http\Controllers\Api\v1\Monitoring\PerencanaanController::class, 'generate_pdf_realisasi']);           


             Route::get('/data_kinerja', [App\Http\Controllers\Api\v1\Monitoring\PelaporanController::class, 'data_kinerja']);           
             Route::get('/capaian', [App\Http\Controllers\Api\v1\Monitoring\PelaporanController::class, 'capaian']);           
        });

        

    });
    /*--------------------------------Monitoring---------------------------*/

    Route::prefix('upload')
        ->middleware('role:Admin_OPD')
        ->group(function(){
        Route::get('/list', [App\Http\Controllers\Api\v1\Dokumen\UploadsController::class, 'list']);            
        Route::post('/create', [App\Http\Controllers\Api\v1\Dokumen\UploadsController::class, 'create']);            
        Route::get('/read/{id}', [App\Http\Controllers\Api\v1\Dokumen\UploadsController::class, 'read']);            
        Route::delete('/delete/{id}', [App\Http\Controllers\Api\v1\Dokumen\UploadsController::class, 'delete']);            
        Route::get('/preview/{id}', [App\Http\Controllers\Api\v1\Dokumen\UploadsController::class, 'preview']);            
        Route::post('/update/{id}', [App\Http\Controllers\Api\v1\Dokumen\UploadsController::class, 'update']);            
    });

});


/*--------------------------------Dashboard---------------------------*/
    Route::prefix('dashboard')
        ->group(function(){               // untuk view pohon kinerja
            Route::get('/kdh/visi', [App\Http\Controllers\Api\v1\Dashboard\PemdaController::class, 'visi']);
            Route::get('/kdh/pohonkinerja', [App\Http\Controllers\Api\v1\Dashboard\PemdaController::class, 'pohonkinerja']);
            Route::get('/kdh/rpjmd', [App\Http\Controllers\Api\v1\Dashboard\PemdaController::class, 'rpjmd']);
            Route::get('/kdh/cascading', [App\Http\Controllers\Api\v1\Dashboard\PemdaController::class, 'cascading']);
            Route::get('/kdh/rkpd', [App\Http\Controllers\Api\v1\Dashboard\PemdaController::class, 'rkpd']);
            Route::get('/kdh/perjanjiankinerja', [App\Http\Controllers\Api\v1\Dashboard\PemdaController::class, 'perjanjiankinerja']);
            Route::get('/kdh/rencana', [App\Http\Controllers\Api\v1\Dashboard\PemdaController::class, 'rencana']);
            Route::get('/kdh/realisasi', [App\Http\Controllers\Api\v1\Dashboard\PemdaController::class, 'realisasi']);


            Route::get('/opd/list', [App\Http\Controllers\Api\v1\Dashboard\OpdController::class, 'list']);
            Route::get('/opd/pohonkinerja', [App\Http\Controllers\Api\v1\Dashboard\OpdController::class, 'pohonkinerja']);
            Route::get('/opd/renstra', [App\Http\Controllers\Api\v1\Dashboard\OpdController::class, 'renstra']);
            Route::get('/opd/cascading', [App\Http\Controllers\Api\v1\Dashboard\OpdController::class, 'cascading']);
            Route::get('/opd/renja', [App\Http\Controllers\Api\v1\Dashboard\OpdController::class, 'renja']);
            Route::get('/opd/perjanjiankinerja', [App\Http\Controllers\Api\v1\Dashboard\OpdController::class, 'perjanjiankinerja']);
            Route::get('/opd/rencana', [App\Http\Controllers\Api\v1\Dashboard\OpdController::class, 'rencana']);
            Route::get('/opd/realisasi', [App\Http\Controllers\Api\v1\Dashboard\OpdController::class, 'realisasi']);
     });
/*--------------------------------Dashboard---------------------------*/
