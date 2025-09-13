<?php
/**
 * Author : Adrean Goh
 */
namespace App\Http\Controllers\Api\Booking;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Reservation;
use App\Models\Facility;
class BookingApi extends Controller
{
public function checkAvail(Request $request){
    $facilityID = $request->input('facilityID');
    $reservationDate = $request->input('reservation_date');
    $startTime = $request->input('start_time');
    $endTime = $request->input('end_time');

    if(!$facilityID || !$reservationDate || !$startTime || !$endTime){
        return response()->json(['error' => 'Missing required parameters'], 400);
    }
    $facility = Facility::find($facilityID);
    if (!$facility) {
        return response()->json(['error' => 'Facility not found'], 404);
    }

    $conflict = Reservation::where('facility_id', $facilityID)
        ->whereDate('reservation_date', $reservationDate)
        ->where(function ($query) use ($startTime, $endTime) {
            $query->where('start_time', '<', $endTime)->where('end_time', '>', $startTime);
        })->exists();

    if ($conflict) {
        return response()->json(['available' => false, 'message' => 'Time slot is already booked'], 200);
    } else {
        
        return response()->json(['available' => true, 'message' => 'Time slot is available'], 200);
    }
}

public function getSchedule(Request $request){
    $facilityID=$request->query('facilityID');
    $reservationDate=$request->query('reservation_date');

    if(!$facilityID || !$reservationDate){
        return response()->json(['error' => 'Missing required query parameters'], 400);
       }

       $reservations = Reservation::where('facility_id', $facilityID)
       ->whereDate('reservation_date', $reservationDate)
       ->get(['start_time', 'end_time']);

       return response()->json(['reservations' => $reservations], 200);
    }
}

