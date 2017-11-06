<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Contest, App\User;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {}

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $contests = Contest::where('status', 'finished')->get();

        foreach($contests as $contest) {
            if ($contest->winner_id) {
                $winner = User::find($contest->winner_id);
                $contest->winnername = $winner->name;
            } else {
                $contest->winnername = "No winner!";
            }
        }

        return view('home', ['contests' => $contests]);
    }
}
