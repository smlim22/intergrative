@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Add New Facility') }}</div>

                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('facilities.store') }}">
                        @csrf

                        <!-- Basic Information -->
                        <div class="mb-3">
                            <label for="name" class="form-label">
                                Facility Name <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="category" class="form-label">
                                Category <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control @error('category') is-invalid @enderror" 
                                   id="category" name="category" value="{{ old('category') }}" required>
                            @error('category')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="3" maxlength="1000">{{ old('description') }}</textarea>
                            <div class="form-text">Maximum 1000 characters</div>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="capacity" class="form-label">Capacity</label>
                            <input type="number" class="form-control @error('capacity') is-invalid @enderror" 
                                   id="capacity" name="capacity" value="{{ old('capacity') }}" min="1">
                            <div class="form-text">Maximum number of people (optional)</div>
                            @error('capacity')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Pricing Rates -->
                        <div class="card mb-3">
                            <div class="card-header">
                                <h6 class="mb-0">Pricing Rates (At least one rate is required)</h6>
                            </div>
                            <div class="card-body">
                                @if ($errors->has('pricing'))
                                    <div class="alert alert-danger">{{ $errors->first('pricing') }}</div>
                                @endif

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="hourly_rate" class="form-label">Hourly Rate (RM)</label>
                                        <input type="number" step="0.01" min="0" 
                                               class="form-control @error('hourly_rate') is-invalid @enderror" 
                                               id="hourly_rate" name="hourly_rate" value="{{ old('hourly_rate') }}">
                                        @error('hourly_rate')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="half_day_rate" class="form-label">Half Day Rate (RM)</label>
                                        <input type="number" step="0.01" min="0" 
                                               class="form-control @error('half_day_rate') is-invalid @enderror" 
                                               id="half_day_rate" name="half_day_rate" value="{{ old('half_day_rate') }}">
                                        @error('half_day_rate')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="full_day_rate" class="form-label">Full Day Rate (RM)</label>
                                        <input type="number" step="0.01" min="0" 
                                               class="form-control @error('full_day_rate') is-invalid @enderror" 
                                               id="full_day_rate" name="full_day_rate" value="{{ old('full_day_rate') }}">
                                        @error('full_day_rate')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="per_use_rate" class="form-label">Per Use Rate (RM)</label>
                                        <input type="number" step="0.01" min="0" 
                                               class="form-control @error('per_use_rate') is-invalid @enderror" 
                                               id="per_use_rate" name="per_use_rate" value="{{ old('per_use_rate') }}">
                                        <div class="form-text">For items like PA systems, equipment rental, etc.</div>
                                        @error('per_use_rate')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="{{ route('facilities.index') }}" class="btn btn-secondary me-md-2">Cancel</a>
                            <button type="submit" class="btn btn-primary">Save Facility</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection