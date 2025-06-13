<?php


use App\Http\Controllers\InterviewHRController;
use App\Http\Controllers\InterviewUserController;
use App\Http\Controllers\OfferingController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PsikotestController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\ReportingController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\ScreeningCvController;
use App\Http\Middleware\AdminMiddleware;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });


Route::get('/', [App\Http\Controllers\PublicController::class, 'index'])->name('welcome');
Route::post('/opportunities/{id}',  [PublicController::class, 'store'])->name('simpanDt');
Route::get('/opportunities/{id}', [PublicController::class, 'show'])->name('show');

Route::get('/coming-soon', function () {
    return view('comingsoon');
})->name('comingsoon');

Route::get('/applicant', function () {
    return view('applicant-management');
})->middleware(['auth', 'checkAccessRole:7'])->name('applicant');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/users', function () {
    return view('users-management');
})->middleware(['auth', 'verified', 'checkAccessRole:1'])->name('users');

Route::get('/categories', function () {
    return view('category-management');
})->middleware(['auth', 'verified', 'checkAccessRole:4'])->name('categories');

Route::get('/divisions', function () {
    return view('divisi-management');
})->middleware(['auth', 'checkAccessRole:5'])->name('divisions');

Route::get('/opportunities', function () {
    return view('opportunity-management');
})->middleware(['auth', 'checkAccessRole:6'])->name('opportunities');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
// Route::get('/cv', function () {
//     return view('cv-screening-management');
// })->middleware(['auth', 'verified'])->name('cv');


Route::prefix('admin')->name('admin.')->middleware(['auth', 'checkAccessRole:8'])->group(function () {
    Route::get('/screenings', [ScreeningCvController::class, 'index'])->name('cv_screenings.index');
    Route::post('/screenings/update/{id}', [ScreeningCvController::class, 'update'])->name('cv_screenings.update');
    Route::get('/screenings/show/{id}', [ScreeningCvController::class, 'show'])->name('cv_screenings.show');
    Route::get('/screenings/edit/{id}', [ScreeningCvController::class, 'edit'])->name('cv_screenings.edit');
    Route::post('/screenings/notify/{id}', [ScreeningCvController::class, 'sendNotification'])->name('cv_screenings.sendNotification');
    Route::get('/screenings/{id}/custom-email', [ScreeningCvController::class, 'showCustomEmailForm'])->name('cv_screenings.customEmailForm');
    Route::post('/screenings/{id}/custom-email', [ScreeningCvController::class, 'sendCustomEmail'])->name('cv_screenings.sendCustomEmail');
});

Route::prefix('admin')->name('admin.')->middleware(['auth', 'checkAccessRole:9'])->group(function () {
    Route::get('/psikotest', [PsikotestController::class, 'index'])->name('psikotests.index');
    Route::get('/psikotest/show/{id}', [PsikotestController::class, 'show'])->name('psikotests.show');
    Route::get('/psikotest/edit/{id}', [PsikotestController::class, 'edit'])->name('psikotests.edit');
    Route::post('/psikotest/update/{id}', [PsikotestController::class, 'update'])->name('psikotests.update');
    Route::post('/psikotest/notify/{id}', [PsikotestController::class, 'sendNotification'])->name('psikotests.sendNotification');
    Route::get('/psikotests/{id}/custom-email', [PsikotestController::class, 'showCustomEmailForm'])->name('psikotests.customEmailForm');
    Route::post('/psikotests/{id}/custom-email', [PsikotestController::class, 'sendCustomEmail'])->name('psikotests.sendCustomEmail');
});

Route::prefix('admin')->name('admin.')->middleware(['auth', 'checkAccessRole:10'])->group(function () {
    Route::get('/InterviewHR', [InterviewHRController::class, 'index'])->name('interview_hr.index');
    Route::get('/InterviewHR/show/{id}', [InterviewHRController::class, 'show'])->name('interview_hr.show');
    Route::get('/InterviewHR/edit/{id}', [InterviewHRController::class, 'edit'])->name('interview_hr.edit');
    Route::post('/InterviewHR/update/{id}', [InterviewHRController::class, 'update'])->name('interview_hr.update');
    Route::post('/InterviewHR/notify/{id}', [InterviewHRController::class, 'sendNotification'])->name('interview_hr.sendNotification');
    Route::get('/interview_hr/{id}/custom-email', [InterviewHRController::class, 'showCustomEmailForm'])->name('interview_hr.customEmailForm');
    Route::post('/interview_hr/{id}/custom-email', [InterviewHRController::class, 'sendCustomEmail'])->name('interview_hr.sendCustomEmail');
});

