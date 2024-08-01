<?php

namespace App\Http\Controllers;

use App\Mail\CompleteAspirationHeadmasterNotificationEmail;
use App\Mail\CompleteAspirationStudentNotificationEmail;
use App\Mail\RequestAspirationHeadmasterNotificationEmail;
use App\Models\Aspiration;
use App\Models\Category;
// use App\Models\UserUpvoteAspiration;
use App\Models\User;
use Exception;
use App\Models\UserReportAspiration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;


class AspirationController extends Controller
{
    public function myAspiration()
    {
        $currUser = Auth::user();
        
        if ($currUser->isSuspended == true) {
            $message = 'Kamu tidak bisa mengakses fitur ini, kamu sedang ter-suspend!';
    
            return view('aspiration.student.myAspiration', compact('message'));
        }

        $aspirations = $currUser->aspirations()->orderBy('created_at', 'desc')->paginate(10)->withQueryString();
        return view('aspiration.student.myAspiration', compact('aspirations'));
    }

    public function showComments($id)
    {
        $aspiration = Aspiration::findOrFail($id);
        return view('aspiration.all.comment', compact('aspiration'));
    }

    public function publicAspiration()
    {
        $selectedCategoryId ="";
        $currUser = Auth::user();
        $message = null;
        $failMessage = "";
        
        if ($currUser->isSuspended == true) {
            $message = 'Kamu tidak bisa mengakses fitur ini, kamu sedang ter-suspend!';
    
            return view('aspiration.all.listAspiration', compact('message'));
        }

        // $userUpvotes = UserUpvoteAspiration::all();
        $categories = Category::all();
        $statuses = [
            'Freshly submitted',
            'In review',
            'Approved',
            'In Progress',
            'Monitoring',
            'Completed',
            'Rejected',
            'Closed',
        ];
        $filterTitle = null;
        $typeSorting = "";
        $aspirations = Aspiration::orderBy('created_at', 'desc')->paginate(10)->withQueryString();
        $pinnedAspirations = Aspiration::where('isPinned', true)
                                            ->orderBy('created_at', 'desc')
                                            ->get();

        Session::put('selected_category', "Semua kategori");

        return view('aspiration.all.listAspiration', compact('aspirations', 'pinnedAspirations', 'categories', 'filterTitle', 'statuses', 'message', 'typeSorting', 'selectedCategoryId', 'failMessage'));
    }

    public function publicAspirationSorting($typeSorting)
    {
        $selectedCategoryId = "";
        $currUser = Auth::user();
        $message = null;
        $failMessage = "";
        
        if ($currUser->isSuspended == true) {
            $message = 'Kamu tidak bisa mengakses fitur ini, kamu sedang ter-suspend!';
    
            return view('aspiration.all.listAspiration', compact('message'));
        }

        // $userUpvotes = UserUpvoteAspiration::all();
        $categories = Category::all();
        $statuses = [
            'Freshly submitted',
            'In review',
            'Approved',
            'In Progress',
            'Monitoring',
            'Completed',
            'Rejected',
            'Closed',
        ];
        $filterTitle = null;
        // $aspirations = Aspiration::orderByDesc('upvote') // Sort in descending order based on upvote count
        if($typeSorting == 'Paling Disukai') {
            $aspirations = Aspiration::withCount(['reactions' => function ($query) {
                $query->where('reaction', 'like');
            }])->orderByDesc('reactions_count')->paginate(10)->withQueryString();
        } else if ($typeSorting == 'Terpopuler') {
            $aspirations = Aspiration::withCount('comments')->orderByDesc('comments_count')->paginate(10)->withQueryString();
        } else {
            $aspirations = Aspiration::orderBy('created_at', 'desc')->paginate(10)->withQueryString();
        }
        
        $pinnedAspirations = Aspiration::where('isPinned', true)
                                            ->orderBy('created_at', 'desc')
                                            ->get();

        return view('aspiration.all.listAspiration', compact('aspirations', 'pinnedAspirations', 'categories', 'filterTitle', 'statuses', 'message', 'typeSorting', 'selectedCategoryId', 'failMessage'));
    }
    
