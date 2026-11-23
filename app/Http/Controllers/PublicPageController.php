<?php

namespace App\Http\Controllers;

use App\Models\ContentPage;
use App\Models\MediaAsset;
use App\Models\Notice;
use App\Models\SiteSetting;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;

class PublicPageController extends Controller
{
    public function show(string $slug): View
    {
        $contentPage = ContentPage::query()
            ->where('slug', $slug)
            ->where('is_published', true)
            ->firstOrFail();

        $pageData = $contentPage->toPageData();

        if ($slug === 'contact') {
            $pageData = $this->applyContactSettings($pageData);
        }

        return view('pages.show', [
            'localGovernmentName' => 'Shani Local Government',
            'page' => $pageData,
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
            'chairmanPhotos' => MediaAsset::query()
                ->where('category', 'chairman')
                ->orderBy('id')
                ->get()
                ->map(fn (MediaAsset $asset): array => [
                    'url' => $asset->url(),
                    'alt' => $asset->alt_text ?: $asset->title,
                ])
                ->all(),
        ]);
    }

    /**
     * @param  array{eyebrow: string, title: string, description: string, details: string, items: array<int, array<string, mixed>>, sources: array<int, array<string, string>>}  $pageData
     * @return array{eyebrow: string, title: string, description: string, details: string, items: array<int, array<string, mixed>>, sources: array<int, array<string, string>>}
     */
    private function applyContactSettings(array $pageData): array
    {
        $contactKeys = [
            'Address' => 'contact_address',
            'Phone' => 'contact_phone',
            'Email' => 'contact_email',
            'Office hours' => 'contact_hours',
        ];

        foreach ($pageData['items'] as &$item) {
            $key = $contactKeys[$item['title']] ?? null;

            if ($key !== null && is_string($item['description'] ?? null)) {
                $item['description'] = SiteSetting::value($key, $item['description']);
            }
        }
        unset($item);

        $pageData['details'] = SiteSetting::value('contact_note', $pageData['details']);

        return $pageData;
    }

    /**
     * @return Collection<int, Notice>
     */
    private function publishedNotices()
    {
        return Notice::query()
            ->published()
            ->latest('published_at')
            ->limit(12)
            ->get();
    }
}
