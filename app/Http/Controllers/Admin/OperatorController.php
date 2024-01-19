<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\SendMailJob;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class OperatorController extends Controller
{
    public function index()
    {
        $operators = User::with(['districts'])->where('role', 'Operator')->get();
        $data = compact('operators');

        //return $operators;

        return view('Operators.list')->with($data);

    }

    public function show(string $id)
    {
        return $id;

    }

    public function create()
    {
        return view('Operators.create');
    }

    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'phoneNo' => 'required',
            'district' => 'required',
            //'password' => 'required|min:6|confirmed',
            //'password_confirmation' => 'required|min:6',
        ]);
        if ($validator->passes()) {
            $data = [
                'name' => $request->name,
                'email' => $request->email,
                'phoneNo' => '+'.$request->phoneNo,
                'fk_district_id' => $request->district,
                'role' => 'Operator',
                'status' => 'Active',
                'password' => Hash::make('admin'),

            ];

            try {

                $user = User::create($data);
                //Mail process starts here
                $user_id1 = $user->id + 100;
                $user_id = Crypt::encrypt($user_id1);
                $mailData = [
                    'title' => 'Mail from ELC',
                    'body' => $user_id,
                ];
                dispatch(new SendMailJob($request->email, $mailData));
                //Mail process ends here
                $request->session()->flash('success', 'Operator account created successfully!');

                return response()->json([
                    'status' => true,
                    'message' => 'Operator account created successfully!',
                ]);

            } catch (\Exception $error) {
                $request->session()->flash('danger', 'Operator not account created!');

                return response()->json([
                    'status' => false,
                    'message' => 'Operator account not created',
                    'error' => $error,

                ]);
            }

        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),

            ]);
        }

    }

    // operator edit function
    public function edit($id)
    {

        $operator = User::where('id', $id)->where('role', 'Operator')->first();
        if (! is_null($operator)) {
            $data = compact('operator');

            //return view('Operators.edit')->with($data);
            return response()->json([
                'data' => $operator,
                'status' => true,
            ]);
        } else {
            return response()->json([
                'message' => 'Operator not found',
                'status' => false,
            ]);
            // return redirect()->route('operator.list')->with('error', 'Operator not found!');
        }

    }

    public function update(string $id, Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users,id,'.$request->id,
            'phoneNo' => 'required',
            'district' => 'required',
        ]);
        if ($validator->passes()) {
            $data = [
                'name' => $request->name,
                'email' => $request->email,
                'phoneNo' => '+'.$request->phoneNo,
                'fk_district_id' => $request->district,
                'role' => 'Operator',
                'status' => 'Active',

            ];

            try {

                $user = User::find($id);
                if (is_null($user)) {
                    $request->session()->flash('error', 'Operator account not found!');

                    return response()->json([
                        'status' => false,
                        'message' => 'Operator account not found!',
                    ]);

                }
                $user->update($data);
                $request->session()->flash('success', 'Operator account updated successfully!');

                return response()->json([
                    'status' => true,
                    'message' => 'Operator account updated successfully!',
                ]);

            } catch (\Exception $error) {
                $request->session()->flash('error', 'Operator not account updated!');

                return response()->json([
                    'status' => false,
                    'message' => 'Operator account not updated',
                    'error' => $error,

                ]);
            }

        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),

            ]);
        }
    }

    public function destroy(string $id)
    {

    }

    public function LinkOpened($id)
    {
        $user_id = Crypt::decrypt($id);

        $user_id = $user_id - 100;
        $record = User::where('id', $user_id)->first();
        if (! is_null($record)) {
            $data = compact('record');

            return view('Operators.linkPassword')->with($data);
        } else {
            return redirect()->route('login');
        }

    }

    public function createLinkPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'role' => 'required',
            'password' => 'required|min:6|confirmed',
            'password_confirmation' => 'required|min:6',
        ]);
        if ($validator->passes()) {

            try {
                //$password = rand(100, 100);
                $user = User::where('email', $request->email)->where('role', $request->role)->first();
                if (! is_null($user)) {
                    $data = [
                        'password' => Hash::make($request->password),
                    ];
                    $user->update($data);

                    return redirect()->route('login')->with('success', 'Please enter your password!');
                // ends here

                } else {

                    return redirect()->route('login')->with('error', 'No account found!');

                }

            } catch (\Exception $error) {

                return redirect()->back()->with('error', 'Some thing went wrong!');
            }
        } else {
            // validator error
            return redirect()->back()->withErrors($validator)->withInput()->with('error', 'No account found!');
        }

    }

    public function test()
    {
        $dataToEncrypt = 'Sensitive data';

        $encryptedData = Crypt::encrypt($dataToEncrypt);

        return $decryptedData = Crypt::decrypt($encryptedData);

    }

    public function changePasswordView()
    {
        return view('Login.changePassword');
    }

    public function validateCurrentPassword($password)
    {
        $userpassword = auth('admin')->user()->password;
        if (Hash::check($password, $userpassword)) {
            return true;
        }
    }

    public function validateNewPassword($newPassword, $currentPassword)
    {

        if ($newPassword = $currentPassword) {
            return true;
        }
    }

    public function changePassword(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'currentPassword' => 'required',
            'password' => 'required|min:6|confirmed',
            'password_confirmation' => 'required|min:6',
        ]);

        $validator->after(function ($validator) use ($request) {
            // Your custom validation logic here
            if (! $this->validateCurrentPassword($request->currentPassword)) {
                $validator->errors()->add('currentPassword', 'The provided current password is incorrect.');
            }
        });
        $validator->after(function ($validator) use ($request) {
            // Your custom validation logic here
            if (! $this->validateNewPassword($request->password, $request->currentPassword)) {
                $validator->errors()->add('password', 'The provided new password is same with current.');
            }
        });

        if ($validator->passes()) {
            // $id = Session::get('user_id');
            // $user = User::where('id', $id)->first();

            try {
                $user = auth('admin')->user();
                $user->update(['password' => Hash::make($request->password)]);
                Session::flush();
                $user->logout();

                return redirect()->route('login')->with('success', 'Please login with new password!');
            } catch (\Exception $error) {
                return redirect()->back()->with('error', "Password can't changed");
            }

        } else {
            return redirect()->back()->withErrors($validator->errors())->withInput();

        }

    }
}
