<?php

namespace App\Http\Controllers;

use App\Models\Aspiration;
use App\Models\Category;
// use App\Models\UserUpvoteAspiration;
use App\Models\User;
// use App\Models\UserReportAspiration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AspirationController extends Controller
{
    public function myAspiration()
    {
        $currUser = Auth::user();
        
        if ($currUser->isSuspended == true) {
            $message = 'Kamu tidak bisa mengakses fitur ini, kamu sedang ter-suspend!';
    
            return view('aspiration.student.myAspiration', compact('message'));
        }

        $aspirations = $currUser->aspirations()->paginate(10)->withQueryString();
        return view('aspiration.student.myAspiration', compact('aspirations'));
    }

    public function showComments($id)
    {
        $aspiration = Aspiration::findOrFail($id);
        return view('aspiration.all.comment', compact('aspiration'));
    }

    public function publicAspiration()
    {
        $currUser = Auth::user();
        $message = null;
        
        if ($currUser->isSuspended == true) {
            $message = 'Kamu tidak bisa mengakses fitur ini, kamu sedang ter-suspend!';
    
            return view('aspiration.all.listAspiration', compact('message'));
        }

        // $userUpvotes = UserUpvoteAspiration::all();
        $categories = Category::all();
        $statuses = [
            'Freshly Submitted',
            'In Review',
            'Approved',
            'In Progress',
            'Monitoring',
            'Completed',
            'Rejected',
        ];
        $filterTitle = null;
        // $aspirations = Aspiration::orderByDesc('upvote') // Sort in descending order based on upvote count
        $aspirations = Aspiration::paginate(10)->withQueryString();

        return view('aspiration.all.listAspiration', compact('aspirations', 'categories', 'filterTitle', 'statuses', 'message'));
    }
    
    public function manageAspiration()
    {
        $currUser = Auth::user();
        
        if (Auth::user()->role == "admin" || Auth::user()->role == "headmaster"){
            $aspirations = Aspiration::paginate(10)->withQueryString();
        }
        else{
            // Get the staffType_id of the current user
            $staffTypeId = $currUser->staffType_id;
    
            // Use whereHas to filter categories based on staffType_id
            $categoriesFilter = Category::where('staffType_id', $staffTypeId)->pluck('id');
    
            // Use whereIn to filter aspirations by category_id
            // $aspirations = Aspiration::whereIn('category_id', $categoriesFilter)->orderByDesc('upvote')->paginate(10)->withQueryString();
        $aspirations = Aspiration::where("category_id", "like", $category_id)->paginate(10)->withQueryString();

        }

        $categories = Category::all();
        $filterTitle = null;
        $message = null;

        return view('aspiration.manageAspiration', compact('aspirations', 'categories', 'filterTitle', 'message'));
    }

    public function updateStatus(Request $request, $id)
    {
        try {
            $aspiration = Aspiration::findOrFail($id);
            $aspiration->status = $request->status;
            $aspiration->save();
    
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 400);
        }
    }
    

    public function publicAspirationFilterCategory($category_id)
    {
        // $userUpvotes = UserUpvoteAspiration::all();
        $category = Category::findOrFail($category_id);
        // $aspirations = Aspiration::where("category_id", "like", $category_id)->orderByDesc('upvote')->paginate(10)->withQueryString();
        $aspirations = Aspiration::where("category_id", "like", $category_id)->paginate(10)->withQueryString();
        $categories = Category::all();

        $message = null;
        $statuses = [
            'Freshly Submitted',
            'In Review',
            'Approved',
            'In Progress',
            'Monitoring',
            'Completed',
            'Rejected',
        ];

        $data = [
            'aspirations' => $aspirations,
            'filterTitle' => $category->name,
            'categories' => $categories,
            'statuses' => $statuses,
            'message' => $message
            // 'userUpvotes' => $userUpvotes
        ];
        
        return view('aspiration.all.listAspiration', $data);
    }

    public function manageAspirationFilterCategory($category_id)
    {
        $category = Category::findOrFail($category_id);
        $aspirations = Aspiration::where("category_id", "like", $category_id)->paginate(10)->withQueryString();
        $categories = Category::all();
        $message = null;

        $data = [
            'aspirations' => $aspirations,
            'filterTitle' => $category->name,
            'categoryNow' => $category->id,
            'categories' => $categories,
            'message' => $message
        ];
        
        return view('aspiration.manageAspiration', $data);
    }
    
    public function manageAspirationFilterStatus($status)
    {
        // Retrieve the category IDs from the manageAspiration function
        $currUser = Auth::user();
        
        if (Auth::user()->role == "admin" || Auth::user()->role == "headmaster"){
            $aspirations = Aspiration::where("status", "like", $status)->paginate(10)->withQueryString();
        }
        else{
            $staffTypeId = $currUser->staffType_id;
            $categoryIds = Category::where('staffType_id', $staffTypeId)->pluck('id');
    
            // Use whereIn to filter aspirations by category_id and status
            $aspirations = Aspiration::whereIn('category_id', $categoryIds)
                ->where('status', $status)
                // ->orderByDesc('upvote')
                ->paginate(10)
                ->withQueryString();
        }

        $message = null;

        $data = [
            'aspirations' => $aspirations,
            'filterTitle' => $status,
            'statusNow' => $status,
            'message' => $message,
        ];

        return view('aspiration.manageAspiration', $data);
    }

    public function addAspiration(Request $request)
    {
        $currUser = Auth::user();

        $request->validate([
            'aspirationName' => 'required|max:50',
            'aspirationDescription' => 'required|max:10000',
            'aspirationCategory' => 'required',
        ]);

        // if ($request->aspirationEvidence != null){
        //     $file = $request->file('aspirationEvidence');
        //     $name = $file->getClientOriginalName();
        //     $filename = now()->timestamp.'_'.$name;
    
        //     $evidenceUrl = Storage::disk('public')->putFileAs('ListEvidence', $file, $filename);
        // }
        // else{
        //     $evidenceUrl = null;
        // }

        $currentYear = now()->year;
        $aspirationCount = Aspiration::whereYear('created_at', $currentYear)->count() + 1;
        $aspiration_no = sprintf('%03d/ASP/%d', $aspirationCount, $currentYear);

        Aspiration::create([
            'aspirationNo' => $aspiration_no,
            'user_id' => $currUser->id,
            'category_id' => $request->aspirationCategory,
            'name' => $request->aspirationName,
            'description' => $request->aspirationDescription,
            'processDate' => null,
            // 'processEstimationDate' => null,
            'processedBy' => null,
            'status' => 'Freshly Submitted',
            // 'evidence' => $evidenceUrl,
            // 'upvote' => null,
            'isChatOpened' => 0,
            'countProblematicAspiration' => null,
            'isPinned' => false,
            'deletedBy' => null,
            'deleteReason' => null
        ]);

        return redirect()->route('aspirations.publicAspirations');
    }

    public function showAddAspirationForm()
    {
        $categories = Category::all();
        return view('aspiration.student.create', compact('categories'));
    }

    public function updateAspirationForm($id){
        $aspiration = Aspiration::findorFail($id);
        $categories = Category::all();
        return view('aspiration.student.edit', compact('aspiration', 'categories'));
    }

    public function updateAspirationLogic(Request $request, $id)
    {
        $request->validate([
            'aspirationName' => 'required|max:50',
            'aspirationDescription' => 'required|max:200',
            'aspirationCategory' => 'required',
        ]);

        $aspiration = Aspiration::findOrFail($id);

        $aspiration->update([
            'category_id' => $request->aspirationCategory,
            'name' => $request->aspirationName,
            'description' => $request->aspirationDescription,
        ]);

        return redirect()->route('aspirations.myAspirations');
    }

    public function cancelAspiration(Request $request)
    {

        $aspiration = Aspiration::findOrFail($request->id);

        $aspiration->update([
            'status' => 'Canceled'
        ]);

        return redirect()->route('aspirations.myAspirations');
    }

    public function deleteAspiration(Request $request){
        $aspiration = Aspiration::find($request->id);
        $aspiration->delete();

        return redirect()->route('aspirations.myAspirations');
        
    }

    public function showDetail($aspiration_id)
    {
        $currUser = Auth::user();

        // Check if the user has already reported this aspiration
        $existingReport = UserReportAspiration::where('aspiration_id', $aspiration_id)
            ->where('user_id', $currUser->id)
            ->first();
        $aspiration = Aspiration::findOrFail($aspiration_id);
        return view('aspiration.detail', compact('aspiration', 'existingReport'));
    }

    public function rejectAspiration(Request $request){
        $aspiration = Aspiration::find($request->id);

        $aspiration->update([
            'status' => "Rejected",
        ]);
        return redirect()->route('aspirations.manageAspirations');
    }

    public function approveAspiration(Request $request){
        $aspiration = Aspiration::find($request->id);

        $aspiration->update([
            'status' => "Approved",
        ]);
        return redirect()->route('aspirations.manageAspirations');
    }

    public function requestApprovalAspiration(Request $request){
        $aspiration = Aspiration::find($request->id);

        $aspiration->update([
            'status' => "Requested Approval",
        ]);
        return redirect()->route('aspirations.manageAspirations');
    }

    public function onProgAspiration(Request $request){
        $aspiration = Aspiration::find($request->id);

        $aspiration->update([
            'status' => "In Progress",
        ]);
        return redirect()->route('aspirations.manageAspirations');
    }

    public function monitoringAspiration(Request $request){
        $aspiration = Aspiration::find($request->id);

        $aspiration->update([
            'status' => "Monitoring",
        ]);
        return redirect()->route('aspirations.manageAspirations');
    }

    public function finishAspiration(Request $request){
        $aspiration = Aspiration::find($request->id);

        $aspiration->update([
            'status' => "Done",
        ]);
        return redirect()->route('aspirations.manageAspirations');
    }

    public function pinAspiration(Request $request){
        $aspiration = Aspiration::find($request->id);

        $aspiration->update([
            'isPinned' => true,
        ]);

        $aspirations = Aspiration::paginate(10)->withQueryString();
        $categories = Category::all();
        $filterTitle = null;
        $message = "pin sukses";
        $statuses = [
            'Freshly Submitted',
            'In Review',
            'Approved',
            'In Progress',
            'Monitoring',
            'Completed',
            'Rejected',
        ];
        return view('aspiration.all.listAspiration', compact('aspirations', 'categories', 'filterTitle', 'message', 'statuses'));
    }

    public function unpinAspiration(Request $request){
        $aspiration = Aspiration::find($request->id);

        $aspiration->update([
            'isPinned' => false,
        ]);

        $aspirations = Aspiration::paginate(10)->withQueryString();
        $categories = Category::all();
        $filterTitle = null;
        $statuses = [
            'Freshly Submitted',
            'In Review',
            'Approved',
            'Rejected',
            'In Progress',
            'Monitoring',
            'Completed',
        ];
        $message = "unpin sukses";
        return view('aspiration.all.listAspiration', compact('aspirations', 'categories', 'filterTitle', 'message', 'statuses'));
    }

    // public function upvote(Request $request){
    //     $aspiration = Aspiration::find($request->id);
    //     $currUserId = Auth::user()->id;

    //     $aspiration->update([
    //         'upvote' => $aspiration->upvote + 1,
    //     ]);

    //     $userUpvote = new UserUpvoteAspiration();
    //     $userUpvote->user_id = $currUserId;
    //     $userUpvote->aspiration_id = $request->id;
    //     $userUpvote->save();

    //     return redirect()->route('aspirations.publicAspirations');
    // }

    // public function unUpvote(Request $request){
    //     $aspiration = Aspiration::find($request->id);
    //     $currUserId = Auth::user()->id;
    
    //     // Decrease the upvote count
    //     $aspiration->update([
    //         'upvote' => $aspiration->upvote - 1,
    //     ]);
    
    //     // Remove the user's upvote record
    //     $userUpvote = UserUpvoteAspiration::where('user_id', $currUserId)
    //                                        ->where('aspiration_id', $request->id)
    //                                        ->first();
    
    //     if ($userUpvote) {
    //         $userUpvote->delete();
    //     }
    
    //     return redirect()->route('aspirations.publicAspirations');
    // }
    


}