<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Find public budget, procurement and project information for Shani Local Government.">
    <meta name="theme-color" content="#103d28">
    <title>Budgets & projects | {{ $localGovernmentName }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700;800&family=Fraunces:opsz,wght@9..144,500;9..144,600;9..144,700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <x-site-header :local-government-name="$localGovernmentName" />
    <main class="content-page page-wrap">
        <div class="eyebrow"><span class="eyebrow-dot"></span> PUBLIC RECORDS</div>
        <h1>Budgets, projects and public records</h1>
        <p class="content-page-lead">Links to published budget and audit records, together with BudgIT’s assessment of local government budget transparency.</p>
        <div class="transparency-status"><span class="updates-icon" aria-hidden="true">▤</span><div><h2>Use public records to follow the money</h2><p>Borno’s finance portal publishes local government budgets and audit reports. Start with Shani’s budget, note each project’s location, approved amount, and responsible office, then compare the plan with dated progress evidence. A budget allocation does not establish that a project was funded or completed.</p></div></div>
        <div class="content-page-grid transparency-grid">
            <article class="content-page-card"><span class="content-page-icon">₦</span><h2>Shani in the 2025 budget</h2><p>Open Borno’s published 27 LGA budget document and search for “208327” or “SHANI” to find Shani’s budget tables and listed capital projects.</p><a class="content-card-link" href="https://pfm.bo.gov.ng/wp-content/uploads/Borno-State-27-LG-Budget-Publication.pdf" target="_blank" rel="noopener noreferrer">Open the 2025 LGA budget <span aria-hidden="true">↗</span></a></article>
            <article class="content-page-card"><span class="content-page-icon">⌖</span><h2>Shani project updates</h2><p>Browse projects published by authorized council staff, including their ward, budget, status, source and latest progress evidence.</p><a class="content-card-link" href="{{ route('projects') }}">View Shani projects <span aria-hidden="true">→</span></a></article>
            <article class="content-page-card"><span class="content-page-icon">↗</span><h2>Track wider public projects</h2><p>BudgIT’s Tracka lets residents explore and monitor public projects. Project status should be checked against dated evidence and official records.</p><a class="content-card-link" href="https://tracka.org/" target="_blank" rel="noopener noreferrer">Visit Tracka <span aria-hidden="true">↗</span></a></article>
            <article class="content-page-card"><span class="content-page-icon">▧</span><h2>Audited accounts</h2><p>Borno’s finance portal lists consolidated audited statements for local governments, including the 2024 financial year.</p><a class="content-card-link" href="https://pfm.bo.gov.ng/lg-audit-report/" target="_blank" rel="noopener noreferrer">Browse LGA audit reports <span aria-hidden="true">↗</span></a></article>
        </div>
        <p class="content-page-note">The 2025 figures are historical budget estimates, not current spending data. BudgIT’s report is an independent transparency assessment, not a government audit. Use the latest available budgets and audited accounts, and label any project status with its source and update date.</p>
        <section class="content-sources" aria-labelledby="transparency-sources-title"><h2 id="transparency-sources-title">Sources and tracking tools</h2><ul><li><a href="https://budgit.org/wp-content/uploads/2026/03/The-Missing-Tier.pdf" target="_blank" rel="noopener noreferrer">BudgIT: The Missing Tier — Mapping Local Government Budget Transparency in Nigeria (2026) <span aria-hidden="true">↗</span></a></li><li><a href="https://budgit.org/our_programs/lg-alert/" target="_blank" rel="noopener noreferrer">BudgIT: LG Alert and local government allocations <span aria-hidden="true">↗</span></a></li><li><a href="https://budgit.org/our_programs/mybudgit/" target="_blank" rel="noopener noreferrer">BudgIT: MyBudgIT — budgets, allocations, and local projects <span aria-hidden="true">↗</span></a></li><li><a href="https://pfm.bo.gov.ng/budgets/" target="_blank" rel="noopener noreferrer">Borno State PFM: budget publications <span aria-hidden="true">↗</span></a></li></ul></section>
        <div class="content-page-actions"><a class="button button-primary" href="{{ route('feedback') }}">Send a public enquiry <span aria-hidden="true">→</span></a><a class="text-link" href="{{ route('map') }}">Explore Shani ward map <span aria-hidden="true">↗</span></a></div>
    </main>
    <x-site-footer :local-government-name="$localGovernmentName" />
</body>
</html>
