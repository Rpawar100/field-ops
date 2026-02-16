<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\DashboardController;
use App\Http\Controllers\Web\UserController;
use App\Http\Controllers\Web\FarmerController;
use App\Http\Controllers\Web\RetailerController;
use App\Http\Controllers\Web\ActivityController;
use App\Http\Controllers\Web\AttendanceController;
use App\Http\Controllers\Web\ATPController;
use App\Http\Controllers\Web\DemoController;
use App\Http\Controllers\Web\OnboardingController;
use App\Http\Controllers\Web\ReportController;

/*
|--------------------------------------------------------------------------
| Web Routes (Blade Portal)
|--------------------------------------------------------------------------
| Routes for the admin/manager Blade web portal interface
*/

// Public Routes
Route::get('/login', 'App\Http\Controllers\Auth\LoginController@show')->name('login');
Route::post('/login', 'App\Http\Controllers\Auth\LoginController@login');
Route::post('/logout', 'App\Http\Controllers\Auth\LoginController@logout')->name('logout');

// Protected Routes (Authenticated Users Only)
Route::middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
    Route::get('/profile', [DashboardController::class, 'profile'])->name('profile');
    Route::get('/settings', [DashboardController::class, 'settings'])->name('settings');

    // User Management (Admin Only)
    Route::middleware(['role:Admin'])->group(function () {
        Route::resource('users', UserController::class);
        Route::post('users/{user}/realign', [UserController::class, 'realign'])->name('users.realign');
        Route::post('users/bulk-upload', [UserController::class, 'bulkUpload'])->name('users.bulk-upload');
        Route::get('users/bulk-download/template', [UserController::class, 'bulkDownload'])->name('users.bulk-download');
    });

    // Master Data Management
    Route::group(['prefix' => 'masters'], function () {
        // ZRTH Management
        Route::resource('zones', 'App\Http\Controllers\Web\ZoneController');
        Route::resource('regions', 'App\Http\Controllers\Web\RegionController');
        Route::resource('territories', 'App\Http\Controllers\Web\TerritoryController');
        Route::resource('headquarters', 'App\Http\Controllers\Web\HeadquartersController');

        // SDTV Management
        Route::resource('states', 'App\Http\Controllers\Web\StateController');
        Route::resource('districts', 'App\Http\Controllers\Web\DistrictController');
        Route::resource('talukas', 'App\Http\Controllers\Web\TalukaController');
        Route::resource('villages', 'App\Http\Controllers\Web\VillageController');

        // Other Masters
        Route::resource('beats', 'App\Http\Controllers\Web\BeatController');
        Route::resource('crops', 'App\Http\Controllers\Web\CropController');
        Route::resource('products', 'App\Http\Controllers\Web\ProductController');
        Route::resource('distributors', 'App\Http\Controllers\Web\DistributorController');
    });

    // Farmer Management
    Route::resource('farmers', FarmerController::class);
    Route::post('farmers/bulk-upload', [FarmerController::class, 'bulkUpload'])->name('farmers.bulk-upload');
    Route::get('farmers/{farmer}/activities', [FarmerController::class, 'activities'])->name('farmers.activities');
    Route::get('farmers/{farmer}/retailers', [FarmerController::class, 'retailers'])->name('farmers.retailers');

    // Retailer Management
    Route::resource('retailers', RetailerController::class);
    Route::post('retailers/bulk-upload', [RetailerController::class, 'bulkUpload'])->name('retailers.bulk-upload');
    Route::post('retailers/{retailer}/kyc-update', [RetailerController::class, 'kycUpdate'])->name('retailers.kyc-update');

    // Activity Management
    Route::resource('activities', ActivityController::class);
    Route::get('activities/{activity}/execute', [ActivityController::class, 'executeForm'])->name('activities.execute-form');
    Route::post('activities/{activity}/execute', [ActivityController::class, 'execute'])->name('activities.execute');
    Route::get('activities/{activity}/photos', [ActivityController::class, 'photos'])->name('activities.photos');

    // Attendance Management
    Route::group(['prefix' => 'attendance'], function () {
        Route::get('/', [AttendanceController::class, 'index'])->name('attendance.index');
        Route::get('/calendar', [AttendanceController::class, 'calendar'])->name('attendance.calendar');
        Route::get('/map', [AttendanceController::class, 'map'])->name('attendance.map');
        Route::get('/report', [AttendanceController::class, 'report'])->name('attendance.report');
        Route::get('/user/{user}', [AttendanceController::class, 'userAttendance'])->name('attendance.user');
        Route::post('/{attendance}/regularize', [AttendanceController::class, 'regularize'])->name('attendance.regularize');
    });

    // Tour Plans (ATP)
    Route::resource('atps', ATPController::class)->names('atps');
    Route::get('atps/{atp}/beats', [ATPController::class, 'beats'])->name('atps.beats');
    Route::post('atps/{atp}/beats', [ATPController::class, 'addBeat'])->name('atps.add-beat');

    // Demo Management
    Route::group(['prefix' => 'demo'], function () {
        Route::get('/', [DemoController::class, 'index'])->name('demo.index');
        Route::get('/distribution', [DemoController::class, 'distribution'])->name('demo.distribution');
        Route::post('/distribution', [DemoController::class, 'storeDistribution'])->name('demo.store-distribution');
        Route::get('/execution', [DemoController::class, 'execution'])->name('demo.execution');
        Route::get('/reconciliation', [DemoController::class, 'reconciliation'])->name('demo.reconciliation');
        Route::get('/{demo}', [DemoController::class, 'show'])->name('demo.show');
    });

    // FA Onboarding (Admin Only)
    Route::middleware(['role:Admin'])->group(function () {
        Route::resource('onboarding', OnboardingController::class);
        Route::post('onboarding/{onboarding}/approve', [OnboardingController::class, 'approve'])->name('onboarding.approve');
        Route::post('onboarding/{onboarding}/reject', [OnboardingController::class, 'reject'])->name('onboarding.reject');
        Route::get('onboarding/{onboarding}/documents', [OnboardingController::class, 'documents'])->name('onboarding.documents');
    });

    // Reports & Analytics
    Route::group(['prefix' => 'reports'], function () {
        Route::get('/', [ReportController::class, 'index'])->name('reports.index');
        Route::get('/activities', [ReportController::class, 'activities'])->name('reports.activities');
        Route::get('/attendance', [ReportController::class, 'attendance'])->name('reports.attendance');
        Route::get('/demo', [ReportController::class, 'demo'])->name('reports.demo');
        Route::get('/coverage', [ReportController::class, 'coverage'])->name('reports.coverage');
        Route::get('/export', [ReportController::class, 'export'])->name('reports.export');
    });

    // Audit Logs
    Route::get('audit-logs', 'App\Http\Controllers\Web\AuditLogController@index')->name('audit-logs.index');

});
