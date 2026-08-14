<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FacilityReportController;

Route::get('/', function () {
    $recentFacilities = \App\Models\FacilityReport::with(['asset'])
        ->withCount('upvotes')
        ->whereNotIn('status', ['Menunggu', 'Ditolak'])
        ->orderBy('upvotes_count', 'desc')
        ->latest()
        ->take(4)
        ->get();

    $recentLostFounds = \App\Models\LostAndFound::where('status', '!=', 'Menunggu Verifikasi')
        ->latest()
        ->take(4)
        ->get();

    $topStudents = \App\Models\User::where('role', 'user')
        ->where('points', '>', 0)
        ->orderBy('points', 'desc')
        ->orderBy('created_at', 'asc')
        ->take(3)
        ->get();

    return view('welcome', compact('recentFacilities', 'recentLostFounds', 'topStudents'));
});

// === RUTENYA LEADERBOARD / GAMIFIKASI (PUBLIK) ===
Route::get('/leaderboard', [DashboardController::class, 'publicLeaderboard'])->name('leaderboard.index');

// === RUTENYA PROFIL PUBLIK (DAPAT DIAKSES SIAPAPUN) ===
Route::get('/u/{nis}', [\App\Http\Controllers\PublicProfileController::class, 'show'])->name('profile.public');

// Guest Routes (Mendaftar dan Masuk)
Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register.show');
    Route::post('/register', [AuthController::class, 'register'])->name('register');
    
    Route::get('/verify-otp', [AuthController::class, 'showVerifyOtp'])->name('verify.otp.show');
    Route::post('/verify-otp', [AuthController::class, 'verifyOtp'])->name('verify.otp');
    Route::post('/verify-otp/resend', [AuthController::class, 'resendOtp'])->name('verify.otp.resend');

    Route::get('/login', [AuthController::class, 'showLogin'])->name('login.show');
    Route::post('/login', [AuthController::class, 'login'])->name('login');

    // Lupa Password Routes
    Route::get('/forgot-password', [\App\Http\Controllers\PasswordResetController::class, 'showForgotForm'])->name('password.request');
    Route::post('/forgot-password', [\App\Http\Controllers\PasswordResetController::class, 'sendOtp'])->name('password.email');
    Route::get('/forgot-password/verify', [\App\Http\Controllers\PasswordResetController::class, 'showVerifyOtp'])->name('password.verify.show');
    Route::post('/forgot-password/verify', [\App\Http\Controllers\PasswordResetController::class, 'verifyOtp'])->name('password.verify');
    Route::post('/forgot-password/resend', [\App\Http\Controllers\PasswordResetController::class, 'resendOtp'])->name('password.verify.resend');
    Route::get('/reset-password', [\App\Http\Controllers\PasswordResetController::class, 'showResetForm'])->name('password.reset.show');
    Route::post('/reset-password', [\App\Http\Controllers\PasswordResetController::class, 'resetPassword'])->name('password.update');
});

