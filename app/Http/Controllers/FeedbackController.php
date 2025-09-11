<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Feedback;
use App\Models\Facility;
use Illuminate\Support\Facades\Auth;
use App\Services\SentimentService;

class FeedbackController extends Controller
{
    // Edit feedback (only owner can edit)
    public function edit($facilityId, $feedbackId)
    {
        $user = Auth::user();
        $feedback = Feedback::where('facility_id', $facilityId)->findOrFail($feedbackId);
        if (!$user || $feedback->user_id !== $user->id) {
            abort(403, 'Unauthorized');
        }
        $facility = Facility::findOrFail($facilityId);
        return view('feedback.edit', compact('facility', 'feedback'));
    }

    // Update feedback (only owner can update)
    public function update(Request $request, $facilityId, $feedbackId)
    {
        $user = Auth::user();
        $feedback = Feedback::where('facility_id', $facilityId)->findOrFail($feedbackId);
        if (!$user || $feedback->user_id !== $user->id) {
            abort(403, 'Unauthorized');
        }
        $feedback->rating = $request->input('rating');
        $feedback->comment = $request->input('comment');
        $feedback->save();
        return redirect()->route('feedback.show', $facilityId)->with('success', 'Feedback updated successfully.');
    }
    // Show feedback form for a facility
    public function create($facilityId)
    {
        $facility = Facility::findOrFail($facilityId);
        $user = Auth::user();
        $existing = null;
        if ($user) {
            $existing = Feedback::where('facility_id', $facilityId)
                ->where('user_id', $user->id)
                ->first();
        }
        return view('feedback.create', compact('facility', 'existing'));
    }

    // Store feedback/rating
    public function store(Request $request, $facilityId)
    {
        $facility = Facility::findOrFail($facilityId);
        $user = Auth::user();

        if ($user) {
            // Check if user already left feedback for this facility
            $existing = Feedback::where('facility_id', $facility->id)
                ->where('user_id', $user->id)
                ->first();
            if ($existing) {
                // Redirect to edit page (to be implemented)
                return redirect()->route('feedback.edit', [$facility->id, $existing->id])
                    ->with('info', 'You have already left feedback. You can edit it.');
            }
            $data = [
                'facility_id' => $facility->id,
                'user_id' => $user->id,
                'rating' => $request->input('rating'),
                'comment' => $request->input('comment'),
            ];
        } else {
            // Public: only one feedback per session/IP (optional, for now allow multiple)
            $data = [
                'facility_id' => $facility->id,
                'user_id' => null,
                'rating' => $request->input('rating'),
                'comment' => null,
            ];
        }
    Feedback::create($data);
    return redirect()->route('feedback.show', $facility->id)->with('success', 'Thank you for your feedback!');
    }

    // Display feedback for a facility
    public function show($facilityId)
    {
        $facility = Facility::findOrFail($facilityId);
        $feedbacks = Feedback::where('facility_id', $facilityId)->latest()->get();
        $user = Auth::user();
        $sentiments = [];
        $averageRating = null;
        if ($feedbacks->count() > 0) {
            $averageRating = round($feedbacks->avg('rating'), 2);
        }
        if ($user && $user->role && $user->role->name === 'admin') {
            foreach ($feedbacks as $feedback) {
                $sentiments[$feedback->id] = SentimentService::analyze($feedback->comment);
            }
        }
        return view('feedback.show', compact('facility', 'feedbacks', 'sentiments', 'averageRating'));
    }
    // Admin-only: Delete feedback
    public function destroy($facilityId, $feedbackId)
    {
        $user = Auth::user();
    if (!$user || !$user->role || $user->role->name !== 'admin') {
            abort(403, 'Unauthorized');
        }
        $feedback = Feedback::where('facility_id', $facilityId)->findOrFail($feedbackId);
        $feedback->delete();
        return redirect()->route('feedback.show', $facilityId)->with('success', 'Feedback deleted successfully.');
    }
}
