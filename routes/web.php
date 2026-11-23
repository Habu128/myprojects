<?php

use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AssistantController;
use App\Http\Controllers\CitizenFeedbackController;
use App\Http\Controllers\CouncilProjectsController;
use App\Http\Controllers\CouncilReportsController;
use App\Http\Controllers\MapDataController;
use App\Http\Controllers\PublicPageController;
use App\Http\Controllers\PublicProjectController;
use App\Http\Controllers\StaffContentController;
use App\Http\Controllers\StaffMediaController;
use App\Http\Controllers\StaffNoticeController;
use App\Http\Controllers\StaffSiteSettingController;
use App\Models\Notice;
use App\Models\SiteSetting;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $localGovernmentName = 'Shani Local Government';

    return view('welcome', [
        'localGovernmentName' => $localGovernmentName,
        'assistantConfigured' => filled(config('services.openai.key')),
        'notices' => Notice::query()->published()->latest('published_at')->limit(3)->get(),
        'settings' => SiteSetting::values(),
    ]);
})->name('home');

foreach ([
    'about' => '/about',
    'emir-history' => '/about/emirate-history',
    'political-history' => '/about/political-history',
    'services' => '/services',
    'transparency' => '/transparency',
    'updates' => '/news',
    'contact' => '/contact',
] as $routeName => $path) {
    Route::get($path, [PublicPageController::class, 'show'])
        ->defaults('slug', $routeName)
        ->name($routeName);
}

Route::get('/map', function () {
    return view('pages.map', [
        'localGovernmentName' => 'Shani Local Government',
    ]);
})->name('map');

Route::get('/map/data', MapDataController::class)->name('map.data');

Route::get('/feedback', [CitizenFeedbackController::class, 'create'])->name('feedback');
Route::post('/feedback', [CitizenFeedbackController::class, 'store'])
    ->middleware('throttle:5,1')
    ->name('feedback.store');
Route::post('/feedback/track', [CitizenFeedbackController::class, 'track'])
    ->middleware('throttle:10,1')
    ->name('feedback.track');

Route::get('/projects', [PublicProjectController::class, 'index'])->name('projects');

Route::get('/council', [PublicPageController::class, 'council'])->name('council');

Route::get('/council/staff/login', [CouncilReportsController::class, 'login'])->name('council.reports.login');
Route::post('/council/staff/login', [CouncilReportsController::class, 'authenticate'])
    ->middleware('throttle:5,1')
    ->name('council.reports.authenticate');
Route::middleware('auth')->prefix('/council/staff')->name('council.reports.')->group(function () {
    Route::get('/reports', [CouncilReportsController::class, 'index'])->name('index');
    Route::patch('/reports/{enquiry}', [CouncilReportsController::class, 'update'])->name('update');
    Route::post('/logout', [CouncilReportsController::class, 'logout'])->name('logout');
});
Route::middleware('auth')->prefix('/council/staff/projects')->name('council.projects.')->group(function () {
    Route::get('/', [CouncilProjectsController::class, 'index'])->name('index');
    Route::get('/create', [CouncilProjectsController::class, 'create'])->name('create');
    Route::post('/', [CouncilProjectsController::class, 'store'])->name('store');
    Route::get('/{councilProject}/edit', [CouncilProjectsController::class, 'edit'])->name('edit');
    Route::put('/{councilProject}', [CouncilProjectsController::class, 'update'])->name('update');
    Route::patch('/{councilProject}/archive', [CouncilProjectsController::class, 'archive'])->name('archive');
});

Route::middleware('auth')->prefix('/council/staff')->name('staff.')->group(function () {
    Route::get('/content', [StaffContentController::class, 'index'])->name('content.index');
    Route::get('/content/{contentPage}/edit', [StaffContentController::class, 'edit'])->name('content.edit');
    Route::put('/content/{contentPage}', [StaffContentController::class, 'update'])->name('content.update');
    Route::get('/notices', [StaffNoticeController::class, 'index'])->name('notices.index');
    Route::get('/notices/create', [StaffNoticeController::class, 'create'])->name('notices.create');
    Route::post('/notices', [StaffNoticeController::class, 'store'])->name('notices.store');
    Route::get('/notices/{notice}/edit', [StaffNoticeController::class, 'edit'])->name('notices.edit');
    Route::put('/notices/{notice}', [StaffNoticeController::class, 'update'])->name('notices.update');
    Route::delete('/notices/{notice}', [StaffNoticeController::class, 'destroy'])->name('notices.destroy');
});

Route::middleware('auth')->prefix('/admin')->name('admin.')->group(function (): void {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::post('/map/refresh', [AdminDashboardController::class, 'refreshMap'])->name('map.refresh');
    Route::get('/settings', [StaffSiteSettingController::class, 'edit'])->name('settings.edit');
    Route::put('/settings', [StaffSiteSettingController::class, 'update'])->name('settings.update');
    Route::get('/media', [StaffMediaController::class, 'index'])->name('media.index');
    Route::post('/media', [StaffMediaController::class, 'store'])->name('media.store');
    Route::put('/media/{mediaAsset}', [StaffMediaController::class, 'update'])->name('media.update');
    Route::delete('/media/{mediaAsset}', [StaffMediaController::class, 'destroy'])->name('media.destroy');
});

Route::post('/assistant/chat', AssistantController::class)
    ->middleware('throttle:10,1')
    ->name('assistant.chat');
