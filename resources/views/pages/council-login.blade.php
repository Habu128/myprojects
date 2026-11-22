<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#103d28">
    <title>Staff sign in | {{ $localGovernmentName }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <x-site-header :local-government-name="$localGovernmentName" />
    <main class="content-page page-wrap">
        <div class="eyebrow"><span class="eyebrow-dot"></span> COUNCIL STAFF</div>
        <h1>Staff sign in</h1>
        <p class="content-page-lead">Authorized staff can review resident reports and publish status updates.</p>
        <form class="feedback-form staff-login-form" method="post" action="{{ route('council.reports.authenticate') }}">
            @csrf
            <div class="feedback-fields">
                <label>Email <input type="email" name="email" autocomplete="username" value="{{ old('email') }}" required>@error('email')<small>{{ $message }}</small>@enderror</label>
                <label>Password <input type="password" name="password" autocomplete="current-password" required>@error('password')<small>{{ $message }}</small>@enderror</label>
            </div>
            <button class="button button-primary" type="submit">Sign in <span aria-hidden="true">→</span></button>
        </form>
    </main>
</body>
</html>
