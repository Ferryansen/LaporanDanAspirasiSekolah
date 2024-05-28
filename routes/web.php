<?php

use App\Http\Controllers\AspirationController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DownloadContentController;
use App\Http\Controllers\PDFController;
use App\Http\Controllers\PusherController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\StaffTypeController;
use App\Http\Controllers\UserReportAspirationController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\AspirationReactionController;
use App\Http\Controllers\ConsultationEventController;
use App\Models\Faq;
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

// UrgentAccess
Route::get('/urgentAccess/{urgentAccess}', [ReportController::class, 'urgentAccessForm'])->name('urgent.accessForm');
Route::post('/urgentAccess/{urgentAccess}', [ReportController::class, 'urgentAccessCheck'])->name('urgent.accessCheck');
Route::get('/urgentAccess/detail/{urgentAccess}', [ReportController::class, 'urgentAccessDetail'])->name('urgent.accessDetail');

// Middlewares
Route::middleware(['auth'])->group(function () {
    Route::get('/logout', [UserController::class, 'logout'])->name('logout');
    Route::get('/profile', [UserController::class, 'showProfile'])->name('myprofile');
    Route::patch('/profile/changepassword', [UserController::class, 'changeProfile'])->name('changepassword');
    Route::get('/searching', [UserController::class, 'search'])->name('searching');
    Route::get('/aspirations/detail/{aspirationId}', [AspirationController::class, 'showDetail'])->name('aspirations.details');
    Route::get('/report/detail/{id}', [ReportController::class, 'reportDetail'])->name('student.reportDetail');
    Route::get('/FAQ', [FaqController::class, 'seeAllFaq'])->name('faq.seeall');
    Route::get('/downloadcenter', [DownloadContentController::class, 'seeAllDownloadContent'])->name('downloadcontent.seeall');
    Route::get('/publicAspirations', [AspirationController::class, 'publicAspiration'])->name('aspirations.publicAspirations');
    Route::get('/publicAspirations/sorting/{typeSorting}', [AspirationController::class, 'publicAspirationSorting'])->name('aspirations.publicAspirations.sorting');
    Route::get('/publicAspirations/{category_id}', [AspirationController::class, 'publicAspirationFilterCategory'])->name('aspirations.publicAspirationsCategory');
    Route::post('/{aspiration}/react', [AspirationReactionController::class, 'react'])->name('aspirations.react');
    Route::post('/comments/{comment}/reply', [CommentController::class, 'reply'])->name('comments.reply');
    Route::post('/{aspiration}/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::post('/openChat', [ReportController::class, 'openChatNotification'])->name('openChat.notif');
    Route::get('/consultation/detail/{consultation_id}', [ConsultationEventController::class, 'consultationDetail'])->name('consultation.detail');
});

Route::middleware(['isschool'])->group(function () {
    Route::prefix('/aspirations')->group(function(){
        Route::get('/manageAspirations', [AspirationController::class, 'manageAspiration'])->name('aspirations.manageAspiration');
        Route::get('/manageAspirations/{category_id}', [AspirationController::class, 'manageAspirationFilterCategory'])->name('aspirations.viewFilterCategory');
        Route::get('/manageAspirationsBy/{status}', [AspirationController::class, 'manageAspirationFilterStatus'])->name('aspirations.viewFilterStatus');
        Route::get('/{id}/comments', [AspirationController::class, 'showComments'])->name('aspiration.comments');
    });
    
    Route::prefix('/report')->group(function(){
        Route::get('/manage', [ReportController::class, 'manageReport'])->name('report.adminHeadmasterStaff.manageReport');
        Route::get('/manage/{category_id}', [ReportController::class, 'manageReportFilterCategory'])->name('report.adminHeadmasterStaff.manageReportFilterCategory');
        Route::get('/manageBy/{status}', [ReportController::class, 'manageReportFilterStatus'])->name('report.adminHeadmasterStaff.manageReportFilterStatus');
    });

    Route::prefix('/support/manage')->group(function(){
        Route::prefix('/FAQ')->group(function(){
            Route::get('/create', [FaqController::class, 'createFaqForm'])->name('faq.createForm');
            Route::post('/create', [FaqController::class, 'createFaq'])->name('faq.create');

            Route::get('/update/{id}', [FaqController::class, 'updateFaqForm'])->name('faq.updateForm');
            Route::patch('/update/{id}', [FaqController::class, 'updateFaq'])->name('faq.update');

            Route::delete('/delete/{id}', [FaqController::class, 'deleteFaq'])->name('faq.delete');
        });

        Route::prefix('/downloadcenter')->group(function(){
            Route::get('/create', [DownloadContentController::class, 'createDownloadContentForm'])->name('downloadcontent.createForm');
            Route::post('/create', [DownloadContentController::class, 'createDownloadContent'])->name('downloadcontent.create');

            Route::get('/update/{id}', [DownloadContentController::class, 'updateDownloadContentForm'])->name('downloadcontent.updateForm');
            Route::patch('/update/{id}', [DownloadContentController::class, 'updateDownloadContent'])->name('downloadcontent.update');

            Route::delete('/delete/{id}', [DownloadContentController::class, 'deleteDownloadContent'])->name('downloadcontent.delete');
        });
    });
});

Route::middleware(['isheadandstaff'])->group(function () {
    Route::prefix('/aspirations')->group(function(){
        Route::post('/{id}/update-status', [AspirationController::class, 'updateStatus'])->name('aspirations.updateStatus');
        // Route::get('/pdf/convert', [PDFController::class, 'pdfGenerationAllAspirations'])->name('aspirations.pdf.convertAspiration');
        // Route::get('/pdf/convert/{category_id}', [PDFController::class, 'pdfGenerationAspirationsByCategory'])->name('aspirations.pdf.convertCategoryAspiration');
        Route::patch('/requestApproval/{id}', [AspirationController::class, 'requestApprovalAspiration'])->name('staff.requestApprovalAspiration');
        Route::patch('/staffReject/{id}', [AspirationController::class, 'rejectAspiration'])->name('staff.rejectAspiration');
        Route::patch('/headApprove/{id}', [AspirationController::class, 'approveAspiration'])->name('headmaster.approveAspiration');
        Route::patch('/headReject/{id}', [AspirationController::class, 'rejectAspiration'])->name('headmaster.rejectAspiration');
        Route::patch('/process/{id}', [AspirationController::class, 'onProgAspiration'])->name('processAspiration');
        Route::patch('/monitoring/{id}', [AspirationController::class, 'monitoringAspiration'])->name('monitoringAspiration');
        Route::patch('/finish/{id}', [AspirationController::class, 'finishAspiration'])->name('finishAspiration');
        
        Route::get('/pin/{id}', [AspirationController::class, 'pinAspiration'])->name('pinAspiration');
        Route::get('/unpin/{id}', [AspirationController::class, 'unpinAspiration'])->name('unpinAspiration');
        Route::post('/update-status', [AspirationController::class, 'updateStatus'])->name('aspiration.updateStatus');
        Route::post('/assign', [AspirationController::class, 'assign'])->name('aspiration.assign');
        Route::post('/aspiration/update-processed-by', [AspirationController::class, 'updateProcessedBy'])->name('aspiration.updateProcessedBy');
        
        Route::get('/manage/{aspiration_id}',[AspirationController::class, 'manageAspirationDetail'])->name('manage.aspiration.detail');
    });

    Route::prefix('/report')->group(function(){
        Route::patch('/requestApproval/{id}', [ReportController::class, 'requestApprovalReport'])->name('staff.requestApprovalReport');
        Route::patch('/staffApprove/{id}', [ReportController::class, 'approveReport'])->name('staff.approveReport');
        Route::patch('/staffReject/{id}', [ReportController::class, 'rejectReport'])->name('staff.rejectReport');
        Route::patch('/headApprove/{id}', [ReportController::class, 'approveReport'])->name('headmaster.approveReport');
        Route::patch('/headReject/{id}', [ReportController::class, 'rejectReport'])->name('headmaster.rejectReport');
        Route::patch('/reviewStaff/{id}', [ReportController::class, 'inReviewStaffReport'])->name('staff.reviewReport');
        Route::patch('/reviewHeadmaster/{id}', [ReportController::class, 'inReviewHeadmasterReport'])->name('headmaster.reviewReport');
        Route::patch('/process/{id}', [ReportController::class, 'onProgReport'])->name('processReport');
        Route::patch('/monitoring/{id}', [ReportController::class, 'monitoringReport'])->name('monitoringReport');
        Route::patch('/finish/{id}', [ReportController::class, 'finishReport'])->name('finishReport');
        Route::get('/report/pdf/convert', [PDFController::class, 'pdfGenerationAllReports'])->name('convertReport');
        Route::get('/report/pdf/convert/{category_id}', [PDFController::class, 'pdfGenerationReportsByCategory'])->name('convertCategoryReport');
    });

    Route::prefix('/consultation/manage')->group(function() {
        Route::get('/', [ConsultationEventController::class, 'seeAllEvents'])->name('consultation.seeAll');
        Route::get('/all', [ConsultationEventController::class, 'fetchAllEvents'])->name('consultation.fetchAll');
        Route::get('/createConsultation', [ConsultationEventController::class, 'createEventForm'])->name('consultation.createForm');
        Route::post('/createConsultation', [ConsultationEventController::class, 'createEvent'])->name('consultation.create');
    });

    Route::get('/dashboard', [UserController::class, 'getDashboard'])->name('dashboard');
    Route::get('/dashboard/filtered', [UserController::class, 'getDashboardddFiltered'])->name('dashboard.filtered');
});


Route::middleware(['isadmin'])->group(function () {
    // Staff Type
    Route::prefix('/staffType')->group(function(){
        Route::get('/createForm', [StaffTypeController::class, 'createStaffTypeForm'])->name('admin.createStaffTypeForm');
        Route::post('/create', [StaffTypeController::class, 'createStaffType'])->name('admin.createStaffType');
        Route::get('/updateForm/{id}', [StaffTypeController::class, 'updateStaffTypeForm'])->name('admin.updateStaffTypeForm');
        Route::patch('/update/{id}', [StaffTypeController::class, 'updateStaffType'])->name('admin.updateStaffType');
        Route::delete('/delete/{id}', [StaffTypeController::class, 'deleteStaffType'])->name('admin.deleteStaffType');
        Route::get('/list', [StaffTypeController::class, 'index'])->name('admin.staffTypeList');
    });
    
    // User
    Route::prefix('/manage/users')->group(function(){
        Route::get('/', [UserController::class, 'seeAllUser'])->name('manage.users.seeall');
        Route::get('/search', [UserController::class, 'searchUserList'])->name('manage.users.search');
        Route::get('/detail/{user_id}', [UserController::class, 'getUserDetail'])->name('manage.users.detail');
        
        Route::get('/register', [UserController::class, 'registerUserForm'])->name('manage.users.register');
        Route::post('/register', [UserController::class, 'registerUser'])->name('manage.users.register.submit');
        
        Route::get('/update/{user_id}', [UserController::class, 'updateUserForm'])->name('manage.users.update');
        Route::patch('/update/{user_id}', [UserController::class, 'updateUser'])->name('manage.users.update.submit');
        Route::patch('/password/change/{user_id}', [UserController::class, 'resetPassword'])->name('manage.users.reset.password');
        Route::post('/delete/selected', [UserController::class, 'removeSelectedUsers'])->name('manage.users.delete.selected');
        Route::delete('/delete/{user_id}', [UserController::class, 'removeUser'])->name('manage.users.delete');
        
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
    
    // Aspiration
    Route::prefix('/aspirations')->group(function(){
        Route::delete('/delete/{id}', [AspirationController::class, 'deleteAspiration'])->name('aspirations.delete');
        
        Route::get('/reported', [UserReportAspirationController::class, 'getAllReportedAspirations'])->name('aspirations.reported');
        Route::get('/reported/{aspiration_id}', [UserReportAspirationController::class, 'getAllReportedAspirationDetail'])->name('aspirations.reported.details');
        Route::delete('/reported/delete/{id}', [UserReportAspirationController::class, 'deleteReportedAspiration'])->name('aspirations.reported.delete');
        
        Route::patch('/suspend/{user_id}', [UserReportAspirationController::class, 'suspend'])->name('manage.users.suspend');
        Route::get('/suspended', [UserReportAspirationController::class, 'getAllSuspendedUsers'])->name('aspirations.suspended.list');
        Route::patch('/unsuspend/{user_id}', [UserReportAspirationController::class, 'unsuspend'])->name('manage.users.unsuspend');
    });

    Route::delete('/report/delete/{id}', [ReportController::class, 'deleteReportAdmin'])->name('admin.deleteReport');

});


Route::middleware(['isstudent'])->group(function () {
    Route::prefix('/aspirations')->group(function(){
        Route::get('/myAspirations', [AspirationController::class, 'myAspiration'])->name('aspirations.myAspirations');
        Route::get('/addForm', [AspirationController::class, 'showAddAspirationForm'])->name('aspirations.addForm');
        Route::post('/create', [AspirationController::class, 'addAspiration'])->name('aspirations.create');
        Route::get('/updateForm/{id}', [AspirationController::class, 'updateAspirationForm'])->name('aspirations.updateForm');
        Route::patch('/update/{id}', [AspirationController::class, 'updateAspirationLogic'])->name('aspirations.update');
        Route::delete('/delete/{id}', [AspirationController::class, 'deleteAspiration'])->name('aspirations.delete');
        Route::post('/reported/create/{aspirationId}', [UserReportAspirationController::class, 'createReportedAspiration'])->name('aspirations.reported.create');
        Route::patch('/upvote/{id}', [AspirationController::class, 'upvote'])->name('upvote');
        Route::patch('/unUpvote/{id}', [AspirationController::class, 'unUpvote'])->name('unUpvote');
    });

    Route::prefix('/report')->group(function(){
        Route::get('/myReport', [ReportController::class, 'myReport'])->name('report.student.myReport');
        Route::get('/createForm', [ReportController::class, 'createReportForm'])->name('student.createReportForm');
        Route::get('/urgent', [ReportController::class, 'urgentReportPage'])->name('student.urgentReportPage');
        Route::post('/create', [ReportController::class, 'createReport'])->name('student.createReport');
        Route::post('/createUrgent', [ReportController::class, 'createReportUrgent'])->name('student.createReportUrgent');
        Route::get('/sirine', function () {
            return view('report.student.urgentSirine');
        });
        // Route::get('/updateForm/{id}', [ReportController::class, 'updateReportForm'])->name('student.updateReportForm');
        // Route::patch('/update/{id}', [ReportController::class, 'updateReport'])->name('student.updateReport');
        Route::patch('/cancel/{id}', [ReportController::class, 'cancelReport'])->name('student.cancelReport');
    });

    Route::prefix('/consultation')->group(function() {
        Route::get('/sessionList', [ConsultationEventController::class, 'sessionList'])->name('consultation.sessionList');
        Route::get('/sessionList/sorting/{typeSorting}', [ConsultationEventController::class, 'sessionListSorting'])->name('consultation.sessionList.sorting');
        Route::get('/mySession', [ConsultationEventController::class, 'mySession'])->name('consultation.mySession');
        Route::get('/mySession/sorting/{typeSorting}', [ConsultationEventController::class, 'mySessionSorting'])->name('consultation.mySession.sorting');
    });


    Route::patch('/updateUrgentPhoneNum', [UserController::class, 'updateUrgentPhoneNum'])->name('student.updateUrgentPhoneNum');
});


// Route::prefix('/aspirations')->group(function(){
    
    
    
    // Route::get('/chatify/{id}', [ReportController::class, 'urgentReportPage'])->name('student.chatify');



// });




















