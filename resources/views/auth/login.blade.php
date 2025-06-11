<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <!-- Your custom CSS -->
    <link rel="stylesheet" href="{{ asset('build/assets/login.css') }}">
</head>

<body>
    <header>
        <h1>Login</h1>
    </header>

    <div class="login">
        {{-- Session Status --}}
        @if (session('status'))
        <div class="session-status">
            {{ session('status') }}
        </div>
        @endif

        <form class="login-form" method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Email -->
            <div>
                <label for="email">Email:</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    value="{{ old('email') }}"
                    placeholder="Enter your Email"
                    required
                    autofocus>
                @if ($errors->has('email'))
                <div class="error">{{ $errors->first('email') }}</div>
                @endif
            </div>

            <!-- Password -->
            <div>
                <label for="password">Password:</label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    placeholder="Enter your password"
                    required>
                @if ($errors->has('password'))
                <div class="error">{{ $errors->first('password') }}</div>
                @endif
            </div>

            <button type="submit">Login</button>
        </form>

        <p>Don't have an account? <a href="{{ route('register') }}">Register</a></p>
    </div>
</body>

</html>