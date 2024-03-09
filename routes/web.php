<?php

use App\Http\Controllers\AspirationController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PDFController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\StaffTypeController;
use App\Http\Controllers\UserReportAspirationController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect('/login');
});

// Auths
Route::get('/login', [UserController::class, 'showLoginForm'])->name('login.form');
Route::post('/login', [UserController::class, 'login'])->name('login');

// Middlewares
Route::middleware(['auth'])->group(function () {
    Route::get('/logout', [UserController::class, 'logout'])->name('logout');
    Route::get('/profile', [UserController::class, 'showProfile'])->name('myprofile');
    Route::post('/profile/changepassword', [UserController::class, 'changeProfile'])->name('changepassword');
    Route::get('/searching', [UserController::class, 'search'])->name('searching');
    Route::get('/aspirations/detail/{aspirationId}', [AspirationController::class, 'showDetail'])->name('aspirations.details');
    Route::get('/report/detail/{id}', [ReportController::class, 'reportDetail'])->name('student.reportDetail');

});

Route::middleware(['isschool'])->group(function () {
    Route::prefix('/aspirations')->group(function(){
        Route::get('/manageAspirations', [AspirationController::class, 'manageAspiration'])->name('aspirations.manageAspirations');
        Route::get('/manageAspirations/{categoryId}', [AspirationController::class, 'manageAspirationFilterCategory'])->name('aspirations.viewFilterCategory');
        Route::get('/manageAspirationsBy/{status}', [AspirationController::class, 'manageAspirationFilterStatus'])->name('aspirations.viewFilterStatus');
    });
    
    Route::prefix('/report')->group(function(){
        Route::get('/manage', [ReportController::class, 'manageReport'])->name('report.adminHeadmasterStaff.manageReport');
        Route::get('/manage/{categoryId}', [ReportController::class, 'manageReportFilterCategory'])->name('report.adminHeadmasterStaff.manageReportFilterCategory');
        Route::get('/manageBy/{status}', [ReportController::class, 'manageReportFilterStatus'])->name('report.adminHeadmasterStaff.manageReportFilterStatus');
    });
});

Route::middleware(['isheadandstaff'])->group(function () {
    Route::prefix('/aspirations')->group(function(){
        Route::get('/pdf/convert', [PDFController::class, 'pdfGenerationAllAspirations'])->name('aspirations.pdf.convertAspiration');
        Route::get('/pdf/convert/{categoryId}', [PDFController::class, 'pdfGenerationAspirationsByCategory'])->name('aspirations.pdf.convertCategoryAspiration');
        Route::patch('/requestApproval/{id}', [AspirationController::class, 'requestApprovalAspiration'])->name('staff.requestApprovalAspiration');
        Route::patch('/staffReject/{id}', [AspirationController::class, 'rejectAspiration'])->name('staff.rejectAspiration');
        Route::patch('/headApprove/{id}', [AspirationController::class, 'approveAspiration'])->name('headmaster.approveAspiration');
        Route::patch('/headReject/{id}', [AspirationController::class, 'rejectAspiration'])->name('headmaster.rejectAspiration');
        Route::patch('/process/{id}', [AspirationController::class, 'onProgAspiration'])->name('processAspiration');
        Route::patch('/monitoring/{id}', [AspirationController::class, 'monitoringAspiration'])->name('monitoringAspiration');
        Route::patch('/finish/{id}', [AspirationController::class, 'finishAspiration'])->name('finishAspiration');
        
        Route::patch('/pin/{id}', [AspirationController::class, 'pinAspiration'])->name('pinAspiration');
        Route::patch('/unpin/{id}', [AspirationController::class, 'unpinAspiration'])->name('unpinAspiration');
    });

    Route::prefix('/report')->group(function(){
        Route::patch('/report/requestApproval/{id}', [ReportController::class, 'requestApprovalReport'])->name('staff.requestApprovalReport');
        Route::patch('/report/staffApprove/{id}', [ReportController::class, 'approveReport'])->name('staff.approveReport');
        Route::patch('/report/staffReject/{id}', [ReportController::class, 'rejectReport'])->name('staff.rejectReport');
        Route::patch('/report/headApprove/{id}', [ReportController::class, 'approveReport'])->name('headmaster.approveReport');
        Route::patch('/report/headReject/{id}', [ReportController::class, 'rejectReport'])->name('headmaster.rejectReport');
        Route::patch('/report/reviewStaff/{id}', [ReportController::class, 'inReviewStaffReport'])->name('staff.reviewReport');
        Route::patch('/report/reviewHeadmaster/{id}', [ReportController::class, 'inReviewHeadmasterReport'])->name('headmaster.reviewReport');
        Route::patch('/report/process/{id}', [ReportController::class, 'onProgReport'])->name('processReport');
        Route::patch('/report/monitoring/{id}', [ReportController::class, 'monitoringReport'])->name('monitoringReport');
        Route::patch('/report/finish/{id}', [ReportController::class, 'finishReport'])->name('finishReport');
        Route::get('/report/pdf/convert', [PDFController::class, 'pdfGenerationAllReports'])->name('convertReport');
        Route::get('/report/pdf/convert/{categoryId}', [PDFController::class, 'pdfGenerationReportsByCategory'])->name('convertCategoryReport');
    });

    Route::get('/dashboard', [UserController::class, 'getDashboard'])->name('dashboard');
    Route::get('/dashboard/filtered', [UserController::class, 'getDashboardddFiltered'])->name('dashboard.filtered');
});


