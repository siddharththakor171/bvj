@extends('layouts.admin')

@section('title', 'Store Profile & Settings')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Store Information & Station Settings</h1>
        <p class="page-subtitle">Manage jeweller hallmark registration credentials and station administrative details.</p>
    </div>
</div>

<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.75rem;" class="profile-grid">
    <!-- Admin User Profile Form -->
    <div class="gold-card">
        <h3 style="font-size: 1.15rem; color: #fff; margin-bottom: 0.25rem;">Administrator Profile</h3>
        <p style="font-size: 0.78rem; color: var(--text-muted); margin-bottom: 1.5rem;">Update operator profile credentials</p>

        <form method="POST" action="{{ route('admin.profile.update') }}">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label class="form-label">Full Name</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" class="form-control" required>
            </div>

            <div class="form-group">
                <label class="form-label">Username</label>
                <input type="text" name="username" value="{{ old('username', $user->username) }}" class="form-control" required>
            </div>

            <div class="form-group">
                <label class="form-label">Email Address</label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" class="form-control" required>
            </div>

            <div class="form-group">
                <label class="form-label">Phone Number</label>
                <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" class="form-control">
            </div>

            <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 1.5rem;">
                <a href="{{ route('admin.password.change') }}" class="btn-outline-gold">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                    Change Password
                </a>
                <button type="submit" class="btn-gold">Save Changes</button>
            </div>
        </form>
    </div>

    <!-- Store & Jeweller Compliance Details -->
    <div class="gold-card">
        <h3 style="font-size: 1.15rem; color: var(--gold-light); margin-bottom: 0.25rem;">Jeweller Business Certificate</h3>
        <p style="font-size: 0.78rem; color: var(--text-muted); margin-bottom: 1.5rem;">BIS Hallmark and statutory registration</p>

        <div style="display: flex; flex-direction: column; gap: 1rem; font-size: 0.88rem;">
            <div style="background: rgba(10,12,16,0.6); padding: 1rem; border-radius: 8px; border: 1px solid var(--border-subtle);">
                <div style="font-size: 0.72rem; color: var(--gold-primary); text-transform: uppercase; letter-spacing: 0.08em; font-weight: 700;">Establishment</div>
                <div style="font-size: 1.1rem; font-weight: 700; color: #fff; font-family: var(--font-heading); margin-top: 0.2rem;">B V JEWELLERS</div>
                <div style="font-size: 0.78rem; color: var(--text-muted);">Crafting Timeless Elegance & Bullion Trust Since 1984</div>
            </div>

            <div style="background: rgba(10,12,16,0.6); padding: 1rem; border-radius: 8px; border: 1px solid var(--border-subtle);">
                <div style="font-size: 0.72rem; color: var(--gold-primary); text-transform: uppercase; letter-spacing: 0.08em; font-weight: 700;">BIS Hallmark Certificate</div>
                <div style="font-size: 0.95rem; font-weight: 600; color: #fff; font-family: monospace; margin-top: 0.2rem;">HM-IND-2026-928810-BVJ</div>
                <div style="font-size: 0.75rem; color: var(--color-success); margin-top: 0.2rem;">&bull; 100% HUID Laser Verified & Bullion Assay Approved</div>
            </div>

            <div style="background: rgba(10,12,16,0.6); padding: 1rem; border-radius: 8px; border: 1px solid var(--border-subtle);">
                <div style="font-size: 0.72rem; color: var(--gold-primary); text-transform: uppercase; letter-spacing: 0.08em; font-weight: 700;">GSTIN & Tax Registration</div>
                <div style="font-size: 0.95rem; font-weight: 600; color: #fff; font-family: monospace; margin-top: 0.2rem;">27AAAAA0000A1Z5</div>
                <div style="font-size: 0.75rem; color: var(--text-muted); margin-top: 0.2rem;">Jewellery CGST 1.5% + SGST 1.5%</div>
            </div>
        </div>
    </div>
</div>

<style>
@media (max-width: 768px) {
    .profile-grid {
        grid-template-columns: 1fr !important;
    }
}
</style>
@endsection
