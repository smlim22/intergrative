@extends('layouts.app')


@section('content')
<div class="container">
    <h1>Facility & Resource Management</h1>
   
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif


    <!-- Search and Filter Form (Include Status Filter) -->
    <form method="GET" action="{{ route('facilities.index') }}" class="row g-3 mb-4">
        <div class="col-md-3">
            <input type="text" name="search" class="form-control"
                   placeholder="Search facility name..." value="{{ request('search') }}">
        </div>
        <div class="col-md-3">
            <select name="category" class="form-control">
                <option value="">All Categories</option>
                @foreach($categories as $category)
                    <option value="{{ $category }}" {{ request('category') == $category ? 'selected' : '' }}>
                        {!! $category !!}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <select name="status" class="form-control">
                <option value="">All Status</option>
                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                <option value="disabled" {{ request('status') == 'disabled' ? 'selected' : '' }}>Disabled</option>
            </select>
        </div>
        <div class="col-md-3">
            <button type="submit" class="btn btn-primary">Search</button>
        </div>
    </form>


    <table class="table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Category</th>
                <th>Description</th>
                <th>Capacity</th>
                <th>Pricing</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($facilities as $facility)
                <tr class="{{ $facility->isDisabled() ? 'table-secondary disabled-facility' : '' }}">
                    <td>
                        <strong>{{ $facility->name }}</strong>
                        @if($facility->isDisabled())
                            <span class="badge bg-danger ms-2">Disabled</span>
                        @endif
                    </td>
                    <td class="{{ $facility->isDisabled() ? 'text-muted' : '' }}">
                        {!! $facility->category !!}
                    </td>
                    <td class="{{ $facility->isDisabled() ? 'text-muted' : '' }}">
                        @if($facility->description)
                            <span data-bs-toggle="tooltip" data-bs-placement="top"
                                  title="{{ $facility->description }}" style="cursor: help;">
                                {{ Str::limit($facility->description, 40) }}
                            </span>
                        @else
                            <em class="text-muted">No description</em>
                        @endif
                    </td>
                    <td class="{{ $facility->isDisabled() ? 'text-muted' : '' }}">
                        {!! $facility->getCapacityDisplay() !!}
                    </td>
                    <td class="{{ $facility->isDisabled() ? 'text-muted' : '' }}">
                        {{ $facility->getFormattedPricing() }}
                    </td>
                    <td>
                        <div class="btn-group" role="group">
                            <a href="{{ route('facilities.edit', $facility) }}"
                               class="btn btn-sm btn-outline-primary {{ $facility->isDisabled() ? 'opacity-75' : '' }}">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                           
                            @if($facility->isActive())
                                <form action="{{ route('facilities.disable', $facility) }}" method="POST"
                                      onsubmit="return confirm('Are you sure you want to disable this facility?')"
                                      style="display: inline;">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="fas fa-ban"></i> Disable
                                    </button>
                                </form>
                            @else
                                <form action="{{ route('facilities.enable', $facility) }}" method="POST"
                                      onsubmit="return confirm('Are you sure you want to enable this facility?')"
                                      style="display: inline;">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-sm btn-success">
                                        <i class="fas fa-check"></i> Enable
                                    </button>
                                </form>
                            @endif
                        </div>
                        <a href="{{ url(path: '/facilities/' . $facility->id . '/feedback') }}" 
                           class="btn btn-info btn-sm mt-1 {{ $facility->isDisabled() ? 'opacity-75' : '' }}">
                            Reviews & Ratings
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center text-muted">
                        <p>No facilities found.</p>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>


    <div class="mt-3">
        <a href="{{ route('facilities.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add New Facility
        </a>
    </div>
</div>
@endsection


@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
</script>
@endsection
@section('styles')
<style>
.disabled-facility {
    background-color: #f8f9fa !important;
    opacity: 0.8;
}

.disabled-facility td {
    background-color: #e9ecef !important;
}

.disabled-facility:hover {
    background-color: #e2e6ea !important;
}

.disabled-facility:hover td {
    background-color: #dee2e6 !important;
}

/* Optional: Add strikethrough effect for disabled facility names */
.disabled-facility .facility-name-disabled {
    text-decoration: line-through;
    color: #6c757d !important;
}

/* Make buttons slightly transparent for disabled facilities */
.disabled-facility .opacity-75 {
    opacity: 0.75 !important;
}
</style>
@endsection