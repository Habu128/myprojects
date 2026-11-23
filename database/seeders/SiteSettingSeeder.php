<?php

namespace Database\Seeders;

use App\Models\SiteSetting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SiteSettingSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * @return array<string, string>
     */
    private function settings(): array
    {
        return [
            'hero_eyebrow' => 'SHANI · BORNO STATE',
            'hero_title_line_1' => 'Shani Local',
            'hero_title_line_2' => 'Government Area',
            'hero_description' => 'Public information about Shani LGA, its wards, council responsibilities, budgets and notices.',
            'hero_note' => 'Shani town is the administrative headquarters.',
            'about_title_line_1' => 'About Shani',
            'about_title_line_2' => 'Local administration',
            'about_lead' => 'Shani is one of Borno State’s 27 Local Government Areas and is part of Borno South. Shani town is the administrative headquarters.',
            'about_detail' => 'Use this site to review ward information, council responsibilities, traditional and political history, public records, and ways to submit feedback.',
            'contact_address' => 'Not published',
            'contact_phone' => 'Not published',
            'contact_email' => 'Not published',
            'contact_hours' => 'Not published',
            'contact_note' => 'Council contact details have not been verified for publication.',
            'logo_path' => 'images/borno-state-logo.png',
            'footer_tagline' => 'Public information for Shani communities.',
        ];
    }

    public function run(): void
    {
        foreach ($this->settings() as $key => $value) {
            SiteSetting::query()->updateOrCreate(
                ['key' => $key],
                ['value' => $value],
            );
        }

        SiteSetting::flushCache();
    }
}
