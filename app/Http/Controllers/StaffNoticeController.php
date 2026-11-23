<?php

namespace App\Http\Controllers;

use App\Models\Notice;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StaffNoticeController extends Controller
{
    public function index(): View
    {
        $this->authorizeStaff();

        return view('pages.staff-notices', [
            'localGovernmentName' => 'Shani Local Government',
            'notices' => Notice::query()->latest('published_at')->paginate(20),
        ]);
    }

    public function create(): View
    {
        $this->authorizeStaff();

        return view('pages.staff-notice-form', [
            'localGovernmentName' => 'Shani Local Government',
            'notice' => null,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorizeStaff();

        $validated = $this->validateNotice($request);
        $validated['published_at'] = $validated['published_at'] ?: ($validated['is_published'] ? now() : null);

        Notice::query()->create($validated);

        return to_route('staff.notices.index')->with('notice_created', true);
    }

    public function edit(Notice $notice): View
    {
        $this->authorizeStaff();

        return view('pages.staff-notice-form', [
            'localGovernmentName' => 'Shani Local Government',
            'notice' => $notice,
        ]);
    }

    public function update(Request $request, Notice $notice): RedirectResponse
    {
        $this->authorizeStaff();

        $validated = $this->validateNotice($request);
        $validated['published_at'] = $validated['published_at'] ?: ($validated['is_published'] ? ($notice->published_at ?? now()) : null);

        $notice->update($validated);

        return to_route('staff.notices.index')->with('notice_updated', true);
    }

    public function destroy(Notice $notice): RedirectResponse
    {
        $this->authorizeStaff();
        $notice->delete();

        return to_route('staff.notices.index')->with('notice_deleted', true);
    }

    /**
     * @return array{title: string, body: string, published_at: string|null, is_published: bool}
     */
    private function validateNotice(Request $request): array
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:180'],
            'body' => ['required', 'string', 'min:10', 'max:10000'],
            'published_at' => ['nullable', 'date'],
        ]);

        return [
            'title' => $validated['title'],
            'body' => $validated['body'],
            'published_at' => $validated['published_at'] ?? null,
            'is_published' => $request->boolean('is_published'),
        ];
    }

    private function authorizeStaff(): void
    {
        abort_unless(Auth::user()?->is_council_staff, 403);
    }
}
