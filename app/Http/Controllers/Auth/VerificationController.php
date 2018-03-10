<?php

namespace App\Http\Controllers\Auth;

use App\Events\UserRequestedVerificationEmail;
use App\Http\Controllers\Controller;
use App\User;
use App\VerificationToken;
use Illuminate\Http\Request;

class VerificationController extends Controller
{
    public function verify(VerificationToken $token)
    {
        $token->user()->update(['verified' => true]);
        $token->delete();

        return redirect()
            ->route('login')
            ->withInfo(__('Email verification successful. Please login again'));
    }

    public function resend(Request $request) {
        $user = User::where('email', $request->email)->firstOrFail();
        if ($user->hasVerified()) {
            return redirect()->route('home');
        }

        event(new UserRequestedVerificationEmail($user));
        return redirect()
            ->route('home')
            ->withInfo(__('Verification email resent. Please check your inbox'));
    }
}
