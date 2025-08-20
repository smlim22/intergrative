@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Edit Facility</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('facilities.update', $facility) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="name" class="form-label">Facility Name <span class="text-danger">*</span></label>
            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                   value="{{ old('name', $facility->name) }}" required>
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="category" class="form-label">Category <span class="text-danger">*</span></label>
            <input type="text" name="category" class="form-control @error('category') is-invalid @enderror" 
                   value="{{ old('category', $facility->category) }}" required>
            @error('category')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea name="description" class="form-control @error('description') is-invalid @enderror" 
                      rows="3">{{ old('description', $facility->description) }}</textarea>
            @error('description')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="card mb-3">
            <div class="card-header">
                <h5 class="mb-0">Pricing Rates</h5>
            </div>
            <div class="card-body">
                @error('pricing')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="hourly_rate" class="form-label">Hourly Rate (RM)</label>
                            <input type="number" step="0.01" name="hourly_rate" 
                                   class="form-control @error('hourly_rate') is-invalid @enderror" 
                                   value="{{ old('hourly_rate', $facility->hourly_rate) }}" min="0">
                            @error('hourly_rate')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="half_day_rate" class="form-label">Half Day Rate (RM)</label>
                            <input type="number" step="0.01" name="half_day_rate" 
                                   class="form-control @error('half_day_rate') is-invalid @enderror" 
                                   value="{{ old('half_day_rate', $facility->half_day_rate) }}" min="0">
                            @error('half_day_rate')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="full_day_rate" class="form-label">Full Day Rate (RM)</label>
                            <input type="number" step="0.01" name="full_day_rate" 
                                   class="form-control @error('full_day_rate') is-invalid @enderror" 
                                   value="{{ old('full_day_rate', $facility->full_day_rate) }}" min="0">
                            @error('full_day_rate')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="per_use_rate" class="form-label">Per Use Rate (RM)</label>
                            <input type="number" step="0.01" name="per_use_rate" 
                                   class="form-control @error('per_use_rate') is-invalid @enderror" 
                                   value="{{ old('per_use_rate', $facility->per_use_rate) }}" min="0">
                            @error('per_use_rate')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                <small class="text-muted">
                    Note: Must provide at least one pricing rate.
                </small>
            </div>
        </div>

        <div class="mb-3">
            <button type="submit" class="btn btn-primary">Update Facility</button>
            <a href="{{ route('facilities.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection
