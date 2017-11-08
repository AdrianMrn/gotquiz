<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

use App\User;

class ReferralController extends Controller
{
    public function referralLink(Request $request, $id) {
        if (Auth::check())
        {
            $request->session()->flash('error', 'Already logged in. Referral not possible.');
            return redirect('/');
        }
        $referrer = User::find($id);
        if (!$referrer)
        {
            $request->session()->flash('error', 'That referrer does not exist.');
            return redirect('/');
        }

        $request->session()->put('referrerid', $id); //saving the referrer id in session for when creating an account
        return redirect()->route('register');
    }
}
