<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExcelController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\KegiatanController;
use App\Http\Controllers\KelompokController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PDFController;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SubKelompokController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function(){
    return redirect()->route('login');
});

Route::prefix('/v2')->group(function () {

    /**
     * * Authentication Route
     */
    Route::get('/app/login', [LoginController::class, 'index'])->middleware('guest')->name('login');
    Route::post('/app/login', [LoginController::class, 'authenticate'])->name('login-submit');
    Route::post('/app/logout', [LoginController::class, 'logout'])->name('logout');

    Route::get('/app/forgot-password', [ForgotPasswordController::class, 'forgot_password'])->middleware('guest')->name('forgot-password');
    Route::post('/app/forgot-password-submit', [ForgotPasswordController::class, 'forgot_password_submit'])->name('forgot-password-submit');

    Route::get('/app/reset-password/{token}', [ResetPasswordController::class, 'reset_password'])->middleware('guest')->name('password.reset');
    Route::post('/app/reset-password/', [ResetPasswordController::class, 'reset_password_submit'])->middleware('guest')->name('password.update');

    /**
     * * Dashboard Route
     */
    Route::get('/app/dashboard', [DashboardController::class, 'index'])->name('dashboard')->middleware('auth');


    /**
     * * Excel Route
     */
    Route::post('/app/kegiatan/excel', [ExcelController::class, 'generateExcel'])->name('excel');

    /**
     * * PDF Route
     */
    // Route::get('/app/kegiatan/page-pdf', [PDFController::class, 'pagePDF'])->name('page-pdf');
    Route::post('/app/kegiatan/pdf', [PDFController::class, 'generatePDF'])->name('pdf');


    /**
     * * User Route
     */
    Route::resource('/app/user', UserController::class)->names([
        'index' => 'user-index',
        'create' => 'user-create',
        'store' => 'user-create-submit',
        'edit' => 'user-edit',
        'update' => 'user-edit-submit',
        'destroy' => 'user-delete',
    ])->middleware('admin');

    /**
     * * Role Route
     */
    Route::resource('/app/role', RoleController::class)->middleware('superadmin')->names([
        'index' => 'role-index',
        'create' => 'role-create',
        'store' => 'role-create-submit',
        'edit' => 'role-edit',
        'update' => 'role-edit-submit',
        'destroy' => 'role-delete',
    ]);

    /**
     * * Kelompok Route
     */
    Route::resource('/app/kelompok', KelompokController::class)->middleware('admin')->names([
        'index' => 'kelompok-index',
        'create' => 'kelompok-create',
        'store' => 'kelompok-create-submit',
        'edit' => 'kelompok-edit',
        'update' => 'kelompok-edit-submit',
        'destroy' => 'kelompok-delete',
    ]);

    /**
     * * SubKelompok Route
     */
    Route::resource('/app/subkelompok', SubKelompokController::class)->middleware('admin')->names([
        'index' => 'subkelompok-index',
        'create' => 'subkelompok-create',
        'store' => 'subkelompok-create-submit',
        'edit' => 'subkelompok-edit',
        'update' => 'subkelompok-edit-submit',
        'destroy' => 'subkelompok-delete',
    ]);

    /**
     * * Kegiatan Route
     */
    Route::resource('/app/kegiatan', KegiatanController::class)->middleware(['auth', 'editkegiatan'])->names([
        'index' => 'kegiatan-index',
        'create' => 'kegiatan-create',
        'show' => 'kegiatan-show',
        'store' => 'kegiatan-create-submit',
        'edit' => 'kegiatan-edit',
        'update' => 'kegiatan-edit-submit',
        'destroy' => 'kegiatan-delete',
    ]);
});