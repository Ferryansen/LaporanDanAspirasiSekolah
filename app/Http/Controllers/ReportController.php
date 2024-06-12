<?php

namespace App\Http\Controllers;

use App\Mail\ApprovalReportStaffNotificationEmail;
use App\Mail\CompleteReportHeadmasterNotificationEmail;
use App\Mail\CompleteReportStudentNotificationEmail;
use App\Mail\CreateReportStaffNotificationEmail;
use App\Mail\CreateReportStudentNotificationEmail;
use App\Mail\InProgressReportStudentNotificationEmail;
use App\Mail\InteractionReportStudentStaffNotificationEmail;
use App\Mail\RejectReportStudentNotificationEmail;
use App\Mail\RequestReportHeadmasterNotificationEmail;
use App\Models\Report;
use App\Models\Category;
use App\Models\UrgentAccess;
use App\Models\User;
use App\Services\SmsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use DateInterval;
use Exception;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class ReportController extends Controller
{
    public function myReport()
    {
        $currUser = Auth::user();
        $currUser_id = $currUser->id;
        // $reports = Report::where('user_id', $currUser->id)->orderBy('isUrgent', 'desc') 
        //              ->orderBy('created_at', 'desc') 
        //              ->paginate(10); 

        $reports = Report::sortable()->where(function ($query) use ($currUser_id) {
                    $query->where('user_id', $currUser_id);
                    })->orderBy('isUrgent', 'desc')->orderBy('created_at', 'desc')->paginate(10);
        
        $data = [
            'reports' => $reports,
        ];

        return view('report.student.myReport', $data);
    }

    public function manageReport()
    {
        $currUser = Auth::user();
        $categories = Category::all();
        
        if (Auth::user()->role == "admin" || Auth::user()->role == "headmaster"){

            $reports = Report::sortable()->orderBy('isUrgent', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        }
        else{
            // Get the staffType_id of the current user
            $staffType_id = $currUser->staffType_id;
    
            // Use whereHas to filter categories based on staffType_id
            $category_ids = Category::where('staffType_id', $staffType_id)->pluck('id');
            $idx = 0;
            foreach ($categories as $category) {
                if (strpos($category->name, "Lainnya") !== false){
                    $idx = $category->id;
                    break;
                }
            }
            $reports = Report::sortable()->where(function ($query) use ($category_ids, $idx) {
                            $query->whereIn('category_id', $category_ids)
                            ->orWhere('category_id', $idx);
                        })->orderBy('isUrgent', 'desc')->orderBy('created_at', 'desc')->paginate(10);

        }

        $filterTitle = null;
        Session::put('selected_status', "Semua status");
        Session::put('selected_category', "Semua kategori");

        return view('report.adminHeadmasterStaff.manageReport', compact('reports', 'categories', 'filterTitle'));
    }

    public function manageReportFilterCategory($category_id)
    {
        $category = Category::findOrFail($category_id);
        $reports = Report::where("category_id", "like", $category_id)->orderBy('isUrgent', 'desc')->orderBy('created_at', 'desc')
        ->paginate(10)->withQueryString();
        $categories = Category::all();

        $data = [
            'reports' => $reports,
            'filterTitle' => $category->name,
            'categoryNow' => $category->id,
            'categories' => $categories
        ];

        Session::put('selected_category', $category->name);
        
        return view('report.adminHeadmasterStaff.manageReport', $data);
    }

    public function manageReportFilterStatus($status)
    {
        // Retrieve the category IDs from the manageAspiration function
        $currUser = Auth::user();

        // if (Auth::user()->role == "admin" || Auth::user()->role == "headmaster"){
        //     $reports = Report::where("status", "like", $status)->paginate(10)->withQueryString();
        // }
        // else{
            $staffType_id = $currUser->staffType_id;
            $category_ids = Category::where('staffType_id', $staffType_id)->pluck('id');
    
            // Use whereIn to filter aspirations by category_id and status
            $reports = Report::whereIn('category_id', $category_ids)
                ->where('status', $status)
                ->orderBy('isUrgent', 'desc')->orderBy('created_at', 'desc')
                ->paginate(10)
                ->withQueryString();
        // }


        $data = [
            'reports' => $reports,
            'statusNow' => $status,
        ];

        Session::put('selected_status', $status);
        return view('report.adminHeadmasterStaff.manageReport', $data);
    }

    public function reportDetail($id) {
        $report = Report::findOrFail($id);
        $link = null;
    
        if ($report->status == "Approved" || 
            $report->status == "In review by staff" || 
            $report->status == "Request Approval" || 
            $report->status == "In Progress" || 
            $report->status == "Monitoring process" || 
            $report->status == "Completed") {

            if(Auth::user()->role == "staff"){
                $link = url("/chat/{$report->user_id}");
            }
            else{
                $link = url("/chat/{$report->processedBy}");
            }
        }

        $evidences = $report->evidences()->where('context', 'reporting')->get();
        if ($report->status == 'Completed') {
            $completionProofs = $report->evidences()->where('context', 'completion')->get();
            return view('report.studentHeadmasterStaff.reportDetail', compact('report', 'link', 'evidences', 'completionProofs'));
        }
    
        return view('report.studentHeadmasterStaff.reportDetail', compact('report', 'link', 'evidences'));
    }

    public function openChatNotification(Request $request) {
        $report = Report::findOrFail($request->reportID);
        $currUser = Auth::user();

        if ($currUser->role == 'staff') {
            $receiver = $report->user;
            $link = url("/chat/{$report->processedBy}");
        } elseif ($currUser->role == 'student') {
            $receiver = $report->processExecutor;
            $link = url("/chat/{$report->user_id}");
        }

        $reportData = [
            'title' => $report->name,
            'link' => $link
        ];

        Mail::to($receiver->email)->send(new InteractionReportStudentStaffNotificationEmail($receiver->name, $reportData));

        return response()->json(['success' => 'Email sent successfully']);
    }
    

    public function createReport(Request $request)
    { 
        try {
            DB::beginTransaction();
    
            $currUser = Auth::user();
            $request->validate([
                'reportName' => 'required',
                'reportDescription' => 'required|max:200',
                'reportCategory' => 'required',
                'reportEvidences.*' => 'file|mimes:png,jpg,jpeg,webp',
                'reportEvidenceVideo.*' => 'required_without:reportEvidences|file|mimes:mp4,avi,quicktime|max:40960',
                'reportEvidences' => [
                    'required_without:reportEvidenceVideo',
                    'array',
                    'max:5'
                ],
            ]);
            
            // Create report
            $currentYear = now()->year;
            $reportCount = Report::whereYear('created_at', $currentYear)->count() + 1;
            $report_no = sprintf('%03d/REP/%d', $reportCount, $currentYear);
        
            $report = Report::create([
                'reportNo' => $report_no,
                'user_id' => $currUser->id,
                'name' => $request->reportName,
                'category_id' => $request->reportCategory,
                'description' => $request->reportDescription,
                'priority' => 4,
                'isUrgent' => false,
                'isChatOpened' => false,
                'processDate' => null,
                'processEstimationDate' => null,
                'approvalBy'=> null,
                'lastUpdatedBy'=> null,
                'status' => "Freshly submitted",
                'rejectReason' => null,
                'closedReason' => null,
                'deletedBy' => null,
                'deleteReason' => null,
            ]);
        
            // Store evidence files
            if ($request->hasFile('reportEvidences')) {
                foreach ($request->file('reportEvidences') as $file) {
                    $name = $file->getClientOriginalName();
                    $filename = now()->timestamp . '_' . $name;

                    $imageUrl = Storage::disk('public')->putFileAs('ListImage', $file, $filename);
                    // Create evidence and associate it with the report
                    $report->evidences()->create([
                        'image' => $imageUrl,
                        'name' => $name,
                        'context' => 'reporting',
                    ]);
                }
            }

            if ($request->hasFile('reportEvidenceVideo')) {
                $name = $request->file('reportEvidenceVideo')->getClientOriginalName();
                $filename = now()->timestamp . '_' . $name;

                
                $videoUrl = Storage::disk('public')->putFileAs('ListVideo', $request->file('reportEvidenceVideo'), $filename);
                // Create evidence and associate it with the report
                $report->evidences()->create([
                    'video' => $videoUrl,
                    'name' => $name,
                    'context' => 'reporting',
                ]);
            }

            $reportData = [
                'reportID' => $report->id,
                'reportNo' => $report_no,
                'title' => $request->reportName,
                'date' => Carbon::now()->format('d/m/Y'),
            ];

            
            Mail::to($currUser->email)->send(new CreateReportStudentNotificationEmail($currUser->name, $reportData));
            $relatedStaffs = $report->category->staffType->users;
            foreach ($relatedStaffs as $staff) {
                Mail::to($staff->email)->send(new CreateReportStaffNotificationEmail($staff->name, $reportData));
            }
            
            DB::commit();
            return redirect()->route('report.student.myReport')->with('successMessage', 'Laporan berhasil dibuat');
        } catch (Exception $e) {
            DB::rollBack();
    
            Log::error('Error creating report: ' . $e->getMessage());
    
            return redirect()->route('report.student.myReport')->with('errorMessage', 'Terjadi kesalahan dalam pembuatan laporan. Silakan coba lagi.');
        }
    }

    public function createReportUrgent(Request $request)
    {
        try{
            $currUser = Auth::user();
            $headmasters = User::where('role', 'headmaster')->get();
    
            // dd($request->all());
            // Validate the form data
            $request->validate([
                'reportName' => 'required',
                'reportDescription' => 'required|max:200',
                'reportCategory' => 'required',
                'mediaFile.*' => 'required|file|mimes:png,jpeg,jpg,webp,mp4,avi,quicktime,webm|max:40960'
            ]);            
            
            // Create report
            $currentYear = now()->year;
            $reportCount = Report::whereYear('created_at', $currentYear)->count() + 1;
            $report_no = sprintf('%03d/REP/%d', $reportCount, $currentYear);

            $processEstimationDate = now()->addDay();
    
            $report = Report::create([
                'reportNo' => $report_no,
                'user_id' => $currUser->id,
                'name' => $request->reportName,
                'category_id' => $request->reportCategory,
                'description' => $request->reportDescription,
                'priority' => 1,
                'isUrgent' => true,
                'isChatOpened' => false,
                'processDate' => null,
                'processEstimationDate' => $processEstimationDate,
                'approvalBy'=> null,
                'lastUpdatedBy'=> null,
                'status' => "Freshly submitted",
                'rejectReason' => null,
                'closedReason' => null,
                'deletedBy' => null,
                'deleteReason' => null,
            ]);
    
            // Handle the file upload
            $mediaFile = $request->file('mediaFile');
            $name = $mediaFile->getClientOriginalName();
            $filename = now()->timestamp . '_' . $name;
    
            // dd($mediaFile->getMimeType());
            // dd($name);
            // dd($filename);
    
            // Determine whether the file is an image or a video
            if (in_array($mediaFile->getMimeType(), ['video/mp4', 'video/avi', 'video/quicktime', 'video/webm', 'application/octet-stream'])) {
                $videoUrl = Storage::disk('public')->putFileAs('ListVideo', $mediaFile, $filename . '.' . $mediaFile->getClientOriginalExtension());
                // Create evidence and associate it with the report
                $report->evidences()->create([
                    'video' => $videoUrl,
                    'name' => $name,
                    'context' => 'reporting',
                ]);
            } else {
                $imageUrl = Storage::disk('public')->putFileAs('ListImage', $mediaFile, $filename);
                // Create evidence and associate it with the report
                $report->evidences()->create([
                    'image' => $imageUrl,
                    'name' => $name,
                    'context' => 'reporting',
                ]);
            }

            $smsService = app(SmsService::class);
            
            do {
                $accessCode = Str::random(6);
                $exists = DB::table('urgent_accesses')->where('accessCode', $accessCode)->exists();
            } while ($exists);
            $expiration = Carbon::now()->addDays(7);

            $urgentAccess = UrgentAccess::create([
                'report_id' => $report->id,
                'accessCode' => $accessCode,
                'expires_at' => $expiration,
            ]);

            if ($currUser->urgentPhoneNumber != null) {
                $toUrgentContact = '+62' . substr($currUser->urgentPhoneNumber, 1);
                $messageUrgentContact = 'Ada kejadian urgent yang dilaporkan oleh ' . $currUser->name . '!!
- Kejadian: ' . $report->name . '
- Detail: ' .  route('urgent.accessForm', $urgentAccess) . '
- Kode akses: ' . $urgentAccess->accessCode;
                
                $smsService->sendSms($toUrgentContact, $messageUrgentContact);
            }
            
            foreach ($headmasters as $headmaster) {
                $toHeadmaster = '+62' . substr($headmaster->phoneNumber, 1);
                $messageHeadmaster = 'Ada laporan urgent dari ' . $currUser->name . '!!
- Judul: ' . $report->name . '
- Deskripsi: ' . $report->description . '
- Detail: ' . route('student.reportDetail', $report->id);

                $smsService->sendSms($toHeadmaster, $messageHeadmaster);
            }

    
            // Redirect back to the appropriate route
            return redirect()->route('report.student.myReport')->with('successMessage', 'Laporan urgent berhasil dibuat');
        }
        catch (\Exception $e) {
            \Log::error('Error creating urgent report: ' . $e->getMessage());
            return back()->withErrors(['error' => 'There was an issue submitting your report. Please try again.']);
        }
    }

    public function urgentAccessForm(UrgentAccess $urgentAccess) {
        return view('urgent.urgentAccessForm', compact('urgentAccess'));
    }

    public function urgentAccessCheck(Request $request, UrgentAccess $urgentAccess) {
        $rule = [
            'accessCode' => 'required',
        ];

        $message = [
            'accessCode.required' => 'Jangan lupa diisi yaa'
        ];

        $validator = Validator::make($request->all(), $rule, $message);
    
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        if ($urgentAccess->accessCode === $request->accessCode) {
            Session::put("validated_access_code_{$urgentAccess->id}", $request->accessCode);

            return redirect()->route('urgent.accessDetail', $urgentAccess);
        }

        return back()->withErrors(['accessCode' => 'Kode akses salah nih, coba lagi yaa']);
    }

    public function urgentAccessDetail(UrgentAccess $urgentAccess) {
        $validatedAccessCode = Session::get("validated_access_code_{$urgentAccess->id}");

        if ($validatedAccessCode != $urgentAccess->accessCode || Carbon::now() > $urgentAccess->expires_at) {
            abort(404);
        }

        return view('urgent.urgentAccessDetail', compact('urgentAccess'));
    }
    
    public function urgentReportPage(){
        $categories = Category::all();

        $data = [
            'categories' => $categories,
        ];

        return view('report.student.urgentReport', $data);
    }


    public function createReportForm(){
        $categories = Category::all();

        $data = [
            'categories' => $categories,
        ];

        return view('report.student.createReportForm', $data);
    }

    // public function updateReportForm($id){
    //     $report = Report::findorFail($id);

    //     $categories = Category::all();

    //     $data = [
    //         'categories' => $categories,
    //     ];


    //     return view('report.student.updateReportForm', compact('report'), $data);
    // }

    // public function updateReport(Request $request, $id){
    //     $request->validate([
    //         'reportName' => 'required',
    //         'reportDescription' => 'required|max:200',
    //         'reportCategory' => 'required',
            // 'reportEvidence' => 'required',
            // 'reportEvidence.*' => 'file|mimes:jpg,png,jpeg',
        // ]);

        // $file = $request->file('reportEvidence');
        // $name = $file->getClientOriginalName();
        // $filename = now()->timestamp.'_'.$name;

        // $imageUrl = Storage::disk('public')->putFileAs('ListImage', $file, $filename);

        // $report = Report::findOrFail($id);

        // $report->update([
        //     'name' => $request->reportName,
        //     'description' => $request->reportDescription,
        //     'category_id' => $request->reportCategory,
            // 'evidence' => $imageUrl,
    //     ]);

    //     return redirect()->route('report.adminHeadmasterStaff.reportDetail', $id);
    // }

    public function cancelReport(Request $request){
        $report = Report::find($request->id);

        $report->update([
            'status' => "Cancelled",
        ]);
        return redirect()->route('report.student.myReport')->with('successMessage', 'Laporan berhasil dibatalkan');
    }

    public function rejectReport(Request $request){
        try {
            DB::beginTransaction();
    
            $report = Report::find($request->id);
            $currUser = Auth::user();
            $rejectedUser = $report->user;

            $report->update([
                'status' => "Rejected",
                'rejectReason' => $request->rejectReason,
                'approvalBy' => $currUser->name
            ]);

            Mail::to($rejectedUser->email)->send(new RejectReportStudentNotificationEmail($rejectedUser->name, $report->name));


            
            DB::commit();
            
            // Success message
            return redirect()->route('report.adminHeadmasterStaff.manageReport')->with('successMessage', 'Laporan berhasil di-reject');
        } catch (Exception $e) {
            DB::rollBack();
    
            // Log the error
            Log::error('Error rejecting report: ' . $e->getMessage());
    
            // Return back with error message
            return redirect()->back()->with('errorMessage', 'Terjadi kesalahan dalam menolak laporan. Silakan coba lagi.');
        }
    }

    public function closeReport(Request $request){
        try {
            DB::beginTransaction();
    
            $report = Report::find($request->id);
            $currUser = Auth::user();
            $closedUser = $report->user;

            $report->update([
                'status' => "Closed",
                'closedReason' => $request->closedReason,
                'approvalBy' => $currUser->name
            ]);
            
            DB::commit();
            
            // Success message
            return redirect()->route('report.adminHeadmasterStaff.manageReport')->with('successMessage', 'Laporan berhasil ditutup');
        } catch (Exception $e) {
            DB::rollBack();
    
            // Log the error
            Log::error('Error rejecting report: ' . $e->getMessage());
    
            // Return back with error message
            return redirect()->back()->with('errorMessage', 'Terjadi kesalahan dalam menutup laporan. Silakan coba lagi.');
        }
    }

    public function inReviewStaffReport(Request $request){
        $report = Report::find($request->id);
        $currUser = Auth::user();

        $report->update([
            'status' => "In review by staff",
            'processedBy' => $currUser->id,
            'lastUpdatedBy' => $currUser->name
        ]);
        return redirect()->route('report.adminHeadmasterStaff.manageReport');
    }
    
    public function inReviewHeadmasterReport(Request $request){
        try {
            DB::beginTransaction();
    
            $report = Report::find($request->id);
            $currUser = Auth::user();
            $relatedHeadmasters = User::where('role', 'headmaster')->get();

            $report->update([
                'status' => "Request Approval",
                'lastUpdatedBy' => $currUser->name
            ]);

            $reportData = [
                'reportID' => $report->id,
                'reportNo' => $report->reportNo,
                'title' => $report->name,
                'relatedStaff' => $currUser->name,
            ];

            foreach ($relatedHeadmasters as $headmaster) {
                Mail::to($headmaster->email)->send(new RequestReportHeadmasterNotificationEmail($headmaster->name, $reportData));
            }

            
            DB::commit();
            
            // Success message
            return redirect()->route('report.adminHeadmasterStaff.manageReport')->with('successMessage', 'Request tindak lanjut laporan telah dikirim');
        } catch (Exception $e) {
            DB::rollBack();
    
            // Log the error
            Log::error('Error requesting review report: ' . $e->getMessage());
    
            // Return back with error message
            return redirect()->back()->with('errorMessage', 'Terjadi kesalahan dalam me-request tindak lanjut laporan. Silakan coba lagi.');
        }
    }

    public function approveReport(Request $request){
        try {
            DB::beginTransaction();
    
            $report = Report::find($request->id);
            $currUser = Auth::user();
            $processExecutor = $report->processExecutor;

            $report->update([
                'status' => "Approved",
                'approvalBy' => $currUser->name
            ]);

            $reportData = [
                'reportID' => $report->id,
                'title' => $report->name,
            ];
            if(Auth::user()->role == "headmaster"){
                Mail::to($processExecutor->email)->send(new ApprovalReportStaffNotificationEmail($processExecutor->name, $reportData));
            } 
            
            DB::commit();
            
            // Success message
            return redirect()->route('report.adminHeadmasterStaff.manageReport')->with('successMessage', 'Tindak lanjut laporan telah disetujui');
        } catch (Exception $e) {
            DB::rollBack();
    
            // Log the error
            Log::error('Error approving request report: ' . $e->getMessage());
    
            // Return back with error message
            return redirect()->back()->with('errorMessage', 'Terjadi kesalahan dalam persetujuan tindak lanjut laporan. Silakan coba lagi.');
        }
    }

    // public function requestApprovalReport(Request $request){
    //     $report = Report::find($request->id);

    //     $report->update([
    //         'status' => "Requested Approval",
    //     ]);
    //     return redirect()->route('admin.manageReport');
    // }

    public function onProgReport(Request $request) {
        try {
            DB::beginTransaction();
            $report = Report::find($request->id);
            $currUser = Auth::user();
    
            // Define the number of workdays to add based on the priority
            $workdaysToAdd = 0;
            if ($request->priority == 1) {
                $workdaysToAdd = 3;
            } else if ($request->priority == 2) {
                $workdaysToAdd = 7;
            } else if ($request->priority == 3) {
                $workdaysToAdd = 10;
            }
    
            // Calculate the process estimation date
            $processEstimationDate = $this->addWorkdays($report->created_at, $workdaysToAdd);
    
            if (!$report->isUrgent) {
                $report->update([
                    'priority' => $request->priority,
                    'status' => "In Progress",
                    'processedBy' => $currUser->id,
                    'lastUpdatedBy' => $currUser->name,
                    'processDate' => now(),
                    'processEstimationDate' => $processEstimationDate
                ]);
            } else {
                $report->update([
                    'status' => "In Progress",
                    'processedBy' => $currUser->id,
                    'lastUpdatedBy' => $currUser->name,
                    'processDate' => now(),
                ]);
            }

            $reportData = [
                'reportID' => $report->id,
                'title' => $report->name,
            ];
    
            Mail::to($report->user->email)->queue(new InProgressReportStudentNotificationEmail($report->user->name, $reportData));
    
            DB::commit();
    
            // Success message
            return redirect()->route('report.adminHeadmasterStaff.manageReport')->with('successMessage', 'Laporan sedang ditindaklanjuti');
        } catch (Exception $e) {
            DB::rollBack();
    
            // Log the error
            Log::error('Error updating in progress status report: ' . $e->getMessage());
    
            // Return back with error message
            return redirect()->back()->with('errorMessage', 'Terjadi kesalahan dalam menindaklanjuti laporan. Silakan coba lagi.');
        }
    }

    private function addWorkdays(Carbon $date, int $workdays) {
        $currentDate = $date->copy();
        while ($workdays > 0) {
            $currentDate->addDay();
            // Check if the current day is a weekday
            if ($currentDate->isWeekday()) {
                $workdays--;
            }
        }
        return $currentDate;
    }

    public function monitoringReport(Request $request){
        $report = Report::find($request->id);
        $currUser = Auth::user();

        $report->update([
            'status' => "Monitoring process",
            'lastUpdatedBy' => $currUser->name
        ]);
        return redirect()->route('report.adminHeadmasterStaff.manageReport');
    }

    public function finishReport(Request $request){
        try {
            DB::beginTransaction();
    
            $report = Report::find($request->id);
            $request->validate([
                'reportEvidences.*' => 'file|mimes:png,jpg,jpeg,webp,mp4,avi,quicktime|max:40960',
                'reportEvidences' => [
                    'required',
                    'array',
                    function ($attribute, $value, $fail) {
                        $imageCount = 0;
                        $videoCount = 0;
        
                        foreach ($value as $file) {
                            if (in_array($file->getClientOriginalExtension(), ['mp4', 'avi', 'quicktime'])) {
                                $videoCount++;
                            } else {
                                $imageCount++;
                            }
                        }
        
                        if ($imageCount > 5) {
                            $fail('Maksimal 5 gambar yang di upload');
                        }
        
                        if ($videoCount > 1) {
                            $fail('Maksimal 1 video yang di upload');
                        }
                    },
                ],
            ]);

            if ($request->hasFile('reportEvidences')) {
                foreach ($request->file('reportEvidences') as $file) {
                    if ($file instanceof \Illuminate\Http\UploadedFile) {
                        $name = $file->getClientOriginalName();
                        $filename = now()->timestamp . '_' . $name;
                        $extension = $file->getClientOriginalExtension();
        
                        if (in_array($extension, ['mp4', 'avi', 'quicktime'])) {
                            $videoUrl = Storage::disk('public')->putFileAs('ListVideo', $file, $filename);
                            $report->evidences()->create([
                                'video' => $videoUrl,
                                'name' => $name,
                                'context' => 'completion',
                            ]);
                        } else {
                            $imageUrl = Storage::disk('public')->putFileAs('ListImage', $file, $filename);
                            $report->evidences()->create([
                                'image' => $imageUrl,
                                'name' => $name,
                                'context' => 'completion',
                            ]);
                        }
                    }
                }
            }

            $currUser = Auth::user();
            $relatedHeadmasters = User::where('role', 'headmaster')->get();

            $report->update([
                'status' => "Completed",
                'lastUpdatedBy' => $currUser->name
            ]);

            $reportData = [
                'reportID' => $report->id,
                'reportNo' => $report->reportNo,
                'title' => $report->name,
                'relatedStaff' => $currUser->name,
                'completionDate' => Carbon::now()->format('d/m/Y'),
            ];

            Mail::to($report->user->email)->send(new CompleteReportStudentNotificationEmail($report->user->name, $reportData));
            foreach ($relatedHeadmasters as $headmaster) {
                Mail::to($headmaster->email)->send(new CompleteReportHeadmasterNotificationEmail($headmaster->name, $reportData));
            }

            
            DB::commit();
            
            // Success message
            return redirect()->route('report.adminHeadmasterStaff.manageReport')->with('successMessage', 'Tindak lanjut laporan selesai');
        } catch (Exception $e) {
            DB::rollBack();
    
            // Log the error
            Log::error('Error finishing report: ' . $e->getMessage());
    
            // Return back with error message
            return redirect()->back()->with('errorMessage', 'Terjadi kesalahan dalam penyelesaian tindak lanjut laporan. Silakan coba lagi.');
        }
    }


    public function deleteReportAdmin(Request $request){
        $report = Report::find($request->id);
        
        foreach ($report->evidences as $evidence){
            if (strpos($evidence->image, 'ListImage') === 0){
                $evidence_path = public_path().'\storage/'.$evidence->image;
                unlink($evidence_path);
            }
            else if (strpos($evidence->video, 'ListVideo') === 0){
                $evidence_path = public_path().'\storage/'.$evidence->video;
                unlink($evidence_path);
            }
        }

        $report->delete();
       
        return redirect()->route('report.adminHeadmasterStaff.manageReport')->with('successMessage', 'Laporan berhasil dihapus');
    }
}
