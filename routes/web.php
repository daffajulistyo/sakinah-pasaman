<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

//public routes
Route::get('/', function () {
    $index = public_path('index.html');
    if (file_exists($index)) {
        return file_get_contents($index);
    }
    return 'Welcome to SAKINAH Webservice ';
});

Route::get('/cetak', [App\Http\Controllers\Api\v1\OPD\IkuController::class, 'generate_pdf']);

// ===================== AUTH-ADMIN (existing, Superadmin only) =====================
Route::middleware('guest')->group(function () {
    Route::get('/auth-admin', [App\Http\Controllers\LoginController::class, 'index'])->name('login');
    Route::post('/', [App\Http\Controllers\LoginController::class, 'authenticate'])->middleware('throttle:10,5');
});

// protected routes for all roles
Route::middleware('auth')->group(function () {
    Route::get('/logout', [App\Http\Controllers\LoginController::class, 'destroy'])->name('logout');
    Route::get('/roleplay/switch/{role_id}', [App\Http\Controllers\RolePlayController::class, 'switch'])->name('switch')->middleware('isActiveUser');
    Route::get('/home', [App\Http\Controllers\DashboardController::class, 'index'])->name('home')->middleware('isActiveUser');
});

// ===================== BACKEND (Bootstrap 5, Superadmin only, Master Data CRUD) =====================
Route::middleware('guest')->group(function () {
    Route::get('/backend', [App\Http\Controllers\Backend\AuthController::class, 'index'])->name('backend.login');
    Route::post('/backend', [App\Http\Controllers\Backend\AuthController::class, 'authenticate'])->middleware('throttle:10,5');
});

Route::middleware(['auth', 'roleplay', 'isActiveUser'])->prefix('backend')->group(function () {
    Route::get('/logout', [App\Http\Controllers\Backend\AuthController::class, 'destroy'])->name('logout');
    Route::get('/home', [App\Http\Controllers\Backend\DashboardController::class, 'index'])->name('home');

    Route::resource('/eselon', App\Http\Controllers\Backend\MasterEselonController::class)->except(['create', 'edit']);
    Route::resource('/golongan', App\Http\Controllers\Backend\MasterGolonganController::class)->except(['create', 'edit']);
    Route::resource('/jenis-jabatan', App\Http\Controllers\Backend\MasterJenisJabatanController::class)->except(['create', 'edit']);
    Route::resource('/jabatan', App\Http\Controllers\Backend\MasterJabatanController::class)->except(['create', 'edit']);
    Route::resource('/sub-opd', App\Http\Controllers\Backend\MasterSubOpdController::class)->except(['create', 'edit']);
    Route::resource('/opd', App\Http\Controllers\Backend\MasterOpdController::class)->except(['create', 'edit']);
    Route::resource('/satuan', App\Http\Controllers\Backend\MasterSatuanController::class)->except(['create', 'edit']);
    Route::resource('/kegiatan', App\Http\Controllers\Backend\MasterKegiatanController::class)->except(['create', 'edit']);
    Route::resource('/sub-kegiatan', App\Http\Controllers\Backend\MasterSubKegiatanController::class)->except(['create', 'edit']);
    Route::resource('/program', App\Http\Controllers\Backend\MasterProgramController::class)->except(['create', 'edit']);
    Route::resource('/pegawai', App\Http\Controllers\Backend\BackendPegawaiController::class)->except(['create', 'edit']);
    Route::resource('/user', App\Http\Controllers\Backend\BackendUserController::class)->except(['create', 'edit']);

    Route::post('/user/assign-role', [App\Http\Controllers\Backend\BackendUserController::class, 'assignRole'])->name('user.assign-role');
    Route::delete('/user/{userId}/role/{roleplayId}', [App\Http\Controllers\Backend\BackendUserController::class, 'removeRole'])->name('user.remove-role');
});

