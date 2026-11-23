<?php

namespace App\Http\Controllers;

use App\Models\Enquiry;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class CouncilReportsController extends Controller
{
    public function login(): View
    {
        return view('pages.council-login', ['localGovernmentName' => 'Shani Local Government']);
    }

    public function authenticate(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (! Auth::attempt([...$credentials, 'is_council_staff' => true])) {
            return back()->withErrors([
                'email' => 'Those sign-in details do not match an authorized staff account.',
            ])->onlyInput('email');
        }

        $request->session()->regenerate();

        return to_route('admin.dashboard');
    }

    public function index(): View
    {
        abort_unless(Auth::user()?->is_council_staff, 403);

        return view('pages.council-reports', [
            'localGovernmentName' => 'Shani Local Government',
            'enquiries' => Enquiry::query()->latest()->paginate(25),
        ]);
    }

    public function update(Request $request, Enquiry $enquiry): RedirectResponse
    {
        abort_unless(Auth::user()?->is_council_staff, 403);

        $validated = $request->validate([
            'status' => ['required', 'in:new,in_review,resolved,closed'],
        ]);

        $enquiry->update(['status' => $validated['status']]);

        return to_route('council.reports.index')->with('report_updated', true);
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return to_route('council.reports.login');
    }
}
