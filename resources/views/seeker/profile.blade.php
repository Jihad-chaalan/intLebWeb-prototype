<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Include your CSS --}}
    <link rel="stylesheet" href="{{ asset('build/assets/seekerProfile.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <title>Profile</title>
</head>

<body>
    <header class="header container">
        <div class="cover-wrapper">
            <img class="cover-picture" id="cover-picture"
                src="{{ $seeker->cover_photo ? asset('storage/' . $seeker->cover_photo) : asset('build/assets/images/cover.jpg') }}"
                alt="Cover Photo" />
            <!-- <input type="file" id="cover-input" accept="image/*" style="display: none;" /> -->
            <!-- Cover Photo Upload Form -->
            <form id="cover-upload-form" method="POST" action="{{ route('seeker.updateCoverPhoto') }}" enctype="multipart/form-data" style="display:none;">
                @csrf
                <input type="file" name="cover_photo" id="cover-input" accept="image/*" />
            </form>
        </div>

        <!-- Profile and Info Section -->
        <div class="profile-info">
            <!-- Profile Picture -->
            <div class="profile-wrapper">
                <img class="profile-picture" id="profile-picture"
                    src="{{ $seeker->profile_photo ? asset('storage/' . $seeker->profile_photo) : asset('build/assets/images/unknown-person.jpg') }}"
                    alt="Profile Picture" />
                <!-- <input type="file" id="profile-input" accept="image/*" style="display: none;" /> -->
                <!-- Profile Picture Upload Form -->
                <form id="profile-upload-form" method="POST" action="{{ route('seeker.updateProfilePicture') }}" enctype="multipart/form-data" style="display:none;">
                    @csrf
                    <input type="file" name="profile_photo" id="profile-input" accept="image/*" />
                </form>
            </div>


            <form id="seeker-info-form" method="POST" action="{{ route('seeker.updateSeekerInfo') }}">
                @csrf
                @method('PUT')

                <div class="personal-info">
                    <h2>{{ $seeker->name ?? 'No name found' }}</h2>

                    <!-- Description -->
                    <div id="desc-container">
                        <p id="desc-display"><i class="fas fa-align-left"></i> {{ $seeker->description ?? 'I am a self-learner, excited to gain real experience with real projects' }}</p>
                        <textarea name="description" id="description" rows="3" style="display: none;">{{ old('description', $seeker->description) }}</textarea>
                    </div>

                    <!-- Phone -->
                    <div id="phone-container">
                        <p id="phone-display"><i class="fas fa-phone"></i> {{ $seeker->phone_number ?? 'No number provided' }}</p>
                        <input type="text" name="phone_number" id="phone" value="{{ old('phone_number', $seeker->phone_number) }}" style="display: none;" />
                    </div>

                    <!-- Email -->
                    <div id="email-container">
                        <p id="email-display"><i class="fas fa-envelope"></i> {{ $seeker->email ?? 'No email provided' }}</p>
                        <input type="email" name="email" id="email" value="{{ old('email', $seeker->email) }}" style="display: none;" />
                    </div>

                    <!-- GitHub -->
                    <div id="github-container">
                        <p id="github-display">
                            <i class="fab fa-github"></i>
                            <a href="{{ $seeker->github_link ?? '#' }}" id="github-link" target="_blank">
                                {{ $seeker->github_link ?? 'No GitHub profile' }}
                            </a>
                        </p>
                        <input type="text" name="github_link" id="github" value="{{ old('github_link', $seeker->github_link) }}" style="display: none;" />
                    </div>

                    <!-- Action Buttons -->
                    <div class="action-buttons">
                        <button type="button" class="edit-btn" onclick="enableEditing()">Edit</button>
                        <button type="submit" class="save-btn" id="save-btn" disabled>Save</button>
                    </div>

                    <!-- Save Message -->
                    @if(session('success'))
                    <p id="save-msg" style="color: green; opacity: 1; transition: opacity 0.5s ease;">
                        {{ session('success') }}
                    </p>
                    @endif
                </div>
            </form>
        </div>
    </header>

    <section class="container">
        <a class="homePage" href="{{ route('seeker.home') }}">Go to Home Page</a>
        <!-- <a class="homePage" href="{{ route('logout') }}" style="background-color : red;">Logout</a> -->
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="homePage" style="background-color: red;">Logout</button>
        </form>
    </section>

    <!-- <section class="skills container">
        <div class="top-skill">
            <h2 class="skill title">Skills</h2>
        </div>


        <div id="selected-skills" class="selected-skills"></div>
        <form method="POST" action="/save-skills" id="skills-form">
            @csrf

            <input type="text" id="skill-input" autocomplete="off" placeholder="Add your skills" />
            <div id="suggestion-box" class="suggestions"></div>

            <div id="selected-skills" class="selected-skills"></div>

            <input type="hidden" name="skills" id="skills-hidden" />

            <button type="submit">Save Skills</button>
        </form>
    </section> -->
    <section class="skills container">
        <div class="top-skill">
            <h2 class="skill title">Skills</h2>
        </div>

        <form method="POST" action="/save-skills" id="skills-form">
            @csrf

            <input type="text" id="skill-input" autocomplete="off" placeholder="Add your skills" />
            <div id="suggestion-box" class="suggestions"></div>

            <input type="hidden" name="skills" id="skills-hidden" />

            <div style="text-align: center;">
                <button type="submit" style="padding: 10px 20px; background-color: #007BFF; color: white; border: none; border-radius: 5px; cursor: pointer; font-size: 16px; margin:5px;">
                    Save Skills
                </button>
            </div>
        </form>

        {{-- Separate skill tags with delete buttons --}}
        <div id="selected-skills" class="selected-skills" style="margin-top: 20px;">
            @foreach ($skills as $skill)
            <div class="skill-tag" style="margin-bottom: 5px;">
                {{ $skill }}

                <form action="{{ route('skills.remove') }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" name="skill" value="{{ $skill }}">
                    <button type="submit" class="remove-icon" style="border:none; background:none; color:red; cursor:pointer;">
                        &times;
                    </button>
                </form>
            </div>
            @endforeach
        </div>
    </section>

    <section class="project container">
        <div class="project-header">
            <h2 class="project title">Projects</h2>
        </div>
        <div class="project-body" style="display: none;"></div>

        <div class="project-footer">
            <span class="add-icon" id="add-project-btn">+</span>
        </div>


        @if ($projects->isEmpty())
        <p>No projects added yet.</p>
        @else
        <div class="projects-list">
            @foreach ($projects as $project)
            <div class="project-wrapper">
                <h3 class="project-title">Project title: {{ $project->title }}</h3>
                <p class="project-description">Project description: {{ $project->description }}</p>
                <p class="project-link">
                    Project link:
                    @if ($project->link)
                    <a href="{{ $project->link }}" target="_blank">{{ $project->link }}</a>
                    @else
                    No link provided
                    @endif
                </p>
                <form method="POST" action="{{ route('seeker.removeProject', $project->id) }}">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="remove-project-btn">Remove</button>
                </form>
            </div>
            @endforeach
        </div>
        @endif
        </div>
    </section>

    <!-- Modal for adding project -->
    <div class="modal" id="project-modal">
        <div class="modal-content">
            <form id="project-form" action="{{ route('seeker.addProject') }}" method="POST">
                @csrf
                <h3>Add New Project</h3>
                <input type="text" name="title" id="project-title" placeholder="Project Title" style="width: -webkit-fill-available;" />
                <textarea name="description" id="project-description" placeholder="Project Description" style="width: -webkit-fill-available;"></textarea>
                <input type="url" name="link" id="project-link" placeholder="Project Link" style="width: -webkit-fill-available;" />
                <div class="modal-buttons">
                    <button type="submit" id="submit-project">Add Project</button>
                    <button id="cancel-project" type="button">Cancel</button>
                </div>
            </form>
        </div>
    </div>
    </div>
    <!-- <form id="project-form" action="{{ route('seeker.addProject') }}" method="POST">
        @csrf
        <input type="text" name="title" id="project-title" placeholder="Project Title" required />
        <textarea name="description" id="project-description" placeholder="Project Description" required></textarea>
        <input type="url" name="link" id="project-link" placeholder="Project Link (optional)" />
        <button type="submit" id="submit-project">Add Project</button>
    </form> -->

    <!-- Scripts -->
    <script src="{{ asset('build/assets/seekerProfile.js') }}"></script>

</body>

</html>