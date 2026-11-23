<?php

namespace App\Http\Controllers;

use App\Models\MediaAsset;
use App\Models\SiteSetting;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class StaffSiteSettingController extends AdminController
{
    private const KEYS = [
        'hero_eyebrow',
        'hero_title_line_1',
        'hero_title_line_2',
        'hero_description',
        'hero_note',
        'about_title_line_1',
        'about_title_line_2',
        'about_lead',
        'about_detail',
        'contact_address',
        'contact_phone',
        'contact_email',
        'contact_hours',
        'contact_note',
        'logo_path',
        'footer_tagline',
    ];

    public function edit(): View
    {
        $this->authorizeStaff();

        return view('pages.admin-settings', [
            'localGovernmentName' => 'Shani Local Government',
            'settings' => SiteSetting::values(),
            'mediaAssets' => MediaAsset::query()->orderBy('title')->get(),
            'keys' => self::KEYS,
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $this->authorizeStaff();

        $rules = [];

        foreach (self::KEYS as $key) {
            $rules[$key] = ['nullable', 'string', 'max:2000'];
        }

        $validated = $request->validate($rules);

        foreach ($validated as $key => $value) {
            SiteSetting::query()->updateOrCreate(
                ['key' => $key],
                ['value' => $value ?? ''],
            );
        }

        SiteSetting::flushCache();

        return to_route('admin.settings.edit')->with('settings_saved', true);
    }
}
