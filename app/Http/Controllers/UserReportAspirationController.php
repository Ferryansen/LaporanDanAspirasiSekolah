<?php

namespace App\Http\Controllers;

use App\Models\Aspiration;
use App\Models\User;
use App\Models\UserReportAspiration;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserReportAspirationController extends Controller
{

    public function createReportedAspiration(Request $request, $aspiration_id)
    {
        $currUser = Auth::user();

        $request->validate([
            'reportAspirationReason' => 'required|max:200',
        ]);

        UserReportAspiration::create([
            'aspiration_id' => $aspiration_id,
            'user_id' => $currUser->id,
            'reportReason' => $request->reportAspirationReason
        ]);

        return redirect()->back()->with('success', 'Aspiration reported successfully');
    }
    
    public function getAllReportedAspirations()
    {
        $userReportAspirations = UserReportAspiration::select('aspiration_id', \DB::raw('COUNT(*) as totalReports'))
            ->groupBy('aspiration_id')
            ->get();

        $aspirationIds = $userReportAspirations->pluck('aspiration_id');

        $aspirations = Aspiration::with(['reportedByUsers' => function ($query) use ($aspirationIds) {
            $query->whereIn('aspiration_id', $aspirationIds);
        }])
        ->find($aspirationIds);

        foreach ($aspirations as $aspiration) {
            $aspiration->user_report_aspirations_count = $userReportAspirations
                ->where('aspiration_id', $aspiration->id)
                ->first()
                ->totalReports ?? 0;
        }

        return view('aspiration.admin.reportedAspirationsView', compact('aspirations'));
    }

    public function getAllReportedAspirationDetail($aspiration_id)
    {
        $aspiration = Aspiration::where('id', $aspiration_id)->firstOrFail();
        $reportReasons = UserReportAspiration::where('aspiration_id', $aspiration_id)->pluck('reportReason');

        return view('aspiration.admin.reportedAspirationDetailView', compact('aspiration', 'reportReasons'));
    }

    public function deleteReportedAspiration(Request $request){
        $aspiration_id = $request->id;    
    
        UserReportAspiration::where('aspiration_id', $aspiration_id)->delete();
        
        $aspiration = Aspiration::findOrFail($aspiration_id);
        $aspiration->delete();
    
        return redirect()->route('aspirations.reported')->with('successMessage', 'Aspirasi berhasil dihapus');
    }

    public function getAllSuspendedUsers() {
        $suspendedUsers = User::where('role', '=', 'student')
        ->where('isSuspended', true)
        ->paginate(10)->withQueryString();

        return view('aspiration.admin.manageSuspendedUserView', compact('suspendedUsers'));
    }

    public function suspend(Request $request, $user_id) {
        $user = User::findOrFail($user_id);
        $message = null;
 
        $rules = [
            'suspendReason' => 'required|max:250',
        ];

        $messages = [
            'suspendReason.required' => 'Jangan lupa masukin alasan suspend-nya yaa',
            'suspendReason.max' => 'Alasan yang kamu masukin cuman bisa maksimal 250 karakter nih',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $credentials['isSuspended'] = true;
        $credentials['suspendReason'] = $request->suspendReason;
        $credentials['suspendDate'] = Carbon::now()->format('Ymd');

        $message = 'Suspend berhasil! Pengguna sudah tidak dapat menyampaikan aspirasi';

        $user->update($credentials);
        return redirect()->back()->with('successMessage', $message);
    }

    public function unsuspend($user_id) {
        $user = User::findOrFail($user_id);
        
        $credentials['isSuspended'] = false;
        $credentials['suspendReason'] = null;
        $credentials['suspendDate'] = null;
        
        $user->update($credentials);
        $message = 'Pencabutan suspend berhasil! Pengguna sudah dapat manyampaikan aspirasi kembali';
        return redirect()->back()->with('successMessage', $message);
    }
}