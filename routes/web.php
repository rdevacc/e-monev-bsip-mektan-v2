<?php

use App\Http\Controllers\ActivityController;
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
use App\Http\Controllers\WorkGroupController;
use App\Http\Controllers\WorkTeamController;
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
        'index' => 'user.index',
        'create' => 'user.create',
        'store' => 'user.create-submit',
        'edit' => 'user.edit',
        'update' => 'user.edit-submit',
        'destroy' => 'user.delete',
    ])->middleware('admin');

    /**
     * * Role Route
     */
    Route::resource('/app/role', RoleController::class)->middleware('superadmin')->names([
        'index' => 'role.index',
        'create' => 'role.create',
        'store' => 'role.create-submit',
        'edit' => 'role.edit',
        'update' => 'role.edit-submit',
        'destroy' => 'role.delete',
    ]);

    /**
     * * Work Group Route
     */
    Route::resource('/app/work-group', WorkGroupController::class)->middleware('admin')->names([
        'index' => 'work-group.index',
        'create' => 'work-group.create',
        'store' => 'work-group.create-submit',
        'edit' => 'work-group.edit',
        'update' => 'work-group.edit-submit',
        'destroy' => 'work-group.delete',
    ]);

    /**
     * * Work Team Route
     */
    Route::resource('/app/work-team', WorkTeamController::class)->middleware('admin')->names([
        'index' => 'work-team.index',
        'create' => 'work-team.create',
        'store' => 'work-team.create-submit',
        'edit' => 'work-team.edit',
        'update' => 'work-team.edit-submit',
        'destroy' => 'work-team.delete',
    ]);

    /**
     * * Activity Route
     */
    Route::resource('/app/activity', ActivityController::class)->middleware(['auth', 'editkegiatan'])->names([
        'index' => 'activity.index',
        'create' => 'activity.create',
        'show' => 'activity.show',
        'store' => 'activity.create-submit',
        'edit' => 'activity.edit',
        'update' => 'activity.edit-submit',
        'destroy' => 'activity.delete',
    ]);
});