<?php

namespace App\Http\Controllers;

use App\Models\MediaAsset;
use App\Models\SiteSetting;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class StaffMediaController extends AdminController
{
    private const CATEGORIES = ['brand', 'chairman', 'facility', 'general'];

    public function index(): View
    {
        $this->authorizeStaff();

        return view('pages.admin-media', [
            'localGovernmentName' => 'Shani Local Government',
            'mediaAssets' => MediaAsset::query()->latest()->paginate(24),
            'categories' => self::CATEGORIES,
            'logoPath' => SiteSetting::value('logo_path'),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorizeStaff();

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:180'],
            'alt_text' => ['nullable', 'string', 'max:255'],
            'category' => ['required', 'in:'.implode(',', self::CATEGORIES)],
            'file' => ['required', 'file', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ]);

        $path = $request->file('file')->store('media', 'public');

        MediaAsset::query()->create([
            'title' => $validated['title'],
            'alt_text' => $validated['alt_text'],
            'category' => $validated['category'],
            'path' => $path,
            'uploaded_by' => Auth::id(),
        ]);

        return to_route('admin.media.index')->with('media_uploaded', true);
    }

    public function update(Request $request, MediaAsset $mediaAsset): RedirectResponse
    {
        $this->authorizeStaff();

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:180'],
            'alt_text' => ['nullable', 'string', 'max:255'],
            'category' => ['required', 'in:'.implode(',', self::CATEGORIES)],
        ]);

        $mediaAsset->update($validated);

        return to_route('admin.media.index')->with('media_updated', true);
    }

    public function destroy(MediaAsset $mediaAsset): RedirectResponse
    {
        $this->authorizeStaff();

        abort_if($mediaAsset->path === SiteSetting::value('logo_path'), 422, 'This image is currently used as the site logo.');

        if ($mediaAsset->isStoredFile()) {
            Storage::disk('public')->delete($mediaAsset->path);
        }

        $mediaAsset->delete();

        return to_route('admin.media.index')->with('media_deleted', true);
    }
}
