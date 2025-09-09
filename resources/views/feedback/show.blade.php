@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Feedback for {{ $facility->name }}</h2>
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
                                @for($i = 1; $i <= 5; $i++)
                                    <span style="color:{{ $i <= $feedback->rating ? '#fd4' : '#ccc' }}">&#9733;</span>
                                @endfor
                            </div>
                            @if($feedback->comment)
                                <div><strong>Comment:</strong> {{ $feedback->comment }}</div>
                                <div><strong>Sentiment:</strong> <span class="text-muted">(pending integration)</span></div>
                            @endif
                            <div class="mt-2">
                                @if($feedback->user)
                                    <small>By: {{ $feedback->user->name }}</small>
                                @else
                                    <small>By: Public User</small>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
