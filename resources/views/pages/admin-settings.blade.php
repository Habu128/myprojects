@php
    $setting = static fn (string $key): string => old($key, $settings[$key] ?? '');
@endphp

<x-admin-shell :local-government-name="$localGovernmentName" title="Site settings" active="settings">
    <div class="admin-heading">
        <div>
            <div class="eyebrow"><span class="eyebrow-dot"></span> ADMIN · SETTINGS</div>
            <h1>Site settings</h1>
            <p>Update the text and contact details that appear across the public pages.</p>
        </div>
        <a class="text-link" href="{{ route('admin.media.index') }}">Manage uploads <span aria-hidden="true">↗</span></a>
    </div>

    @if (session('settings_saved'))
        <div class="admin-alert">Site settings were saved.</div>
    @endif
    @if ($errors->any())
        <div class="admin-alert admin-alert-error">{{ $errors->first() }}</div>
    @endif

    <form method="post" action="{{ route('admin.settings.update') }}">
        @csrf
        @method('PUT')

        <section class="admin-panel">
            <div class="admin-panel-head"><div><span class="card-kicker">HOMEPAGE</span><h2>Hero section</h2></div></div>
            <div class="admin-form-grid">
                <label for="hero_eyebrow">Eyebrow<input id="hero_eyebrow" name="hero_eyebrow" type="text" value="{{ $setting('hero_eyebrow') }}" maxlength="2000"></label>
                <label for="hero_note">Headquarters note<input id="hero_note" name="hero_note" type="text" value="{{ $setting('hero_note') }}" maxlength="2000"></label>
                <label for="hero_title_line_1">Title, first line<input id="hero_title_line_1" name="hero_title_line_1" type="text" value="{{ $setting('hero_title_line_1') }}" required maxlength="2000"></label>
                <label for="hero_title_line_2">Title, second line<input id="hero_title_line_2" name="hero_title_line_2" type="text" value="{{ $setting('hero_title_line_2') }}" required maxlength="2000"></label>
                <label class="wide" for="hero_description">Description<textarea id="hero_description" name="hero_description" rows="3" maxlength="2000">{{ $setting('hero_description') }}</textarea></label>
            </div>
        </section>

        <section class="admin-panel">
            <div class="admin-panel-head"><div><span class="card-kicker">HOMEPAGE</span><h2>About section</h2></div></div>
            <div class="admin-form-grid">
                <label for="about_title_line_1">Title, first line<input id="about_title_line_1" name="about_title_line_1" type="text" value="{{ $setting('about_title_line_1') }}" maxlength="2000"></label>
                <label for="about_title_line_2">Title, second line<input id="about_title_line_2" name="about_title_line_2" type="text" value="{{ $setting('about_title_line_2') }}" maxlength="2000"></label>
                <label class="wide" for="about_lead">Lead paragraph<textarea id="about_lead" name="about_lead" rows="3" maxlength="2000">{{ $setting('about_lead') }}</textarea></label>
                <label class="wide" for="about_detail">Detail paragraph<textarea id="about_detail" name="about_detail" rows="3" maxlength="2000">{{ $setting('about_detail') }}</textarea></label>
            </div>
        </section>

        <section class="admin-panel">
            <div class="admin-panel-head"><div><span class="card-kicker">PUBLIC CONTACT</span><h2>Council contact details</h2></div></div>
            <div class="admin-form-grid">
                <label for="contact_address">Address<input id="contact_address" name="contact_address" type="text" value="{{ $setting('contact_address') }}" maxlength="2000"></label>
                <label for="contact_phone">Phone<input id="contact_phone" name="contact_phone" type="text" value="{{ $setting('contact_phone') }}" maxlength="2000"></label>
                <label for="contact_email">Email<input id="contact_email" name="contact_email" type="email" value="{{ $setting('contact_email') }}" maxlength="2000"></label>
                <label for="contact_hours">Office hours<input id="contact_hours" name="contact_hours" type="text" value="{{ $setting('contact_hours') }}" maxlength="2000"></label>
                <label class="wide" for="contact_note">Verification note<textarea id="contact_note" name="contact_note" rows="3" maxlength="2000">{{ $setting('contact_note') }}</textarea></label>
            </div>
            <p class="admin-note">Only publish contact details that have been confirmed against an official council source.</p>
        </section>

        <section class="admin-panel">
            <div class="admin-panel-head"><div><span class="card-kicker">IDENTITY</span><h2>Site logo and footer</h2></div></div>
            <div class="admin-form-grid">
                <label for="logo_path">Site logo<select id="logo_path" name="logo_path">
                    @foreach ($mediaAssets as $asset)
                        <option value="{{ $asset->path }}" @selected($setting('logo_path') === $asset->path)>{{ $asset->title }} · {{ $asset->path }}</option>
                    @endforeach
                </select></label>
                <label for="footer_tagline">Footer tagline<input id="footer_tagline" name="footer_tagline" type="text" value="{{ $setting('footer_tagline') }}" maxlength="2000"></label>
            </div>
            <p class="admin-note">Add new logo files in Media uploads, then select them here. Uploaded chairman photos automatically appear on the council directory.</p>
        </section>

        <div class="admin-actions admin-actions-sticky">
            <button class="button button-primary" type="submit">Save settings <span aria-hidden="true">→</span></button>
            <a class="text-link" href="{{ route('home') }}" target="_blank" rel="noopener noreferrer">Preview site <span aria-hidden="true">↗</span></a>
        </div>
    </form>
</x-admin-shell>
