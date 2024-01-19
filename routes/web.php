<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\CandidateController;
use App\Http\Controllers\Admin\OperatorController;
use App\Http\Controllers\Admin\PhoneAuthController;
use App\Http\Controllers\Admin\VotesController;
use App\Http\Controllers\Dashboard\HomeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

$request = new Request();
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

//login middleware

Route::group(['middleware' => 'notLoggedIn'], function () {
    Route::get('/', [AuthController::class, 'login'])->name('login');
    Route::get('/forgot-password', [AuthController::class, 'forgotPasswordView'])->name('forgot');

    Route::get('/create-password/{id}', [OperatorController::class, 'LinkOpened'])->name('linkopened');

    Route::post('/operator/create-password', [OperatorController::class, 'createLinkPassword'])->name('passwordcreate');
    Route::post('/forgot-password', [AuthController::class, 'forgotPassword'])->name('forgotReset');
    Route::post('/authenticate', [AuthController::class, 'authenticate'])->name('admin.authenticate');

    // dummy routes starts
    Route::get('/authenticate', [AuthController::class, 'login']);
    Route::get('/operator/create-password', [AuthController::class, 'login']);
    // dummy routes ends here

});

Route::group(['middleware' => 'loginguard'], function () {
    // logined routes goes here
    Route::get('/get-divisons', [VotesController::class, 'getDivision'])->name('getDivision');
    Route::get('/get-party', [VotesController::class, 'getParty'])->name('getParty');
    Route::get('/get-district', [VotesController::class, 'getDistrict'])->name('getDistrict');
    Route::get('/get-district2', [VotesController::class, 'getDistrict2'])->name('getDistrict2');
    Route::get('/get-seatype', [VotesController::class, 'getSeattype'])->name('getseatype');
    Route::get('/get-candidate', [VotesController::class, 'getCandidate'])->name('getCandidate');
    Route::get('/change-password', [OperatorController::class, 'changePasswordView'])->name('changePassword');

    Route::post('/change-password', [OperatorController::class, 'changePassword'])->name('postChangePassword');

    Route::group(['middleware' => 'adminguard', 'prefix' => 'admin'], function () {
        Route::get('/home', [HomeController::class, 'index'])->name('admin.home');
        // candidates routes
        Route::get('/candidate/create', [CandidateController::class, 'create'])->name('candidate.create');
        Route::get('/close-enty',[AuthController::class, 'closeSystemEntry'])->name('close.entry');

        Route::get('/candidate', [CandidateController::class, 'index'])->name('candidate.list');

        // operator routes
        Route::get('/operator/create', [OperatorController::class, 'create'])->name('operator.create');

        Route::get('/operator', [OperatorController::class, 'index'])->name('operator.list');
        Route::get('/operator/show/{id}', [OperatorController::class, 'show'])->name('operator.show');
        Route::get('/operator/edit/{id}', [OperatorController::class, 'edit'])->name('operator.edit')->where('id', '[0-9]+');

        Route::post('/operator/change-status', [OperatorController::class, 'ChangeAllOperatorStatus'])->name('operator.changeAllStatus');
        // dummy routes start
        Route::get('/operator/change-status', [AuthController::class, 'login']);

        //dummy routes ends
        Route::post('/operator', [OperatorController::class, 'store'])->name('operator.store');
        Route::put('/operator/{id}', [OperatorController::class, 'update'])->name('operator.update')->where('id', '[0-9]+');
        Route::post('/candidate', [CandidateController::class, 'store'])->name('candidate.store');

        // admin routes goes here
    });
    Route::group(['middleware' => 'operatorguard', 'prefix' => 'operator'], function () {
        //operator routes goes here
        Route::get('/send-otp', [PhoneAuthController::class, 'index'])->name('operator.sendotp');

        Route::post('/check-number', [PhoneAuthController::class, 'CheckPhoneNumber'])->name('operator.checknumber');
        Route::post('/check-email', [PhoneAuthController::class, 'CheckEmail'])->name('operator.checkEmail');
        Route::post('/set-otp-session', [PhoneAuthController::class, 'setSession'])->name('operator.setotpsession');

        // dummy routes start
        Route::get('/set-otp-session', [AuthController::class, 'login']);
        Route::get('/check-email', [AuthController::class, 'login']);
        Route::get('/check-number', [AuthController::class, 'login']);
        // dummy routes end

        Route::group(['middleware' => 'otpguard'], function () {
            //otp routes goes here
            // votes entring

            Route::get('/votes/create', [VotesController::class, 'create'])->name('votes.create');

            Route::post('/votes', [VotesController::class, 'store'])->name('votes.store');
            Route::get('/votes/{id}/edit', [VotesController::class, 'edit'])->name('votes.edit');
            Route::put('/votes/{id}', [VotesController::class, 'update'])->name('votes.update');
            Route::post('/votes/pk-votes-details', [VotesController::class, 'enterPkVotesDetails'])->name('votes.pkdetails');
            Route::post('/votes/Na-votes-details', [VotesController::class, 'enterNaVotesDetails'])->name('votes.Nadetails');
            Route::post('/votes/pk-seats-entry-detail', [VotesController::class, 'submitpkvotesentry'])->name('votes.pkseatsentry');
            Route::post('/votes/na-seats-entry-detail', [VotesController::class, 'submitnavotesentry'])->name('votes.naseatsentry');

        });
    });
    Route::get('/PK-votes', [VotesController::class, 'index'])->name('votes.pklist');
    Route::get('/NA-votes', [VotesController::class, 'naSeats'])->name('votes.nalist');
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
});
//Auth::routes(['verify' => true]);
Route::get('/no-access', [AuthController::class, 'noAccess'])->name('noAccess');

// All post routes converted to get
