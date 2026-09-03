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
        <h3 style="font-size: 1.15rem; color: #1f1c18; margin-bottom: 0.25rem;">Administrator Profile</h3>
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
        <h3 style="font-size: 1.15rem; color: var(--gold-primary); margin-bottom: 0.25rem; font-weight: 700;">Jeweller Business Certificate</h3>
        <p style="font-size: 0.78rem; color: var(--text-muted); margin-bottom: 1.5rem;">BIS Hallmark and statutory registration</p>

        <form method="POST" action="{{ route('admin.profile.certificate.update') }}">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label class="form-label">Establishment</label>
                <input type="text" name="establishment" value="{{ old('establishment', $storeSetting->establishment) }}" class="form-control" required>
            </div>
            <div class="form-group">
                <label class="form-label">Tagline</label>
                <input type="text" name="tagline" value="{{ old('tagline', $storeSetting->tagline) }}" class="form-control" required>
            </div>
            <div class="form-group">
                <label class="form-label">BIS Hallmark Certificate</label>
                <input type="text" name="bis_certificate" value="{{ old('bis_certificate', $storeSetting->bis_certificate) }}" class="form-control" required>
            </div>
            <div class="form-group">
                <label class="form-label">BIS Verification Note</label>
                <input type="text" name="bis_note" value="{{ old('bis_note', $storeSetting->bis_note) }}" class="form-control" required>
            </div>
            <div class="form-group">
                <label class="form-label">GSTIN</label>
                <input type="text" name="gstin" value="{{ old('gstin', $storeSetting->gstin) }}" class="form-control" required>
            </div>
            <div class="form-group">
                <label class="form-label">GST Note</label>
                <input type="text" name="gst_note" value="{{ old('gst_note', $storeSetting->gst_note) }}" class="form-control" required>
            </div>
            <button type="submit" class="btn-gold">Save Certificate Details</button>
        </form>
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
