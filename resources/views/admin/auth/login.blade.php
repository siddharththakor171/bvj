<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Portal Login | B V JEWELLERS</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@500;600;700;800&family=Playfair+Display:ital,wght@0,500;0,600;0,700;1,400&family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        .login-page {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1rem;
            position: relative;
            background: radial-gradient(circle at center, #141a26 0%, #080a0e 100%);
        }

        .login-card {
            width: 100%;
            max-width: 440px;
            background: rgba(18, 23, 33, 0.92);
            border: 1px solid rgba(223, 183, 108, 0.25);
            border-radius: 16px;
            padding: 2.5rem 2.25rem;
            box-shadow: 0 25px 60px rgba(0, 0, 0, 0.8), 0 0 30px rgba(223, 183, 108, 0.1);
            backdrop-filter: blur(20px);
            position: relative;
            z-index: 10;
        }

        .login-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 20%;
            right: 20%;
            height: 2px;
            background: linear-gradient(90deg, transparent, #dfb76c, transparent);
        }

        .login-brand {
            text-align: center;
            margin-bottom: 2rem;
        }

        .brand-emblem {
            width: 54px;
            height: 54px;
            margin: 0 auto 1rem;
            background: var(--gold-gradient);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #0b0d11;
            font-family: var(--font-heading);
            font-size: 1.6rem;
            font-weight: 800;
            box-shadow: 0 0 25px rgba(223, 183, 108, 0.4);
        }

        .login-brand h1 {
            font-size: 1.45rem;
            color: #fff;
            letter-spacing: 0.1em;
            margin-bottom: 0.25rem;
        }

        .login-brand p {
            font-size: 0.75rem;
            color: var(--gold-primary);
            letter-spacing: 0.15em;
            text-transform: uppercase;
        }

        .demo-credentials-box {
            background: rgba(223, 183, 108, 0.08);
            border: 1px dashed rgba(223, 183, 108, 0.35);
            border-radius: 8px;
            padding: 0.75rem 1rem;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .demo-text {
            font-size: 0.78rem;
            color: var(--gold-light);
        }

        .demo-text strong {
            color: #fff;
        }

        .fill-demo-btn {
            background: rgba(223, 183, 108, 0.2);
            border: 1px solid var(--gold-primary);
            color: #fff;
            padding: 0.25rem 0.6rem;
            border-radius: 4px;
            font-size: 0.72rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
        }

        .fill-demo-btn:hover {
            background: var(--gold-primary);
            color: #0b0d11;
        }

        .password-field-wrapper {
            position: relative;
        }

        .password-toggle-btn {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: transparent;
            border: none;
            color: var(--text-muted);
            cursor: pointer;
            padding: 0.25rem;
        }

        .password-toggle-btn:hover {
            color: var(--gold-primary);
        }
    </style>
</head>
<body class="login-page">

<div class="login-card">
    <div class="login-brand">
        <div class="brand-emblem">BV</div>
        <h1>B V JEWELLERS</h1>
        <p>Vault & Administration Portal</p>
    </div>

    <!-- Demo Credentials Quick-Fill Banner -->
    <div class="demo-credentials-box">
        <div class="demo-text">
            Default Access: <strong>admin</strong> / <strong>admin</strong>
        </div>
        <button type="button" class="fill-demo-btn" onclick="fillDemoCredentials()">Auto-Fill</button>
    </div>

    <!-- Error Messages -->
    @if($errors->any())
        <div class="alert alert-danger" style="margin-bottom: 1.25rem;">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"></circle>
                <line x1="12" y1="8" x2="12" y2="12"></line>
                <line x1="12" y1="16" x2="12.01" y2="16"></line>
            </svg>
            <span>{{ $errors->first() }}</span>
        </div>
    @endif

    @if(session('info'))
        <div class="alert alert-info" style="margin-bottom: 1.25rem;">
            <span>{{ session('info') }}</span>
        </div>
    @endif

    <!-- Login Form -->
    <form method="POST" action="{{ route('login.submit') }}">
        @csrf

        <div class="form-group">
            <label class="form-label" for="loginInput">Username or Email</label>
            <input 
                type="text" 
                id="loginInput" 
                name="login" 
                class="form-control" 
                value="{{ old('login', 'admin') }}" 
                placeholder="Enter username (e.g. admin)" 
                required 
                autofocus
            >
        </div>

        <div class="form-group">
            <label class="form-label" for="passwordInput">Password</label>
            <div class="password-field-wrapper">
                <input 
                    type="password" 
                    id="passwordInput" 
                    name="password" 
                    class="form-control" 
                    value="admin"
                    placeholder="Enter password" 
                    required
                >
                <button type="button" class="password-toggle-btn" onclick="togglePasswordVisibility('passwordInput', 'toggleEyeIcon')">
                    <svg id="toggleEyeIcon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                        <circle cx="12" cy="12" r="3"></circle>
                    </svg>
                </button>
            </div>
        </div>

        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.5rem;">
            <label style="display: flex; align-items: center; gap: 0.5rem; color: var(--text-muted); font-size: 0.82rem; cursor: pointer;">
                <input type="checkbox" name="remember" checked style="accent-color: var(--gold-primary);">
                Remember this station
            </label>
        </div>

        <button type="submit" class="btn-gold" style="width: 100%; justify-content: center; padding: 0.8rem; font-size: 0.95rem;">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
            </svg>
            Enter Vault Admin
        </button>
    </form>

    <div style="text-align: center; margin-top: 1.75rem; font-size: 0.72rem; color: #64748b; letter-spacing: 0.05em;">
        &copy; {{ date('Y') }} B V JEWELLERS &bull; BIS 100% Hallmarked Jeweller
    </div>
</div>

<script>
    function fillDemoCredentials() {
        document.getElementById('loginInput').value = 'admin';
        document.getElementById('passwordInput').value = 'admin';
    }

    function togglePasswordVisibility(inputId, iconId) {
        const input = document.getElementById(inputId);
        const icon = document.getElementById(iconId);
        if (input.type === 'password') {
            input.type = 'text';
            icon.innerHTML = '<path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path><line x1="1" y1="1" x2="23" y2="23"></line>';
        } else {
            input.type = 'password';
            icon.innerHTML = '<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle>';
        }
    }
</script>

</body>
</html>
