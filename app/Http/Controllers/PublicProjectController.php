<?php

namespace App\Http\Controllers;

use App\Models\CouncilProject;
use Illuminate\View\View;

class PublicProjectController extends Controller
{
    public function index(): View
    {
        return view('pages.projects', [
            'localGovernmentName' => 'Shani Local Government',
            'projects' => CouncilProject::query()
                ->orderByDesc('budget_year')
                ->latest('updated_at')
                ->paginate(24),
        ]);
    }
}