Route::prefix('admin')->name('admin.')->middleware(['auth', 'checkAccessRole:11'])->group(function () {
    Route::get('/InterviewUser', [InterviewUserController::class, 'index'])->name('interview_user.index');
    Route::get('/InterviewUser/show/{id}', [InterviewUserController::class, 'show'])->name('interview_user.show');
    Route::get('/InterviewUser/edit/{id}', [InterviewUserController::class, 'edit'])->name('interview_user.edit');
    Route::post('/InterviewUser/update/{id}', [InterviewUserController::class, 'update'])->name('interview_user.update');
    Route::post('/InterviewUser/notify/{id}', [InterviewUserController::class, 'sendNotification'])->name('interview_user.sendNotification');
    Route::get('/interview_user/{id}/custom-email', [InterviewUserController::class, 'showCustomEmailForm'])->name('interview_user.customEmailForm');
    Route::post('/interview_user/{id}/custom-email', [InterviewUserController::class, 'sendCustomEmail'])->name('interview_user.sendCustomEmail');
});

Route::prefix('admin')->name('admin.')->middleware(['auth', 'checkAccessRole:12'])->group(function () {
    Route::get('/Offering', [OfferingController::class, 'index'])->name('offerings.index');
    Route::get('/Offering/show/{id}', [OfferingController::class, 'show'])->name('offerings.show');
    Route::get('/Offering/edit/{id}', [OfferingController::class, 'edit'])->name('offerings.edit');
    Route::post('/Offering/update/{id}', [OfferingController::class, 'update'])->name('offerings.update');
    Route::post('/Offering/notify/{id}', [OfferingController::class, 'sendNotification'])->name('offerings.sendNotification');
});

Route::prefix('admin')->name('admin.')->middleware(['auth', 'checkAccessRole:13'])->group(function () {
    Route::get('/reporting', [ReportingController::class, 'index'])->name('reportings.index');
    Route::post('/reporting/export', [ReportingController::class, 'export'])->name('reportings.export');
});

Route::prefix('admin')->name('admin.')->middleware(['auth', 'checkAccessRole:2'])->group(function () {
    Route::get('/roles', [RoleController::class, 'index'])->name('roles.index');
    Route::get('/roles/create', [RoleController::class, 'create'])->name('roles.create');
    Route::post('/roles', [RoleController::class, 'store'])->name('roles.store');
    Route::get('/roles/show/{id}', [RoleController::class, 'show'])->name('roles.show');
    Route::get('/roles/{id}/edit', [RoleController::class, 'edit'])->name('roles.edit');
    Route::put('/roles/{id}', [RoleController::class, 'update'])->name('roles.update');
    Route::delete('/roles/{id}', [RoleController::class, 'destroy'])->name('roles.destroy');
});

Route::prefix('admin')->name('admin.')->middleware(['auth', 'checkAccessRole:3'])->group(function () {
    Route::get('/permissions', [PermissionController::class, 'index'])->name('permissions.index');
    Route::get('/permissions/create', [PermissionController::class, 'create'])->name('permissions.create');
    Route::post('/permissions', [PermissionController::class, 'store'])->name('permissions.store');
    Route::get('/permissions/show/{id}', [PermissionController::class, 'show'])->name('permissions.show');
    Route::get('/permissions/{id}/edit', [PermissionController::class, 'edit'])->name('permissions.edit');
    Route::put('/permissions/{id}', [PermissionController::class, 'update'])->name('permissions.update');
    Route::delete('/permissions/{id}', [PermissionController::class, 'destroy'])->name('permissions.destroy');
});


// Route autentikasi
require __DIR__ . '/auth.php';
