<?php

use App\Http\Controllers\ActivityController;
use App\Http\Controllers\ActivityExportController;
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

/**
 * * Authentication Route *
 */
Route::get('/v2/app/login', [LoginController::class, 'index'])->middleware('guest')->name('login');
Route::post('/v2/app/login', [LoginController::class, 'authenticate'])->name('login-submit');
Route::post('/v2/app/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/v2/app/forgot-password', [ForgotPasswordController::class, 'forgot_password'])->middleware('guest')->name('forgot-password');
Route::post('/v2/app/forgot-password-submit', [ForgotPasswordController::class, 'forgot_password_submit'])->name('forgot-password-submit');

Route::get('/v2/app/reset-password/{token}', [ResetPasswordController::class, 'reset_password'])->middleware('guest')->name('password.reset');
Route::post('/v2/app/reset-password/', [ResetPasswordController::class, 'reset_password_submit'])->middleware('guest')->name('password.update');


Route::prefix('/v2')->middleware('auth')->group(function () {

    /**
     * * Dashboard Route
     */
    Route::get('/app/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    /**
     * * Excel Route
     */
    Route::get('/app/export', [ActivityExportController::class, 'index'])->name('export.index');

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
    Route::resource('/app/role', RoleController::class)->names([
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
    Route::resource('/app/work-group', WorkGroupController::class)->names([
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
    Route::resource('/app/work-team', WorkTeamController::class)->names([
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
    Route::resource('/app/activity', ActivityController::class)->names([
        'index' => 'activity.index',
        'create' => 'activity.create',
        'show' => 'activity.show',
        'store' => 'activity.create-submit',
        'edit' => 'activity.edit',
        'update' => 'activity.edit-submit',
        'destroy' => 'activity.delete',
    ]);

    Route::get('/app/activity/{id}/monthly-data', [ActivityController::class, 'getMonthlyData'])
    ->name('activity.monthly-data');

    Route::post('/app/activity/{activity}/clear-monthly', [ActivityController::class, 'clearMonthlyData'])->name('activity.clearMonthlyData');

});