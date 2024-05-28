<?php

namespace App\Http\Controllers;

use App\Mail\CancelledConsultationStudentNotificationEmail;
use App\Mail\UpdateInfoConsultationStudentNotificationEmail;
use App\Models\ConsultationEvent;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

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

    public function consultationDetail($consultation_id) {
        $event = ConsultationEvent::findOrFail($consultation_id);

        return view('consultation.consultationDetailView', compact('event'));
    }

    public function updateEventForm($consultation_id) {
        $event = ConsultationEvent::findOrFail($consultation_id);

        return view('consultation.staffAndHeadmaster.updateConsultationView', compact('event'));
    }

    public function updateEvent($consultation_id, Request $request) {
        $event = ConsultationEvent::findOrFail($consultation_id);
        $consultationData = [];

        $rules = [
            'title' => 'required|max:250',
            'description' => 'required|max:1000',
            'startDateTime' => 'required',
            'endDateTime' => 'required|after:startDateTime',
            'consultationType' => 'required',
            'attendeeLimit' => 'required|integer|min:1',
            'location' => 'max:250'
        ];

        $messages = [
            'title.required' => 'Jangan lupa masukin judulnya yaa',
            'title.max' => 'Judul yang kamu masukin cuman bisa maksimal 250 karakter nih',

            'description.required' => 'Jangan lupa masukin deskripsi yaa',
            'description.max' => 'Deskripsi yang kamu masukin cuman bisa maksimal 1000 karakter nih',

            'startDateTime.required' => 'Jangan lupa masukin tanggal dan waktu mulai konsultasi yaa',
            'endDateTime.required' => 'Jangan lupa masukin tanggal dan waktu selesai konsultasi yaa',
            'endDateTime.after' => 'Tanggal dan waktu selesainya kurang pas nih',
            
            'consultationType.required' => 'Jangan lupa pilih jenis konsultasinya yaa',
            
            'attendeeLimit.required' => 'Jangan lupa masukin limit pesertanya yaa',
            'attendeeLimit.integer' => 'Limit yang kamu masukin harus angka nih',
            'attendeeLimit.min' => 'Limit peserta minimalnya harus 1 orang nih.',
            
            'location.max' => 'Lokasi konsultasi yang kamu masukin cuman bisa maksimal 250 karakter nih',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);
    
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $credentials = [
            'title' => $request->title,
            'description' => $request->description,
            'start' => $request->startDateTime,
            'end' => $request->endDateTime,
            'attendeeLimit' => $request->attendeeLimit,
        ];

        if ($request->startDateTime != $event->start || $request->endDateTime != $event->end) {
            if($event->status == 'Belum dimulai' || $event != 'Sedang dimulai') {
                $credentials['status'] = 'Pindah jadwal';
            }

        }
        
        if($request->location != null) {
            $credentials['location'] = $request->location;
            $consultationData['location'] = $request->location;
        }
        
        if ($request->startDateTime != $event->start) {
            $consultationData['date'] = $request->startDateTime;
        }

        if($request->consultationType == 'online') {
            $credentials['is_online'] = true;
            $consultationData['is_online'] = $request->consultationType;
        } elseif ($request->consultationType == 'offline') {
            $credentials['is_online'] = false;
            $consultationData['is_online'] = $request->consultationType;
        }

        $attendees = $event->attendees;
        $consultationData['title'] = $event->title;

        foreach ($attendees as $attendee) {
            $currAttendee = User::findOrFail($attendee);

            Mail::to($currAttendee->email)->send(new UpdateInfoConsultationStudentNotificationEmail($currAttendee->name, $consultationData));
        }

        $event->update($credentials);
        return redirect()->route('consultation.detail', ['consultation_id' => $event->id])->with('successMessage', 'Sesi konsultasi berhasil diperbarui');
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
        $rules = [
            'title' => 'required|max:250',
            'description' => 'required|max:1000',
            'startDateTime' => 'required',
            'endDateTime' => 'required|after:startDateTime',
            'consultationType' => 'required',
            'attendeeLimit' => 'required|integer|min:1',
            'location' => 'max:250'
        ];

        $messages = [
            'title.required' => 'Jangan lupa masukin judulnya yaa',
            'title.max' => 'Judul yang kamu masukin cuman bisa maksimal 250 karakter nih',

            'description.required' => 'Jangan lupa masukin deskripsi yaa',
            'description.max' => 'Deskripsi yang kamu masukin cuman bisa maksimal 1000 karakter nih',

            'startDateTime.required' => 'Jangan lupa masukin tanggal dan waktu mulai konsultasi yaa',
            'endDateTime.required' => 'Jangan lupa masukin tanggal dan waktu selesai konsultasi yaa',
            'endDateTime.after' => 'Tanggal dan waktu selesainya kurang pas nih',
            
            'consultationType.required' => 'Jangan lupa pilih jenis konsultasinya yaa',
            
            'attendeeLimit.required' => 'Jangan lupa masukin limit pesertanya yaa',
            'attendeeLimit.integer' => 'Limit yang kamu masukin harus angka nih',
            'attendeeLimit.min' => 'Limit peserta minimalnya harus 1 orang nih.',
            
            'location.max' => 'Lokasi konsultasi yang kamu masukin cuman bisa maksimal 250 karakter nih',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);
    
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $credentials = [
            'title' => $request->title,
            'description' => $request->description,
            'start' => $request->startDateTime,
            'end' => $request->endDateTime,
            'consultant' =>Auth::user()->id,
            'attendeeLimit' => $request->attendeeLimit,
            'status' => 'Belum dimulai',
        ];

        if($request->location != null) {
            $credentials['location'] = $request->location;
        }

        if($request->consultationType == 'online') {
            $credentials['is_online'] = true;
        } elseif ($request->consultationType == 'offline') {
            $credentials['is_online'] = false;
        }

        ConsultationEvent::create($credentials);
        return redirect()->route('consultation.seeAll')->with('successMessage', 'Sesi konsultasi berhasil ditambahkan');
    }

    public function openEvent($consultation_id) {
        $event = ConsultationEvent::findOrFail($consultation_id);

        $credential = [
            'status' => 'Sedang dimulai'
        ];

        $event->update($credential);
        return redirect($event->location);
    }

    public function cancelEvent($consultation_id) {
        $event = ConsultationEvent::findOrFail($consultation_id);
        $attendees = json_decode($event->attendees, true);

        $credential = [
            'status' => 'Dibatalkan'
        ];

        $consultationData = [
            'title' => $event->title,
        ];

        foreach ($attendees as $attendee) {
            $currAttendee = User::findOrFail($attendee);

            Mail::to($currAttendee->email)->send(new CancelledConsultationStudentNotificationEmail($currAttendee->name, $consultationData));
        }


        $event->update($credential);
        return redirect()->route('consultation.seeAll')->with('successMessage', 'Sesi konsultasi berhasil dibatalkan');
    }
}
