<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\SendMailJob;
use App\Mail\SendMail;
use App\Models\UsersCodes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class PhoneAuthController extends Controller
{
    public function index()
    {
        return view('Phone.phone');
    }

    public function CheckPhoneNumber(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'number' => 'required',
        ]);
        if ($validator->passes()) {
            $operatorNumber = Auth::guard('admin')->user()->phoneNo;
            if ($operatorNumber == $request->number) {
                return response()->json([
                    'number' => $operatorNumber,
                    'status' => 'success',
                    'message' => 'Phone munber matched',
                ]);
            } else {
                return response()->json([
                    'number' => $operatorNumber,
                    'status' => 'notmatched',
                    'message' => 'Phone munber does not matched',
                ]);
            }

        } else {
            return response()->json([
                'errors' => $validator->errors(),
                'status' => 'failed',
            ]);
        }
    }

    public function setSession(Request $request)
    {

        if ($request->has('emailcode')) {

            $user = auth('admin')->user();
            //$codes = UsersCodes::where('fk_user_id', $user->id)->first();
            $codes = UsersCodes::where('fk_user_id', $user->id)->latest('created_at')->first();


            if ($request->emailcode == $codes->codes) {

                Session::put([
                    'otpstatus' => 'okay',
                ]);
                $codes->delete();

                return response()->json([
                    'status' => true,
                ]);
            }
        } else {
            Session::put([
                'otpstatus' => 'okay',
            ]);

            return response()->json([
                'status' => true,
            ]);

        }

    }

    public function test()
    {
        return 'logined with otp';
    }

    public function CheckEmail(Request $request)
    {

        try {

            $user = auth('admin')->user();
            if ($user->role == 'Operator') {
                $codes = rand(1, 99999);
                $data = [
                    'codes' => $codes,
                    'fk_user_id' => $user->id,
                ];
                UsersCodes::create($data);

                $mailData = [
                    'title' => 'Mail from ELC',
                    'body' => 'Please enter your otp to login your account',
                    'codes' => $codes,

                ];
                dispatch(new SendMailJob($user->email, $mailData));
                //Mail::to($user->email)->send(new SendMail($mailData));

                return response()->json([
                    'status' => true,
                    'message' => 'Verification Code send successfully',
                ]);

            }

        } catch (\Exception $error) {

            return response()->json([
                'status' => false,
                'message' => 'Verification Code not send!',
            ]);

        }

    }
}
