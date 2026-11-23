<?php

namespace App\Http\Controllers;

use App\Models\ContentPage;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StaffContentController extends Controller
{
    public function index(): View
    {
        $this->authorizeStaff();

        return view('pages.staff-content', [
            'localGovernmentName' => 'Shani Local Government',
            'pages' => ContentPage::query()->orderBy('slug')->get(),
        ]);
    }

    public function edit(ContentPage $contentPage): View
    {
        $this->authorizeStaff();

        return view('pages.staff-content-form', [
            'localGovernmentName' => 'Shani Local Government',
            'contentPage' => $contentPage,
        ]);
    }

    public function update(Request $request, ContentPage $contentPage): RedirectResponse
    {
        $this->authorizeStaff();

        $validated = $request->validate([
            'eyebrow' => ['required', 'string', 'max:120'],
            'title' => ['required', 'string', 'max:180'],
            'description' => ['required', 'string', 'max:2000'],
            'details' => ['nullable', 'string', 'max:4000'],
            'items' => ['required', 'json'],
            'sources' => ['required', 'json'],
        ]);

        $validated['details'] = $validated['details'] ?: null;
        $validated['items'] = $this->decodeJson($validated['items']);
        $validated['sources'] = $this->decodeJson($validated['sources']);
        $validated['is_published'] = $request->boolean('is_published');

        $contentPage->update($validated);

        return to_route('staff.content.index')->with('content_updated', true);
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function decodeJson(string $value): array
    {
        /** @var mixed $decoded */
        $decoded = json_decode($value, true);

        if (! is_array($decoded)) {
            abort(422, 'Content items and sources must be JSON arrays.');
        }

        /** @var array<int, array<string, mixed>> $decoded */
        return $decoded;
    }

    private function authorizeStaff(): void
    {
        abort_unless(Auth::user()?->is_council_staff, 403);
    }
}
