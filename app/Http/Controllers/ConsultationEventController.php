<?php

namespace App\Http\Controllers;

use App\Models\ConsultationEvent;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ConsultationEventController extends Controller
{
    public function seeAllEvents() {
        return view('consultation.staffAndHeadmaster.manageConsultationView');
    }

    public function fetchAllEvents() {
        $events = ConsultationEvent::all();

        return response()->json($events);
    }
    
    public function createEventForm(Request $request) {
        $startDate = $request->input('start');
        $endDate = $request->input('end');
    
        return view('consultation.staffAndHeadmaster.createConsultationView', [
            'startDate' => $startDate,
            'endDate' => $endDate,
        ]);
    }

    public function createEvent(Request $request)
    {
        // $request->validate([
        //     'title' => 'required|string|max:255',
        //     'description' => 'required|string|max:1000',
        //     'start' => 'required|date',
        //     'end' => 'required|date',
        //     'consultant' => Auth::user()->id,
        //     'attendeeLimit' => 'required|integer',
        //     'location' => 'nullable|string|max:255',
        //     'status' => 'pending',
        //     'is_private' => false,
        //     'is_online' => 'required|boolean',
        // ]);
        $credentials = [
            'title' => $request->title,
            'description' => $request->description,
            'start' => $request->startDateTime,
            'end' => $request->endDateTime,
            'consultant' =>Auth::user()->id,
            'attendeeLimit' => $request->attendeeLimit,
            'location' => $request->location,
            'status' => 'pending',
            'is_private' => false,
            'is_online' => true
        ];

        ConsultationEvent::create($credentials);
        return redirect()->route('consultation.seeAll');
    }
}
