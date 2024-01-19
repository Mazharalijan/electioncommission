<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\SendMailJob;
use App\Mail\SendMail;
use App\Models\Admin\Userotps;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function index()
    {
        //return 'Logined';

    }

    public function login()
    {
        return view('Login.login1');
    }

    public function authenticate(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required',
        ]);
        if ($validator->passes()) {
            //return 'Hello 1';
            if (Auth::guard('admin')->attempt(['email' => $request->email, 'password' => $request->password], $request->get('remember'))) {
                $admin = Auth::guard('admin')->user();

                //return 'Hello 2';
                if ($admin->role == 'Admin') {
                    //return 'Hello 3';
                    Session::put([
                        'user_id' => $admin->id,
                        'role' => $admin->role,
                    ]);

                    return redirect()->route('admin.home');
                //admin redirection  here
                } elseif ($admin->role == 'Operator') {
                    //return 'Hello 4';
                    //operator redirection here
                    Session::put([
                        'user_id' => $admin->id,
                        'role' => $admin->role,
                        'phoneNo' => $admin->phoneNo,
                        'email' => $admin->email,
                    ]);

                    return redirect()->route('operator.sendotp');

                } else {
                    //return 'Hello 5';

                    Auth::guard('admin')->logout();

                    return redirect()->route('login')->with('error', 'You are not authorized to access admin panel.');
                }
            } else {

                return redirect()->route('login')->withInput($request->only('email'))->with('error', 'Either Email/password is incorrect');
            }

        } else {

            //return $validator->messages();

            return redirect()->route('login')
                ->withErrors($validator->errors())
                ->withInput($request->only('email'));
        }

    }

    public function otpMatch(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'otp' => 'required',
        ]);
        if ($validator->passes()) {

            $otpId = Session::get('otp_id');
            $operator_id = Session::get('user_id');
            $exp_time = Session::get('expire_time');
            $userotp = Userotps::where('otpId', $otpId)->where('fk_user_id', $operator_id)->where('userotp', $request->otp)->first();
            if (! is_null($userotp)) {
                // otp match logic goes here
                $currentTime = Carbon::now();
                $exptime = $userotp->otp_expires_at;
                $checkexpt = $exptime->addMinutes(2);
                if ($exptime->gt($checkexpt)) {
                    $data = [
                        'otpstatus' => 'expired',
                    ];
                    $userotp->update($data);

                    return redirect()->back()->with('danger', 'This otp has been expired')->withInput();

                } else {
                    // validated
                    // login
                    $data = [
                        'otpstatus' => 'okay',
                    ];
                    $userotp->update($data);

                    //return view();
                }

            } else {
                //
                return redirect()->back()->with('danger', 'You have entered incorrect otp!')->withInput();

            }

        } else {
            // validation error
            return redirect()
                ->route()
                ->withErrors($validator)
                ->withInput();

        }

    }

    public function logout()
    {

        Auth::guard('admin')->logout();
        Session::flush();

        return redirect()->route('login');

    }

    // forgot password

    public function forgotPasswordView()
    {
        return view('Login.forgotPassword2');
    }

    public function forgotPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|exists:users,email|email',
        ]);
        if ($validator->passes()) {

            try {
                $user = User::where('email', $request->email)->first();
                $user_id1 = $user->id + 100;
                $user_id = Crypt::encrypt($user_id1);
                $mailData = [

                    'title' => 'Mail from ELC for Forgot password',
                    //'body' => 'please set your password and login your account',
                    'body' => $user_id,

                ];
                dispatch(new SendMailJob($request->email, $mailData));
                //Mail::to($request->email)->send(new SendMail($mailData));

                return redirect()->route('login')->with('success', 'Password reset link sended to your email');
            } catch (\Exception $error) {

                return redirect()->route('forgot')->with('error', "Password can't reset");

            }

        } else {

            return redirect()->back()->withErrors($validator->errors())->withInput($request->only('email'));

        }

    }

    public function noAccess(Request $request)
    {
        return $request->method();
    }
    public function closeSystemEntry(){
        User::where('role', 'Operator')->update(['status' => 'In-Active']);

        return redirect()->route('admin.home')->with('success', 'All operater entry closed!');


    }
}
