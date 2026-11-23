<x-admin-shell :local-government-name="$localGovernmentName" title="Media uploads" active="media">
    <div class="admin-heading">
        <div>
            <div class="eyebrow"><span class="eyebrow-dot"></span> ADMIN · MEDIA</div>
            <h1>Media uploads</h1>
            <p>Upload the logo, chairman photographs and other images used on the public website.</p>
        </div>
        <a class="text-link" href="{{ route('admin.settings.edit') }}">Choose the site logo <span aria-hidden="true">↗</span></a>
    </div>

    @if (session('media_uploaded') || session('media_updated') || session('media_deleted'))
        <div class="admin-alert">The media library was updated.</div>
    @endif
    @if ($errors->any())
        <div class="admin-alert admin-alert-error">{{ $errors->first() }}</div>
    @endif

    <section class="admin-panel">
        <div class="admin-panel-head"><div><span class="card-kicker">NEW UPLOAD</span><h2>Add an image</h2></div></div>
        <form class="admin-form-grid" method="post" action="{{ route('admin.media.store') }}" enctype="multipart/form-data">
            @csrf
            <label for="title">Title<input id="title" name="title" type="text" value="{{ old('title') }}" placeholder="e.g. Shani chairman portrait" required></label>
            <label for="category">Category<select id="category" name="category" required>
                @foreach ($categories as $category)
                    <option value="{{ $category }}" @selected(old('category') === $category)>{{ ucfirst($category) }}</option>
                @endforeach
            </select></label>
            <label class="wide" for="alt_text">Alternative text<input id="alt_text" name="alt_text" type="text" value="{{ old('alt_text') }}" placeholder="Describe the image for screen readers"></label>
            <label class="wide" for="file">Image file<input id="file" name="file" type="file" accept="image/jpeg,image/png,image/webp" required></label>
            <div class="admin-actions wide">
                <button class="button button-primary" type="submit">Upload image <span aria-hidden="true">→</span></button>
                <span class="admin-note">JPG, PNG or WEBP up to 4 MB.</span>
            </div>
        </form>
    </section>

    <section class="admin-panel">
        <div class="admin-panel-head"><div><span class="card-kicker">LIBRARY</span><h2>Uploaded media</h2></div></div>
        <div class="admin-media-grid">
            @forelse ($mediaAssets as $asset)
                <article class="admin-media-card">
                    <img src="{{ $asset->url() }}" alt="{{ $asset->alt_text ?: $asset->title }}" loading="lazy">
                    <div class="admin-media-body">
                        @if ($asset->path === $logoPath)<span class="admin-pill">Site logo</span>@endif
                        <strong>{{ $asset->title }}</strong>
                        <small>{{ ucfirst($asset->category) }} · {{ $asset->path }}</small>
                        <form method="post" action="{{ route('admin.media.update', $asset) }}">
                            @csrf
                            @method('PUT')
                            <label>Title<input name="title" type="text" value="{{ $asset->title }}" required></label>
                            <label>Alternative text<input name="alt_text" type="text" value="{{ $asset->alt_text }}"></label>
                            <label>Category<select name="category" required>
                                @foreach ($categories as $category)
                                    <option value="{{ $category }}" @selected($asset->category === $category)>{{ ucfirst($category) }}</option>
                                @endforeach
                            </select></label>
                            <button class="button button-secondary" type="submit">Save details</button>
                        </form>
                        <form method="post" action="{{ route('admin.media.destroy', $asset) }}" onsubmit="return confirm('Delete this image?');">
                            @csrf
                            @method('DELETE')
                            <button class="admin-delete" type="submit" @disabled($asset->path === $logoPath)>Delete image</button>
                        </form>
                    </div>
                </article>
            @empty
                <p class="admin-empty">No images have been uploaded yet.</p>
            @endforelse
        </div>
        {{ $mediaAssets->links() }}
    </section>
</x-admin-shell>