    public function manageAspiration()
    {
        $currentUser = Auth::user();
        $allUser = User::all();
        $selectedCategoryId = '';

        // Fetch users with the same staff_id as the current user
        $users = User::where('staffType_id', $currentUser->staffType_id)->get();

        $categories = Category::all();
            $idx = 0;
        foreach ($categories as $category) {
            if (strpos($category->name, "Lainnya") !== false){
                $idx = $category->id;
                break;
            }
        }

        if ($currentUser->role == 'headmaster') {
            // Fetch all aspirations if the user is a headmaster
            $aspirations = Aspiration::all();
        } else {
            // Fetch aspirations where the category's staffType_id matches the current user's staffType_id
            $aspirations = Aspiration::where(function($query) use ($currentUser, $idx) {
                $query->whereHas('category', function ($query) use ($currentUser) {
                    $query->whereHas('staffType', function ($query) use ($currentUser) {
                        $query->where('id', $currentUser->staffType_id);
                    });
                })->orWhere('category_id', $idx);
            })->get();
        }
        Session::put('selected_category', "Semua kategori");

        // Pass the users and aspirations to the view
        return view('aspiration.staffHeadmaster.manageAspiration', compact('users', 'aspirations', 'allUser', 'categories', 'selectedCategoryId'));
    }

    public function updateStatus(Request $request)
    {
        try {
            DB::beginTransaction();
    
            // Validate the request
            $request->validate([
                'status' => 'required',
                'aspiration_id' => 'required|exists:aspirations,id',
            ]);
            
            // Find the aspiration
            $aspiration = Aspiration::find($request->aspiration_id);
            $headmasters = User::where('role', 'headmaster')->get();
            
            $aspirationData = [
                'aspirationID' => $aspiration->id,
                'aspirationNo' => $aspiration->aspirationNo,
                'title' => $aspiration->name,
                'relatedStaff' => $aspiration->processedBy
            ];
            
            if ($request->status == 'Request Approval') {
                foreach ($headmasters as $headmaster) {
                    Mail::to($headmaster->email)->queue(new RequestAspirationHeadmasterNotificationEmail($headmaster->name, $aspirationData));
                }
            }
            
            if ($request->status == 'Approved'){
                $aspiration->approvedBy = Auth::user()->id;
            }

            // Update the status field
            $aspiration->status = $request->status;
            $aspiration->save();

            DB::commit();
    
            // Redirect back with a success message
            return redirect()->back()->with('successMessage', 'Status aspirasi telah diubah');
        } catch (Exception $e) {
            DB::rollBack();
    
            // Log the error
            Log::error('Error updating status aspiration: ' . $e->getMessage());
    
            // Return back with error message
            return redirect()->back()->with('errorMessage', 'Terjadi kesalahan dalam perubahan status. Silakan coba lagi.');
        }
    }

