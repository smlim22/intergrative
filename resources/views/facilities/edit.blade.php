@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Edit Facility: {{ $facility->name }}</h1>

    <form action="{{ route('facilities.update', $facility) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="name" class="form-label">Facility Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                           id="name" name="name" value="{{ old('name', $facility->name) }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="col-md-6">
                <div class="mb-3">
                    <label for="category" class="form-label">Category <span class="text-danger">*</span></label>
                    <div class="category-selector">
                        <!-- Dropdown for existing categories -->
                        <select class="form-control @error('category') is-invalid @enderror" 
                                id="categoryDropdown" name="category_dropdown" onchange="handleCategoryChange()">
                            <option value="">-- Select Existing Category --</option>
                            @foreach($existingCategories as $category)
                                <option value="{{ $category }}" 
                                    {{ (old('category', html_entity_decode($facility->category)) == html_entity_decode($category)) ? 'selected' : '' }}>
                                    {!! html_entity_decode($category) !!}
                                </option>
                            @endforeach
                            <option value="__new__">+ Add New Category</option>
                        </select>
                        
                        <!-- Text input for new category (hidden by default) -->
                        <input type="text" class="form-control mt-2 @error('category') is-invalid @enderror" 
                               id="categoryInput" name="category" 
                               value="{{ old('category', html_entity_decode($facility->category)) }}" 
                               placeholder="Enter new category name" 
                               style="display: none;" required>
                    </div>
                    @error('category')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="form-text text-muted"><i>*Select from existing categories or add a new one</i></small>
                </div>
            </div>
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea class="form-control @error('description') is-invalid @enderror" 
                      id="description" name="description" rows="3">{{ old('description', $facility->description) }}</textarea>
            @error('description')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-4">
            <label for="capacity" class="form-label">Capacity</label>
            <input type="number" class="form-control @error('capacity') is-invalid @enderror" 
                   id="capacity" name="capacity" value="{{ old('capacity', $facility->capacity) }}" min="1">
            @error('capacity')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Pricing Section -->
        <div class="card mb-3">
            <div class="card-header">
                <h5>Pricing Information</h5>
                <small class="text-muted"><i>*Provide at least one pricing rate</i></small>
            </div>
            <div class="card-body">
                @error('pricing')
                    <div class="alert alert-danger mb-3">
                        <i class="fas fa-exclamation-triangle"></i> {{ $message }}
                    </div>
                @enderror
                <div class="row">
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="hourly_rate" class="form-label">Hourly Rate (RM)</label>
                            <input type="number" class="form-control @error('hourly_rate') is-invalid @enderror" 
                                   id="hourly_rate" name="hourly_rate" value="{{ old('hourly_rate', $facility->hourly_rate) }}" 
                                   step="0.01" min="0">
                            @error('hourly_rate')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="half_day_rate" class="form-label">Half Day Rate (RM)</label>
                            <input type="number" class="form-control @error('half_day_rate') is-invalid @enderror" 
                                   id="half_day_rate" name="half_day_rate" value="{{ old('half_day_rate', $facility->half_day_rate) }}" 
                                   step="0.01" min="0">
                            @error('half_day_rate')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="full_day_rate" class="form-label">Full Day Rate (RM)</label>
                            <input type="number" class="form-control @error('full_day_rate') is-invalid @enderror" 
                                   id="full_day_rate" name="full_day_rate" value="{{ old('full_day_rate', $facility->full_day_rate) }}" 
                                   step="0.01" min="0">
                            @error('full_day_rate')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="per_use_rate" class="form-label">Per Use Rate (RM)</label>
                            <input type="number" class="form-control @error('per_use_rate') is-invalid @enderror" 
                                   id="per_use_rate" name="per_use_rate" value="{{ old('per_use_rate', $facility->per_use_rate) }}" 
                                   step="0.01" min="0">
                            @error('per_use_rate')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-between">
            <a href="{{ route('facilities.index') }}" class="btn btn-secondary">Cancel</a>
            <button type="submit" class="btn btn-primary">Update Facility</button>
        </div>
    </form>
</div>

<script>
function handleCategoryChange() {
    const dropdown = document.getElementById('categoryDropdown');
    const input = document.getElementById('categoryInput');
    
    if (dropdown.value === '__new__') {
        // Show input for new category
        dropdown.style.display = 'none';
        input.style.display = 'block';
        input.focus();
        input.required = true;
        dropdown.required = false;
        
        // Add button to go back to dropdown
        if (!document.getElementById('backToDropdown')) {
            const backButton = document.createElement('button');
            backButton.type = 'button';
            backButton.id = 'backToDropdown';
            backButton.className = 'btn btn-sm btn-outline-secondary mt-2';
            backButton.innerHTML = '← Back to Categories';
            backButton.onclick = function() {
                dropdown.style.display = 'block';
                input.style.display = 'none';
                input.value = "{{ old('category', html_entity_decode($facility->category)) }}";
                input.required = false;
                dropdown.required = true;
                dropdown.value = '{{ html_entity_decode($facility->category) }}';
                this.remove();
            };
            input.parentNode.appendChild(backButton);
        }
    } else if (dropdown.value !== '') {
        // Set selected category
        input.value = dropdown.value;
        input.required = true;
        dropdown.required = false;
    } else {
        // No selection
        input.value = '';
        input.required = true;
        dropdown.required = false;
    }
}

// Handle form submission to ensure category value is set correctly
document.querySelector('form').addEventListener('submit', function(e) {
    const dropdown = document.getElementById('categoryDropdown');
    const input = document.getElementById('categoryInput');
    
    if (dropdown.style.display !== 'none' && dropdown.value && dropdown.value !== '__new__') {
        input.value = dropdown.value;
    }
});

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    const input = document.getElementById('categoryInput');
    const dropdown = document.getElementById('categoryDropdown');
    const currentCategory = "{{ old('category', html_entity_decode($facility->category)) }}";
    
    // Check if current category exists in dropdown
    const categoryExists = Array.from(dropdown.options).some(option => option.value === currentCategory);
    
    if (currentCategory && !categoryExists) {
        // If current category is not in dropdown (new category), show input
        dropdown.value = '__new__';
        handleCategoryChange();
        input.value = currentCategory;
    }
});
</script>
@endsection