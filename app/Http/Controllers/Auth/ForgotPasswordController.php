<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Password;
use Illuminate\Validation\ValidationException;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    ///**
    // * @param Request $request
    // * @return RedirectResponse
    // * @throws ValidationException
    // */
    //public function sendResetLinkEmail(Request $request)
    //{
    //    $this->validate($request, ['username' => 'required'], ['username.required' => 'Please enter your username.']);
    //
    //    $response = $this->broker()->sendResetLink(
    //        $request->only('username')
    //    );
    //
    //    if ($response === Password::RESET_LINK_SENT) {
    //        return back()->with('status', trans($response));
    //    }
    //
    //    return back()->withErrors(
    //        ['email' => trans($response)]
    //    );
    //}
}
