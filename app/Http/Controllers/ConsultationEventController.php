<?php

namespace App\Http\Controllers;

use App\Mail\CancelledConsultationStudentNotificationEmail;
use App\Mail\InvitationConsultationStudentNotificationEmail;
use App\Mail\RegisteredConsultationStudentNotificationEmail;
use App\Mail\UpdateInfoConsultationStudentNotificationEmail;
use App\Models\ConsultationEvent;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class ConsultationEventController extends Controller
{
    public function seeAllEvents() {
        return view('consultation.staff.manageConsultationView');
    }

    public function sessionList() {
        $consultations = ConsultationEvent::where(function ($query) {
            $query->where('start', '>', now())
                  ->orWhere('end', '>', now());
            })
            ->orderBy('start', 'asc')
            ->paginate(10)
            ->withQueryString();
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
            $consultations = ConsultationEvent::where(function ($query) {
                $query->where('start', '>', now())
                      ->orWhere('end', '>', now());
                })
                ->orderBy('start', 'asc')
                ->paginate(10)
                ->withQueryString();
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

        if (Auth::user()->role == 'staff') {
            $attendees = User::whereIn('id', $event->attendees)->get();
            
            return view('consultation.consultationDetailView', compact('event', 'attendees'));
        }

        return view('consultation.consultationDetailView', compact('event'));
    }

    public function updateEventForm($consultation_id) {
        $event = ConsultationEvent::findOrFail($consultation_id);

        return view('consultation.staff.updateConsultationView', compact('event'));
    }

    public function updateEvent($consultation_id, Request $request) {
        try {
            DB::beginTransaction();
    
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

            $startDateTimeRequest = Carbon::parse($request->startDateTime);

            $startDateTimeEvent = Carbon::parse($event->start);

            if (!$startDateTimeRequest->eq($startDateTimeEvent)) {
                if ($event->status == 'Belum dimulai' || $event->status == 'Sedang dimulai') {
                    $credentials['status'] = 'Pindah jadwal';
                }
            }
            
            if($request->location != null) {
                $credentials['location'] = $request->location;
                $consultationData['location'] = $request->location;
            } else {
                $consultationData['location'] = null;
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

                Mail::to($currAttendee->email)->queue(new UpdateInfoConsultationStudentNotificationEmail($currAttendee->name, $consultationData));
            }

            $event->update($credentials);
            
            DB::commit();
            
            // Success message
            return redirect()->route('consultation.detail', ['consultation_id' => $event->id])->with('successMessage', 'Sesi konsultasi berhasil diperbarui');
        } catch (Exception $e) {
            DB::rollBack();
    
            // Log the error
            dd($e->getMessage());
            Log::error('Error updating status consultation: ' . $e->getMessage());
    
            // Return back with error message
            return redirect()->back()->with('errorMessage', 'Terjadi kesalahan dalam pembaharuan sesi konsultasi. Silakan coba lagi.');
        }
    }
    
    public function createEventForm(Request $request) {
        $startDate = $request->input('start');
        $endDate = $request->input('end');
    
        return view('consultation.staff.createConsultationView', [
            'startDate' => $startDate,
            'endDate' => $endDate,
        ]);
    }

    public function fetchAllStudents(Request $request) {
        $students = [];

        if ($search = $request->name) {
            $students = User::where('role', 'LIKE', 'student')
                                ->where('name', 'LIKE', "%$search%")->get();
        }

        return response()->json($students);
    }

    public function createEvent(Request $request)
    {
        try {
            DB::beginTransaction();
    
            $rules = [
                'title' => 'required|max:250',
                'description' => 'required|max:1000',
                'consultationVisibility' => 'required',
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
    
                'consultationVisibility.required' => 'Jangan lupa masukin visibilitas konsultasinya yaa',
    
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
                dd($validator);
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
                'is_confirmed' => true,
            ];
    
            if ($request->consultationVisibility == 'public') {
                $credentials['is_private'] = false;
            } elseif ($request->consultationVisibility == 'private') {
                $credentials['is_private'] = true;
            }
            
            if ($request->location != null) {
                $credentials['location'] = $request->location;
            }
    
            if ($request->attendees != null) {
                $credentials['attendees'] = $request->attendees;
            } else {
                $credentials['attendees'] = [];
            }
    
            if($request->consultationType == 'online') {
                $credentials['is_online'] = true;
            } elseif ($request->consultationType == 'offline') {
                $credentials['is_online'] = false;
            }
    
            
            $event = ConsultationEvent::create($credentials);
    
            if ($request->attendees != null) {
                $consultationData = [
                    'ID' => $event->id,
                    'title' => $event->title,
                    'date' => $event->start,
                    'endDate' => $event->end,
                    'consultant' => $event->consultBy->name,
                ];
    
                foreach ($request->attendees as $attendee) {
                    $currAttendee = User::findOrFail($attendee);
    
                    Mail::to($currAttendee->email)->queue(new InvitationConsultationStudentNotificationEmail($currAttendee->name, $consultationData));
                }
            }
    
            
            DB::commit();
            
            // Success message
            return redirect()->route('consultation.seeAll')->with('successMessage', 'Sesi konsultasi berhasil ditambahkan');
        } catch (Exception $e) {
            DB::rollBack();
    
            // Log the error
            Log::error('Error creating consultation: ' . $e->getMessage());
    
            // Return back with error message
            return redirect()->back()->with('errorMessage', 'Terjadi kesalahan dalam penambahan sesi konsultasi. Silakan coba lagi.');
        }
    }

    public function openEvent($consultation_id) {
        $event = ConsultationEvent::findOrFail($consultation_id);

        $credential = [
            'status' => 'Sedang dimulai'
        ];

        $event->update($credential);
        return redirect($event->location);
    }

    public function confirmEvent($consultation_id) {
        $event = ConsultationEvent::findOrFail($consultation_id);

        $credential = [
            'consultant' => Auth::user()->id,
            'status' => 'Belum dimulai',
            'is_confirmed' => true,
        ];

        $event->update($credential);
        return redirect()->back()->with('successMessage', 'Kehadiran konsultasi berhasil dikonfirmasi');
    }

    public function cancelEvent($consultation_id) {
        try {
            DB::beginTransaction();
    
            $event = ConsultationEvent::findOrFail($consultation_id);
            $attendees = $event->attendees;

            $credential = [
                'status' => 'Dibatalkan'
            ];

            $consultationData = [
                'title' => $event->title,
            ];

            foreach ($attendees as $attendee) {
                $currAttendee = User::findOrFail($attendee);

                Mail::to($currAttendee->email)->queue(new CancelledConsultationStudentNotificationEmail($currAttendee->name, $consultationData));
            }


            $event->update($credential);
            
            DB::commit();
            
            // Success message
            return redirect()->route('consultation.seeAll')->with('successMessage', 'Sesi konsultasi berhasil dibatalkan');
        } catch (Exception $e) {
            DB::rollBack();
    
            // Log the error
            Log::error('Error cancelling consultation: ' . $e->getMessage());
    
            // Return back with error message
            return redirect()->back()->with('errorMessage', 'Terjadi kesalahan dalam pembatalan sesi konsultasi. Silakan coba lagi.');
        }
    }

    public function addAttendees($consultation_id) {
        try {
            DB::beginTransaction();
    
            $userId = Auth::id();
            // Find the consultation event by ID
            $event = ConsultationEvent::findOrFail($consultation_id);
            // Get the existing attendees array
            $attendees = $event->attendees ?? [];

            // Check if the current user's ID is already in the attendees array
            if (!in_array($userId, $attendees)) {
                // Add the current user's ID to the attendees array
                $attendees[] = $userId;
                // Update the event's attendees attribute
                $event->attendees = $attendees;
                // Save the updated event
                $event->save();
            }

            $consultations = ConsultationEvent::where('start', '>', now())->orderBy('start', 'asc')->paginate(10)->withQueryString();
            $typeSorting ="";

            $consultationData = [
                'title' => $event->title,
                'date' => $event->start,
                'endDate' => $event->end,
            ];

            Mail::to(Auth::user()->email)->queue(new RegisteredConsultationStudentNotificationEmail(Auth::user()->name, $consultationData));

            
            DB::commit();
            
            // Success message
            return view('consultation.student.sessionList', compact('consultations', 'typeSorting'));
        } catch (Exception $e) {
            DB::rollBack();
    
            // Log the error
            Log::error('Error registering consultation: ' . $e->getMessage());
    
            // Return back with error message
            return redirect()->back()->with('errorMessage', 'Terjadi kesalahan dalam pendaftaran konsultasi. Silakan coba lagi.');
        }
    }

    public function removeAttendees($consultation_id) {
        // Get the current user's ID
        $userId = Auth::id();
        // Find the consultation event by ID
        $event = ConsultationEvent::findOrFail($consultation_id);
        // Get the existing attendees array, default to empty array if null
        $attendees = $event->attendees ?? [];
    
        // Check if the current user's ID is in the attendees array
        if (in_array($userId, $attendees)) {
            // Remove the current user's ID from the attendees array
            $attendees = array_diff($attendees, [$userId]);
            // Re-index the array to ensure it's correctly formatted
            $attendees = array_values($attendees);
            // Update the event's attendees attribute
            $event->attendees = $attendees;
            // Save the updated event
            $event->save();
        }
    
        $consultations = ConsultationEvent::where('start', '>', now())->orderBy('start', 'asc')->paginate(10)->withQueryString();
        $typeSorting ="";
        return view('consultation.student.sessionList', compact('consultations', 'typeSorting'));
    }
}
