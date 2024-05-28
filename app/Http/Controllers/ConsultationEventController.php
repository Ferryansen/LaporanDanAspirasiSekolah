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

    public function sessionList() {
        $consultations = ConsultationEvent::where('start', '>', now())->orderBy('start', 'asc')->paginate(10)->withQueryString();
        $typeSorting ="";
        return view('consultation.student.sessionList', compact('consultations', 'typeSorting'));
    }

    public function sessionListSorting($typeSorting) {
        switch ($typeSorting) {
            case 'UpComing':
                $consultations = ConsultationEvent::where('start', '>', now())->orderBy('start', 'asc')->paginate(10)->withQueryString();
                break;
            case 'OnGoing':
                $consultations = ConsultationEvent::where('start', '<=', now())->where('end', '>=', now())->orderBy('start', 'asc')->paginate(10)->withQueryString();
                break;
            default:
                $consultations = ConsultationEvent::where('start', '>', now())->orderBy('start', 'asc')->paginate(10)->withQueryString();
                break;
        }
        return view('consultation.student.sessionList', compact('consultations', 'typeSorting'));
    }
    

    public function mySession() {
        // Assuming the current user's ID is retrieved from the Auth facade
        $userId = Auth::id();

        // Filter consultations where the current user is an attendee
        $consultations = ConsultationEvent::whereJsonContains('attendees', [$userId])->paginate(10)->withQueryString();
        $typeSorting ="";

        return view('consultation.student.mySession', compact('consultations', 'typeSorting'));
    }

    public function mySessionSorting($typeSorting) {
        // Get the current user's ID
        $userId = Auth::id();
    
        switch ($typeSorting) {
            case 'UpComing':
                $consultations = ConsultationEvent::where('start', '>', now())
                    ->whereJsonContains('attendees', [$userId])
                    ->orderBy('start', 'asc')
                    ->paginate(10)
                    ->withQueryString();
                break;
            case 'OnGoing':
                $consultations = ConsultationEvent::where('start', '<=', now())
                    ->where('end', '>=', now())
                    ->whereJsonContains('attendees', [$userId])
                    ->orderBy('start', 'asc')
                    ->paginate(10)
                    ->withQueryString();
                break;
            case 'End':
                $consultations = ConsultationEvent::where('end', '<', now())
                    ->whereJsonContains('attendees', [$userId])
                    ->orderBy('start', 'asc')
                    ->paginate(10)
                    ->withQueryString();
                break;
            default:
                $consultations = ConsultationEvent::whereJsonContains('attendees', [$userId])
                    ->paginate(10)
                    ->withQueryString();
                break;
        }
    
        return view('consultation.student.mySession', compact('consultations', 'typeSorting'));
    }

    public function fetchAllEvents() {
        $events = ConsultationEvent::all();

        return response()->json($events);
    }

    public function consultationDetail(){

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
