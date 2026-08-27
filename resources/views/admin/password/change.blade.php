@extends('layouts.admin')

@section('title', 'Change Password')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Change Password & Security</h1>
        <p class="page-subtitle">Update your master security credentials for B V JEWELLERS Admin Portal.</p>
    </div>
    <div>
        <a href="{{ route('admin.dashboard') }}" class="btn-outline-gold">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="19" y1="12" x2="5" y2="12"></line>
                <polyline points="12 19 5 12 12 5"></polyline>
            </svg>
            Back to Dashboard
        </a>
    </div>
</div>

<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 1.75rem; align-items: start;">
    <!-- Change Password Form Card -->
    <div class="gold-card">
        <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1.5rem; padding-bottom: 1rem; border-bottom: 1px solid var(--border-color);">
            <div class="stat-icon-wrapper" style="width: 40px; height: 40px; font-size: 1.1rem;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                    <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                </svg>
            </div>
            <div>
                <h2 style="font-size: 1.15rem; color: #1f1c18;">Update Master Password</h2>
                <p style="font-size: 0.78rem; color: var(--text-muted);">Logged in as: <strong style="color: var(--gold-primary);">{{ $user->username }}</strong> ({{ $user->email }})</p>
            </div>
        </div>

        @if($errors->any())
            <div class="alert alert-danger">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="12" y1="8" x2="12" y2="12"></line>
                    <line x1="12" y1="16" x2="12.01" y2="16"></line>
                </svg>
                <div>
                    @foreach($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.password.update') }}">
            @csrf

            <!-- Current Password -->
            <div class="form-group">
                <label class="form-label" for="current_password">Current Password</label>
                <div style="position: relative;">
                    <input 
                        type="password" 
                        id="current_password" 
                        name="current_password" 
                        class="form-control" 
                        placeholder="Enter current password (default: admin)"
                        required
                    >
                    <button type="button" class="password-toggle-btn" onclick="togglePassVisibility('current_password', 'currentEye')">
                        <svg id="currentEye" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                            <circle cx="12" cy="12" r="3"></circle>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- New Password -->
            <div class="form-group">
                <label class="form-label" for="new_password">New Password</label>
                <div style="position: relative;">
                    <input 
                        type="password" 
                        id="new_password" 
                        name="password" 
                        class="form-control" 
                        placeholder="Enter at least 6 characters" 
                        oninput="evaluatePasswordStrength(this.value)"
                        required
                    >
                    <button type="button" class="password-toggle-btn" onclick="togglePassVisibility('new_password', 'newEye')">
                        <svg id="newEye" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                            <circle cx="12" cy="12" r="3"></circle>
                        </svg>
                    </button>
                </div>

                <!-- Password Strength Visual Meter -->
                <div style="margin-top: 0.6rem;">
                    <div style="display: flex; align-items: center; justify-content: space-between; font-size: 0.72rem; margin-bottom: 0.25rem;">
                        <span style="color: var(--text-muted); font-weight: 600;">Strength:</span>
                        <span id="strengthText" style="font-weight: 700; color: #78716c;">Too Short</span>
                    </div>
                    <div style="width: 100%; height: 6px; background: #eae5da; border-radius: 4px; overflow: hidden;">
                        <div id="strengthBar" style="width: 0%; height: 100%; transition: width 0.3s, background-color 0.3s;"></div>
                    </div>
                </div>
            </div>

            <!-- Confirm New Password -->
            <div class="form-group">
                <label class="form-label" for="password_confirmation">Confirm New Password</label>
                <div style="position: relative;">
                    <input 
                        type="password" 
                        id="password_confirmation" 
                        name="password_confirmation" 
                        class="form-control" 
                        placeholder="Re-enter new password" 
                        required
                    >
                    <button type="button" class="password-toggle-btn" onclick="togglePassVisibility('password_confirmation', 'confirmEye')">
                        <svg id="confirmEye" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                            <circle cx="12" cy="12" r="3"></circle>
                        </svg>
                    </button>
                </div>
            </div>

            <div style="margin-top: 1.75rem;">
                <button type="submit" class="btn-gold" style="width: 100%; justify-content: center; padding: 0.75rem;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="20 6 9 17 4 12"></polyline>
                    </svg>
                    Save & Apply New Password
                </button>
            </div>
        </form>
    </div>

    <!-- Security Information & Guidelines Card -->
    <div style="display: flex; flex-direction: column; gap: 1.25rem;">
        <div class="gold-card" style="border-color: rgba(184, 134, 11, 0.35);">
            <h3 style="font-size: 1.05rem; color: var(--gold-primary); margin-bottom: 0.85rem; display: flex; align-items: center; gap: 0.5rem; font-weight: 700;">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                </svg>
                Jeweller Vault Security Guidelines
            </h3>
            <p style="font-size: 0.85rem; color: #44403c; line-height: 1.6; margin-bottom: 1rem;">
                The B V JEWELLERS administrative portal controls sensitive bullion inventory, live market pricing, and high-value customer order ledgers.
            </p>
            <ul style="list-style: none; display: flex; flex-direction: column; gap: 0.75rem; font-size: 0.82rem; color: #57534e;">
                <li style="display: flex; align-items: flex-start; gap: 0.5rem;">
                    <span style="color: var(--gold-primary); font-weight: bold;">&#10003;</span>
                    Use at least 8 characters with a blend of letters, numbers, and symbols.
                </li>
                <li style="display: flex; align-items: flex-start; gap: 0.5rem;">
                    <span style="color: var(--gold-primary); font-weight: bold;">&#10003;</span>
                    Do not share the master credentials on communal sales counter terminals.
                </li>
                <li style="display: flex; align-items: flex-start; gap: 0.5rem;">
                    <span style="color: var(--gold-primary); font-weight: bold;">&#10003;</span>
                    Update your password regularly after quarterly bullion inventory audits.
                </li>
            </ul>
        </div>

        <div class="gold-card" style="background: #fbf9f4;">
            <h4 style="font-size: 0.9rem; color: #1f1c18; margin-bottom: 0.5rem; font-weight: 700;">Need Assistance?</h4>
            <p style="font-size: 0.8rem; color: var(--text-muted); line-height: 1.5;">
                If you ever get locked out of your jewellery portal, run `php artisan db:seed` from your server console to reset back to default credentials (`admin`/`admin`).
            </p>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function togglePassVisibility(inputId, iconId) {
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

    function evaluatePasswordStrength(val) {
        const bar = document.getElementById('strengthBar');
        const text = document.getElementById('strengthText');
        
        let score = 0;
        if (val.length >= 6) score += 25;
        if (val.length >= 10) score += 25;
        if (/[A-Z]/.test(val) && /[a-z]/.test(val)) score += 25;
        if (/[0-9]/.test(val) || /[^A-Za-z0-9]/.test(val)) score += 25;

        bar.style.width = score + '%';

        if (score === 0) {
            text.textContent = 'Too Short';
            text.style.color = '#78716c';
            bar.style.backgroundColor = '#78716c';
        } else if (score <= 25) {
            text.textContent = 'Weak';
            text.style.color = '#dc2626';
            bar.style.backgroundColor = '#dc2626';
        } else if (score <= 50) {
            text.textContent = 'Fair';
            text.style.color = '#d97706';
            bar.style.backgroundColor = '#d97706';
        } else if (score <= 75) {
            text.textContent = 'Good';
            text.style.color = '#996515';
            bar.style.backgroundColor = '#996515';
        } else {
            text.textContent = 'Strong & Secure';
            text.style.color = '#059669';
            bar.style.backgroundColor = '#059669';
        }
    }
</script>
@endpush
@endsection
