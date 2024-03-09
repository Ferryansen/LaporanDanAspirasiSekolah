<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    public function myReport()
    {
        $currUser = Auth::user();
        $reports = $currUser->reports()->paginate(10)->withQueryString();
        // $reports = Report::all();
        // dd($reports);

        $data = [
            'reports' => $reports,
        ];

        return view('report.student.myReport', $data);
    }

    public function manageReport()
    {
        $currUser = Auth::user();
        
        if (Auth::user()->role == "admin" || Auth::user()->role == "headmaster"){
            $reports = Report::paginate(10)->withQueryString();
        }
        else{
            // Get the staffTypeId of the current user
            $staffTypeId = $currUser->staffTypeId;
    
            // Use whereHas to filter categories based on staffTypeId
            $categoriesFilter = Category::where('staffTypeId', $staffTypeId)->pluck('id');
    
            // Use whereIn to filter reports by categoryId
            $reports = Report::whereIn('categoryId', $categoriesFilter)->paginate(10)->withQueryString();
        }

        $categories = Category::all();
        $filterTitle = null;

        return view('report.adminHeadmasterStaff.manageReport', compact('reports', 'categories', 'filterTitle'));
    }

    public function manageReportFilterCategory($categoryId)
    {
        $category = Category::findOrFail($categoryId);
        $reports = Report::where("categoryId", "like", $categoryId)->paginate(10)->withQueryString();
        $categories = Category::all();

        $data = [
            'reports' => $reports,
            'filterTitle' => $category->name,
            'categoryNow' => $category->id,
            'categories' => $categories
        ];
        
        return view('report.adminHeadmasterStaff.manageReport', $data);
    }

    public function manageReportFilterStatus($status)
    {
        // Retrieve the category IDs from the manageAspiration function
        $currUser = Auth::user();

        if (Auth::user()->role == "admin" || Auth::user()->role == "headmaster"){
            $reports = Report::where("status", "like", $status)->paginate(10)->withQueryString();
        }
        else{
            $staffTypeId = $currUser->staffTypeId;
            $categoryIds = Category::where('staffTypeId', $staffTypeId)->pluck('id');
    
            // Use whereIn to filter aspirations by category_id and status
            $reports = Report::whereIn('categoryId', $categoryIds)
                ->where('status', $status)
                ->paginate(10)
                ->withQueryString();
        }


        $data = [
            'reports' => $reports,
            'filterTitle' => $status,
            'statusNow' => $status,
        ];

        return view('report.adminHeadmasterStaff.manageReport', $data);
    }

    public function reportDetail($id){
        $report = Report::findorFail($id);

        return view('report.studentHeadmasterStaff.reportDetail', compact('report'));
    }

    public function createReport(Request $request)
    {
        $currUser = Auth::user();
        $request->validate([
            'reportName' => 'required',
            'reportDescription' => 'required|max:200',
            'reportCategory' => 'required',
            'reportEvidences.*' => 'required|file|mimes:png,jpg,jpeg,webp,mp4,avi,quicktime|max:204800', // Adjust validation rules for evidence files
            'reportEvidences' => [
                'required',
                'array',
                'file',
                'mimes:png,jpg,jpeg,webp,mp4,avi,quicktime',
                'max:204800',
                function ($filesArray, $fail) {
                    // Check if there are more than 5 image files or more than 1 video file
                    $imageCount = 0;
                    $videoCount = 0;
                    foreach ($filesArray as $file) {
                        if ($file->getMimeType() == 'video/mp4') {
                            $videoCount++;
                        } else {
                            $imageCount++;
                        }
                    }
                    if ($imageCount > 5) {
                        $fail('The maximum number of image files allowed is 5.');
                    }
                    if ($videoCount > 1) {
                        $fail('Only one video file is allowed.');
                    }
                },
            ],
        ]);
    
        // Create report
        $currentYear = now()->year;
        $reportCount = Report::whereYear('created_at', $currentYear)->count() + 1;
        $report_no = sprintf('%03d/REP/%d', $reportCount, $currentYear);
    
        $report = Report::create([
            'reportNo' => $report_no,
            'userId' => $currUser->id,
            'name' => $request->reportName,
            'categoryId' => $request->reportCategory,
            'description' => $request->reportDescription,
            'isUrgent' => false,
            'isChatOpened' => false,
            'processDate' => null,
            'processEstimationDate' => null,
            'approvalBy'=> null,
            'lastUpdatedBy'=> null,
            'status' => "Freshly submitted",
            'deletedBy' => null,
            'deleteReason' => null,
        ]);
    
        // Store evidence files
        if ($request->hasFile('reportEvidences')) {
            foreach ($request->file('reportEvidences') as $file) {
                $name = $file->getClientOriginalName();
                $filename = now()->timestamp . '_' . $name;
    
                // Determine whether the file is an image or a video
                if ($file->getMimeType() == 'video/mp4' || $file->getMimeType() == 'video/avi' || $file->getMimeType() == 'video/quicktime') {
                    $videoUrl = Storage::disk('public')->putFileAs('ListVideo', $file, $filename);
                    // Create evidence and associate it with the report
                    $report->evidences()->create([
                        'video' => $videoUrl,
                        'name' => $name
                    ]);
                } else {
                    $imageUrl = Storage::disk('public')->putFileAs('ListImage', $file, $filename);
                    // Create evidence and associate it with the report
                    $report->evidences()->create([
                        'image' => $imageUrl,
                        'name' => $name
                    ]);
                }
            }
        }
    
        return redirect()->route('report.student.myReport');
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
        //     'categoryID' => $request->reportCategory,
            // 'evidence' => $imageUrl,
    //     ]);

    //     return redirect()->route('report.adminHeadmasterStaff.reportDetail', $id);
    // }

    public function cancelReport(Request $request){
        $report = Report::find($request->id);

        $report->update([
            'status' => "Cancelled",
        ]);
        return redirect()->route('report.student.myReport');
    }

    public function rejectReport(Request $request, $userId){
        $report = Report::find($request->id);
        $user = User::findOrFail($userId);

        $report->update([
            'status' => "Rejected",
            'approvalBy' => $user->name
        ]);
        return redirect()->route('report.adminHeadmasterStaff.manageReport');
    }

    public function inReviewStaffReport(Request $request, $userId){
        $report = Report::find($request->id);
        $user = User::findOrFail($userId);

        $report->update([
            'status' => "In review by staff",
            'lastUpdatedBy' => $user->name
        ]);
        return redirect()->route('report.adminHeadmasterStaff.manageReport');
    }
    
    public function inReviewHeadmasterReport(Request $request, $userId){
        $report = Report::find($request->id);
        $user = User::findOrFail($userId);

        $report->update([
            'status' => "In review by headmaster",
            'lastUpdatedBy' => $user->name
        ]);
        return redirect()->route('report.adminHeadmasterStaff.manageReport');
    }

    public function approveReport(Request $request, $userId){
        $report = Report::find($request->id);
        $user = User::findOrFail($userId);

        $report->update([
            'status' => "Approved",
            'approvalBy' => $user->name
        ]);
        return redirect()->route('report.adminHeadmasterStaff.manageReport');
    }

    // public function requestApprovalReport(Request $request){
    //     $report = Report::find($request->id);

    //     $report->update([
    //         'status' => "Requested Approval",
    //     ]);
    //     return redirect()->route('admin.manageReport');
    // }

    public function onProgReport(Request $request, $userId){
        $report = Report::find($request->id);
        $user = User::findOrFail($userId);

        $report->update([
            'status' => "In Progress",
            'lastUpdatedBy' => $user->name
        ]);
        return redirect()->route('report.adminHeadmasterStaff.manageReport');
    }

    public function monitoringReport(Request $request, $userId){
        $report = Report::find($request->id);
        $user = User::findOrFail($userId);

        $report->update([
            'status' => "Monitoring process",
            'lastUpdatedBy' => $user->name
        ]);
        return redirect()->route('report.adminHeadmasterStaff.manageReport');
    }

    public function finishReport(Request $request, $userId){
        $report = Report::find($request->id);
        $user = User::findOrFail($userId);

        $report->update([
            'status' => "Completed",
            'lastUpdatedBy' => $user->name
        ]);
        return redirect()->route('report.adminHeadmasterStaff.manageReport');
    }


    public function deleteReportAdmin(Request $request){
        $report = Report::find($request->id);
        // $evidence_path = public_path().'\storage/'.$report->evidence;
        // unlink($evidence_path);
        $report->delete();
       
        return redirect()->route('report.adminHeadmasterStaff.manageReport');
    }
}
