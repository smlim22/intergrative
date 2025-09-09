@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Leave Feedback for {{ $facility->name }}</h2>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form method="POST" action="{{ route('feedback.store', $facility->id) }}">
        @csrf
        <div class="mb-3">
            <label for="rating" class="form-label">Rating:</label><br>
            <span class="star-rating">
                        @for($i = 5; $i >= 1; $i--)
                            <input type="radio" name="rating" value="{{ $i }}" id="star{{ $i }}" required>
                            <label for="star{{ $i }}">&#9733;</label>
                        @endfor
            </span>
        </div>
        @auth
        <div class="mb-3">
            <label for="comment" class="form-label">Feedback:</label>
            <textarea name="comment" id="comment" class="form-control" rows="3"></textarea>
        </div>
        @endauth
        <button type="submit" class="btn btn-primary">Submit</button>
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
