<?php
Namespace App\Http\Controllers;
use Illuminate\Http\Request;

class BookingController extends Controller
{

    public function checkAvail(Request $request){}

    public function schedule()
{
    $facilities = \App\Models\Facility::active()->get();
    return view('booking.schedule', compact('facilities'));
}
public function bookCheck(Request $request)
{
    $facilities = \App\Models\Facility::all();
    return view('booking.bookCheck', compact('facilities'));
}

public function index(Request $request)
{
    $facilities = \App\Models\Facility::active()->get();

    $selectedFacility = $request->query('facilityID');
    $selectedDate = $request->query('reservation_date');
    $selectedStart = $request->query('start_time');
    $selectedEnd = $request->query('end_time');

    return view('booking.booking', compact('facilities', 'selectedFacility', 'selectedDate', 'selectedStart', 'selectedEnd'));
}

}