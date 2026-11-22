<?php

namespace App\Http\Controllers;

use App\Models\Enquiry;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CitizenFeedbackController extends Controller
{
    public function create(): View
    {
        return view('pages.feedback', [
            'localGovernmentName' => 'Shani Local Government',
            'wards' => ['Bargu / Burashika', 'Buma', 'Gasi / Salifawa', 'Gora', 'Gwalasho', 'Gwaskara', 'Kombo', 'Kubo', 'Kwaba', 'Shani', 'Walama'],
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['nullable', 'string', 'min:2', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'topic' => ['required', 'in:service,community-facility,road-or-water,health,education,other'],
            'ward' => ['nullable', 'in:Bargu / Burashika,Buma,Gasi / Salifawa,Gora,Gwalasho,Gwaskara,Kombo,Kubo,Kwaba,Shani,Walama'],
            'message' => ['required', 'string', 'min:10', 'max:5000'],
        ]);

        $enquiry = Enquiry::create([
            'name' => $validated['name'] ?? 'Resident',
            'email' => $validated['email'] ?? '',
            'phone' => $validated['phone'] ?? null,
            'message' => $validated['message'],
            'status' => 'new',
            'tracking_code' => Str::upper(Str::random(10)),
            'topic' => $validated['topic'],
            'ward' => $validated['ward'] ?? null,
        ]);

        return to_route('feedback')->with('tracking_code', $enquiry->tracking_code);
    }

    public function track(Request $request): View|RedirectResponse
    {
        $validated = $request->validate([
            'tracking_code' => ['required', 'string', 'alpha_num', 'size:10'],
        ]);

        $enquiry = Enquiry::query()
            ->where('tracking_code', Str::upper($validated['tracking_code']))
            ->first();

        if ($enquiry === null) {
            return back()->withErrors(['tracking_code' => 'We could not find that reference. Check the code and try again.']);
        }

        return view('pages.feedback-track', [
            'localGovernmentName' => 'Shani Local Government',
            'enquiry' => $enquiry,
        ]);
    }
}