// Authenticated Routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // === RUTENYA PROFILE & SETTINGS ===
    Route::get('/profile', [\App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::patch('/profile/password', [\App\Http\Controllers\ProfileController::class, 'updatePassword'])->name('profile.password');
    Route::delete('/profile', [\App\Http\Controllers\ProfileController::class, 'destroy'])->name('profile.destroy');

    // === RUTENYA NOTIFIKASI IN-APP ===
    Route::post('/notifications/read-all', [\App\Http\Controllers\NotificationController::class, 'markAllAsRead'])->name('notifications.read_all');
    Route::post('/notifications/{id}/read', [\App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('notifications.read');

    // === RUTENYA ASSET CATALOG (CMDB) ===
    Route::get('/assets', [\App\Http\Controllers\AssetCatalogController::class, 'index'])->name('assets.catalog');
    Route::post('/assets/store', [\App\Http\Controllers\AssetCatalogController::class, 'store'])->name('assets.store');
    Route::post('/assets/import', [\App\Http\Controllers\AssetCatalogController::class, 'import'])->name('assets.import');
    Route::put('/assets/{id}', [\App\Http\Controllers\AssetCatalogController::class, 'update'])->name('assets.update');
    Route::get('/assets/template/csv', [\App\Http\Controllers\AssetCatalogController::class, 'downloadTemplate'])->name('assets.template');
    Route::get('/assets/{id}/track', [\App\Http\Controllers\AssetCatalogController::class, 'show'])->name('assets.track');

    // === RUTENYA WEB SCANNER (IN-APP) ===
    Route::get('/scanner', function () {
        return view('scanner.index');
    })->name('scanner.index');

    // === RUTENYA GLOBAL SEARCH HUB ===
    Route::get('/explore', [\App\Http\Controllers\SearchController::class, 'index'])->name('search.index');

    // Router dasar untuk mengarahkan User biasa vs Admin saat berhasil login
    Route::get('/dashboard', function() {
        if (auth()->user()->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('user.dashboard');
    })->name('dashboard');

    // === RUTENYA USER BIASA / SISWA / GURU ===
    Route::prefix('user')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'userIndex'])->name('user.dashboard');
    });

    // Rutinitas Pelaporan Fasilitas (Bisa diakses User)
    Route::prefix('fasilitas')->group(function () {
        Route::get('/feed', [FacilityReportController::class, 'indexPublic'])->name('fasilitas.feed');
        Route::get('/lapor', [FacilityReportController::class, 'create'])->name('fasilitas.create');
        Route::post('/lapor', [FacilityReportController::class, 'store'])->name('fasilitas.store');
        Route::get('/{id}/detail', [FacilityReportController::class, 'show'])->name('fasilitas.show');
        Route::post('/{id}/comment', [FacilityReportController::class, 'storeComment'])->name('fasilitas.comment.store');
        Route::post('/{id}/upvote', [FacilityReportController::class, 'toggleUpvote'])->name('fasilitas.upvote');
    });

    // === FAQ (Portal Bantuan Mandiri Publik) ===
    Route::get('/faq', [App\Http\Controllers\KnowledgeBaseController::class, 'publicIndex'])->name('faq.index');
    Route::get('/faq/{id}/read', [App\Http\Controllers\KnowledgeBaseController::class, 'readArticle'])->name('faq.read');

    // === RUTENYA SATPAM / SARPRAS / ADMIN ===
    Route::prefix('admin')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'adminIndex'])->name('admin.dashboard');
        Route::get('/export-report', [DashboardController::class, 'exportReport'])->name('admin.export_report');
        
        Route::get('/fasilitas', [FacilityReportController::class, 'adminIndex'])->name('admin.fasilitas.index');
        Route::patch('/fasilitas/{id}/status', [FacilityReportController::class, 'updateStatus'])->name('admin.fasilitas.update_status');

        Route::get('/faq', [App\Http\Controllers\KnowledgeBaseController::class, 'adminIndex'])->name('admin.faq.index');
        Route::post('/faq', [App\Http\Controllers\KnowledgeBaseController::class, 'store'])->name('admin.faq.store');
        Route::delete('/faq/{id}', [App\Http\Controllers\KnowledgeBaseController::class, 'destroy'])->name('admin.faq.destroy');

        Route::get('/lost-found', [\App\Http\Controllers\LostFoundController::class, 'adminIndex'])->name('admin.lost_found.index');
        Route::patch('/lost-found/{id}/status', [\App\Http\Controllers\LostFoundController::class, 'updateStatus'])->name('admin.lost_found.update_status');
        Route::patch('/lost-found/claims/{id}/status', [\App\Http\Controllers\LostFoundClaimController::class, 'updateStatus'])->name('admin.lost_found.claim.update_status');
        
        // Endpoint AJAX Scanner Web
        Route::post('/handover/scan', [\App\Http\Controllers\LostFoundClaimController::class, 'verifyHandoverQR'])->name('admin.handover.verify');
        
        // === RUTENYA MASTER DATA SISWA (WHITELIST) ===
        Route::get('/student-masters', [\App\Http\Controllers\StudentMasterController::class, 'index'])->name('admin.student_masters.index');
        Route::post('/student-masters/import', [\App\Http\Controllers\StudentMasterController::class, 'importCsv'])->name('admin.student_masters.import');
    });

    // === RUTENYA LOST & FOUND ===
    Route::get('/lost-found', [\App\Http\Controllers\LostFoundController::class, 'index'])->name('lost-found.index');
    Route::get('/lost-found/lapor', [\App\Http\Controllers\LostFoundController::class, 'create'])->name('lost-found.create');
    Route::post('/lost-found/lapor', [\App\Http\Controllers\LostFoundController::class, 'store'])->name('lost-found.store');
    Route::post('/lost-found/{id}/claim', [\App\Http\Controllers\LostFoundClaimController::class, 'store'])->name('lost-found.claim.store');
});
