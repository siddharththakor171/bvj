@extends('layouts.admin')

@section('title', 'Category Management')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Category Management</h1>
        <p class="page-subtitle">Create and maintain the categories available when adding jewellery products.</p>
    </div>
</div>

<div style="display: grid; grid-template-columns: minmax(280px, .8fr) minmax(0, 1.6fr); gap: 1.5rem; align-items: start;">
    <section class="gold-card" style="padding: 1.25rem;">
        <h2 style="margin: 0 0 1rem; font-size: 1.15rem;">Create Category</h2>
        <form method="POST" action="{{ route('admin.categories.store') }}">
            @csrf
            <div class="form-group">
                <label class="form-label" for="category-name">Name</label>
                <input id="category-name" name="name" value="{{ old('name') }}" class="form-control" maxlength="255" required autofocus>
                @error('name')<div style="color: #dc2626; font-size: .8rem; margin-top: .35rem;">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label class="form-label" for="category-description">Description <span style="color: var(--text-muted);">(optional)</span></label>
                <textarea id="category-description" name="description" class="form-control" rows="4">{{ old('description') }}</textarea>
            </div>
            <button class="btn-gold" type="submit">Create Category</button>
        </form>
    </section>

    <section class="gold-card">
        <div class="table-responsive">
            <table class="luxury-table">
                <thead><tr><th>Category</th><th>Products</th><th>Description</th><th style="text-align: right;">Actions</th></tr></thead>
                <tbody>
                    @forelse($categories as $category)
                        <tr>
                            <td><strong>{{ $category->name }}</strong></td>
                            <td>{{ $category->products_count }}</td>
                            <td>{{ $category->description ?: '—' }}</td>
                            <td style="text-align: right;">
                                <details style="display: inline-block; text-align: left; margin-right: .5rem;">
                                    <summary class="btn-outline-gold btn-sm" style="cursor: pointer;">Edit</summary>
                                    <form method="POST" action="{{ route('admin.categories.update', $category) }}" style="padding: 1rem 0; min-width: 250px;">
                                        @csrf
                                        @method('PUT')
                                        <input name="name" value="{{ $category->name }}" class="form-control" maxlength="255" required style="margin-bottom: .5rem;">
                                        <textarea name="description" class="form-control" rows="3" style="margin-bottom: .5rem;">{{ $category->description }}</textarea>
                                        <button class="btn-gold btn-sm" type="submit">Save</button>
                                    </form>
                                </details>
                                <form method="POST" action="{{ route('admin.categories.destroy', $category) }}" style="display: inline;" onsubmit="return confirm('Delete this empty category?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn-outline-gold btn-sm" type="submit" {{ $category->products_count ? 'disabled' : '' }}>Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" style="text-align: center; padding: 2rem;">No categories have been created.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
</div>
@endsection
