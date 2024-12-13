<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\LevelController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StokController;
use App\Http\Controllers\SalesController;


use Monolog\Level;

//Jobsheet 7 
Route::pattern('id', '[0-9]+'); //artinya ketika ada parameter{id}, maka harus berupa angka

// Route untuk landing page
    Route::get('/', function () {
        return view('landing');
    })->name('landing');

// Tugas Register
// Route untuk halaman register
Route::get('/register', [AuthController::class, 'register'])->name('register');
Route::post('/register', [AuthController::class, 'postRegister']);

Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'postlogin']);

Route::middleware(['auth'])->group(function(){ //artinya semua route di dalam group ini harus login dulu
    //masukkan semua route yang perlu autentikasi disnii

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

/*Route::get('/', function () {
    return view('welcome');
});

Route::get('/level',[LevelController::class, 'index']);
Route::get('/kategori',[KategoriController::class, 'index']);
Route::get('/user',[UserController::class, 'index']);
Route::get('/user/tambah',[UserController::class, 'tambah']);
Route::post('/user/tambah_simpan',[UserController::class, 'tambah_simpan']);
Route::get('/user/ubah/{id}',[UserController::class, 'ubah']);
Route::put('/user/ubah_simpan/{id}',[UserController::class, 'ubah_simpan']);
Route::get('/user/hapus/{id}',[UserController::class, 'hapus']);
*/

Route::get('logout', [AuthController::class, 'logout'])->middleware('auth');
Route::post('logout', [AuthController::class, 'logout'])->name('logout');

//Jobsheet 5
Route::get('/dashboard', [WelcomeController::class,'index']);
// Route::get('/barang', [UserController::class, 'index']);
// Route::get('/level',[LevelController::class, 'index']);
// Route::get('/kategori',[KategoriController::class, 'index']);

Route::middleware(['authorize:ADM'])->group(function(){
Route::group(['prefix' => 'user'], function(){
    Route::get('/', [UserController::class, 'index']);          //menampilkan halaman awal user
    Route::post('/list', [UserController::class, 'list']);      //menampilkan data user dalam bentuk json untuk datatables
    Route::get('/create', [UserController::class, 'create']);   //Menampilkan halaman form tambah user
    Route::post('/', [UserController::class, 'store']);         //Menyimpan data user baru
    Route::get('/create_ajax', [UserController::class, 'create_ajax']); //Menampilkan halaman form tambah user ajax
    Route::post('/ajax', [UserController::class, 'store_ajax']);      //Menyimpan data user baru ajax 
    Route::get('/{id}', [UserController::class, 'show']);       //menampilkan detail user
    Route::get('/{id}/edit', [UserController::class, 'edit']);  //menampilkan halaman form edit user
    Route::put('/{id}', [UserController::class, 'update']);     //menyimpan perubahan data user
    Route::get('/{id}/edit_ajax', [UserController::class, 'edit_ajax']); //Menampilkan halaman form edit user Ajax
    Route::put('/{id}/update_ajax', [UserController::class, 'update_ajax']);  //Menyimpan perubahan data user
    Route::get('/{id}/delete_ajax', [UserController::class, 'confirm_ajax']);  //tampilan form confirm ddelete user Ajax
    Route::delete('/{id}/delete_ajax', [UserController::class, 'delete_ajax']); //untuk hapus data user ajax
    Route::delete('/{id}', [UserController::class, 'destroy']); //mengahapus data user
    Route::get('/import', [UserController::class, 'import']);
    Route::post('/import_ajax', [UserController::class, 'import_ajax']);
    Route::get('/export_excel', [UserController::class, 'export_excel']); // export excel
    Route::get('/export_pdf', [UserController::class, 'export_pdf']);
});
});

Route::middleware(['authorize:ADM'])->group(function(){
    Route::group(['prefix' => 'level'], function(){
    Route::get('/', [LevelController::class, 'index']);
    Route::post('/list', [LevelController::class, 'list']);
    Route::get('/create', [LevelController::class, 'create']);
    Route::post('/', [LevelController::class, 'store']);
    Route::get('/create_ajax', [LevelController::class, 'create_ajax']); 
    Route::post('/ajax', [LevelController::class, 'store_ajax']);   
    Route::get('/{id}', [LevelController::class, 'show']);
    Route::get('/{id}/edit', [LevelController::class, 'edit']);
    Route::put('/{id}', [LevelController::class, 'update']);
    Route::get('/{id}/edit_ajax', [LevelController::class, 'edit_ajax']); 
    Route::put('/{id}/update_ajax', [LevelController::class, 'update_ajax']);  
    Route::get('/{id}/delete_ajax', [LevelController::class, 'confirm_ajax']);  
    Route::delete('/{id}/delete_ajax', [LevelController::class, 'delete_ajax']); 
    Route::delete('/{id}', [LevelController::class, 'destroy']);
    Route::get('/import', [LevelController::class, 'import']);
    Route::post('/import_ajax', [LevelController::class, 'import_ajax']);
    Route::get('/export_excel', [LevelController::class, 'export_excel']); // export excel
    Route::get('/export_pdf', [LevelController::class, 'export_pdf']); // export pdf
});
});

Route::middleware(['authorize:ADM,MNG,STF' ])->group(function(){
Route::group(['prefix' => 'barang'], function(){
    Route::get('/', [BarangController::class, 'index']);
    Route::post('/list', [BarangController::class, 'list']);
    Route::get('/create', [BarangController::class, 'create']);
    Route::post('/', [BarangController::class, 'store']);
    Route::get('/create_ajax', [BarangController::class, 'create_ajax']); 
    Route::post('/ajax', [BarangController::class, 'store_ajax']);   
    Route::get('/{id}', [BarangController::class, 'show']);
    Route::get('/{id}/show_ajax', [BarangController::class, 'show_ajax']); 
    // Route::get('/{id}/edit', [BarangController::class, 'edit']);
    Route::put('/{id}', [BarangController::class, 'update']);
    Route::get('/{id}/edit_ajax', [BarangController::class, 'edit_ajax']);
    Route::put('/{id}/update_ajax', [BarangController::class, 'update_ajax']);  
    Route::get('/{id}/delete_ajax', [BarangController::class, 'confirm_ajax']);  
    Route::delete('/{id}/delete_ajax', [BarangController::class, 'delete_ajax']); 
    Route::delete('/{id}', [BarangController::class, 'destroy']);
    Route::get('/import',[BarangController::class,'import']);
    Route::post('/import_ajax',[BarangController::class,'import_ajax']);
    Route::get('/export_excel',[BarangController::class,'export_excel']);
    Route::get('/export_pdf',[BarangController::class,'export_pdf']);

});
});

Route::middleware(['authorize:ADM,MNG,STF'])->group(function(){
Route::group(['prefix' => 'kategori'], function(){
    Route::get('/', [KategoriController::class, 'index']);
    Route::post('/list', [KategoriController::class, 'list']);
    Route::get('/create', [KategoriController::class, 'create']);
    Route::post('/', [KategoriController::class, 'store']);
    Route::get('/create_ajax', [KategoriController::class, 'create_ajax']); 
    Route::post('/ajax', [KategoriController::class, 'store_ajax']);   
    Route::get('/{id}', [KategoriController::class, 'show']);
    Route::get('/{id}/edit', [KategoriController::class, 'edit']);
    Route::put('/{id}', [KategoriController::class, 'update']);
    Route::get('/{id}/edit_ajax', [KategoriController::class, 'edit_ajax']);
    Route::put('/{id}/update_ajax', [KategoriController::class, 'update_ajax']);  
    Route::get('/{id}/delete_ajax', [KategoriController::class, 'confirm_ajax']);  
    Route::delete('/{id}/delete_ajax', [KategoriController::class, 'delete_ajax']); 
    Route::delete('/{id}', [KategoriController::class, 'destroy']);
    Route::get('/import',[KategoriController::class,'import']);
    Route::post('/import_ajax',[KategoriController::class,'import_ajax']);
    Route::get('/export_excel',[KategoriController::class,'export_excel']);
    Route::get('/export_pdf',[KategoriController::class,'export_pdf']);
});
});

Route::middleware(['authorize:ADM,MNG' ])->group(function(){
Route::group(['prefix' => 'supplier'], function(){
    Route::get('/', [SupplierController::class, 'index']);
    Route::post('/list', [SupplierController::class, 'list']);
    Route::get('/create', [SupplierController::class, 'create']);
    Route::post('/', [SupplierController::class, 'store']);
    Route::get('/create_ajax', [SupplierController::class, 'create_ajax']); 
    Route::post('/ajax', [SupplierController::class, 'store_ajax']);   
    Route::get('/{id}', [SupplierController::class, 'show']);
    Route::get('/{id}/edit', [SupplierController::class, 'edit']);
    Route::put('/{id}', [SupplierController::class, 'update']);
    Route::get('/{id}/edit_ajax', [SupplierController::class, 'edit_ajax']);
    Route::put('/{id}/update_ajax', [SupplierController::class, 'update_ajax']);  
    Route::get('/{id}/delete_ajax', [SupplierController::class, 'confirm_ajax']);  
    Route::delete('/{id}/delete_ajax', [SupplierController::class, 'delete_ajax']); 
    Route::delete('/{id}', [SupplierController::class, 'destroy']);
    Route::get('/import', [SupplierController::class, 'import']);
    Route::post('/import_ajax', [SupplierController::class, 'import_ajax']);
    Route::get('/export_excel', [SupplierController::class, 'export_excel']); 
    Route::get('/export_pdf', [SupplierController::class, 'export_pdf']); 
});
});

Route::middleware(['authorize:ADM,MNG,STF'])->group(function(){
    Route::group(['prefix' => 'stok'], function(){
        Route::get('/', [StokController::class, 'index']);     
        Route::post('/list', [StokController::class, 'list']);     
        Route::get('/create_ajax', [StokController::class, 'create_ajax']); 
        Route::post('/store_ajax', [StokController::class, 'store_ajax']);  
        Route::get('/{id}/edit_stok', [StokController::class, 'edit_stok']); 
        Route::put('/{id}/update_stok', [StokController::class, 'update_stok']);  
        Route::get('/{id}/delete_ajax', [StokController::class, 'confirm_ajax']);  
        Route::delete('/{id}/delete_ajax', [StokController::class, 'delete_ajax']);
        Route::get('/import',[StokController::class,'import']);
        Route::post('/import_ajax',[StokController::class,'import_ajax']);
        Route::get('/export_excel',[StokController::class,'export_excel']);
        Route::get('/export_pdf',[StokController::class,'export_pdf']);
    }); 
});
Route::middleware(['auth'])->group(function () {
    Route::prefix('sales')->group(function () {
        Route::get('/', [SalesController::class, 'index'])->name('sales.index');
        Route::get('/list', [SalesController::class, 'list'])->name('sales.list');
        Route::get('/{id}/show_ajax', [SalesController::class, 'show_ajax'])->name('sales.show_ajax');
        Route::get('/export_pdf', [SalesController::class, 'export_pdf'])->name('sales.export_pdf');
    });
});


//profile 
Route::middleware(['auth'])->group(function () {
    Route::get('/profil', [ProfileController::class, 'index'])->name('profil.index');
    Route::post('/profil/update', [ProfileController::class, 'update'])->name('profil.update');
    Route::post('/profil/update-avatar', [ProfileController::class, 'updateAvatar'])->name('profil.updateAvatar');
    Route::post('/profil/update_data_diri', [ProfileController::class, 'updateDataDiri'])->name('profil.updateDataDiri');
    Route::post('/profil/update_password', [ProfileController::class, 'updatePassword'])->name('profil.updatePassword');

});
});