// ===================== AUTH-ADMIN PROTECTED (existing) =====================
Route::middleware(['auth','roleplay','isActiveUser'])->group(function () {

    Route::prefix('managements')->group(function(){
        Route::get('/user/datatable', [App\Http\Controllers\Managements\UserController::class, 'datatable']);
        Route::resource('/user', App\Http\Controllers\Managements\UserController::class)->except(['create', 'edit'])
        ->where(['user' => '[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}']);

        Route::get('/functions/showall', [App\Http\Controllers\Managements\FunctionsController::class, 'showall']);
        Route::get('/functions/datatable', [App\Http\Controllers\Managements\FunctionsController::class, 'datatable']);
        Route::resource('/functions', App\Http\Controllers\Managements\FunctionsController::class)->except(['create', 'edit'])
        ->where(['function' => '[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}']);

        Route::get('/controllers/showall', [App\Http\Controllers\Managements\ControllersController::class, 'showall']);
        Route::get('/controllers/datatable', [App\Http\Controllers\Managements\ControllersController::class, 'datatable']);
        Route::resource('/controllers', App\Http\Controllers\Managements\ControllersController::class)->except(['create', 'edit'])
        ->where(['controller' => '[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}']);

        Route::get('/modules/datatable', [App\Http\Controllers\Managements\ModulesController::class, 'datatable']);
        Route::resource('/modules', App\Http\Controllers\Managements\ModulesController::class)->except(['create', 'edit'])
        ->where(['module' => '[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}']);

        Route::get('/roles/showall', [App\Http\Controllers\Managements\RolesController::class, 'showall']);
        Route::get('/roles/datatable', [App\Http\Controllers\Managements\RolesController::class, 'datatable']);
        Route::resource('/roles', App\Http\Controllers\Managements\RolesController::class)->except(['create', 'edit'])
        ->where(['role' => '[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}']);

        Route::get('/actions/{id}', [App\Http\Controllers\Managements\ActionsController::class, 'index']);
        Route::post('/actions', [App\Http\Controllers\Managements\ActionsController::class, 'store']);
        Route::get('/actions/showall/{id}', [App\Http\Controllers\Managements\ActionsController::class, 'showall']);
        Route::delete('/actions/{id}', [App\Http\Controllers\Managements\ActionsController::class, 'destroy']);

        Route::get('/permissions/showall/{id}', [App\Http\Controllers\Managements\PermissionsController::class, 'showall']);
        Route::resource('/permissions', App\Http\Controllers\Managements\PermissionsController::class)->except(['create', 'edit','show'])
        ->where(['permission' => '[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}']);

        Route::get('/menugroups/datatable', [App\Http\Controllers\Managements\MenugroupsController::class, 'datatable']);
        Route::get('/menugroups/showall', [App\Http\Controllers\Managements\MenugroupsController::class, 'showall']);
        Route::resource('/menugroups', App\Http\Controllers\Managements\MenugroupsController::class)->except(['create', 'edit'])
        ->where(['menugroup' => '[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}']);

        Route::get('/menus/datatable', [App\Http\Controllers\Managements\MenusController::class, 'datatable']);
        Route::get('/menus/showall', [App\Http\Controllers\Managements\MenusController::class, 'showall']);
        Route::resource('/menus', App\Http\Controllers\Managements\MenusController::class)->except(['create', 'edit'])
        ->where(['menu' => '[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}']);

    });

    Route::prefix('data')->group(function(){
        Route::get('/usersakip/masteropd', [App\Http\Controllers\Data\UserSakipController::class, 'masteropd']);
        Route::get('/usersakip/roles', [App\Http\Controllers\Data\UserSakipController::class, 'roles']);
        Route::get('/usersakip/datatable', [App\Http\Controllers\Data\UserSakipController::class, 'datatable']);
        Route::get('/usersakip/showall', [App\Http\Controllers\Data\UserSakipController::class, 'showall']);
        Route::resource('/usersakip', App\Http\Controllers\Data\UserSakipController::class)->except(['create', 'edit']);

        Route::get('/adminkdh/datatable', [App\Http\Controllers\Data\AdminKdhController::class, 'datatable']);
        Route::get('/adminkdh', [App\Http\Controllers\Data\AdminKdhController::class, 'index'])->name('adminkdh.index');
        Route::post('/adminkdh', [App\Http\Controllers\Data\AdminKdhController::class, 'store'])->name('adminkdh.store');
        Route::delete('/adminkdh/{user_id}/{id_roleplay}', [App\Http\Controllers\Data\AdminKdhController::class, 'destroy'])->name('adminkdh.destroy');
        Route::get('/adminkdh/tambah', [App\Http\Controllers\Data\AdminKdhController::class, 'update'])->name('adminkdh.tambah');

        Route::get('/pegawai/datatable', [App\Http\Controllers\Data\PegawaiController::class, 'datatable']);

        Route::get('/adminopd/datatable', [App\Http\Controllers\Data\AdminOpdController::class, 'datatable']);
        Route::get('/adminopd', [App\Http\Controllers\Data\AdminOpdController::class, 'index'])->name('adminopd.index');
        Route::post('/adminopd', [App\Http\Controllers\Data\AdminOpdController::class, 'store'])->name('adminopd.store');
        Route::delete('/adminopd/{user_id}/{id_roleplay}', [App\Http\Controllers\Data\AdminOpdController::class, 'destroy'])->name('adminopd.destroy');
        Route::get('/adminopd/tambah', [App\Http\Controllers\Data\AdminOpdController::class, 'update'])->name('adminopd.tambah');

        Route::get('/masterprogram/datatable', [App\Http\Controllers\Data\MasterProgramController::class, 'datatable']);
        Route::get('/masterprogram', [App\Http\Controllers\Data\MasterProgramController::class, 'index'])->name('masterprogram.index');
        Route::post('/masterprogram', [App\Http\Controllers\Data\MasterProgramController::class, 'store'])->name('masterprogram.store');
        Route::delete('/masterprogram/{id}', [App\Http\Controllers\Data\MasterProgramController::class, 'destroy'])->name('masterprogram.destroy');
        Route::get('/masterprogram/tambah', [App\Http\Controllers\Data\MasterProgramController::class, 'tambah'])->name('masterprogram.tambah');
    });
});

// SPA fallback — semua route yg bukan api/web existing ditangani React
Route::get('/{any}', function () {
    $index = public_path('index.html');
    if (file_exists($index)) {
        return file_get_contents($index);
    }
    return 'SAKINAH frontend — run "npx vite" in resources/js for dev mode';
})->where('any', '^(?!\/?api|\/?auth-admin|\/?backend|\/?home|\/?logout|\/?cetak|\/?managements|\/?data|\/?sanctum).*$');
