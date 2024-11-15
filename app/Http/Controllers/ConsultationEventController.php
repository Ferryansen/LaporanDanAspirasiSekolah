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
        $consultations = ConsultationEvent::where('is_private', false)
                                        ->where(function ($query) {
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
        $userId = Auth::id();

        $consultations = ConsultationEvent::whereJsonContains('attendees', [$userId])->paginate(10)->withQueryString();
        $typeSorting ="";

        return view('consultation.student.mySession', compact('consultations', 'typeSorting'));
    }

    public function mySessionSorting($typeSorting) {
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
            ];

            $startDateTimeRequest = Carbon::parse($request->startDateTime);

            $startDateTimeEvent = Carbon::parse($event->start);

            if (!$startDateTimeRequest->eq($startDateTimeEvent)) {
                if ($event->status == 'Belum dimulai' || $event->status == 'Sedang dimulai') {
                    $credentials['status'] = 'Pindah jadwal';
                }
                $consultationData['date'] = $request->startDateTime;
            } else {
                $consultationData['date'] = null;
            }
            
            if($request->location != null) {
                $credentials['location'] = $request->location;
                $consultationData['location'] = $request->location;
            } else {
                $consultationData['location'] = null;
            }

            if ($event->attendeeLimit != null) {
                $credentials['attendeeLimit'] = $request->attendeeLimit;
            }

            if($request->consultationType == 'online') {
                $credentials['is_online'] = true;
                $consultationData['is_online'] = $request->consultationType;
            } elseif ($request->consultationType == 'offline') {
                $credentials['is_online'] = false;
                $consultationData['is_online'] = $request->consultationType;
            }

            $attendees = $event->attendees;
            
            if (count($attendees) > 0) {
                $consultationData['title'] = $event->title;
                $consultationData['consultant'] = $event->consultBy->name;

                foreach ($attendees as $attendee) {
                    $currAttendee = User::findOrFail($attendee);
    
                    Mail::to($currAttendee->email)->queue(new UpdateInfoConsultationStudentNotificationEmail($currAttendee->name, $consultationData));
                }
            }


            $event->update($credentials);
            
            DB::commit();
            
            return redirect()->route('consultation.detail', ['consultation_id' => $event->id])->with('successMessage', 'Sesi konsultasi berhasil diperbarui');
        } catch (Exception $e) {
            DB::rollBack();
    
            Log::error('Error updating status consultation: ' . $e->getMessage());
    
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
                $attendees = array_map('intval', $request->attendees);
                $credentials['attendees'] = $attendees;
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
            
            return redirect()->route('consultation.seeAll')->with('successMessage', 'Sesi konsultasi berhasil ditambahkan');
        } catch (Exception $e) {
            DB::rollBack();
    
            Log::error('Error creating consultation: ' . $e->getMessage());
    
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

        $attendees = $event->attendees;
            
        if (count($attendees) > 0) {
            $consultationData = [
                'title' => $event->title,
                'location' => $event->location,
                'date' => $event->start,
                'endDate' => $event->end,
                'consultant' => Auth::user()->name,
            ];

            if($event->is_online == true) {
                $consultationData['is_online'] = 'online';
            } elseif ($event->is_online == false) {
                $consultationData['is_online'] = 'offline';
            }
            
            foreach ($attendees as $attendee) {
                $currAttendee = User::findOrFail($attendee);

                Mail::to($currAttendee->email)->queue(new UpdateInfoConsultationStudentNotificationEmail($currAttendee->name, $consultationData));
            }
        }

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
            
            return redirect()->route('consultation.seeAll')->with('successMessage', 'Sesi konsultasi berhasil dibatalkan');
        } catch (Exception $e) {
            DB::rollBack();
    
            Log::error('Error cancelling consultation: ' . $e->getMessage());
    
            return redirect()->back()->with('errorMessage', 'Terjadi kesalahan dalam pembatalan sesi konsultasi. Silakan coba lagi.');
        }
    }

    public function addAttendees($consultation_id) {
        try {
            DB::beginTransaction();
    
            $userId = Auth::id();
            $event = ConsultationEvent::findOrFail($consultation_id);
            $attendees = $event->attendees ?? [];
            
            if ($event->attendeeLimit != null) {
                if (count($event->attendees) >= $event->attendeeLimit) {
                    return redirect()->back()->with('errorMessage', 'Waduh, sesi ini sudah melebihi limit nih'); 
                }
            }

            if (!in_array($userId, $attendees)) {
                $attendees[] = $userId;
                $event->attendees = $attendees;
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
            
            return view('consultation.student.sessionList', compact('consultations', 'typeSorting'));
        } catch (Exception $e) {
            DB::rollBack();
    
            Log::error('Error registering consultation: ' . $e->getMessage());
    
            return redirect()->back()->with('errorMessage', 'Terjadi kesalahan dalam pendaftaran konsultasi. Silakan coba lagi.');
        }
    }

    public function removeAttendees($consultation_id) {
        $userId = Auth::id();
        $event = ConsultationEvent::findOrFail($consultation_id);
        $attendees = $event->attendees ?? [];
    
        if (in_array($userId, $attendees)) {
            $attendees = array_diff($attendees, [$userId]);
            $attendees = array_values($attendees);
            $event->attendees = $attendees;
            $event->save();
        }
    
        $consultations = ConsultationEvent::where('start', '>', now())->orderBy('start', 'asc')->paginate(10)->withQueryString();
        $typeSorting ="";
        return view('consultation.student.sessionList', compact('consultations', 'typeSorting'));
    }
}