<?php

namespace App\Http\Controllers;

use App\Models\Aspiration;
use App\Models\Report;
use App\Models\StaffType;
use App\Models\Category;
use App\Models\ConsultationEvent;

use Illuminate\Support\Carbon;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ImportUser;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{    
    public function getDashboard() {

        $categories = Category::all();
        $currUserRole = Auth::user()->role;
        $statuses = ['In Progress', 'Request Approval', 'Rejected', 'Completed', 'Closed'];

        if ($currUserRole == 'headmaster') {
            $reports = Report::whereIn('status', $statuses)->orderBy('created_at', 'desc')->get();
            $aspirations = Aspiration::withCount(['likes'])
                             ->whereIn('status', $statuses)->orderBy('likes_count', 'desc')
                             ->get();
        }else{
        $reports = Report::all()->sortByDesc('created_at');
        $aspirations = Aspiration::withCount(['likes'])
                         ->orderBy('likes_count', 'desc')
                         ->get();
        }
        $users = User::all();
        $staffTypes = StaffType::all();

        // Base query to get today's reports
        $laporanForFilterQuery = Report::whereDate('created_at', Carbon::today());

        // Filter reports based on the user's role
        if ($currUserRole == 'headmaster') {
            $laporanForFilterQuery->whereIn('status', $statuses);
        }

        // Get the filtered reports
        $laporanForFilter = $laporanForFilterQuery->get();

        // Apply the same filtering logic for the counts
        if ($currUserRole == 'headmaster') {
            $laporanToday = Report::whereDate('created_at', Carbon::today())
                                ->whereIn('status', $statuses)
                                ->count();

            $laporanYesterday = Report::whereDate('created_at', Carbon::yesterday())
                                    ->whereIn('status', $statuses)
                                    ->count();
        } else {
            $laporanToday = Report::whereDate('created_at', Carbon::today())->count();
            $laporanYesterday = Report::whereDate('created_at', Carbon::yesterday())->count();
        }

        if ($laporanYesterday != 0) {
            // Calculate the percentage change
            $persenLaporan = ($laporanToday - $laporanYesterday) / $laporanYesterday * 100;
            $statusLaporan = $persenLaporan >= 0 ? "increase" : "decrease";
        } else {
            // Handle the case when $laporanYesterday is zero
            $persenLaporan = 0;
            $statusLaporan = "N/A"; // You can set any default value or handle it differently
        }


         // Base query to get today's reports
         $aspirasiForFilterQuery = Aspiration::whereDate('created_at', Carbon::today());

         // Filter reports based on the user's role
         if ($currUserRole == 'headmaster') {
             $aspirasiForFilterQuery->whereIn('status', $statuses);
         }
 
         // Get the filtered reports
         $aspirasiForFilter = $aspirasiForFilterQuery->get();
 
         // Apply the same filtering logic for the counts
         if ($currUserRole == 'headmaster') {
             $aspirasiToday = Aspiration::whereDate('created_at', Carbon::today())
                                 ->whereIn('status', $statuses)
                                 ->count();
 
             $aspirasiYesterday = Aspiration::whereDate('created_at', Carbon::yesterday())
                                     ->whereIn('status', $statuses)
                                     ->count();
         } else {
             $aspirasiToday = Aspiration::whereDate('created_at', Carbon::today())->count();
             $aspirasiYesterday = Aspiration::whereDate('created_at', Carbon::yesterday())->count();
         }

        if ($aspirasiYesterday != 0) {
            // Calculate the percentage change
            $persenAspirasi = ($aspirasiToday - $aspirasiYesterday) / $aspirasiYesterday * 100;
            $statusAspirasi = $persenAspirasi >= 0 ? "increase" : "decrease";
        } else {
            // Handle the case when $laporanYesterday is zero
            $persenAspirasi = 0;
            $statusAspirasi = "N/A"; // You can set any default value or handle it differently
        }

        $laporanCategoryForFilterRole = Report::whereDate('created_at', Carbon::today());

        if ($currUserRole == 'headmaster') {
            $laporanCategoryForFilterRole->whereIn('status', $statuses);
        }

        $laporanCategoryForFilter =  $laporanCategoryForFilterRole->get();

        $aspirasiCategoryForFilterRole = Aspiration::whereDate('created_at', Carbon::today());

        if ($currUserRole == 'headmaster') {
            $aspirasiCategoryForFilterRole->whereIn('status', $statuses);
        }

        $aspirasiForFilterStatusQuery = Aspiration::whereDate('created_at', Carbon::today());

        // Filter reports based on the user's role
        if ($currUserRole == 'headmaster') {
            $aspirasiForFilterStatusQuery->whereIn('status', $statuses);
        }

        // Get the filtered reports
        $aspirasiStatusFilter = $aspirasiForFilterStatusQuery->get();

        $laporanForFilterStatusQuery = Report::whereDate('created_at', Carbon::today());

        // Filter reports based on the user's role
        if ($currUserRole == 'headmaster') {
            $laporanForFilterStatusQuery->whereIn('status', $statuses);
        }

        // Get the filtered reports
        $laporanStatusFilter = $laporanForFilterStatusQuery->get();

        $aspirasiCategoryForFilter = $aspirasiCategoryForFilterRole->get();

        $konsultasiToday = ConsultationEvent::whereDate('start', Carbon::today())
                                ->count();

        $konsultasiYesterday = ConsultationEvent::whereDate('start', Carbon::yesterday())
                                ->count();

        if ($konsultasiYesterday != 0) {
            $persenKonsultasi = ($konsultasiToday - $konsultasiYesterday) / $konsultasiYesterday * 100;
            $statusKonsultasi = $persenKonsultasi >= 0 ? "increase" : "decrease";
        }else{
            $persenKonsultasi = 0;
            $statusKonsultasi = "N/A";
        }

        $konsultasiCount = ConsultationEvent::whereDate('start', Carbon::today())
        ->count();

        $statusFilter = "Today";
        $laporanCountFilter = "Today";
        $aspirasiCountFilter = "Today";
        $laporanCategoryFilter = "Today";
        $aspirasiCategoryFilter = "Today";
        $konsultasiFilter = "Today";


        return view('user.admin.dashboard',compact('konsultasiCount', 'persenKonsultasi', 'statusKonsultasi', 'konsultasiFilter','currUserRole','persenLaporan', 'statusLaporan', 'persenAspirasi', 'statusAspirasi', 'laporanCountFilter', 'aspirasiCountFilter', 'laporanCategoryFilter', 'aspirasiCategoryFilter', 'categories', 'reports', 'aspirations', 'users', 'staffTypes', 'aspirasiCategoryForFilter', 'laporanCategoryForFilter', 'aspirasiForFilter', 'laporanForFilter', 'statusFilter', 'laporanStatusFilter', 'aspirasiStatusFilter'));
    }

    public function getDashboardddFiltered(Request $request) {
        $currUserRole = Auth::user()->role;
        $statuses = ['In Progress', 'Request Approval', 'Rejected', 'Completed', 'Closed'];

        $konsultasiFilter = $request->konsultasiFilter;
        if ($konsultasiFilter == "Today") {
            $konsultasiToday = ConsultationEvent::whereDate('start', Carbon::today())
                                ->count();

            $konsultasiYesterday = ConsultationEvent::whereDate('start', Carbon::yesterday())
                                    ->count();

            if ($konsultasiYesterday != 0) {
                $persenKonsultasi = ($konsultasiToday - $konsultasiYesterday) / $konsultasiYesterday * 100;
                $statusKonsultasi = $persenKonsultasi >= 0 ? "increase" : "decrease";
            }else{
                $persenKonsultasi = 0;
                $statusKonsultasi = "N/A";
            }

            $konsultasiCount = ConsultationEvent::whereDate('start', Carbon::today())
            ->count();
        }elseif ($konsultasiFilter == "This Month") {
            $konsultasiToday = ConsultationEvent::whereMonth('start', Carbon::now()->month)->whereYear('start', Carbon::now()->year)
                                ->count();

            $konsultasiYesterday = ConsultationEvent::whereMonth('start', Carbon::now()->subMonth()->month)
                                ->whereYear('start', Carbon::now()->subMonth()->year)
                                ->count();

            if ($konsultasiYesterday != 0) {
                $persenKonsultasi = ($konsultasiToday - $konsultasiYesterday) / $konsultasiYesterday * 100;
                $statusKonsultasi = $persenKonsultasi >= 0 ? "increase" : "decrease";
            }else{
                $persenKonsultasi = 0;
                $statusKonsultasi = "N/A";
            }

            $konsultasiCount = ConsultationEvent::whereMonth('start', Carbon::now()->month)->whereYear('start', Carbon::now()->year)
            ->count();
        }else {
            $konsultasiToday = ConsultationEvent::whereYear('start', Carbon::now()->year)
                                ->count();

            $konsultasiYesterday = ConsultationEvent::whereYear('start', Carbon::now()->subYear()->year)
                                ->count();

            if ($konsultasiYesterday != 0) {
                $persenKonsultasi = ($konsultasiToday - $konsultasiYesterday) / $konsultasiYesterday * 100;
                $statusKonsultasi = $persenKonsultasi >= 0 ? "increase" : "decrease";
            }else{
                $persenKonsultasi = 0;
                $statusKonsultasi = "N/A";
            }

            $konsultasiCount = ConsultationEvent::whereYear('start', Carbon::now()->year)
            ->count();
        }

        $laporanCountFilter = $request->laporanCountFilter;
        if($laporanCountFilter == "Today"){
            $laporanForFilterQuery = Report::whereDate('created_at', Carbon::today());

            // Filter reports based on the user's role
            if ($currUserRole == 'headmaster') {
                $laporanForFilterQuery->whereIn('status', $statuses);
            }
    
            // Get the filtered reports
            $laporanForFilter = $laporanForFilterQuery->get();
    
            // Apply the same filtering logic for the counts
            if ($currUserRole == 'headmaster') {
                $laporanToday = Report::whereDate('created_at', Carbon::today())
                                    ->whereIn('status', $statuses)
                                    ->count();
    
                $laporanYesterday = Report::whereDate('created_at', Carbon::yesterday())
                                        ->whereIn('status', $statuses)
                                        ->count();
            } else {
                $laporanToday = Report::whereDate('created_at', Carbon::today())->count();
                $laporanYesterday = Report::whereDate('created_at', Carbon::yesterday())->count();
            }

            if ($laporanYesterday != 0) {
                // Calculate the percentage change
                $persenLaporan = ($laporanToday - $laporanYesterday) / $laporanYesterday * 100;
                $statusLaporan = $persenLaporan >= 0 ? "increase" : "decrease";
            } else {
                // Handle the case when $laporanYesterday is zero
                $persenLaporan = 0;
                $statusLaporan = "N/A"; // You can set any default value or handle it differently
            }
        }elseif($laporanCountFilter == "This Month"){
            $laporanForFilterQuery = Report::whereMonth('created_at', Carbon::now()->month)
                ->whereYear('created_at', Carbon::now()->year);

            // Filter reports based on the user's role
            if ($currUserRole == 'headmaster') {
                $laporanForFilterQuery->whereIn('status', $statuses);
            }
    
            // Get the filtered reports
            $laporanForFilter = $laporanForFilterQuery->get();

            if ($currUserRole == 'headmaster') {
                $laporanThisMonth = Report::whereMonth('created_at', Carbon::now()->month)
                ->whereYear('created_at', Carbon::now()->year)->whereIn('status', $statuses)
                ->count();
    
                $laporanLastMonth = Report::whereMonth('created_at', Carbon::now()->subMonth()->month)
                ->whereYear('created_at', Carbon::now()->subMonth()->year)->whereIn('status', $statuses)
                ->count();
            } else {
                $laporanThisMonth = Report::whereMonth('created_at', Carbon::now()->month)
                ->whereYear('created_at', Carbon::now()->year)
                ->count();
                $laporanLastMonth = Report::whereMonth('created_at', Carbon::now()->subMonth()->month)
                ->whereYear('created_at', Carbon::now()->subMonth()->year)
                ->count();
            }

            if ($laporanLastMonth != 0) {
                // Calculate the percentage change
                $persenLaporan = ($laporanThisMonth - $laporanLastMonth) / $laporanLastMonth * 100;
                $statusLaporan = $persenLaporan >= 0 ? "increase" : "decrease";
            } else {
                // Handle the case when $laporanYesterday is zero
                $persenLaporan = 0;
                $statusLaporan = "N/A"; // You can set any default value or handle it differently
            }
        }elseif($laporanCountFilter == "This Year"){
            $laporanForFilterQuery = Report::whereYear('created_at', Carbon::now()->year);

            // Filter reports based on the user's role
            if ($currUserRole == 'headmaster') {
                $laporanForFilterQuery->whereIn('status', $statuses);
            }

            // Get the filtered reports
            $laporanForFilter = $laporanForFilterQuery->get();

            if ($currUserRole == 'headmaster') {
                $laporanThisYear = Report::
                whereYear('created_at', Carbon::now()->year)->whereIn('status', $statuses)
                ->count();
    
                $laporanLastYear = Report::whereYear('created_at', Carbon::now()->subYear()->year)->whereIn('status', $statuses)
                ->count();
            } else {
                $laporanThisYear = Report::whereYear('created_at', Carbon::now()->year)
                ->count();
                $laporanLastYear = Report::whereYear('created_at', Carbon::now()->subYear()->year)->count();
            }

            if ($laporanLastYear != 0) {
                // Calculate the percentage change
                $persenLaporan = ($laporanThisYear - $laporanLastYear) / $laporanLastYear * 100;
                $statusLaporan = $persenLaporan >= 0 ? "increase" : "decrease";
            } else {
                // Handle the case when $laporanYesterday is zero
                $persenLaporan = 0;
                $statusLaporan = "N/A"; // You can set any default value or handle it differently
            }
        }else{
            if ($currUserRole == 'headmaster') {
            $laporanForFilter = Report::whereIn('status', $statuses)->get();
            }else{
            $laporanForFilter = Report::all();
            }
        }

        $aspirasiCountFilter = $request->aspirasiCountFilter;
        if($aspirasiCountFilter == "Today"){
            $aspirasiForFilterQuery = Aspiration::whereDate('created_at', Carbon::today());

            // Filter reports based on the user's role
            if ($currUserRole == 'headmaster') {
                $aspirasiForFilterQuery->whereIn('status', $statuses);
            }
    
            // Get the filtered reports
            $aspirasiForFilter = $aspirasiForFilterQuery->get();
    
            // Apply the same filtering logic for the counts
            if ($currUserRole == 'headmaster') {
                $aspirasiToday = Aspiration::whereDate('created_at', Carbon::today())
                                    ->whereIn('status', $statuses)
                                    ->count();
    
                $aspirasiYesterday = Aspiration::whereDate('created_at', Carbon::yesterday())
                                        ->whereIn('status', $statuses)
                                        ->count();
            } else {
                $aspirasiToday = Aspiration::whereDate('created_at', Carbon::today())->count();
                $aspirasiYesterday = Aspiration::whereDate('created_at', Carbon::yesterday())->count();
            }

            if ($aspirasiYesterday != 0) {
                // Calculate the percentage change
                $persenAspirasi = ($aspirasiToday - $aspirasiYesterday) / $aspirasiYesterday * 100;
                $statusAspirasi = $persenAspirasi >= 0 ? "increase" : "decrease";
            } else {
                // Handle the case when $laporanYesterday is zero
                $persenAspirasi = 0;
                $statusAspirasi = "N/A"; // You can set any default value or handle it differently
            }
        }elseif($aspirasiCountFilter == "This Month"){
            $aspirasiForFilterQuery = Aspiration::whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year);

            // Filter reports based on the user's role
            if ($currUserRole == 'headmaster') {
                $aspirasiForFilterQuery->whereIn('status', $statuses);
            }

            // Get the filtered reports
            $aspirasiForFilter = $aspirasiForFilterQuery->get();

            if ($currUserRole == 'headmaster') {
                $aspirasiThisMonth = Aspiration::whereMonth('created_at', Carbon::now()->month)
                ->whereYear('created_at', Carbon::now()->year)->whereIn('status', $statuses)
                ->count();

                $aspirasiLastMonth = Aspiration::whereMonth('created_at', Carbon::now()->subMonth()->month)
                ->whereYear('created_at', Carbon::now()->subMonth()->year)->whereIn('status', $statuses)
                ->count();
            } else {
                $aspirasiThisMonth = Aspiration::whereMonth('created_at', Carbon::now()->month)
                ->whereYear('created_at', Carbon::now()->year)
                ->count();
                $aspirasiLastMonth = Aspiration::whereMonth('created_at', Carbon::now()->subMonth()->month)
                ->whereYear('created_at', Carbon::now()->subMonth()->year)
                ->count();
            }
            
            if ($aspirasiLastMonth != 0) {
                // Calculate the percentage change
                $persenAspirasi = ($aspirasiThisMonth - $aspirasiLastMonth) / $aspirasiLastMonth * 100;
                $statusAspirasi = $persenAspirasi >= 0 ? "increase" : "decrease";
            } else {
                // Handle the case when $laporanYesterday is zero
                $persenAspirasi = 0;
                $statusAspirasi = "N/A"; // You can set any default value or handle it differently
            }
        }elseif($aspirasiCountFilter == "This Year"){
            $aspirasiForFilterQuery = Aspiration::whereYear('created_at', Carbon::now()->year);

            // Filter reports based on the user's role
            if ($currUserRole == 'headmaster') {
                $aspirasiForFilterQuery->whereIn('status', $statuses);
            }

            // Get the filtered reports
            $aspirasiForFilter = $aspirasiForFilterQuery->get();

            if ($currUserRole == 'headmaster') {
                $aspirasiThisYear = Aspiration::
                whereYear('created_at', Carbon::now()->year)->whereIn('status', $statuses)
                ->count();
    
                $aspirasiLastYear = Aspiration::whereYear('created_at', Carbon::now()->subYear()->year)->whereIn('status', $statuses)
                ->count();
            } else {
                $aspirasiThisYear = Aspiration::whereYear('created_at', Carbon::now()->year)
                ->count();
                $aspirasiLastYear = Aspiration::whereYear('created_at', Carbon::now()->subYear()->year)->count();
            }

            if ($aspirasiLastYear != 0) {
                // Calculate the percentage change
                $persenAspirasi = ($aspirasiThisYear - $aspirasiLastYear) / $aspirasiLastYear * 100;
                $statusAspirasi = $persenAspirasi >= 0 ? "increase" : "decrease";
            } else {
                // Handle the case when $laporanYesterday is zero
                $persenAspirasi = 0;
                $statusAspirasi = "N/A"; // You can set any default value or handle it differently
            }
        }else{
            if ($currUserRole == 'headmaster') {
                $aspirasiForFilter = Aspiration::whereIn('status', $statuses)->get();
                }else{
                $aspirasiForFilter = Aspiration::all();
                }
        }

        $laporanCategoryFilter = $request->laporanCategoryFilter;
        if($laporanCategoryFilter == "Today"){

            $laporanForFilterQuery = Report::whereDate('created_at', Carbon::today());

            // Filter reports based on the user's role
            if ($currUserRole == 'headmaster') {
                $laporanForFilterQuery->whereIn('status', $statuses);
            }
    
            // Get the filtered reports
            $laporanCategoryForFilter = $laporanForFilterQuery->get();
        }elseif($laporanCategoryFilter == "This Month"){
            $laporanForFilterQuery = Report::whereMonth('created_at', Carbon::now()->month)
                ->whereYear('created_at', Carbon::now()->year);

            // Filter reports based on the user's role
            if ($currUserRole == 'headmaster') {
                $laporanForFilterQuery->whereIn('status', $statuses);
            }
    
            // Get the filtered reports
            $laporanCategoryForFilter = $laporanForFilterQuery->get();
        }elseif($laporanCategoryFilter == "This Year"){
            $laporanForFilterQuery = Report::whereYear('created_at', Carbon::now()->year);

            // Filter reports based on the user's role
            if ($currUserRole == 'headmaster') {
                $laporanForFilterQuery->whereIn('status', $statuses);
            }

            // Get the filtered reports
            $laporanCategoryForFilter = $laporanForFilterQuery->get();
        }else{
            if ($currUserRole == 'headmaster') {
                $laporanCategoryForFilter = Report::whereIn('status', $statuses)->get();
                }else{
                $laporanCategoryForFilter = Report::all();
                }
        }

        $aspirasiCategoryFilter = $request->aspirasiCategoryFilter;
        if($aspirasiCategoryFilter == "Today"){
            $aspirasiForFilterQuery = Aspiration::whereDate('created_at', Carbon::today());

            // Filter reports based on the user's role
            if ($currUserRole == 'headmaster') {
                $aspirasiForFilterQuery->whereIn('status', $statuses);
            }
    
            // Get the filtered reports
            $aspirasiCategoryForFilter = $aspirasiForFilterQuery->get();
        }elseif($aspirasiCategoryFilter == "This Month"){
            $aspirasiForFilterQuery = Aspiration::whereMonth('created_at', Carbon::now()->month)
                ->whereYear('created_at', Carbon::now()->year);

            if ($currUserRole == 'headmaster') {
                $aspirasiForFilterQuery->whereIn('status', $statuses);
            }
    
            // Get the filtered reports
            $aspirasiCategoryForFilter = $aspirasiForFilterQuery->get();
        }elseif($aspirasiCategoryFilter == "This Year"){
            $aspirasiForFilterQuery = Aspiration::whereYear('created_at', Carbon::now()->year);

            // Filter reports based on the user's role
            if ($currUserRole == 'headmaster') {
                $aspirasiForFilterQuery->whereIn('status', $statuses);
            }

            // Get the filtered reports
            $aspirasiCategoryForFilter = $aspirasiForFilterQuery->get();
        }else{
            if ($currUserRole == 'headmaster') {
                $aspirasiCategoryForFilter = Aspiration::whereIn('status', $statuses)->get();
                }else{
                $aspirasiCategoryForFilter = Aspiration::all();
                }
        }

        $statusFilter = $request->statusFilter;
        if($statusFilter == "Today"){
            $aspirasiForFilterQuery = Aspiration::whereDate('created_at', Carbon::today());

            // Filter reports based on the user's role
            if ($currUserRole == 'headmaster') {
                $aspirasiForFilterQuery->whereIn('status', $statuses);
            }
    
            // Get the filtered reports
            $aspirasiStatusFilter = $aspirasiForFilterQuery->get();

            $laporanForFilterQuery = Report::whereDate('created_at', Carbon::today());

            // Filter reports based on the user's role
            if ($currUserRole == 'headmaster') {
                $laporanForFilterQuery->whereIn('status', $statuses);
            }
    
            // Get the filtered reports
            $laporanStatusFilter = $laporanForFilterQuery->get();
        }elseif($statusFilter == "This Month"){
            $aspirasiForFilterQuery = Aspiration::whereMonth('created_at', Carbon::now()->month)
                ->whereYear('created_at', Carbon::now()->year);

            if ($currUserRole == 'headmaster') {
                $aspirasiForFilterQuery->whereIn('status', $statuses);
            }
    
            // Get the filtered reports
            $aspirasiStatusFilter = $aspirasiForFilterQuery->get();

            $laporanForFilterQuery = Report::whereMonth('created_at', Carbon::now()->month)
                ->whereYear('created_at', Carbon::now()->year);

            if ($currUserRole == 'headmaster') {
                $laporanForFilterQuery->whereIn('status', $statuses);
            }
    
            // Get the filtered reports
            $laporanStatusFilter = $laporanForFilterQuery->get();
        }elseif($statusFilter == "This Year"){
            $aspirasiForFilterQuery = Aspiration::whereYear('created_at', Carbon::now()->year);

            // Filter reports based on the user's role
            if ($currUserRole == 'headmaster') {
                $aspirasiForFilterQuery->whereIn('status', $statuses);
            }

            // Get the filtered reports
            $aspirasiStatusFilter = $aspirasiForFilterQuery->get();

            $laporanForFilterQuery = Report::whereYear('created_at', Carbon::now()->year);

            // Filter reports based on the user's role
            if ($currUserRole == 'headmaster') {
                $laporanForFilterQuery->whereIn('status', $statuses);
            }

            // Get the filtered reports
            $laporanStatusFilter = $laporanForFilterQuery->get();
        }else{
            if ($currUserRole == 'headmaster') {
                $aspirasiStatusFilter = Aspiration::whereIn('status', $statuses)->get();
                $laporanStatusFilter = Report::whereIn('status', $statuses)->get();
                }else{
                $aspirasiStatusFilter = Aspiration::all();
                $laporanStatusFilter = Report::all();
                }
        }

        $categories = Category::all();
        if ($currUserRole == 'headmaster') {
            $reports = Report::whereIn('status', $statuses)->orderBy('created_at', 'desc')->get();
            $aspirations = Aspiration::withCount(['likes'])
                             ->whereIn('status', $statuses)->orderBy('likes_count', 'desc')
                             ->get();
        }else{
        $reports = Report::all()->sortByDesc('created_at');
        $aspirations = Aspiration::withCount(['likes'])
                         ->orderBy('likes_count', 'desc')
                         ->get();
        }
        $users = User::all();
        $staffTypes = StaffType::all();


        return view('user.admin.dashboard',compact('konsultasiCount', 'persenKonsultasi', 'statusKonsultasi', 'konsultasiFilter', 'currUserRole','persenLaporan', 'statusLaporan', 'persenAspirasi', 'statusAspirasi', 'laporanCountFilter', 'aspirasiCountFilter', 'laporanCategoryFilter', 'aspirasiCategoryFilter', 'categories', 'reports', 'aspirations', 'users', 'staffTypes', 'aspirasiCategoryForFilter', 'laporanCategoryForFilter', 'aspirasiForFilter', 'laporanForFilter', 'statusFilter', 'laporanStatusFilter', 'aspirasiStatusFilter'));
    }

    public function showProfile() {
        return view('user.myProfileView');
    }

    public function changeProfile(Request $request) {
        $user = Auth::user();

        $rules = [
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
            'new_password_confirmation' => 'required',
        ];

        $messages = [
            'current_password.required' => 'Jangan lupa isi password lamanya yaa',
            'new_password.required' => 'Jangan lupa isi password barunya yaa',
            'new_password.min' => 'Duh, minimal password-nya 8 karakter nih',

            'new_password_confirmation.required' => 'Jangan lupa isi konfirmasi password-nya yaa',
            'new_password_confirmation.confirmed' => 'Duh, konfirmasi password-nya masih kurang tepat nih',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);
    
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()->back()->withErrors(['current_password' => 'Password lamanya kurang tepat nih']);
        }
        
        $user->update([
            'password' => Hash::make($request->new_password),
        ]);
        return redirect()->back()->with('changePassSuccessMessage', 'Password berhasil diperbarui');
    }

    public function updateUrgentPhoneNum(Request $request) {
        $user = Auth::user();

        $rules = [
            'urgent_phone_number' => 'required|numeric|digits_between:10,12|regex:/^08[0-9]+$/',
        ];

        $messages = [
            'urgent_phone_number.required' => 'Nomor teleponnya belum diisi nih',
            'urgent_phone_number.numeric' => 'Yuk masukin nomor telepon dengan format yang benar',
            'urgent_phone_number.digits_between' => 'Duh, minimalnya 10 digit dan maksimalnya 12 digit yaa',
            'urgent_phone_number.regex' => 'Nomor teleponnya harus dimulai dari "08" yaa',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);
    
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        $user->update([
            'urgentPhoneNumber' => $request->urgent_phone_number,
        ]);

        return redirect()->back()->with('updateUrgentSuccessMessage', 'Nomor telepon urgent berhasil diperbarui');
    }

    public function resetPassword($user_id) {
        $currUser = User::findOrFail($user_id);

        $formattedDate = date('dmY', strtotime($currUser->birthDate));
        $freshPassword = "D3f@ult" . $formattedDate;
        $credentials['password'] = Hash::make($freshPassword);

        $currUser->update($credentials);
        return redirect()->route('manage.users.seeall')->with('successMessage', 'Password pengguna berhasil di-reset');
    }

    public function seeAllUser() {
        $users = User::where('role', '!=', 'admin')
                        ->orderBy('created_at', 'desc')
                        ->paginate(10)
                        ->withQueryString();

        return view('user.admin.manageUsersView', compact('users'));
    }

    public function getUserDetail($user_id) {
        $currUser = User::findOrFail($user_id);

        if ($currUser->role == "student") {
            $problematicAspirations = $currUser->aspirations()->whereHas('reportedByUsers', function ($query) use ($currUser) {
                $query->where('user_id', '<>', $currUser->id);
            })->count();

            return view('user.admin.userDetailView', compact('currUser', 'problematicAspirations'));
        }

        return view('user.admin.userDetailView', compact('currUser'));
    }

    public function updateUserForm($user_id) {
        $staffTypes = StaffType::all();
        $currUser = User::findOrFail($user_id);

        return view('user.admin.updateUserView', compact('staffTypes', 'currUser'));
    }

    public function updateUser(Request $request, $user_id) {
        $currUser = User::findOrFail($user_id);


        $rules = [
            'name' => 'required|max:250',
            'email' => 'required|email',
            'phoneNumber' => 'required|numeric|digits_between:10,12|regex:/^08[0-9]+$/',
            'gender' => 'required',
            'birthDate' => 'required|date|before:today|after:1900-01-01',
            'role' => 'required',
            'staffType' => 'required_if:role,3',
        ];
    
        $messages = [
            'name.required' => 'Jangan lupa masukin nama penggunanya yaa',
            'name.max' => 'Nama yang kamu masukin cuman bisa maksimal 250 karakter nih',
            
            'email.required' => 'Jangan lupa masukin email penggunanya yaa',
            'email.email' => 'Yuk masukin email dengan format yang benar',
            
            'phoneNumber.required' => 'Jangan lupa masukin nomor telepon penggunanya yaa',
            'phoneNumber.numeric' => 'Yuk masukin nomor telepon dengan format yang benar',
            'phoneNumber.digits_between' => 'Duh, minimalnya 10 digit dan maksimalnya 12 digit yaa',
            'phoneNumber.regex' => 'Nomor teleponnya harus dimulai dari "08" yaa',
            
            'gender.required' => 'Jangan lupa masukin gender penggunanya yaa',
            
            'birthDate.required' => 'Jangan lupa masukin tanggal lahir penggunanya yaa',
            'birthDate.date' => 'Yuk masukin tanggal lahir dengan format yang benar',
            'birthDate.before' => 'Tanggal lahir ini kurang tepat yaa',
            'birthDate.after' => 'Tanggal lahir ini kurang tepat yaa',
            
            'role.required' => 'Jangan lupa masukin role penggunanya yaa',
            
            'staffType.required_if' => 'Jangan lupa masukin tipe staf penggunanya yaa',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);
    
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $credentials = [
            'name' => $request->name,
            'phoneNumber' => $request->phoneNumber,
            'gender' => $request->gender,
            'birthDate' => $request->birthDate,
        ];

        if ($currUser->email != $request->email) {
            $ruleEmail = [
                'email' => 'unique:users',
            ];

            $messageEmail = [
                'email.unique' => 'Ups, email ini sudah terpakai',
            ];

            $validator = Validator::make(['email' => $request->email], $ruleEmail, $messageEmail);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $credentials['email'] = $request->email;
        }

        if ($request->has('staffType')) {
            $credentials['staffType_id'] = $request->staffType;
        } else {
            $credentials['staffType_id'] = null;
        }

        if ($request->role == 1) {
            $credentials['role'] = "student";
        } elseif ($request->role == 2) {
            $credentials['role'] = "headmaster";
        } elseif ($request->role == 3) {
            $credentials['role'] = "staff";
        }

        $currUser->update($credentials);
        return redirect()->route('manage.users.seeall')->with('successMessage', 'Pengguna berhasil diperbarui');
    }

    public function removeSelectedUsers(Request $request) {
        $checkedUsers = $request->input('checkedUsers', '');
        $checkedUsersArray = explode(",", $checkedUsers);

        foreach ($checkedUsersArray as $user_id) {
            $user = User::findOrFail($user_id);
            $user->delete();
        }

        $response = [
            'redirectUrl' => '/manage/users',
        ];
        $request->session()->flash('successMessage', count($checkedUsersArray).' pengguna yang dipilih berhasil dihapus');
        return response()->json($response);
    }

    public function removeUser($user_id) {
        $user = User::findOrFail($user_id);
        $user->delete();

        return redirect()->route('manage.users.seeall')->with('successMessage', 'Pengguna berhasil dihapus');
    }

    public function importStudentsForm(Request $request) {
        return view('user.admin.importStudentsView');
    }

    public function importStudents(Request $request) {
        $rules = [
            'file' => 'required|mimes:xlsx'
        ];

        $messages = [
            'file.required' => 'Jangan lupa masukin file-nya yaa',
            'file.mimes' => 'File yang bisa dimasukin cuman .xlsx aja nih',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);
    
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        $fileUpload = $request->file('file');
        $fileName = rand().$fileUpload->getClientOriginalName();
        $filePath = 'public/' . $fileName;

        Storage::putFileAs('public', $fileUpload, $fileName);
        try {
            Excel::import(new ImportUser, storage_path('app/' . $filePath));
            Storage::delete($filePath);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }

        return redirect()->route('manage.users.seeall')->with('successMessage', 'Proses import murid selesai');
    }

    public function registerUserForm() {
        $staffTypes = StaffType::all();

        return view('user.admin.registerUserView', compact('staffTypes'));
    }

    public function registerUser(Request $request) {
        $rules = [
            'name' => 'required|max:250',
            'email' => 'required|email|unique:users',
            'phoneNumber' => 'required|numeric|digits_between:10,12|regex:/^08[0-9]+$/',
            'gender' => 'required',
            'birthDate' => 'required|date_format:d/m/Y|before:today|after:1900-01-01',
            'role' => 'required',
            'staffType' => 'required_if:role,3',
        ];
    
        $messages = [
            'name.required' => 'Jangan lupa masukin nama penggunanya yaa',
            'name.max' => 'Nama yang kamu masukin cuman bisa maksimal 250 karakter nih',
            
            'email.required' => 'Jangan lupa masukin email penggunanya yaa',
            'email.email' => 'Yuk masukin email dengan format yang benar',
            'email.unique' => 'Ups, email ini sudah terpakai',
            
            'phoneNumber.required' => 'Jangan lupa masukin nomor telepon penggunanya yaa',
            'phoneNumber.numeric' => 'Yuk masukin nomor telepon dengan format yang benar',
            'phoneNumber.digits_between' => 'Duh, minimalnya 10 digit dan maksimalnya 12 digit yaa',
            'phoneNumber.regex' => 'Nomor teleponnya harus dimulai dari "08" yaa',
            
            'gender.required' => 'Jangan lupa masukin gender penggunanya yaa',
            
            'birthDate.required' => 'Jangan lupa masukin tanggal lahir penggunanya yaa',
            'birthDate.date_format' => 'Yuk masukin tanggal lahir dengan format yang benar',
            'birthDate.before' => 'Tanggal lahir ini kurang tepat yaa',
            'birthDate.after' => 'Tanggal lahir ini kurang tepat yaa',
            
            'role.required' => 'Jangan lupa masukin role penggunanya yaa',
            
            'staffType.required_if' => 'Jangan lupa masukin tipe staf penggunanya yaa',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);
    
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $userService = new UserService;
        $user_no = $userService->generateUserNoByFormFormat($request->birthDate);

        $birthDate = \DateTime::createFromFormat('d/m/Y', $request->birthDate);
        $formattedBirthDate = $birthDate->format('Y-m-d');

        $credentials = [
            'userNo' => $user_no,
            'name' => $request->name,
            'email' => $request->email,
            'phoneNumber' => $request->phoneNumber,
            'gender' => $request->gender,
            'birthDate' => $formattedBirthDate,
            'isSuspended' => false,
            'suspendReason' => '',
        ];

        if ($request->has('staffType')) {
            $credentials['staffType_id'] = $request->staffType;
        } else {
            $credentials['staffType_id'] = null;
        }

        if ($request->role == 1) {
            $credentials['role'] = "student";
        } elseif ($request->role == 2) {
            $credentials['role'] = "headmaster";
        } elseif ($request->role == 3) {
            $credentials['role'] = "staff";
        }

        $formattedDate = date('dmY', strtotime($formattedBirthDate));
        $freshPassword = "D3f@ult" . $formattedDate;
        $credentials['password'] = Hash::make($freshPassword);

        User::create($credentials);
        return redirect()->route('manage.users.seeall')->with('successMessage', 'Pengguna berhasil didaftarkan');
    }

    public function showLoginForm() {
        if (Auth::check()) {
            $currUser = Auth::user()->role;
            
            if($currUser == "student"){
                return redirect('/report/myReport');
            }
            else if($currUser == "headmaster" || $currUser == "staff"){
                return redirect('/dashboard');
            }
            else if($currUser == "admin"){
                return redirect('/report/manage');
            }
        }

        return view('user.loginView');
    }

    public function login(Request $request) {
        $rules = [
            'email' => 'required|email',
            'password' => 'required',
        ];
    
        $messages = [
            'email.required' => 'Jangan lupa masukin email yaa',
            'password.required' => 'Jangan lupa masukin password yaa',
        ];
    
        $validator = Validator::make($request->all(), $rules, $messages);
    
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $credentials = [
            'email' => $request->email,
            'password' => $request->password
        ];

        if (Auth::attempt($credentials, true)) {
            $currUser = Auth::user()->role;
            if($currUser == "student"){
                return redirect('/report/myReport');
            }
            else if($currUser == "headmaster" || $currUser == "staff"){
                return redirect('/dashboard');
            }
            else if($currUser == "admin"){
                return redirect('/report/manage');
            }

            logout();
        }
    
        return redirect()->back()->withErrors([
            'email' => 'Duh, Email/Password ini enggak sesuai',
            'password' => 'Duh, Email/Password ini enggak sesuai'
        ])->withInput($request->except('password'));
    }

    public function searchUserList(Request $request)
    {
        $userListFounded = User::where("name", "like", "%$request->userName%")
                                    ->where('role', '!=', 'admin')
                                    ->orderBy('created_at', 'desc')
                                    ->paginate(10)
                                    ->withQueryString();

        $data = [
            'users' => $userListFounded,
            'searchNameParam' => $request->userName
        ];

        return view('user.admin.manageUsersView', $data);
    }

    public function search(Request $request)
    {
        $currUser = Auth::user();
        $query = null;
        $query2 = null;
        $reports = null;
        $aspirations = null;
        $message = null;
        $failMessage = "";

        if ($currUser->role == "student") {
            $query = $currUser->reports()->getQuery();
            $query2 = Aspiration::query();
        } elseif ($currUser->role == "admin" || $currUser->role == "headmaster" || $currUser->role == "staff") {
            $query = Report::query();
            $query2 = Aspiration::query();
        }

        if ($request->has("name")) {
            $query->where("name", "like", "%$request->name%");
            $query2->where("name", "like", "%$request->name%");
        }

        if ($request->has("year")) {
            if ($request->year != 1) {
                $query->whereYear("created_at", $request->year);
                $query2->whereYear("created_at", $request->year);
            }
        }

        if ($request->has("status")) {
            if ($request->status != 1) {
                $query->where("status", "like", "%$request->status%");
                $query2->where("status", "like", "%$request->status%");
            }
        }

        $prereports = $query->paginate(10);
        $prereports2 = $query2->paginate(10);
        $reports = $prereports->appends($request->query());
        $aspirations = $prereports2->appends($request->query());        

        if ($aspirations->isEmpty() && $reports->isEmpty()) {
            return redirect()->back()->with('errorSearch', 'Duh.. ga ketemu nih :(');
        } 
        else {
            $data = [
                'reports' => $reports,
                'aspirations' => $aspirations,
                'searchParams' => $request->all(),
                'message' => $message,
                'failMessage' => $failMessage,
            ];

            return view('search', $data);
        }
    }

    public function logout() {
        Auth::logout();

        return redirect('/');
    }
}