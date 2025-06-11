<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>User Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    {{-- Local CSS --}}
    <link rel="stylesheet" href="{{ asset('build/assets/seekerHome.css') }}">

    {{-- Font Awesome CDN --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body>

    <header>
        <div class="profile-info">
            <img src="{{ asset('build/assets/images/unknown-person.jpg') }}" alt="Profile Picture">
            <span class="username">{{ Auth::user()->name ?? 'John Doe' }}</span>
            <a class="profile-link" href="{{ route('seeker.profile') }}">My Profile</a>
        </div>
        <!-- <div class="search-bar">
            <input type="text" id="company-search" placeholder="Search opportunities or companies...">
            <ul id="search-results" style="position: absolute; top: 100%; left: 0; background: white; border: 1px solid #ccc; list-style: none; margin: 0; padding: 0; width: 100%; max-height: 200px; overflow-y: auto;"></ul> -->

        </div>
        <!-- <div class="search-bar" style="position: relative;">
            <input type="text" id="company-search" placeholder="Search opportunities or companies..." autocomplete="off" style="width: 100%;">
            <ul id="search-results" style="position: absolute; top: 100%; left: 0; background: white; border: 1px solid #ccc; list-style: none; margin: 0; padding: 0; width: 100%; max-height: 200px; overflow-y: auto;"></ul>
        </div> -->
    </header>

    <div class="container">
        <!-- Posts Section -->
        <section class="posts-section">
            <!-- @foreach($posts ?? [] as $post)
            <div class="post">
                <div class="post-header">
                    <h3>Position: {{ $post->position }}</h3>
                    <span>Company: {{ $post->company->name ?? 'Unknown' }}</span>
                </div>
                <p>Technology: {{ $post->technology}}</p>
                <p>{{ $post->description ?? 'No description available.' }}</p>

                @if(Str::endsWith($post->photo, ['.jpg', '.png', '.jpeg', '.gif']))
                <img src="{{ asset('storage/' . $post->photo) }}" alt="Post Image">
                @elseif(Str::endsWith($post->photo, ['.mp4', '.mov', '.avi']))
                <video controls src="{{ asset('storage/' . $post->media) }}"></video>
                @endif

                <form method="POST" action="{{ route('seeker.applyToPost', $post) }}">
                    @csrf
                    <button type="submit" class="apply-btn" onclick="disableButton(this)">Apply</button>
                </form>
            </div>
            @endforeach -->
            @foreach($posts as $post)
            <div class="post">
                <div class="post-header">
                    <h3>Position: {{ $post->position }}</h3>
                    <span>Company: {{ $post->company->name ?? 'Unknown' }}</span>
                </div>
                <p>Technology: {{ $post->technology}}</p>
                <p>{{ $post->description }}</p>
                @if(Str::endsWith($post->photo, ['.jpg', '.png', '.jpeg', '.gif']))
                <img src="{{ asset('storage/' . $post->photo) }}" alt="Post Image">
                @elseif(Str::endsWith($post->photo, ['.mp4', '.mov', '.avi']))
                <video controls src="{{ asset('storage/' . $post->photo) }}"></video>
                @endif

                @if(in_array($post->id, $appliedPostIds))
                <button class="apply-btn" disabled style="background-color: #ccc; color: #666; padding: 10px 16px; border: none; border-radius: 5px; cursor: not-allowed; opacity: 0.7;">
                    Applied
                </button>
                @else
                <form method="POST" action="{{ route('seeker.applyToPost', $post->id) }}">
                    @csrf
                    <button type="submit" class="apply-btn">Apply</button>
                </form>
                @endif
            </div>
            @endforeach
        </section>

        <!-- Sidebar -->
        <aside class="sidebar">
            @if(request('company_id'))
            <div style="margin-bottom: 1rem;">
                <form action="{{ route('seeker.home') }}" method="GET">
                    <button type="submit" style="padding: 5px 10px; background: #ccc; border: none;">Clear Filter</button>
                </form>
            </div>
            @endif
            <h4>Suggested Companies</h4>
            <!-- @foreach($suggestedCompanies as $company)
            <div class="company">
                <span>{{ $company -> name}}</span>
                <button class="follow-btn">Follow</button>
            </div>
            @endforeach -->
            @foreach($suggestedCompanies as $company)
            <div class="company">
                <span>{{ $company->name }}</span>
                <form action="{{ route('seeker.home') }}" method="GET" style="display: inline;">
                    <input type="hidden" name="company_id" value="{{ $company->id }}">
                    <button class="follow-btn" type="submit">See Posts</button>
                </form>
            </div>
            @endforeach
        </aside>
    </div>

</body>

</html>