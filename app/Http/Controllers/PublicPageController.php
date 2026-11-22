<?php

namespace App\Http\Controllers;

use App\Models\ContentPage;
use App\Models\Notice;
use Illuminate\Contracts\View\View;

class PublicPageController extends Controller
{
    public function show(string $slug): View
    {
        $contentPage = ContentPage::query()
            ->where('slug', $slug)
            ->where('is_published', true)
            ->firstOrFail();

        return view('pages.show', [
            'localGovernmentName' => 'Shani Local Government',
            'page' => $contentPage->toPageData(),
            'notices' => $this->publishedNotices(),
        ]);
    }

    public function council(): View
    {
        $contentPage = ContentPage::query()
            ->where('slug', 'council')
            ->where('is_published', true)
            ->firstOrFail();

        return view('pages.council', [
            'localGovernmentName' => 'Shani Local Government',
            'page' => $contentPage->toPageData(),
        ]);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection<int, Notice>
     */
    private function publishedNotices()
    {
        return Notice::query()
            ->where('is_published', true)
            ->where(function ($query): void {
                $query->whereNull('published_at')->orWhere('published_at', '<=', now());
            })
            ->latest('published_at')
            ->limit(12)
            ->get();
    }
}
