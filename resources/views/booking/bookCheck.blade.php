@extends('layouts.app')
@section('title', 'Check Availability')
@section('content')
<?php 
/**
 * Author : Adrean Goh
 */
$user = auth()->user();
$facilityID = $_GET['facilityID'] ?? '';
$reservation_date = $_GET['reservation_date'] ?? '';
$start_time = $_GET['start_time'] ?? '';
$end_time = $_GET['end_time'] ?? '';
?>
<div class="container">
    <h2 class="mb-4">Confirm Booking</h2>

    <div class="mb-3">
        <label class="form-label fw-bold">Facility</label>
        <input type="text" class="form-control" value="{{ $facilities->find($facilityID)->name ?? 'Unknown' }}" readonly>
    </div>

    <div class="row mb-3">
        <div class="col-md-4">
            <label for="reservation_date" class="form-label fw-bold">Date</label>
            <input type="date" class="form-control" id="reservation_date" value="<?php echo $reservation_date; ?>" min="{{ date('Y-m-d') }}">
        </div>
        <div class="col-md-4">
            <label for="start_time" class="form-label fw-bold">Start Time</label>
            <input type="time" class="form-control" id="start_time" value="<?php echo $start_time; ?>" min="08:00" max="18:00">
        </div>
        <div class="col-md-4">
            <label for="end_time" class="form-label fw-bold">End Time</label>
            <input type="time" class="form-control" id="end_time" value="<?php echo $end_time; ?>" min="08:00" max="18:00">
        </div>
    </div>

    <div id="availability-message"></div>

    <?php $role=$user->role->name; ?>
    @if($role == 'admin' || $role == 'student')
        <form id="confirmBookingForm" action="{{ route('paypal.bookingStudentAdmin') }}" method="POST">
            @csrf
            <input type="hidden" name="facility_id" id="facilityInput" value="<?php echo $facilityID; ?>">
            <input type="hidden" name="reservation_date" id="dateInput" value="<?php echo $reservation_date; ?>">
            <input type="hidden" name="start_time" id="startInput" value="<?php echo $start_time; ?>">
            <input type="hidden" name="end_time" id="endInput" value="<?php echo $end_time; ?>">
            <button type="submit" id="confirmBookingBtn" class="btn btn-success" disabled>Confirm Booking</button>
        </form>
    @else
        <form id="paypalBookingForm" action="{{ route('paypal.checkout') }}" method="POST">
            @csrf
            <input type="hidden" name="facility_id" id="facilityInput2" value="<?php echo $facilityID; ?>">
            <input type="hidden" name="reservation_date" id="dateInput2" value="<?php echo $reservation_date; ?>">
            <input type="hidden" name="start_time" id="startInput2" value="<?php echo $start_time; ?>">
            <input type="hidden" name="end_time" id="endInput2" value="<?php echo $end_time; ?>">
            <button type="submit" id="paypalBookingBtn" class="btn btn-primary" disabled>Proceed to Payment</button>
        </form>
    @endif
</div>

<script>
function checkAvailability() {
    let facilityID = "<?php echo $facilityID; ?>";
    let reservation_date = document.getElementById('reservation_date').value;
    let start_time = document.getElementById('start_time').value;
    let end_time = document.getElementById('end_time').value;

    if (!facilityID || !reservation_date || !start_time || !end_time) return;

    fetch('/api/booking/check-availability', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json','Accept': 'application/json' },
        body: JSON.stringify({ facilityID, reservation_date, start_time, end_time })
    })
    .then(res => res.json())
    .then(data => {
        let msg = document.getElementById('availability-message');
        let confirmBtn = document.getElementById('confirmBookingBtn');
        let paypalBtn = document.getElementById('paypalBookingBtn');

        if (data.available) {
            msg.innerHTML = `<div class="alert alert-success">${data.message}</div>`;
            if (confirmBtn) confirmBtn.disabled = false;
            if (paypalBtn) paypalBtn.disabled = false;
        } else {
            msg.innerHTML = `<div class="alert alert-danger">${data.message}</div>`;
            if (confirmBtn) confirmBtn.disabled = true;
            if (paypalBtn) paypalBtn.disabled = true;
        }
    })
    .catch(() => {
        document.getElementById('availability-message').innerHTML =
            '<div class="alert alert-warning">Error checking availability.</div>';
    });
}

['reservation_date','start_time','end_time'].forEach(id => {
    document.getElementById(id).addEventListener('change', checkAvailability);
});
//Auto run on load
checkAvailability();
</script>
@endsection
