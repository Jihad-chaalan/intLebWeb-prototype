<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Register</title>
    <link rel="stylesheet" href="{{ asset('build/assets/register.css') }}" />
    <script>
        function showInfo(role) {
            const seekerInfo = document.querySelector('.seeker-info');
            const companyInfo = document.querySelector('.company-info');

            // Disable all inputs first
            seekerInfo.querySelectorAll('input').forEach(input => input.disabled = true);
            companyInfo.querySelectorAll('input').forEach(input => input.disabled = true);

            // Hide both
            seekerInfo.style.display = "none";
            companyInfo.style.display = "none";

            if (role === "int-seeker") {
                seekerInfo.style.display = "flex";
                seekerInfo.querySelectorAll('input').forEach(input => input.disabled = false);

                // Add required attributes here for seeker inputs
                seekerInfo.querySelectorAll('input').forEach(input => input.required = true);
                companyInfo.querySelectorAll('input').forEach(input => input.required = false);
            } else if (role === "company") {
                companyInfo.style.display = "flex";
                companyInfo.querySelectorAll('input').forEach(input => input.disabled = false);

                // Add required attributes here for company inputs
                companyInfo.querySelectorAll('input').forEach(input => input.required = true);
                seekerInfo.querySelectorAll('input').forEach(input => input.required = false);
            }
        }


        window.onload = function() {
            const roleSelect = document.getElementById("role");
            if (roleSelect) {
                const selectedRole = roleSelect.value;
                if (selectedRole) {
                    showInfo(selectedRole);
                }
            }
        };
    </script>
</head>

<body>
    <header>
        <h1>Register</h1>
    </header>

    <div id="register-form">
        @if ($errors->any())
        <div class="error">
            <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data">
            @csrf

            <!-- Role -->
            <label for="role">Register as:</label>
            <select id="role" name="role" onchange="showInfo(this.value)" required>
                <option value="" disabled {{ old('role') ? '' : 'selected' }}>Select your Role</option>
                <option value="int-seeker" {{ old('role') == 'int-seeker' ? 'selected' : '' }}>Int Seeker</option>
                <option value="company" {{ old('role') == 'company' ? 'selected' : '' }}>Company</option>
            </select>
            @error('role')
            <div class="error">{{ $message }}</div>
            @enderror

            <!-- Internship Seeker Info -->
            <div class="seeker-info" style="display: none; flex-direction: column;">
                <label for="name">Full Name:</label>
                <input type="text" id="name" name="name" placeholder="Enter your Name" value="{{ old('name') }}" />
                @error('name')
                <div class="error">{{ $message }}</div>
                @enderror

                <label for="seeker_email">Email:</label>
                <input type="email" id="seeker_email" name="email" placeholder="Enter your Email" value="{{ old('email') }}" />
                @error('email')
                <div class="error">{{ $message }}</div>
                @enderror

                <label for="seeker_password">Password:</label>
                <input type="password" id="seeker_password" name="password" placeholder="Enter your password" />
                @error('password')
                <div class="error">{{ $message }}</div>
                @enderror

                <label for="seeker_password_confirmation">Confirm Password:</label>
                <input type="password" id="seeker_password_confirmation" name="password_confirmation" placeholder="Confirm your password" />
                @error('password_confirmation')
                <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <!-- Company Info -->
            <div class="company-info" style="display: none; flex-direction: column;">
                <label for="company_name">Company Name:</label>
                <input type="text" id="company_name" name="company_name" placeholder="Enter Company Name" value="{{ old('company_name') }}" />
                @error('company_name')
                <div class="error">{{ $message }}</div>
                @enderror

                <label for="company_document">Upload Registration Document:</label>
                <input type="file" id="company_document" name="company_document" accept=".pdf,.jpg,.png,.doc,.docx" />
                @error('company_document')
                <div class="error">{{ $message }}</div>
                @enderror

                <label for="company_location">Company Location:</label>
                <input type="text" id="company_location" name="company_location" placeholder="Enter Location" value="{{ old('company_location') }}" />
                @error('company_location')
                <div class="error">{{ $message }}</div>
                @enderror

                <label for="company_email">Email:</label>
                <input type="email" id="company_email" name="company_email" placeholder="Enter your Email" value="{{ old('company_email') }}" />
                @error('company_email')
                <div class="error">{{ $message }}</div>
                @enderror

                <label for="company_password">Password:</label>
                <input type="password" id="company_password" name="company_password" placeholder="Enter your password" />
                @error('company_password')
                <div class="error">{{ $message }}</div>
                @enderror

                <label for="company_password_confirmation">Confirm Password:</label>
                <input type="password" id="company_password_confirmation" name="company_password_confirmation" placeholder="Confirm your password" />
                @error('company_password_confirmation')
                <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit">Register</button>
        </form>
        <p>Already have an account? <a href="{{ route('login') }}">Login</a></p>
    </div>
</body>

</html>