    public function assign(Request $request)
    {
        // Validate the request
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'aspiration_id' => 'required|exists:aspirations,id',
        ]);

        // Find the aspiration
        $aspiration = Aspiration::find($request->aspiration_id);

        // Find the user
        $user = User::find($request->user_id);

        // Update the processedBy field
        $aspiration->processedBy = $user->id;
        $aspiration->save();

        // Redirect back with a success message
        return redirect()->back()->with('success', 'Penanggung jawab telah diubah');
    }

    public function updateProcessedBy(Request $request)
    {
        // Validate the request
        $request->validate([
            'aspiration_id' => 'required|exists:aspirations,id',
        ]);

        // Find the aspiration
        $aspiration = Aspiration::find($request->aspiration_id);

        // Set the processedBy field to the current user's name
        $aspiration->processedBy = Auth::user()->id;
        $aspiration->status = "In review";
        $aspiration->save();

        // Redirect back with a success message
        return redirect()->route('aspirations.manageAspiration');
    }
    

    public function publicAspirationFilterCategory($category_id)
    {
        $selectedCategoryId = $category_id;
        // $userUpvotes = UserUpvoteAspiration::all();
        $category = Category::findOrFail($category_id);
        // $aspirations = Aspiration::where("category_id", "like", $category_id)->orderByDesc('upvote')->paginate(10)->withQueryString();
        $aspirations = Aspiration::where("category_id", "like", $category_id)->orderBy('created_at', 'desc')->paginate(10)->withQueryString();
        $pinnedAspirations = Aspiration::where('isPinned', true)
                                            ->where('category_id',              $category_id)
                                            ->orderBy('created_at', 'desc')
                                            ->get();
        $categories = Category::all();

        $message = null;
        $statuses = [
            'Freshly submitted',
            'In review',
            'Approved',
            'In Progress',
            'Monitoring',
            'Completed',
            'Rejected',
            'Closed',
        ];

        $typeSorting = "";

        $data = [
            'aspirations' => $aspirations,
            'pinnedAspirations' => $pinnedAspirations,
            'filterTitle' => $category->name,
            'categories' => $categories,
            'statuses' => $statuses,
            'message' => $message,
            'typeSorting' => $typeSorting,
            'selectedCategoryId' => $selectedCategoryId,
            'failMessage' => ""
        ];
        
        return view('aspiration.all.listAspiration', $data);
    }

    public function manageAspirationFilterCategory($category_id)
    {
        $currentUser = Auth::user();
        $allUser = User::all();

        $selectedCategoryId = $category_id;
        // Fetch users with the same staff_id as the current user
        $users = User::where('staffType_id', $currentUser->staffType_id)->get();

        $categories = Category::all();
            
        $category = Category::findOrFail($category_id);
        $aspirations = Aspiration::where("category_id", "like", $category_id)->paginate(10)->withQueryString();
        
        return view('aspiration.staffHeadmaster.manageAspiration', compact('users', 'aspirations', 'allUser', 'categories', 'selectedCategoryId'));
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

        $rules = [
            'aspirationName' => 'required|max:50',
            'aspirationDescription' => 'required|max:254',
            'aspirationCategory' => 'required',
        ];

        $messages = [
            'aspirationName.required' => 'Jangan lupa masukin judul yaa',
            'aspirationName.max' => 'Judul yang kamu masukin cuman bisa maksimal 50 karakter nih',
            
            'aspirationDescription.required' => 'Jangan lupa masukin deskripsi yaa',
            'aspirationDescription.max' => 'Deskripsi yang kamu masukin cuman bisa maksimal 255 karakter nih',

            'aspirationCategory.required' => 'Jangan lupa pilih kategori yaa',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);
    
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

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
            $latestAspiration = Aspiration::whereYear('created_at', $currentYear)->latest('created_at')->first();
            if(!$latestAspiration){
                $numberAspiration = 1;
            }
            else{
                $numberAspiration = intval(substr($latestAspiration->aspirationNo, 0, 3)) + 1;
            }
            
            $aspiration_no = sprintf('%03d/ASP/%d', $numberAspiration, $currentYear);

        Aspiration::create([
            'aspirationNo' => $aspiration_no,
            'user_id' => $currUser->id,
            'category_id' => $request->aspirationCategory,
            'name' => $request->aspirationName,
            'description' => $request->aspirationDescription,
            'processDate' => null,
            // 'processEstimationDate' => null,
            'processedBy' => null,
            'status' => 'Freshly submitted',
            // 'evidence' => $evidenceUrl,
            // 'upvote' => null,
            'countProblematicAspiration' => null,
            'isPinned' => false,
            'rejectReason' => null,
            'closedReason' => null,
            'deletedBy' => null,
            'deleteReason' => null,
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
        $rules = [
            'aspirationName' => 'required|max:50',
            'aspirationDescription' => 'required|max:200',
            'aspirationCategory' => 'required',
        ];

        $messages = [
            'aspirationName.required' => 'Jangan lupa masukin judul yaa',
            'aspirationName.max' => 'Judul yang kamu masukin cuman bisa maksimal 50 karakter nih',
            
            'aspirationDescription.required' => 'Jangan lupa masukin deskripsi yaa',
            'aspirationDescription.max' => 'Deskripsi yang kamu masukin cuman bisa maksimal 255 karakter nih',

            'aspirationCategory.required' => 'Jangan lupa pilih kategori yaa',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);
    
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $aspiration = Aspiration::findOrFail($id);

        $aspiration->update([
            'category_id' => $request->aspirationCategory,
            'name' => $request->aspirationName,
            'description' => $request->aspirationDescription,
        ]);

        return redirect()->route('aspirations.myAspirations');
    }

    public function deleteAspiration(Request $request){
        $aspiration = Aspiration::find($request->id);
        $aspiration->delete();

        return redirect()->route('aspirations.myAspirations');
        
    }

    public function rejectAspiration(Request $request){
        $aspiration = Aspiration::find($request->id);

        $aspiration->update([
            'status' => "Rejected",
            'rejectReason' => $request->rejectReason,
        ]);
        return redirect()->route('aspirations.manageAspiration');
    }
    
    public function closeAspiration(Request $request){
        $aspiration = Aspiration::find($request->id);

        $aspiration->update([
            'status' => "Closed",
            'closedReason' => $request->closedReason,
        ]);
        return redirect()->route('aspirations.manageAspiration');
    }

    public function finishAspiration(Request $request){
        DB::beginTransaction();
    
        try {
            $aspiration = Aspiration::find($request->id);
            $headmasters = User::where('role', 'headmaster')->get();
            $positiveReactions = $aspiration->likes()->whereHas('user', function($query) {
                $query->where('role', 'student');
            })
            ->get();
    
            $request->validate([
                'aspirationEvidences.*' => 'file|mimes:png,jpg,jpeg,webp,mp4,avi,quicktime|max:40960',
                'aspirationEvidences' => [
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
    
            if ($request->hasFile('aspirationEvidences')) {
                foreach ($request->file('aspirationEvidences') as $file) {
                    if ($file instanceof \Illuminate\Http\UploadedFile) {
                        $name = $file->getClientOriginalName();
                        $filename = now()->timestamp . '_' . $name;
                        $extension = $file->getClientOriginalExtension();
    
                        if (in_array($extension, ['mp4', 'avi', 'quicktime'])) {
                            $videoUrl = Storage::disk('public')->putFileAs('ListVideo', $file, $filename);
                            $aspiration->evidences()->create([
                                'video' => $videoUrl,
                                'name' => $name,
                                'context' => 'completion',
                            ]);
                        } else {
                            $imageUrl = Storage::disk('public')->putFileAs('ListImage', $file, $filename);
                            $aspiration->evidences()->create([
                                'image' => $imageUrl,
                                'name' => $name,
                                'context' => 'completion',
                            ]);
                        }
                    }
                }
            }
    
            $aspiration->status = 'Completed';
            $aspiration->save();
    
            $aspirationData = [
                'aspirationID' => $aspiration->id,
                'aspirationNo' => $aspiration->aspirationNo,
                'title' => $aspiration->name,
                'relatedStaff' => $aspiration->processedBy
            ];
    
            $aspirationOwner = $aspiration->user;
            Mail::to($aspirationOwner->email)->queue(new CompleteAspirationStudentNotificationEmail($aspirationOwner->name, $aspirationData));
    
            foreach ($positiveReactions as $positiveReaction) {
                $student = User::findOrFail($positiveReaction->user_id);
                if ($student != $aspirationOwner) {
                    Mail::to($student->email)->queue(new CompleteAspirationStudentNotificationEmail($student->name, $aspirationData));
                }
            }
    
            foreach ($headmasters as $headmaster) {
                Mail::to($headmaster->email)->queue(new CompleteAspirationHeadmasterNotificationEmail($headmaster->name, $aspirationData));
            }
    
            DB::commit();
    
            return redirect()->route('aspirations.manageAspiration')->with('successMessage', 'Aspirasi berhasil diselesaikan');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error finishing aspiration: ' . $e->getMessage());
    
            return redirect()->back()->with('errorMessage', 'Terjadi kesalahan dalam penyelesaian, tolong dicoba lagi yaa');
        }
    }

    public function pinAspiration(Request $request){
        $aspiration = Aspiration::find($request->id);

        $pinnedCount = Aspiration::where('isPinned', true)->count();

        $selectedCategoryId = "";

        $typeSorting = "";
        $failMessage = "";

        // Check if there are already 5 pinned aspirations
        if ($pinnedCount >= 5) {
            // If there are 5 or more pinned aspirations, return with fail message
            $aspirations = Aspiration::orderBy('created_at', 'desc')->paginate(10)->withQueryString();
            $categories = Category::all();
            $filterTitle = null;
            $failMessage = "Hanya boleh ada 5 aspirasi yang di-pin bersamaan.";

            $statuses = [
                'Freshly submitted',
                'In review',
                'Approved',
                'In Progress',
                'Monitoring',
                'Completed',
                'Rejected',
                'Closed',
            ];

            return view('aspiration.all.listAspiration', compact('aspirations', 'categories', 'filterTitle', 'statuses', 'selectedCategoryId', 'typeSorting', 'failMessage'));
        }

        $aspiration->update([
            'isPinned' => true,
        ]);

        $aspirations = Aspiration::orderBy('created_at', 'desc')->paginate(10)->withQueryString();
        $pinnedAspirations = Aspiration::where('isPinned', true)
                                            ->orderBy('created_at', 'desc')
                                            ->get();
        $categories = Category::all();
        $filterTitle = null;
        $message = "pin sukses";
        $statuses = [
            'Freshly submitted',
            'In review',
            'Approved',
            'In Progress',
            'Monitoring',
            'Completed',
            'Rejected',
            'Closed',
        ];
        return view('aspiration.all.listAspiration', compact('aspirations', 'pinnedAspirations', 'categories', 'filterTitle', 'message', 'statuses', 'selectedCategoryId', 'typeSorting', 'failMessage'));
    }

    public function unpinAspiration(Request $request){
        $aspiration = Aspiration::find($request->id);
        $selectedCategoryId = "";
        $typeSorting = "";
        $failMessage = "";

        $aspiration->update([
            'isPinned' => false,
        ]);

        $aspirations = Aspiration::orderBy('created_at', 'desc')->paginate(10)->withQueryString();
        $pinnedAspirations = Aspiration::where('isPinned', true)
                                            ->orderBy('created_at', 'desc')
                                            ->get();
        $categories = Category::all();
        $filterTitle = null;
        $statuses = [
            'Freshly submitted',
            'In review',
            'Approved',
            'Rejected',
            'Closed',
            'In Progress',
            'Monitoring',
            'Completed',
        ];
        $message = "unpin sukses";
        return view('aspiration.all.listAspiration', compact('aspirations', 'pinnedAspirations', 'categories', 'filterTitle', 'message', 'statuses', 'selectedCategoryId', 'typeSorting', 'failMessage'));
    }

    public function manageAspirationDetail($aspiration_id) {
        $aspiration = Aspiration::findOrFail($aspiration_id);
        $evidences = $aspiration->evidences()->where('context', 'completion')->get();

        return view('aspiration.staffHeadmaster.manageAspirationDetailView', compact('aspiration', 'evidences'));
    }

}