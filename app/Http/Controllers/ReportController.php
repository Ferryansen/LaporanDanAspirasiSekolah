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
        $reports = $currUser->reports()->with('user')->paginate(10)->withQueryString();
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
            // Get the staffType_id of the current user
            $staffType_id = $currUser->staffType_id;
    
            // Use whereHas to filter categories based on staffType_id
            $categoriesFilter = Category::where('staffType_id', $staffType_id)->pluck('id');
    
            // Use whereIn to filter reports by category_id
            $reports = Report::whereIn('category_id', $categoriesFilter)->paginate(10)->withQueryString();
        }

        $categories = Category::all();
        $filterTitle = null;

        return view('report.adminHeadmasterStaff.manageReport', compact('reports', 'categories', 'filterTitle'));
    }

    public function manageReportFilterCategory($category_id)
    {
        $category = Category::findOrFail($category_id);
        $reports = Report::where("category_id", "like", $category_id)->paginate(10)->withQueryString();
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
            $staffType_id = $currUser->staffType_id;
            $category_ids = Category::where('staffType_id', $staffType_id)->pluck('id');
    
            // Use whereIn to filter aspirations by category_id and status
            $reports = Report::whereIn('category_id', $category_ids)
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
            'reportEvidences.*' => 'file|mimes:png,jpg,jpeg,webp',
            'reportEvidenceVideo.*' => 'file|mimes:mp4,avi,quicktime|max:40960',
            'reportEvidences' => [
                'required',
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
                // if ($file->getMimeType() == 'video/mp4' || $file->getMimeType() == 'video/avi' || $file->getMimeType() == 'video/quicktime') {
                //     $videoUrl = Storage::disk('public')->putFileAs('ListVideo', $file, $filename);
                //     // Create evidence and associate it with the report
                //     $report->evidences()->create([
                //         'video' => $videoUrl,
                //         'name' => $name
                //     ]);
                // } else {
                    $imageUrl = Storage::disk('public')->putFileAs('ListImage', $file, $filename);
                    // Create evidence and associate it with the report
                    $report->evidences()->create([
                        'image' => $imageUrl,
                        'name' => $name
                    ]);
                // }
            }
        }

        if ($request->hasFile('reportEvidenceVideo')) {
            // foreach ($request->file('reportEvidenc') as $file) {
                $name = $request->file('reportEvidenceVideo')->getClientOriginalName();
                $filename = now()->timestamp . '_' . $name;

                // Determine whether the file is an image or a video
                // if ($file->getMimeType() == 'video/mp4' || $file->getMimeType() == 'video/avi' || $file->getMimeType() == 'video/quicktime') {
                //     $videoUrl = Storage::disk('public')->putFileAs('ListVideo', $file, $filename);
                //     // Create evidence and associate it with the report
                //     $report->evidences()->create([
                //         'video' => $videoUrl,
                //         'name' => $name
                //     ]);
                // } else {
                    $videoUrl = Storage::disk('public')->putFileAs('ListVideo', $request->file('reportEvidenceVideo'), $filename);
                    // Create evidence and associate it with the report
                    $report->evidences()->create([
                        'video' => $videoUrl,
                        'name' => $name
                    ]);
                // }
            // }
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
        return redirect()->route('report.student.myReport');
    }

    public function rejectReport(Request $request){
        $report = Report::find($request->id);
        $currUser = Auth::user();

        $report->update([
            'status' => "Rejected",
            'approvalBy' => $currUser->name
        ]);
        return redirect()->route('report.adminHeadmasterStaff.manageReport');
    }

    public function inReviewStaffReport(Request $request){
        $report = Report::find($request->id);
        $currUser = Auth::user();

        $report->update([
            'status' => "In review by staff",
            'lastUpdatedBy' => $currUser->name
        ]);
        return redirect()->route('report.adminHeadmasterStaff.manageReport');
    }
    
    public function inReviewHeadmasterReport(Request $request){
        $report = Report::find($request->id);
        $currUser = Auth::user();

        $report->update([
            'status' => "In review to headmaster",
            'lastUpdatedBy' => $currUser->name
        ]);
        return redirect()->route('report.adminHeadmasterStaff.manageReport');
    }

    public function approveReport(Request $request){
        $report = Report::find($request->id);
        $currUser = Auth::user();

        $report->update([
            'status' => "Approved",
            'approvalBy' => $currUser->name
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

    public function onProgReport(Request $request){
        $report = Report::find($request->id);
        $currUser = Auth::user();

        $report->update([
            'status' => "In Progress",
            'lastUpdatedBy' => $currUser->name
        ]);
        return redirect()->route('report.adminHeadmasterStaff.manageReport');
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
        $report = Report::find($request->id);
        $currUser = Auth::user();

        $report->update([
            'status' => "Completed",
            'lastUpdatedBy' => $currUser->name
        ]);
        return redirect()->route('report.adminHeadmasterStaff.manageReport');
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
       
        return redirect()->route('report.adminHeadmasterStaff.manageReport');
    }
}
