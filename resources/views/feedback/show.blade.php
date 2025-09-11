@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Feedback for {{ $facility->name }}</h2>
    @if(isset($averageRating))
        <div class="mb-3">
            <strong>Average Rating:</strong>
            <span>
                @for($i = 1; $i <= 5; $i++)
                    <span style="color:{{ $i <= round($averageRating) ? '#fd4' : '#ccc' }}">&#9733;</span>
                @endfor
                ({{ $averageRating }} / 5)
            </span>
        </div>
    @endif
    @if($feedbacks->isEmpty())
        <p>No feedback yet.</p>
    @else
        <div class="row">
            @foreach($feedbacks as $feedback)
                <div class="col-md-6 mb-3">
                    <div class="card">
                        <div class="card-body">
                            <div>
                                <strong>Rating:</strong>
                                @for($i = 5; $i >= 1; $i--)
                                    <span style="color:{{ $i > 5 - $feedback->rating ? '#fd4' : '#ccc' }}">&#9733;</span>
                                @endfor
                            </div>
                            @if($feedback->comment)
                                <div><strong>Comment:</strong> {{ $feedback->comment }}</div>
                                @auth
                                    @if(auth()->user()->role && auth()->user()->role->name == 'admin')
                                        <div><strong>Sentiment:</strong> <span class="text-muted">{{ $sentiments[$feedback->id] ?? 'N/A' }}</span></div>
                                    @endif
                                @endauth
                            @endif
                            <div class="mt-2">
                                @if($feedback->user)
                                    <small>By: {{ $feedback->user->name }}</small>
                                @else
                                    <small>By: Public User</small>
                                @endif
                            </div>
                            @auth
                                @if(auth()->user()->role && auth()->user()->role->name == 'admin')
                                    <form action="{{ route('feedback.destroy', [$facility->id, $feedback->id]) }}" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this feedback?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger mt-2">Delete</button>
                                    </form>
                                @endif
                            @endauth
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
