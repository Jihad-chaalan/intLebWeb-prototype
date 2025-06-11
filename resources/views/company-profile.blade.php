<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Company</title>

    <!-- CSS -->
    <!-- <link rel="stylesheet" href="{{ asset('css/companyProfile.css') }}"> -->
    <link rel="stylesheet" href="{{ asset('build/assets/companyProfile.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body>

    <!-- <header class="header container">
        <div class="cover-wrapper">
            <img class="cover-picture" id="cover-picture" src="{{ $company->cover_photo ? asset('storage/' . $company->cover_photo) : asset('build/assets/images/cover.jpg') }}" alt="Cover Photo" />
            <input type="file" id="cover-input" accept="image/*" style="display: none;" />
        </div>

        <div class="profile-info">
            <div class="profile-wrapper">
                <img class="profile-picture" id="profile-picture" src="{{ $company->profile_picture ? asset('storage/' . $company->profile_picture) : asset('build/assets/images/unknown-person.jpg') }}" alt="Profile Picture" />
                <input type="file" id="profile-input" accept="image/*" style="display: none;" />
            </div>

            <div class="personal-info">
                <h2>{{ $company->name }}</h2>

                <div id="desc-container">
                    <p id="desc-display"><i class="fas fa-align-left"></i>{{ $company->description ?? 'This company is a tech company using ...' }}</p>
                    <textarea id="description" rows="3" style="display:none;"></textarea>
                </div>

                <div id="phone-container">
                    <p id="phone-display"><i class="fas fa-phone"></i> {{ $company->phone ?? 'NO Phone Number' }}</p>
                    <input type="text" id="phone" style="display:none;" />
                </div>

                <div id="email-container">
                    <p id="email-display"><i class="fas fa-envelope"></i> {{ $company->email }}</p>
                    <input type="email" id="email" style="display:none;" />
                </div>

                <p id="website-display">
                    <i class="fas fa-globe"></i>
                    <a href="{{ $company->website ?? '#' }}" id="website-link" target="_blank">{{ $company->website ?? 'https://companywebsite' }}</a>
                </p>
                <input type="text" id="website" style="display: none;" />

                <div id="location-container">
                    <p id="location-display">
                        <i class="fas fa-map-marker-alt"></i>
                        <span id="location-text">{{ $company->address ?? 'Al Maaref University - Block C جامعة المعارف'}}</span>
                    </p>
                    <input type="text" id="location-input" placeholder="Enter company address" style="display: none;" />

                    <div id="map-container" style="margin-top: 10px;">
                        <iframe
                            id="map-frame"
                            src="https://www.google.com/maps?q={{ $company->address ?? 'Al Maaref University - Block C جامعة المعارف'}}&output=embed"
                            width="300"
                            height="150"
                            frameborder="0"
                            style="border:0; border-radius: 10px;"
                            allowfullscreen=""
                            loading="lazy"></iframe>
                    </div>
                </div>

                <div class="action-buttons">
                    <button class="edit-btn" onclick="enableEditing()">Edit</button>
                    <button class="save-btn" onclick="saveInfo()" disabled>Save</button>
                </div>

                <p id="save-msg" style="color: green; display: none;">Company Profile information saved successfully ✅</p>
            </div>
        </div>
    </header> -->


    <header class="header container">
        <!-- Cover Section -->
        <div class="cover-wrapper">
            <img class="cover-picture" id="cover-picture"
                src="{{ $company->cover_photo ? asset('storage/' . $company->cover_photo) : asset('build/assets/images/cover.jpg') }}"
                alt="Cover Photo" />
            <!-- <input type="file" id="cover-input" accept="image/*" style="display: none;" /> -->
            <!-- Cover Photo Upload Form -->
            <form id="cover-upload-form" method="POST" action="{{ route('company.updateCoverPhoto') }}" enctype="multipart/form-data" style="display:none;">
                @csrf
                <input type="file" name="cover_photo" id="cover-input" accept="image/*" />
            </form>
        </div>

        <!-- Profile and Info Section -->
        <div class="profile-info">
            <!-- Profile Picture -->
            <div class="profile-wrapper">
                <img class="profile-picture" id="profile-picture"
                    src="{{ $company->profile_photo ? asset('storage/' . $company->profile_photo) : asset('build/assets/images/unknown-person.jpg') }}"
                    alt="Profile Picture" />
                <!-- <input type="file" id="profile-input" accept="image/*" style="display: none;" /> -->
                <!-- Profile Picture Upload Form -->
                <form id="profile-upload-form" method="POST" action="{{ route('company.updateProfilePicture') }}" enctype="multipart/form-data" style="display:none;">
                    @csrf
                    <input type="file" name="profile_photo" id="profile-input" accept="image/*" />
                </form>
            </div>

            <!-- Start Form -->
            <form id="company-info-form" method="POST" action="{{ route('company.updateCompanyInfo') }}">
                @csrf
                @method('PUT')

                <div class="personal-info">
                    <h2>{{ $company->name }}</h2>

                    <!-- Description -->
                    <div id="desc-container">
                        <p id="desc-display"><i class="fas fa-align-left"></i> {{ $company->description ?? 'This company is a tech company using ...' }}</p>
                        <textarea name="description" id="description" rows="3" style="display: none;">{{ old('description', $company->description) }}</textarea>
                    </div>

                    <!-- Phone -->
                    <div id="phone-container">
                        <p id="phone-display"><i class="fas fa-phone"></i> {{ $company->phone_number ?? 'NO Phone Number' }}</p>
                        <input type="text" name="phone_number" id="phone" value="{{ old('phone_number', $company->phone_number) }}" style="display: none;" />
                    </div>

                    <!-- Email -->
                    <div id="email-container">
                        <p id="email-display"><i class="fas fa-envelope"></i> {{ $company->email }}</p>
                        <input type="email" name="email" id="email" value="{{ old('email', $company->email) }}" style="display: none;" />
                    </div>

                    <!-- Website -->
                    <p id="website-display">
                        <i class="fas fa-globe"></i>
                        <a href="{{ $company->website ?? '#' }}" id="website-link" target="_blank">
                            {{ $company->website ?? 'https://companywebsite' }}
                        </a>
                    </p>
                    <input type="text" name="website" id="website" value="{{ old('website', $company->website) }}" style="display: none;" />

                    <!-- Location -->
                    <div id="location-container">
                        <p id="location-display">
                            <i class="fas fa-map-marker-alt"></i>
                            <span id="location-text">{{ $company->address ?? 'Al Maaref University - Block C جامعة المعارف' }}</span>
                        </p>
                        <input type="text" name="address" id="location-input" placeholder="Enter company address"
                            value="{{ old('address', $company->address) }}" style="display: none;" />

                        <!-- Embedded Map -->
                        <div id="map-container" style="margin-top: 10px;">
                            <iframe id="map-frame"
                                src="https://www.google.com/maps?q={{ $company->address ?? 'Al Maaref University - Block C جامعة المعارف'}}&output=embed"
                                width="300" height="150" frameborder="0"
                                style="border:0; border-radius: 10px;" allowfullscreen="" loading="lazy"></iframe>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="action-buttons">
                        <button type="button" class="edit-btn" onclick="enableEditing()">Edit</button>
                        <button type="submit" class="save-btn" id="save-btn" disabled>Save</button>
                    </div>


                    <!-- Save Message -->
                    @if(session('success'))
                    <!-- <p id="save-msg" style="color: green;">{{ session('success') }}</p> -->
                    <p id="save-msg" style="color: green; opacity: 1; transition: opacity 0.5s ease;">
                        {{ session('success') }}
                    </p>
                    @endif
                </div>
            </form>
            <!-- End Form -->
            <form method="POST" action="{{ route('logout') }}" style="margin-top: 20px; text-align: center;">
                @csrf
                <button type="submit" style="background-color: #dc3545; color: white; border: none; padding: 10px 20px; border-radius: 8px; cursor: pointer; margin:30px; position:absolute; right:0;">
                    🚪 Logout
                </button>
            </form>
        </div>
    </header>


    <section class="post container">
        <header class="header-section">
            <h2 class="title">Your Posts</h2>
            <button onclick="openPostModal()">Add Int Post</button>
        </header>

        <!-- <div class="message" id="no-post-msg">
            <p>You don't have any post.</p>
            <p>Add New Internship position post and give the student chance the experience</p>
        </div>

        <div id="posts-container"></div> -->
        @if($posts->isEmpty())
        <div class="message" id="no-post-msg">
            <p>You don't have any post.</p>
            <p>Add New Internship position post and give the student chance the experience</p>
        </div>
        @else
        <div id="posts-container">
            @foreach($posts as $post)
            <div class="post-box">
                <div class="post-header">
                    <h4>Position: {{ $post->position }}</h4>
                    <div class="dropdown">
                        <span class="dots" onclick="toggleMenu(this)">&#8942;</span>
                        <div class="dropdown-menu">
                            <!-- <button onclick="editPost({{ $post->id }})">Edit</button> -->
                            <!-- <button onclick="editPost('{{ $post }}')">Edit</button> -->
                            <button onclick="editPost({{ $post }})">Edit</button>
                            <!-- <button onclick="deletePost('{{ $post->id }}')">Delete</button> -->
                            <form method="POST" action="{{ route('company.deletePost', $post->id) }}" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick="return confirm('Are you sure you want to delete this post?');">
                                    Delete
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                <p>Technologies: {{ $post->technology }}</p>

                @if($post->photo)
                <div class="media-container">
                    @if(str_starts_with($post->photo, 'video'))
                    <video controls src="{{ asset('storage/' . $post->photo) }}"></video>
                    @else
                    <img src="{{ asset('storage/' . $post->photo) }}" alt="Post Media" />
                    @endif
                </div>
                @endif

                <!-- <p class="applicants-count" onclick="toggleApplicants(this)">{{ $post->applicants_count ?? 0 }} applicants</p>
                <ul class="applicants-list" style="display: none;">
                    <li>No one has applied yet.</li>
                </ul> -->
                <p class="applicants-count" onclick="toggleApplicants(this)">
                    {{ $post->applications->count() }} applicants
                </p>
                <ul class="applicants-list" style="display: none;">
                    @if ($post->applications->isEmpty())
                    <li>No one has applied yet.</li>
                    @else
                    @foreach ($post->applications as $application)
                    <li>{{ $application->seeker->name ?? 'Unknown' }} - {{ $application->seeker->user->email ?? 'N/A' }}</li>
                    @endforeach
                    @endif
                </ul>
            </div>
            @endforeach
        </div>
        @endif
    </section>
    <!-- @foreach($posts as $post)
    <h3>{{ $post->title }}</h3>
    <ul>
        @foreach($post->applications as $application)
        <li>
            Seeker:
            @if($application->seeker)
            {{ $application->seeker->name ?? '[No Name]' }}
            @else
            <span style="color:red;">No seeker</span>
            @endif

            - Email:
            @if($application->seeker && $application->seeker->user)
            {{ $application->seeker->user->email ?? '[No Email]' }}
            @else
            <span style="color:red;">No user</span>
            @endif
        </li>
        @endforeach
    </ul>
    @endforeach -->


    <!-- <div class="modal" id="postModal">
        <div class="modal-content">
            <span class="close" onclick="closePostModal()">&times;</span>
            <h3>Create Internship Post</h3>
            <form method="POST" action="{{ route('company.addInternshipPost') }}" enctype="multipart/form-data" id="postForm">
                @csrf
                <input type="text" id="position" name="position" placeholder="Position (e.g. Frontend Developer)" required />
                <input type="text" id="technology" name="technology" placeholder="Technologies (e.g. React, Laravel)" required />
                <input type="text" id="description" name="description" placeholder="the internship include..." />
                <input type="file" id="media" name="photo" accept="image/*,video/*" />
                <button type="submit">Confirm Post</button>
            </form>

        </div>
    </div> -->
    <!-- <div class="modal" id="postModal">
        <div class="modal-content">
            <span class="close" onclick="closePostModal()">&times;</span>
            <h3 id="modalTitle">Create Internship Post</h3>

            <form method="POST" enctype="multipart/form-data" action="/company/posts" id="postForm">

                @csrf
                <input type="hidden" name="_method" id="formMethod" value="POST">

                <input type="text" id="position" name="position" placeholder="Position" required />
                <input type="text" id="technology" name="technology" placeholder="Technologies" required />
                <input type="text" id="description" name="description" placeholder="Description" />
                <input type="file" id="media" name="photo" accept="image/*,video/*" />

                <button type="submit">Confirm Post</button>
            </form>
        </div>
    </div> -->

    <div class="modal" id="postModal" style="display:none;">
        <div class="modal-content">
            <span class="close" onclick="closePostModal()">&times;</span>
            <h3 id="modalTitle">Create Internship Post</h3>

            <form method="POST" enctype="multipart/form-data" id="postForm" action="{{route('company.addInternshipPost')}}">
                @csrf
                <input type="hidden" name="_method" id="formMethod" value="POST">
                <input type="hidden" name="post_id" id="postId" value="">

                <input type="text" id="position" name="position" placeholder="Position" required />
                <input type="text" id="technology" name="technology" placeholder="Technologies" required />
                <input type="text" id="post-description" name="description" placeholder="Description" required />
                <input type="file" id="media" name="photo" accept="image/*,video/*" />

                <button type="submit">Confirm Post</button>
            </form>
        </div>
    </div>

    <!-- Scripts -->
    <script src="{{ asset('build/assets/companyProfile.js') }}">
    </script>
</body>

</html>