@extends('layouts.app')
@section('title', 'Check Availability')
@section('content')
<?php
$user = auth()->user();
$facilityID = $_GET['facilityID'] ?? '';
$reservation_date = $_GET['reservation_date'] ?? '';
$start_time = $_GET['start_time'] ?? '';
$end_time = $_GET['end_time'] ?? '';
?>

<div class="container">
    <h2 class="mb-4">Confirm Booking</h2>

    <!-- Facility Info -->
    <div class="mb-3">
        <label class="form-label fw-bold">Facility</label>
        <input type="text" class="form-control" value="{{ $facilities->find($facilityID)->name ?? 'Unknown' }}" readonly>
    </div>

    <!-- Booking Form -->
    <form id="bookingForm" 
          action="{{ $user->role->name === 'public' ? route('paypal.checkout') : route('paypal.bookingStudentAdmin') }}" 
          method="POST">
        @csrf

        <div class="row mb-3">
            <div class="col-md-4">
                <label class="form-label fw-bold">Date</label>
                <input type="date" class="form-control" name="reservation_date" value="{{ $reservation_date }}" min="{{ date('Y-m-d') }}">
            </div>
            <div class="col-md-4">
                <label class="form-label fw-bold">Start Time</label>
                <input type="time" class="form-control" name="start_time" value="{{ $start_time }}" min="08:00" max="18:00">
            </div>
            <div class="col-md-4">
                <label class="form-label fw-bold">End Time</label>
                <input type="time" class="form-control" name="end_time" value="{{ $end_time }}" min="08:00" max="18:00">
            </div>
        </div>

        <input type="hidden" name="facility_id" value="{{ $facilityID }}">

        <!-- Availability Message -->
        <div id="availability-message" class="mb-3"></div>

        <!-- Submit -->
        <button type="submit" class="btn {{ $user->role->name === 'public' ? 'btn-primary' : 'btn-success' }}" disabled>
            {{ $user->role->name === 'public' ? 'Proceed to Payment' : 'Confirm Booking' }}
        </button>
    </form>
</div>

{{-- Pass dynamic data first --}}
<script>
    window.bookingData = {
        facilityID: "{{ $facilityID }}"
    };
</script>

{{-- Then load external JS --}}
<script src="{{ asset('publicjs/booking.js') }}"></script>

@endsection
