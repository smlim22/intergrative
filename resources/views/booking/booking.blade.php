@extends('layouts.app')
@section('title', 'Booking')
@section('content')
<?php 
/** UNUSED FILE
 * Author : Adrean Goh
 */
use App\Models\User;
use App\Models\Facility; 

$user = auth()->user();

//Query (URL) parameters
$facilID = $_GET['facilityID'] ?? '';
$date    = $_GET['reservation_date'] ?? '';
$start   = $_GET['start_time'] ?? '';
$end     = $_GET['end_time'] ?? '';


$facilityName = '';
if ($facilID) {
    $facility = Facility::find($facilID);
    $facilityName = $facility ? $facility->name : 'Unknown Facility';
}

$role = $user->role->name; 
?>
<div class="container">
    <h2 class="mb-4">Book a Facility</h2>

    <div class="alert alert-info">
        <strong>Facility:</strong> <?php echo $facilityName; ?><br>
        <strong>Date:</strong> <?php echo $date; ?><br>
        <strong>Time:</strong> <?php echo $start; ?> - <?php echo $end; ?>
    </div>

    <?php if($role == 'admin' || $role == 'student'){ ?>
        <form id="confirmBookingForm" action="{{ route('paypal.bookingStudentAdmin') }}" method="POST">
            @csrf
            <input type="hidden" name="facility_id" value="<?php echo $facilID; ?>">
            <input type="hidden" name="reservation_date" value="<?php echo $date; ?>">
            <input type="hidden" name="start_time" value="<?php echo $start; ?>">
            <input type="hidden" name="end_time" value="<?php echo $end; ?>">
            <button type="submit" class="btn btn-success">Confirm Booking</button>
        </form>
    <?php } else { ?>
        <form id="paypalBookingForm" action="{{ route('paypal.checkout') }}" method="POST">
            @csrf
            <input type="hidden" name="facility_id" value="<?php echo $facilID; ?>">
            <input type="hidden" name="reservation_date" value="<?php echo $date; ?>">
            <input type="hidden" name="start_time" value="<?php echo $start; ?>">
            <input type="hidden" name="end_time" value="<?php echo $end; ?>">
            <button type="submit" class="btn btn-primary">Proceed to Payment</button>
        </form>
    <?php } ?>
</div>
@endsection
