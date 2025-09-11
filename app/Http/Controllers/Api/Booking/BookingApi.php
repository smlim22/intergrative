<?php
namespace App\Http\Controllers\Api\Booking;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Reservation;

class BookingApi extends Controller
{
   public function checkAvail(Request $request){
    $facilityID=$request->query('facilityID');
    $reservationDate=$request->query('reservation_date');
    $startTime=$request->query('start_time');
    $endTime=$request->query('end_time');
   
   if(!$facilityID || !$reservationDate || !$startTime || !$endTime){
    return response()->json(['error' => 'Missing required query parameters'], 400);
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
       ->get(['start_time', 'end_time', 'status']);

       return response()->json(['reservations' => $reservations], 200);
    }
}

