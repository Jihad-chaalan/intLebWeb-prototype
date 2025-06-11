<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Admin Dashboard</title>

    <!-- Link CSS -->
    <link rel="stylesheet" href="{{ asset('build/assets/admin.css') }}">
</head>

<body>
    <div class="sidebar">
        <div class="logo">CS Admin Portal</div>
        <div class="nav-item active" onclick="showSection('dashboard')">
            📊 Dashboard
        </div>
        <div class="nav-item" onclick="showSection('posts')">
            📝 Posts Management
        </div>
        <div class="nav-item" onclick="showSection('companies')">
            🏢 Companies
        </div>
        <div class="nav-item" onclick="showSection('seekers')">
            👥 Internship Seekers
        </div>
        <form method="POST" action="{{ route('logout') }}" style="margin-top: 20px; text-align: center;">
            @csrf
            <button type="submit" style="background-color: #dc3545; color: white; border: none; padding: 10px 20px; border-radius: 8px; cursor: pointer;">
                🚪 Logout
            </button>
        </form>
    </div>

    <div class="main-content">
        <!-- Dashboard Section -->
        <div id="dashboard" class="content-section active">
            <div class="welcome-header">
                <h1>Welcome, Admin! 👋</h1>
                <p>Manage your CS internship portal from this comprehensive dashboard</p>
            </div>

            <div class="dashboard-stats">
                <div class="stat-card">
                    <div class="stat-number" id="companiesCount">{{ $companiesCount ?? 0 }}</div>
                    <div class="stat-label">Total Companies</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number" id="seekersCount">{{ $seekersCount ?? 0 }}</div>
                    <div class="stat-label">Internship Seekers</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number" id="postsCount">{{ $postsCount ?? 0 }}</div>
                    <div class="stat-label">Active Posts</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number" id="pendingRequests">{{ $pendingRequests ?? 0 }}</div>
                    <div class="stat-label">Verification Requests</div>
                </div>
            </div>
        </div>

        <!-- Posts Management Section -->
        <div id="posts" class="content-section">
            <h2 class="section-title">Posts Management</h2>
            @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
            @endif

            <!-- <div class="filter-bar">
                <input type="text" class="search-box" placeholder="Search posts..." id="postsSearch" />
                <select class="filter-select" id="postsFilter">
                    <option value="all">All Posts</option>
                    <option value="active">Active</option>
                    <option value="expired">Expired</option>
                </select>
            </div> -->

            <div class="table-container">
                <table id="postsTable">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Company</th>
                            <th>Posted Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($posts as $post)
                        <tr>
                            <td>{{ $post->position }}</td>
                            <td>{{ $post->company->name }}</td>

                            <td>{{ $post->created_at }}</td>
                            <td>
                                <form action="{{ route('admin.posts.destroy', $post->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-delete" onclick="return confirm('Are you sure you want to delete this post?')">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                        @if ($posts->isEmpty())
                        <!-- <tr>
                            <td colspan="6" class="text-center">No posts found.</td>
                        </tr> -->
                        @endif
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Companies Section -->
        <div id="companies" class="content-section">
            <h2 class="section-title">Companies Management</h2>

            <!-- <div class="filter-bar">
                <input type="text" class="search-box" placeholder="Search companies..." id="companiesSearch" />
                <select class="filter-select" id="companiesFilter">
                    <option value="all">All Companies</option>
                    <option value="verified">Verified</option>
                    <option value="pending">Pending Verification</option>
                </select>
            </div> -->

            <div id="companiesList">
                @foreach ($companies as $company)
                <div class="company-card">
                    <div class="company-header">
                        <div class="company-name">{{ $company->name }}</div>
                    </div>
                    <p><strong>Email:</strong> {{ $company->email }}</p>
                    <p><strong>Location:</strong> {{ $company->address }}</p>
                    <p><strong>Active Posts:</strong> {{ $company->posts()->count() }}</p>
                    <p>
                        <strong>Registration Document:</strong>
                        <img
                            src="{{ $company->registration_document ? asset('storage/' . $company->registration_document) : asset('build/assets/images/cover.jpg') }}"
                            alt="No document"
                            style="width: 200px; height: 150px; border-radius: 2px; object-fit: cover; vertical-align: middle;">
                    </p>
                    <!-- <p><strong>Registration Document:</strong> <img src="{{ $company->registration_document ? asset('storage/' . $company->registration_document) : asset('build/assets/images/cover.jpg') }}" alt="No document"></p> -->
                    <div style="margin: 5px;">
                        @if ($company->verified == 0)
                        <form action="{{ route('admin.company.verify', $company->id) }}" method="POST" style="display:inline;">
                            @csrf
                            <button type="submit"
                                style="
                background-color: #007bff;
                color: white;
                padding: 8px 16px;
                border: none;
                border-radius: 4px;
                cursor: pointer;
                font-weight: 600;
                transition: background-color 0.3s ease;
            "
                                onmouseover="this.style.backgroundColor='#0056b3'"
                                onmouseout="this.style.backgroundColor='#007bff'">
                                Verify
                            </button>
                            <!-- <button type="submit" class="btn btn-primary">Verify</button> -->
                        </form>
                        @else
                        <button
                            disabled
                            style="
            background-color: #28a745;
            color: white;
            padding: 8px 16px;
            border: none;
            border-radius: 4px;
            font-weight: 600;
            cursor: default;
            display: flex;
            align-items: center;
            gap: 6px;
        ">
                            Verified <span style="font-size: 18px;">&#x2705;</span>
                        </button>
                        @endif
                    </div>

                </div>
                @endforeach
                @if ($companies->isEmpty())
                <p>No companies found.</p>
                @endif
            </div>
        </div>

        <!-- Internship Seekers Section -->
        <div id="seekers" class="content-section">
            <h2 class="section-title">Internship Seekers Profiles</h2>

            <!-- <div class="filter-bar">
                <input type="text" class="search-box" placeholder="Search seekers..." id="seekersSearch" />
                <select class="filter-select" id="seekersFilter">
                    <option value="all">All Seekers</option>
                    <option value="students">Students</option>
                    <option value="graduates">Recent Graduates</option>
                </select>
            </div> -->

            <div id="seekersList">
                @foreach ($seekers as $seeker)
                <div class="profile-card">
                    <div class="profile-header">
                        <!-- <div class="profile-avatar"> -->
                        <!-- <img src="{{ $seeker->cover_photo ? asset('storage/' . $seeker->cover_photo) : asset('build/assets/images/cover.jpg') }}" alt=""> -->
                        <img
                            src="{{ $seeker->cover_photo ? asset('storage/' . $seeker->cover_photo) : asset('build/assets/images/cover.jpg') }}"
                            alt="Cover Photo"
                            style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover; display: inline-block;">
                        <!-- </div> -->
                        <div>
                            <h3>{{ $seeker->name }}</h3>
                            <p>{{ $seeker->status }}</p>
                        </div>
                    </div>
                    <p><strong>Email:</strong> {{ $seeker->email }}</p>
                    <p><strong>Phone:</strong> {{ $seeker->phone_number}}</p>
                    <p><strong>Skills:</strong>
                        {{ is_array($seeker->skills) ? implode(', ', $seeker->skills) : $seeker->skills }}
                    </p>
                    <p><strong>Created at:</strong> {{ $seeker->created_at }}</p>


                </div>
                @endforeach
                @if ($seekers->isEmpty())
                <p>No internship seekers found.</p>
                @endif
            </div>
        </div>
    </div>

    <script>
        // Navigation functionality
        function showSection(sectionId) {
            // Hide all sections
            const sections = document.querySelectorAll('.content-section');
            sections.forEach(section => section.classList.remove('active'));

            // Remove active class from nav items
            const navItems = document.querySelectorAll('.nav-item');
            navItems.forEach(item => item.classList.remove('active'));

            // Show selected section
            document.getElementById(sectionId).classList.add('active');

            // Add active class to clicked nav item
            event.target.classList.add('active');
        }

        // Posts management functions
        function editPost(postId) {
            alert(`Editing post with ID: ${postId}`);
            // Here you would typically open a modal or redirect to edit page
        }

        function deletePost(postId) {
            if (confirm('Are you sure you want to delete this post?')) {
                // Remove the row from table
                const row = event.target.closest('tr');
                row.remove();

                // Update posts count
                const currentCount = parseInt(document.getElementById('postsCount').textContent);
                document.getElementById('postsCount').textContent = currentCount - 1;

                alert(`Post ${postId} deleted successfully!`);
            }
        }



        // Search functionality for posts
        document.getElementById('postsSearch').addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const rows = document.querySelectorAll('#postsTable tbody tr');

            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(searchTerm) ? '' : 'none';
            });
        });

        // Search functionality for companies
        document.getElementById('companiesSearch').addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const companies = document.querySelectorAll('.company-card');

            companies.forEach(company => {
                const text = company.textContent.toLowerCase();
                company.style.display = text.includes(searchTerm) ? '' : 'none';
            });
        });

        // Search functionality for seekers
        document.getElementById('seekersSearch').addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const seekers = document.querySelectorAll('.profile-card');

            seekers.forEach(seeker => {
                const text = seeker.textContent.toLowerCase();
                seeker.style.display = text.includes(searchTerm) ? '' : 'none';
            });
        });

        // Filter functionality
        document.getElementById('postsFilter').addEventListener('change', function() {
            const filterValue = this.value;
            const rows = document.querySelectorAll('#postsTable tbody tr');

            rows.forEach(row => {
                if (filterValue === 'all') {
                    row.style.display = '';
                } else {
                    const status = row.querySelector('.status-badge').textContent.toLowerCase();
                    row.style.display = status.includes(filterValue) ? '' : 'none';
                }
            });
        });

        // Animate stats on page load
        window.addEventListener('load', function() {
            const stats = document.querySelectorAll('.stat-number');
            stats.forEach(stat => {
                const target = parseInt(stat.textContent);
                let current = 0;
                const increment = target / 50;
                const timer = setInterval(() => {
                    current += increment;
                    if (current >= target) {
                        current = target;
                        clearInterval(timer);
                    }
                    stat.textContent = Math.floor(current);
                }, 30);
            });
        });
    </script>
</body>

</html>