Route::middleware(['isadmin'])->group(function () {
    Route::prefix('/staffType')->group(function(){
        Route::get('/createForm', [StaffTypeController::class, 'createStaffTypeForm'])->name('admin.createStaffTypeForm');
        Route::post('/create', [StaffTypeController::class, 'createStaffType'])->name('admin.createStaffType');
        Route::get('/updateForm/{id}', [StaffTypeController::class, 'updateStaffTypeForm'])->name('admin.updateStaffTypeForm');
        Route::patch('/update/{id}', [StaffTypeController::class, 'updateStaffType'])->name('admin.updateStaffType');
        Route::delete('/delete/{id}', [StaffTypeController::class, 'deleteStaffType'])->name('admin.deleteStaffType');
        Route::get('/list', [StaffTypeController::class, 'index'])->name('admin.staffTypeList');
    });
    
    Route::prefix('/manage/users')->group(function(){
        Route::get('/', [UserController::class, 'seeAllUser'])->name('manage.users.seeall');
        Route::get('/detail/{userId}', [UserController::class, 'getUserDetail'])->name('manage.users.detail');
        
        Route::get('/register', [UserController::class, 'registerUserForm'])->name('manage.users.register');
        Route::post('/register', [UserController::class, 'registerUser'])->name('manage.users.register.submit');
        
        Route::get('/update/{userId}', [UserController::class, 'updateUserForm'])->name('manage.users.update');
        Route::patch('/update/{userId}', [UserController::class, 'updateUser'])->name('manage.users.update.submit');
        Route::patch('/password/change/{userId}', [UserController::class, 'resetPassword'])->name('manage.users.reset.password');
        Route::patch('/status/{userId}', [UserController::class, 'suspend'])->name('manage.users.suspend');
        Route::delete('/delete/{userId}', [UserController::class, 'removeUser'])->name('manage.users.delete');
        
        Route::get('/studentsimport', [UserController::class, 'importStudentsForm'])->name('manage.users.importstudents'); 
        Route::post('/studentsimport', [UserController::class, 'importStudents'])->name('manage.users.importstudents.submit');
    });
    
    //Category
    Route::prefix('/categories')->group(function(){
        Route::get('/list', [CategoryController::class, 'index'])->name('categories.list');
        Route::get('/addForm', [CategoryController::class, 'showAddCategoryForm'])->name('categories.addForm');
        Route::post('/create', [CategoryController::class, 'addCategory'])->name('categories.create');
        Route::get('/updateForm/{id}', [CategoryController::class, 'updateFormCategory'])->name('categories.updateForm');
        Route::patch('/update/{id}', [CategoryController::class, 'updateCategory'])->name('categories.update');
        Route::delete('/delete/{id}', [CategoryController::class, 'deleteCategory'])->name('categories.delete');
    });
    
    Route::prefix('/aspirations')->group(function(){
        Route::delete('/delete/{id}', [AspirationController::class, 'deleteAspiration'])->name('aspirations.delete');
        Route::get('/reported', [UserReportAspirationController::class, 'getAllReportedAspirations'])->name('aspirations.reported');
        Route::get('/reported/{aspirationId}', [UserReportAspirationController::class, 'getAllReportedAspirationDetail'])->name('aspirations.reported.details');
        Route::delete('/reported/delete/{id}', [UserReportAspirationController::class, 'deleteReportedAspiration'])->name('aspirations.reported.delete');
    });

    Route::patch('/report/delete/{id}', [ReportController::class, 'deleteReportAdmin'])->name('admin.deleteReport');
});


Route::middleware(['isstudent'])->group(function () {
    Route::prefix('/aspirations')->group(function(){
        Route::get('/publicAspirations', [AspirationController::class, 'publicAspiration'])->name('aspirations.publicAspirations');
        Route::get('/publicAspirations/{categoryId}', [AspirationController::class, 'publicAspirationFilterCategory'])->name('aspirations.publicAspirationsCategory');
        Route::get('/myAspirations', [AspirationController::class, 'myAspiration'])->name('aspirations.myAspirations');
        Route::get('/addForm', [AspirationController::class, 'showAddAspirationForm'])->name('aspirations.addForm');
        Route::post('/create', [AspirationController::class, 'addAspiration'])->name('aspirations.create');
        Route::get('/updateForm/{id}', [AspirationController::class, 'updateAspirationForm'])->name('aspirations.updateForm');
        Route::patch('/update/{id}', [AspirationController::class, 'updateAspirationLogic'])->name('aspirations.update');
        Route::patch('/cancel/{id}', [AspirationController::class, 'cancelAspiration'])->name('aspirations.cancel');
        Route::post('/reported/create/{aspirationId}', [UserReportAspirationController::class, 'createReportedAspiration'])->name('aspirations.reported.create');
        Route::patch('/upvote/{id}', [AspirationController::class, 'upvote'])->name('upvote');
        Route::patch('/unUpvote/{id}', [AspirationController::class, 'unUpvote'])->name('unUpvote');
    });

    Route::prefix('/report')->group(function(){
        Route::get('/myReport', [ReportController::class, 'myReport'])->name('report.student.myReport');
        Route::get('/createForm', [ReportController::class, 'createReportForm'])->name('student.createReportForm');
        Route::post('/create', [ReportController::class, 'createReport'])->name('student.createReport');
        // Route::get('/updateForm/{id}', [ReportController::class, 'updateReportForm'])->name('student.updateReportForm');
        // Route::patch('/update/{id}', [ReportController::class, 'updateReport'])->name('student.updateReport');
        Route::patch('/cancel/{id}', [ReportController::class, 'cancelReport'])->name('student.cancelReport');
    });
});

//Aspiration
// Route::prefix('/aspirations')->group(function(){





// });




















