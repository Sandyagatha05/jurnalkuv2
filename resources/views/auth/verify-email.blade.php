<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Email Verification - {{ config('app.name') }}</title>
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

        .verify-wrapper {
            width: 100%;
            max-width: 480px;
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
        .verify-card {
            background: #ffffff;
            padding: 2rem;
            border-radius: 0.9rem;
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.08);
            margin-top: 1.75rem;
        }

        .btn-primary-custom {
            background-color: var(--primary);
            border-color: var(--primary);
            transition: all 0.25s ease;
        }

        .btn-primary-custom:hover {
            background-color: #14284d;
            transform: translateY(-2px);
            box-shadow: 0 8px 18px rgba(25, 51, 102, 0.25);
        }

        .small-text {
            font-size: 0.9rem;
        }
    </style>
</head>
<body>

<div class="verify-wrapper">

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
    <div class="verify-card">

        <div class="mb-3 text-muted small-text">
            Thanks for signing up! Before getting started, please verify your email address
            by clicking the link we just sent to your email.
            <br><br>
            If you didn’t receive the email, you can request another verification link.
        </div>

        @if (session('status') == 'verification-link-sent')
            <div class="alert alert-success small">
                A new verification link has been sent to your email address.
            </div>
        @endif

        <div class="d-flex justify-content-between align-items-center mt-4">

            <!-- Resend -->
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button type="submit" class="btn btn-primary-custom text-white">
                    Resend Email
                </button>
            </form>

            <!-- Logout -->
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-link text-decoration-none small-text">
                    Log out
                </button>
            </form>

        </div>

    </div>
</div>

</body>
</html>
