<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Confirm Password - {{ config('app.name') }}</title>
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

        .confirm-wrapper {
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
        .confirm-card {
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
        .btn-confirm {
            background-color: var(--primary);
            border-color: var(--primary);
            transition: all 0.25s ease;
        }

        .btn-confirm:hover {
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
    </style>
</head>
<body>

<div class="confirm-wrapper">

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
    <div class="confirm-card">

        <div class="info-text mb-4 text-start">
            This is a secure area of the application.  
            Please confirm your password before continuing.
        </div>

        <form method="POST" action="{{ route('password.confirm') }}">
            @csrf

            <!-- Password -->
            <div class="mb-4 text-start">
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

            <!-- Submit -->
            <div class="d-grid">
                <button type="submit" class="btn btn-confirm text-white">
                    Confirm
                </button>
            </div>
        </form>

    </div>
</div>

<script>
    function togglePassword() {
        const input = document.getElementById('password');
        const icon = document.getElementById('eyeIcon');

        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.replace('fa-eye', 'fa-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.replace('fa-eye-slash', 'fa-eye');
        }
    }
</script>

</body>
</html>
