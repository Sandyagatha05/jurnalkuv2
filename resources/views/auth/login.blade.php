<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - {{ config('app.name') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --primary: #193366;
            --secondary: #E8BA30;
            --bg-soft: #f4f6f9;
        }

        body {
            min-height: 100vh;
            background-color: var(--bg-soft);
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Figtree', sans-serif;
        }

        .login-wrapper {
            width: 100%;
            max-width: 420px;
            text-align: center;
        }

        /* Brand */
        .brand {
            text-decoration: none;
            color: inherit;
        }

        .brand-icon {
            width: 55px;
            height: 55px;
            background-color: var(--primary);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 0.75rem;
        }

        /* Card */
        .login-card {
            background: #ffffff;
            padding: 2rem;
            border-radius: 0.9rem;
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.08);
            margin-top: 1.75rem;
        }

        .form-label {
            font-size: 0.9rem;
            font-weight: 500;
        }

        /* Login Button */
        .btn-login {
            background-color: var(--primary);
            border-color: var(--primary);
            transition: all 0.25s ease;
        }

        .btn-login:hover {
            background-color: #14284d;
            transform: translateY(-2px);
            box-shadow: 0 8px 18px rgba(25, 51, 102, 0.25);
        }

        /* Password toggle */
        .password-wrapper {
            position: relative;
        }

        .password-toggle {
            position: absolute;
            top: 50%;
            right: 12px;
            transform: translateY(-50%);
            cursor: pointer;
            color: #6c757d;
        }

        .password-toggle:hover {
            color: var(--primary);
        }

        .small-text {
            font-size: 0.85rem;
        }

        .register-link {
            color: #2385e7ff;
            text-decoration: none;
            font-weight: 500;
        }

        .register-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="login-wrapper">

    <!-- Brand (OUTSIDE BOX) -->
    <a href="{{ route('home') }}" class="brand d-inline-flex align-items-center gap-3">
        <div class="brand-icon">
            <i class="fas fa-book-open"></i>
        </div>
        <div class="text-start">
            <div class="fw-bold" style="font-size: 1.5rem; color: var(--primary);">
                Jurnalku
            </div>
            <div class="text-muted" style="font-size: 0.95rem;">
                Academic Journal
            </div>
        </div>
    </a>

    <!-- Card -->
    <div class="login-card">

        <!-- Session Status -->
        @if (session('status'))
            <div class="alert alert-success small">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Email -->
            <div class="mb-3 text-start">
                <label for="email" class="form-label">Email</label>
                <input
                    id="email"
                    type="email"
                    name="email"
                    class="form-control @error('email') is-invalid @enderror"
                    value="{{ old('email') }}"
                    required
                    autofocus
                    autocomplete="username"
                >
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Password -->
            <div class="mb-3 text-start">
                <label for="password" class="form-label">Password</label>
                <div class="password-wrapper">
                    <input
                        id="password"
                        type="password"
                        name="password"
                        class="form-control @error('password') is-invalid @enderror"
                        required
                        autocomplete="current-password"
                    >
                    <span class="password-toggle" onclick="togglePassword()">
                        <i id="eyeIcon" class="fas fa-eye"></i>
                    </span>
                </div>
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Remember + Forgot -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember_me">
                    <label class="form-check-label small-text" for="remember_me">
                        Remember me
                    </label>
                </div>

                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="small-text text-decoration-none">
                        Forgot password?
                    </a>
                @endif
            </div>

            <!-- Login Button -->
            <div class="d-grid">
                <button type="submit" class="btn btn-login text-white">
                    Log in
                </button>
            </div>
        </form>

        <!-- Register Text -->
        @if (Route::has('register'))
            <div class="mt-3 small-text text-center">
                Don't have an account?
                <a href="{{ route('register') }}" class="register-link">
                    Register
                </a>
            </div>
        @endif

    </div>
</div>

<!-- Password Toggle Script -->
<script>
    function togglePassword() {
        const passwordInput = document.getElementById('password');
        const eyeIcon = document.getElementById('eyeIcon');

        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            eyeIcon.classList.replace('fa-eye', 'fa-eye-slash');
        } else {
            passwordInput.type = 'password';
            eyeIcon.classList.replace('fa-eye-slash', 'fa-eye');
        }
    }
</script>

</body>
</html>
