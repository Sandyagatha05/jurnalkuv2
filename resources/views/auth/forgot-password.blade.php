<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Forgot Password - {{ config('app.name') }}</title>
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

        .forgot-wrapper {
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
        .forgot-card {
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

        .info-text {
            font-size: 0.9rem;
            color: #6c757d;
        }

        /* Button */
        .btn-reset {
            background-color: var(--primary);
            border-color: var(--primary);
            transition: all 0.25s ease;
        }

        .btn-reset:hover {
            background-color: #14284d;
            transform: translateY(-2px);
            box-shadow: 0 8px 18px rgba(25, 51, 102, 0.25);
        }

        .small-text {
            font-size: 0.85rem;
        }

        .login-link {
            color: #2385e7ff;
            text-decoration: none;
            font-weight: 500;
        }

        .login-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="forgot-wrapper">

    <!-- Brand -->
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
    <div class="forgot-card">

        <div class="info-text mb-3 text-start">
            Forgot your password? No problem.  
            Just enter your email address and we will send you a password reset link.
        </div>

        <!-- Session Status -->
        @if (session('status'))
            <div class="alert alert-success small">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <!-- Email -->
            <div class="mb-4 text-start">
                <label for="email" class="form-label">Email</label>
                <input
                    id="email"
                    type="email"
                    name="email"
                    class="form-control @error('email') is-invalid @enderror"
                    value="{{ old('email') }}"
                    required
                    autofocus
                >
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Submit -->
            <div class="d-grid">
                <button type="submit" class="btn btn-reset text-white">
                    Email Password Reset Link
                </button>
            </div>
        </form>

        <!-- Back to login -->
        <div class="mt-3 small-text text-center">
            Remember your password?
            <a href="{{ route('login') }}" class="login-link">
                Log in
            </a>
        </div>

    </div>
</div>

</body>
</html>
