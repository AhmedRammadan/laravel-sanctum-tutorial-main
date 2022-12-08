<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\StoreUserRequest;
use App\Models\User;
use App\Models\UsersVerify;
use App\Traits\HttpResponses;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    use HttpResponses;

    public function login(LoginUserRequest $request)
    {
        $request->validated($request->only(['email', 'password']));

        if(!Auth::attempt($request->only(['email', 'password']))) {
            return $this->error('', 'Credentials do not match', 401);
        }
        $base_url = Config('app.url');
        
        $user = User::where('email', $request->email)->first();
        $user->image_profile =  $base_url.'/storage/'.$user->image_profile;
        return $this->success([
            'user' => $user,
            'token' => $user->createToken('API Token')->plainTextToken
        ]);
    }

    public function register(StoreUserRequest $request) 
    {
        $request->validated($request->only(['name', 'email', 'image_profile', 'password']));

        $image_path = $request->file('image_profile')->store('images_profiles', 'public');
        
        $base_url = Config('app.url');
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'image_profile' =>$base_url.'/storage/'. $image_path,
            'password' => Hash::make($request->password),
        ]);

        $token = Str::random(64);

        UsersVerify::create([
            'user_id' => $user->id,
            'token' => $token
        ]);

        Mail::send('emails.emailVerificationEmail', ['token' => $token], function ($message) use ($request) {
            $message->to($request->email);
            $message->subject('Email Verification Mail');
        });

        return $this->success([],'You need to confirm your account. We have sent you an activation code, please check your email.');
    }

    public function logout() 
    {
     Auth::user()->currentAccessToken()->delete();

        return $this->success([
            'message' => 'You have succesfully been logged out and your token has been removed'
        ]);
    }
  
    public function verifyAccount($token)
    {
        $verifyUser = UsersVerify::where('token', $token)->first();

        $message = 'Sorry your email cannot be identified.';

        if (!is_null($verifyUser)) {
            $user = $verifyUser->user;

            if (!$user->is_email_verified) {
                $verifyUser->user->is_email_verified = 1;
                $verifyUser->user->save();
                $message = "Your e-mail is verified. You can now login.";
            } else {
                $message = "Your e-mail is already verified. You can now login.";
            }
        }

        return $this->success([
            // 'user' => $verifyUser->user,
            // 'message' => $message,
            // 'token' => $verifyUser->user->createToken('API Token')->plainTextToken
        ],$message);
    }
}
