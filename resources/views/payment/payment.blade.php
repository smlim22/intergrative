@extends('layouts.app')

@section('title', 'Payment')

@section('content')
<?php 
/**
 * Author : Adrean Goh
 */
?>
<div class="card shadow p-4">
    <h2 class="mb-3">Make Payment</h2>

    <!--  success/error msg -->
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    
    <form method="POST" action="{{ route('payment.process') }}">
        @csrf
        <div class="mb-3">
            <label for="facility_id" class="form-label">Select Facility</label>
            <select class="form-select" id="facility_id" name="facility_id" required>
                @foreach($facilities as $facility)
                    <option value="{{ $facility->id }}">{{ $facility->name }} (RM{{ $facility->price }})</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="reservation_time" class="form-label">Reservation Date & Time</label>
            <input type="datetime-local" id="reservation_time" name="reservation_time" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="amount" class="form-label">Amount (RM)</label>
            <input type="number" id="amount" name="amount" class="form-control" step="0.01" required>
        </div>

        <button type="submit" class="btn btn-success">Pay Now</button>
    </form>
</div>
@endsection
