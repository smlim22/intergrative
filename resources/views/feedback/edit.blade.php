@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Edit Your Feedback for {{ $facility->name }}</h2>
    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form method="POST" action="{{ route('feedback.update', [$facility->id, $feedback->id]) }}">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="rating" class="form-label">Rating:</label><br>
            <span class="star-rating">
                @for($i = 5; $i >= 1; $i--)
                    <input type="radio" name="rating" value="{{ $i }}" id="star{{ $i }}" {{ $feedback->rating == $i ? 'checked' : '' }} required>
                    <label for="star{{ $i }}">&#9733;</label>
                @endfor
            </span>
        </div>
        <div class="mb-3">
            <label for="comment" class="form-label">Feedback:</label>
            <textarea name="comment" id="comment" class="form-control" rows="3">{{ $feedback->comment }}</textarea>
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
    </form>
</div>
<style>
.star-rating input[type="radio"] {
    display: none;
}
.star-rating {
    display: flex;
    flex-direction: row-reverse;
    justify-content: flex-end;
}
.star-rating label {
    font-size: 2em;
    color: #ccc;
    cursor: pointer;
}
.star-rating input[type="radio"]:checked ~ label {
    color: #fd4;
}
.star-rating label:hover,
.star-rating label:hover ~ label {
    color: #fd4;
}
</style>
@endsection
