<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Feedback;
use App\Models\Facility;
use Illuminate\Support\Facades\Auth;

class FeedbackController extends Controller
{
    // Show feedback form for a facility
    public function create($facilityId)
    {
        $facility = Facility::findOrFail($facilityId);
        return view('feedback.create', compact('facility'));
    }

    // Store feedback/rating
    public function store(Request $request, $facilityId)
    {
        $facility = Facility::findOrFail($facilityId);
        $user = Auth::user();

        $data = [
            'facility_id' => $facility->id,
            'rating' => $request->input('rating'),
        ];

        if ($user) {
            // Student: allow comment
            $data['user_id'] = $user->id;
            $data['comment'] = $request->input('comment');
        } else {
            // Public: no comment, user_id is null
            $data['user_id'] = null;
            $data['comment'] = null;
        }

        Feedback::create($data);

        return redirect()->back()->with('success', 'Thank you for your feedback!');
    }

    // Display feedback for a facility
    public function show($facilityId)
    {
        $facility = Facility::findOrFail($facilityId);
        $feedbacks = Feedback::where('facility_id', $facilityId)->latest()->get();
        return view('feedback.show', compact('facility', 'feedbacks'));
    }
    // Admin-only: Delete feedback
    public function destroy($facilityId, $feedbackId)
    {
        $user = auth()->user();
    if (!$user || !$user->role || $user->role->name !== 'admin') {
            abort(403, 'Unauthorized');
        }
        $feedback = Feedback::where('facility_id', $facilityId)->findOrFail($feedbackId);
        $feedback->delete();
        return redirect()->route('feedback.show', $facilityId)->with('success', 'Feedback deleted successfully.');
    }
}
