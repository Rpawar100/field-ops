<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\ZoneController;
use App\Http\Controllers\API\RegionController;
use App\Http\Controllers\API\TerritoryController;
use App\Http\Controllers\API\HeadquartersController;
use App\Http\Controllers\API\SDTVController;
use App\Http\Controllers\API\BeatController;
use App\Http\Controllers\API\CropController;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\DistributorController;
use App\Http\Controllers\API\FarmerController;
use App\Http\Controllers\API\RetailerController;
use App\Http\Controllers\API\ActivityController;
use App\Http\Controllers\API\AttendanceController;
use App\Http\Controllers\API\ATPController;
use App\Http\Controllers\API\DemoController;
use App\Http\Controllers\API\OnboardingController;
use App\Http\Controllers\API\ReportController;
use App\Http\Controllers\API\DashboardController;
use App\Http\Controllers\API\NotificationController;

/*
|--------------------------------------------------------------------------
| API Routes (v1)
|--------------------------------------------------------------------------
| All API routes are prefixed with /api/v1
*/

Route::prefix('v1')->group(function () {

    // ==================== AUTHENTICATION (PUBLIC) ====================
    Route::prefix('auth')->group(function () {
        Route::post('/register', [AuthController::class, 'register'])->name('auth.register');
        Route::post('/login', [AuthController::class, 'login'])->name('auth.login');
        Route::post('/password-reset', [AuthController::class, 'passwordReset'])->name('auth.password-reset');
    });

    // ==================== PROTECTED ROUTES ====================
    // All routes below require authentication
    Route::middleware('auth:sanctum')->group(function () {

        // Authentication
        Route::prefix('auth')->group(function () {
            Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout');
            Route::post('/refresh', [AuthController::class, 'refresh'])->name('auth.refresh');
            Route::get('/profile', [AuthController::class, 'profile'])->name('auth.profile');
        });

        // ==================== DASHBOARD & NOTIFICATIONS ====================
        Route::prefix('dashboard')->group(function () {
            Route::get('/', [DashboardController::class, 'index'])->name('dashboard.index');
            Route::get('/kpis', [DashboardController::class, 'kpis'])->name('dashboard.kpis');
            Route::get('/recent-activities', [DashboardController::class, 'recentActivities'])->name('dashboard.recent-activities');
            Route::get('/team-performance', [DashboardController::class, 'teamPerformance'])->name('dashboard.team-performance');
        });

        Route::prefix('notifications')->group(function () {
            Route::get('/', [NotificationController::class, 'index'])->name('notifications.index');
            Route::post('/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
            Route::post('/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.read-all');
            Route::delete('/{id}', [NotificationController::class, 'delete'])->name('notifications.delete');
        });

        // ==================== MASTER DATA MANAGEMENT ====================
        // ZRTH Hierarchy
        Route::apiResource('zones', ZoneController::class)->except(['destroy'])->names('zones');
        Route::post('zones/{zone}/realign', [ZoneController::class, 'realign'])->name('zones.realign');
        Route::delete('zones/{zone}', [ZoneController::class, 'destroy'])->name('zones.destroy');

        Route::apiResource('regions', RegionController::class)->except(['destroy'])->names('regions');
        Route::post('regions/{region}/realign', [RegionController::class, 'realign'])->name('regions.realign');
        Route::delete('regions/{region}', [RegionController::class, 'destroy'])->name('regions.destroy');

        Route::apiResource('territories', TerritoryController::class)->except(['destroy'])->names('territories');
        Route::post('territories/{territory}/realign', [TerritoryController::class, 'realign'])->name('territories.realign');
        Route::delete('territories/{territory}', [TerritoryController::class, 'destroy'])->name('territories.destroy');

        Route::apiResource('headquarters', HeadquartersController::class)->names('headquarters');

        // SDTV Hierarchy
        Route::prefix('sdtv')->group(function () {
            Route::get('/states', [SDTVController::class, 'indexStates'])->name('sdtv.states.index');
            Route::post('/states', [SDTVController::class, 'storeState'])->name('sdtv.states.store');
            Route::get('/states/{id}', [SDTVController::class, 'showState'])->name('sdtv.states.show');
            Route::put('/states/{id}', [SDTVController::class, 'updateState'])->name('sdtv.states.update');
            Route::delete('/states/{id}', [SDTVController::class, 'destroyState'])->name('sdtv.states.destroy');

            Route::get('/districts', [SDTVController::class, 'indexDistricts'])->name('sdtv.districts.index');
            Route::post('/districts', [SDTVController::class, 'storeDistrict'])->name('sdtv.districts.store');
            Route::get('/districts/{id}', [SDTVController::class, 'showDistrict'])->name('sdtv.districts.show');
            Route::put('/districts/{id}', [SDTVController::class, 'updateDistrict'])->name('sdtv.districts.update');
            Route::delete('/districts/{id}', [SDTVController::class, 'destroyDistrict'])->name('sdtv.districts.destroy');

            Route::get('/talukas', [SDTVController::class, 'indexTalukas'])->name('sdtv.talukas.index');
            Route::post('/talukas', [SDTVController::class, 'storeTaluka'])->name('sdtv.talukas.store');
            Route::get('/talukas/{id}', [SDTVController::class, 'showTaluka'])->name('sdtv.talukas.show');
            Route::put('/talukas/{id}', [SDTVController::class, 'updateTaluka'])->name('sdtv.talukas.update');
            Route::delete('/talukas/{id}', [SDTVController::class, 'destroyTaluka'])->name('sdtv.talukas.destroy');

            Route::get('/villages', [SDTVController::class, 'indexVillages'])->name('sdtv.villages.index');
            Route::post('/villages', [SDTVController::class, 'storeVillage'])->name('sdtv.villages.store');
            Route::get('/villages/{id}', [SDTVController::class, 'showVillage'])->name('sdtv.villages.show');
            Route::put('/villages/{id}', [SDTVController::class, 'updateVillage'])->name('sdtv.villages.update');
            Route::delete('/villages/{id}', [SDTVController::class, 'destroyVillage'])->name('sdtv.villages.destroy');
        });

        // ==================== USER MANAGEMENT ====================
        Route::apiResource('users', UserController::class)->names('users');
        Route::post('users/{user}/realign', [UserController::class, 'realign'])->name('users.realign');
        Route::post('users/bulk-upload', [UserController::class, 'bulkUpload'])->name('users.bulk-upload');
        Route::get('users/bulk-download/template', [UserController::class, 'bulkDownload'])->name('users.bulk-download');

        // ==================== PRODUCTS & MASTERS ====================
        // Beats
        Route::apiResource('beats', BeatController::class)->names('beats');
        Route::post('beats/{beat}/realign', [BeatController::class, 'realign'])->name('beats.realign');
        Route::post('beats/{beat}/assign-farmers', [BeatController::class, 'assignFarmers'])->name('beats.assign-farmers');
        Route::post('beats/{beat}/assign-retailers', [BeatController::class, 'assignRetailers'])->name('beats.assign-retailers');

        // Crops & Products
        Route::apiResource('crops', CropController::class)->names('crops');
        Route::apiResource('products', ProductController::class)->names('products');
        Route::get('products/{product}/history', [ProductController::class, 'getHistory'])->name('products.history');

        // Distributors
        Route::apiResource('distributors', DistributorController::class)->names('distributors');
        Route::post('distributors/{distributor}/realign', [DistributorController::class, 'realign'])->name('distributors.realign');
        Route::post('distributors/{distributor}/assign-users', [DistributorController::class, 'assignUsers'])->name('distributors.assign-users');

        // ==================== FARMER MANAGEMENT ====================
        Route::apiResource('farmers', FarmerController::class)->names('farmers');
        Route::post('farmers/bulk-upload', [FarmerController::class, 'bulkUpload'])->name('farmers.bulk-upload');
        Route::post('farmers/{farmer}/toggle-status', [FarmerController::class, 'toggleStatus'])->name('farmers.toggle-status');
        Route::get('farmers/beat/{beat}/list', [FarmerController::class, 'getByBeat'])->name('farmers.by-beat');
        Route::post('farmers/{farmer}/add-retailer', [FarmerController::class, 'addRetailer'])->name('farmers.add-retailer');

        // ==================== RETAILER MANAGEMENT ====================
        Route::apiResource('retailers', RetailerController::class)->names('retailers');
        Route::post('retailers/bulk-upload', [RetailerController::class, 'bulkUpload'])->name('retailers.bulk-upload');
        Route::post('retailers/{retailer}/kyc-update', [RetailerController::class, 'kycUpdate'])->name('retailers.kyc-update');
        Route::get('retailers/beat/{beat}/list', [RetailerController::class, 'getByBeat'])->name('retailers.by-beat');
        Route::post('retailers/{retailer}/add-distributors', [RetailerController::class, 'addDistributor'])->name('retailers.add-distributors');

        // ==================== ACTIVITY EXECUTION ====================
        Route::apiResource('activities', ActivityController::class)->names('activities');
        Route::get('activities/date/{date}/list', [ActivityController::class, 'getByDate'])->name('activities.by-date');
        Route::get('activities/atp/{atp}/list', [ActivityController::class, 'getByATP'])->name('activities.by-atp');
        Route::post('activities/{activity}/execute', [ActivityController::class, 'execute'])->name('activities.execute');
        Route::post('activities/{activity}/upload-photo', [ActivityController::class, 'uploadPhoto'])->name('activities.upload-photo');
        Route::get('activities/{activity}/photos', [ActivityController::class, 'getPhotos'])->name('activities.photos');

        // ==================== ATTENDANCE ====================
        Route::prefix('attendance')->group(function () {
            Route::post('/check-in', [AttendanceController::class, 'checkIn'])->name('attendance.check-in');
            Route::post('/check-out', [AttendanceController::class, 'checkOut'])->name('attendance.check-out');
            Route::get('/today', [AttendanceController::class, 'getToday'])->name('attendance.today');
            Route::get('/month', [AttendanceController::class, 'getMonth'])->name('attendance.month');
            Route::get('/history', [AttendanceController::class, 'getHistory'])->name('attendance.history');
            Route::get('/dashboard', [AttendanceController::class, 'getDashboard'])->name('attendance.dashboard');
            Route::get('/team', [AttendanceController::class, 'getTeamAttendance'])->name('attendance.team');
            Route::post('/regularize', [AttendanceController::class, 'regularize'])->name('attendance.regularize');
        });

        // ==================== ADVANCE TOUR PLAN ====================
        Route::apiResource('atps', ATPController::class)->names('atps');
        Route::post('atps/{atp}/add-beat', [ATPController::class, 'addBeat'])->name('atps.add-beat');
        Route::delete('atps/{atp}/beats/{beat}', [ATPController::class, 'removeBeat'])->name('atps.remove-beat');
        Route::post('atps/{atp}/execute', [ATPController::class, 'execute'])->name('atps.execute');
        Route::get('atps/planned/{date}/list', [ATPController::class, 'getPlanned'])->name('atps.planned');
        Route::get('atps/territory/{territory}/beats', [ATPController::class, 'autoFillBeats'])->name('atps.auto-beats');

        // ==================== DEMO MANAGEMENT ====================
        Route::prefix('demo')->group(function () {
            // Distribution
            Route::get('/distributions', [DemoController::class, 'indexDistribution'])->name('demo.distributions.index');
            Route::post('/distributions', [DemoController::class, 'storeDistribution'])->name('demo.distributions.store');
            Route::get('/distributions/{id}', [DemoController::class, 'showDistribution'])->name('demo.distributions.show');

            // Execution
            Route::get('/executions', [DemoController::class, 'executionIndex'])->name('demo.executions.index');
            Route::post('/executions', [DemoController::class, 'executionStore'])->name('demo.executions.store');

            // Operations
            Route::get('/reconciliation', [DemoController::class, 'reconciliation'])->name('demo.reconciliation');
            Route::post('/reschedule', [DemoController::class, 'reschedule'])->name('demo.reschedule');
            Route::post('/failure', [DemoController::class, 'failure'])->name('demo.failure');
        });

        // ==================== FA ONBOARDING ====================
        Route::prefix('onboarding')->group(function () {
            Route::get('/', [OnboardingController::class, 'index'])->name('onboarding.index');
            Route::get('/{id}', [OnboardingController::class, 'show'])->name('onboarding.show');
            Route::post('/', [OnboardingController::class, 'store'])->name('onboarding.store');
            Route::put('/{id}', [OnboardingController::class, 'update'])->name('onboarding.update');

            // Documents
            Route::post('/{id}/upload-document', [OnboardingController::class, 'uploadDocument'])->name('onboarding.upload-document');
            Route::get('/{id}/documents', [OnboardingController::class, 'getDocuments'])->name('onboarding.documents');
            Route::get('/documents/{docId}/download', [OnboardingController::class, 'downloadDocument'])->name('onboarding.download-document');
            Route::get('/{id}/download-merged-pdf', [OnboardingController::class, 'downloadMergedPDF'])->name('onboarding.download-pdf');

            // Workflow
            Route::post('/{id}/approve', [OnboardingController::class, 'approve'])->name('onboarding.approve');
            Route::post('/{id}/reject', [OnboardingController::class, 'reject'])->name('onboarding.reject');
            Route::post('/{id}/cancel', [OnboardingController::class, 'cancel'])->name('onboarding.cancel');
        });

        // ==================== REPORTS & ANALYTICS ====================
        Route::prefix('reports')->group(function () {
            Route::get('/activity-summary', [ReportController::class, 'activitySummary'])->name('reports.activity-summary');
            Route::get('/attendance-summary', [ReportController::class, 'attendanceSummary'])->name('reports.attendance-summary');
            Route::get('/demo-summary', [ReportController::class, 'demoSummary'])->name('reports.demo-summary');
            Route::get('/coverage-summary', [ReportController::class, 'coverageSummary'])->name('reports.coverage-summary');
            Route::get('/user-distribution', [ReportController::class, 'userDistribution'])->name('reports.user-distribution');
            Route::get('/zrth-hierarchy', [ReportController::class, 'zrthHierarchy'])->name('reports.zrth-hierarchy');
            Route::get('/export-activities', [ReportController::class, 'exportActivityReport'])->name('reports.export-activities');
            Route::get('/export-attendance', [ReportController::class, 'exportAttendanceReport'])->name('reports.export-attendance');
        });

    }); // End of auth:sanctum middleware group

}); // End of v1 prefix group
