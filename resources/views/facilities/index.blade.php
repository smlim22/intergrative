@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Facilities</h1>
    
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <form method="GET" action="{{ route('facilities.index') }}" class="row g-3 mb-4">
    <div class="col-md-4">
        <input type="text" name="search" class="form-control" placeholder="Search by name..." value="{{ request('search') }}">
    </div>
    <div class="col-md-4">
        <select name="category" class="form-control">
            <option value="">All Categories</option>
            @foreach($categories as $category)
                <option value="{{ $category }}" {{ request('category') == $category ? 'selected' : '' }}>{{ $category }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-4">
        <button type="submit" class="btn btn-primary">Search / Filter</button>
    </div>
</form>

    <table class="table">
        <thead>
            <tr>
                <th>Name</th><th>Category</th><th>Description</th><th>Hourly</th><th>Half Day</th><th>Full Day</th><th>Per Use</th><th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($facilities as $facility)
                <tr>
                    <td>{{ $facility->name }}</td>
                    <td>{{ $facility->category }}</td>
                    <td>
                        @if($facility->description)
                            <span data-bs-toggle="tooltip" data-bs-placement="top" title="{{ $facility->description }}">
                                {{ Str::limit($facility->description, 50) }}
                            </span>
                        @else
                            <span class="text-muted">No description</span>
                        @endif
                    </td>
                    <td>{{ $facility->hourly_rate ?? '-' }}</td>
                    <td>{{ $facility->half_day_rate ?? '-' }}</td>
                    <td>{{ $facility->full_day_rate ?? '-' }}</td>
                    <td>{{ $facility->per_use_rate ?? '-' }}</td>
                    <td>
                        <div class="btn-group" role="group">
                            <a href="{{ route('facilities.edit', $facility) }}" class="btn btn-sm btn-outline-primary">
                                Edit
                            </a>
                            <form action="{{ route('facilities.destroy', $facility) }}" method="POST" class="d-inline" 
                                  onsubmit="return confirm('Are you sure you want to delete this facility?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                    Delete
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>


    <div class="col-md-4">
        <a href="{{ route('facilities.create') }}" class="btn btn-primary">Add New Facility</a>
    </div>



</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Bootstrap tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
</script>
@endsection
