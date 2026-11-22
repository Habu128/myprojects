<?php

namespace App\Http\Controllers;

use App\Models\CouncilProject;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class CouncilProjectsController extends Controller
{
    private const WARDS = [
        'Bargu / Burashika', 'Buma', 'Gasi / Salifawa', 'Gora', 'Gwalasho',
        'Gwaskara', 'Kombo', 'Kubo', 'Kwaba', 'Shani', 'Walama',
    ];

    public function create(): View
    {
        $this->authorizeCouncilStaff();

        return view('pages.council-project-form', [
            'localGovernmentName' => 'Shani Local Government',
            'wards' => self::WARDS,
            'project' => null,
        ]);
    }

    public function index(): View
    {
        $this->authorizeCouncilStaff();

        return view('pages.council-projects', [
            'localGovernmentName' => 'Shani Local Government',
            'wards' => self::WARDS,
            'projects' => CouncilProject::query()->latest('updated_at')->paginate(20),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorizeCouncilStaff();

        $validated = $this->validateProject($request);
        $validated['updated_by'] = Auth::id();

        CouncilProject::query()->create($validated);

        return to_route('council.projects.index')->with('project_created', true);
    }

    public function edit(CouncilProject $councilProject): View
    {
        $this->authorizeCouncilStaff();

        return view('pages.council-project-form', [
            'localGovernmentName' => 'Shani Local Government',
            'wards' => self::WARDS,
            'project' => $councilProject,
        ]);
    }

    public function update(Request $request, CouncilProject $councilProject): RedirectResponse
    {
        $this->authorizeCouncilStaff();

        $validated = $this->validateProject($request);
        $validated['updated_by'] = Auth::id();

        $councilProject->update($validated);

        return to_route('council.projects.index')->with('project_updated', true);
    }

    public function archive(CouncilProject $councilProject): RedirectResponse
    {
        $this->authorizeCouncilStaff();
        $councilProject->update([
            'status' => 'archived',
            'updated_by' => Auth::id(),
        ]);

        return to_route('council.projects.index')->with('project_archived', true);
    }

    /**
     * @return array{
     *     title: string,
     *     ward: string,
     *     description: string,
     *     budget_amount: numeric-string|null,
     *     budget_year: int|null,
     *     status: string,
     *     source_title: string,
     *     source_url: string,
     *     evidence_url: string|null
     * }
     */
    private function validateProject(Request $request): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:180'],
            'ward' => ['required', 'in:'.implode(',', self::WARDS)],
            'description' => ['required', 'string', 'min:10', 'max:4000'],
            'budget_amount' => ['nullable', 'numeric', 'min:0', 'max:9999999999999'],
            'budget_year' => ['nullable', 'integer', 'min:2000', 'max:'.((int) date('Y') + 5)],
            'status' => ['required', 'in:planned,in_progress,completed,paused,archived'],
            'source_title' => ['required', 'string', 'max:255'],
            'source_url' => ['required', 'url', 'max:2048'],
            'evidence_url' => ['nullable', 'required_if:status,completed', 'url', 'max:2048'],
        ]);
    }

    private function authorizeCouncilStaff(): void
    {
        abort_unless(Auth::user()?->is_council_staff, 403);
    }
}
