<?php

namespace Database\Seeders;

use App\Models\MediaAsset;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MediaAssetSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * @return array<int, array{title: string, path: string, alt_text: string, category: string}>
     */
    private function assets(): array
    {
        return [
            [
                'title' => 'Borno State Government logo',
                'path' => 'images/borno-state-logo.png',
                'alt_text' => 'Official Borno State Government logo',
                'category' => 'brand',
            ],
            [
                'title' => 'Shani chairman photograph 1',
                'path' => 'images/chairman-1.jpg',
                'alt_text' => 'Supplied photograph of the Shani LGA chairman',
                'category' => 'chairman',
            ],
            [
                'title' => 'Shani chairman photograph 2',
                'path' => 'images/chairman-2.jpg',
                'alt_text' => 'Supplied photograph of the Shani LGA chairman',
                'category' => 'chairman',
            ],
            [
                'title' => 'Shani chairman photograph 3',
                'path' => 'images/chairman-3.jpg',
                'alt_text' => 'Supplied photograph of the Shani LGA chairman',
                'category' => 'chairman',
            ],
            [
                'title' => 'Shani chairman photograph 4',
                'path' => 'images/chairman-4.jpg',
                'alt_text' => 'Supplied photograph of the Shani LGA chairman',
                'category' => 'chairman',
            ],
        ];
    }

    public function run(): void
    {
        foreach ($this->assets() as $asset) {
            MediaAsset::query()->updateOrCreate(
                ['path' => $asset['path']],
                $asset,
            );
        }
    }
}
