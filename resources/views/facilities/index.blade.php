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
                <td>{{ $facility->name }}</td>
                <td>{{ $facility->category }}</td>
                <td>
                    @if($facility->description)
                        <span data-bs-toggle="tooltip" data-bs-placement="top" 
                              title="{{ $facility->description }}" style="cursor: help;">
                            {{ Str::limit($facility->description, 30) }}
                        </span>
                    @else
                        -
                    @endif
                </td>
                <td>{{ $facility->capacity ?? '-' }}</td>
                <td>{{ $facility->hourly_rate ? 'RM' . number_format($facility->hourly_rate, 2) : '-' }}</td>
                <td>{{ $facility->half_day_rate ? 'RM' . number_format($facility->half_day_rate, 2) : '-' }}</td>
                <td>{{ $facility->full_day_rate ? 'RM' . number_format($facility->full_day_rate, 2) : '-' }}</td>
                <td>{{ $facility->per_use_rate ? 'RM' . number_format($facility->per_use_rate, 2) : '-' }}</td>
                <td>
                    <div class="btn-group" role="group">
                        <a href="{{ route('facilities.edit', $facility) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                        <form action="{{ route('facilities.destroy', $facility) }}" method="POST" 
                              onsubmit="return confirm('Are you sure you want to delete this facility?')" 
                              style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                        </form>
                    </div>
                            <a href="{{ url('/facilities/' . $facility->id . '/feedback') }}" class="btn btn-info btn-sm mt-1">Reviews & Ratings</a>
                </td>
            </tr>
        </thead>
        <tbody>
            @forelse($facilities as $facility)
                <tr class="{{ $facility->isDisabled() ? 'table-secondary' : '' }}">
                    <td>
                        <strong>{{ $facility->name }}</strong>
                        @if($facility->isDisabled())
                            <span class="badge bg-danger ms-2">Disabled</span>
                        @endif
                    </td>
                    <td>{!! $facility->category !!}</td>
                    <td>
                        @if($facility->description)
                            <span data-bs-toggle="tooltip" data-bs-placement="top" 
                                  title="{{ $facility->description }}" style="cursor: help;">
                                {{ Str::limit($facility->description, 40) }}
                            </span>
                        @else
                            <em class="text-muted">No description</em>
                        @endif
                    </td>
                    <td>{!! $facility->getCapacityDisplay() !!}</td>
                    <td>{{ $facility->getFormattedPricing() }}</td>
                    <td>
                        <div class="btn-group" role="group">
                            <a href="{{ route('facilities.edit', $facility) }}" 
                               class="btn btn-sm btn-outline-primary">
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