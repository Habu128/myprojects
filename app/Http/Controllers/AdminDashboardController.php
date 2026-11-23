<?php

namespace App\Http\Controllers;

use App\Models\ContentPage;
use App\Models\CouncilProject;
use App\Models\Enquiry;
use App\Models\MediaAsset;
use App\Models\Notice;
use App\Models\SiteSetting;
use App\Services\WardMapDataService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Cache;

class AdminDashboardController extends AdminController
{
    public function index(): View
    {
        $this->authorizeStaff();

        return view('pages.admin-dashboard', [
            'localGovernmentName' => 'Shani Local Government',
            'stats' => [
                'published_pages' => ContentPage::query()->where('is_published', true)->count(),
                'draft_pages' => ContentPage::query()->where('is_published', false)->count(),
                'live_notices' => Notice::query()->published()->count(),
                'active_projects' => CouncilProject::query()->whereNot('status', 'archived')->count(),
                'open_reports' => Enquiry::query()->whereIn('status', ['new', 'in_review'])->count(),
                'media_assets' => MediaAsset::query()->count(),
            ],
            'recentNotices' => Notice::query()->latest('updated_at')->limit(5)->get(),
            'recentProjects' => CouncilProject::query()->latest('updated_at')->limit(5)->get(),
            'recentReports' => Enquiry::query()->latest()->limit(5)->get(),
            'mapCached' => Cache::has('ward-map-data'),
            'settings' => SiteSetting::values(),
        ]);
    }

    public function refreshMap(WardMapDataService $wardMapDataService): RedirectResponse
    {
        $this->authorizeStaff();
        $wardMapDataService->clearCache();

        return back()->with('map_refreshed', true);
    }
}
