<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Check the status of a resident report sent to Shani Local Government.">
    <meta name="theme-color" content="#103d28">
    <title>Report status | {{ $localGovernmentName }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700;800&family=Fraunces:opsz,wght@9..144,500;9..144,600;9..144,700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <x-site-header :local-government-name="$localGovernmentName" />
    <main class="content-page page-wrap">
        <div class="eyebrow"><span class="eyebrow-dot"></span> REPORT TRACKING</div>
        <h1>Your report status</h1>
        <p class="content-page-lead">Reference <strong>{{ $enquiry->tracking_code }}</strong></p>
        <div class="transparency-status" role="status">
            <span class="updates-icon" aria-hidden="true">✓</span>
            <div>
                <h2>{{ match ($enquiry->status) {
                    'new' => 'Received',
                    'in_review' => 'Under review',
                    'resolved' => 'Marked as resolved',
                    'closed' => 'Closed',
                    default => 'Status update',
                } }}</h2>
                <p>Submitted {{ $enquiry->created_at->format('j F Y') }}. Status last updated {{ $enquiry->updated_at->format('j F Y, g:i a') }}. A response time is not guaranteed.</p>
                @if ($enquiry->ward)<p><strong>Ward:</strong> {{ $enquiry->ward }}</p>@endif
            </div>
        </div>
        <div class="content-page-actions"><a class="button button-primary" href="{{ route('feedback') }}">Return to reports <span aria-hidden="true">→</span></a></div>
    </main>
    <x-site-footer :local-government-name="$localGovernmentName" />
</body>
</html>
