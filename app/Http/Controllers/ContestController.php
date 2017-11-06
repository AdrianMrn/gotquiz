<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Carbon\Carbon;

use App\Contest, App\Participation;

class ContestController extends Controller
{
    public function getAll() {
        $contests = Contest::simplePaginate(10);

        return view('admin.contests', ['contests' => $contests]);
    }

    public function update($id, Request $request)
    {
        if (!$id) {
            $contest = new Contest;
            $contest->start = Carbon::now()->addDays(1);
            $contest->end = Carbon::now()->addDays(2);
            $contest->contest_admin_id = Auth::user()->id;
            $contest->winner_points = 0; //future: get rid of this (fixed in migration by adding default)
    
            $contest->save();
    
            return redirect()->route('admin.contests');
        } else {
            //future: validation for contest->end > contest->start
            //future: validation for no intersections with other contests
            $contest = Contest::find($id);
            $contest->winner_id = $request->winner;
            $contest->start = $request->start;
            $contest->end = $request->end;
            $contest->status = $request->status;
            $contest->participations_allowed_daily = $request->participations_allowed_daily;
            $contest->contest_admin_id = $request->contest_admin_id;
            $contest->save();
            return redirect()->route('admin.contests');
        }
    }

    public function detail($id) {
        $contest = Contest::find($id);
        return view('admin.contestDetail', ['contest' => $contest]);
    }

    public function destroy($id)
    {
        Contest::find($id)->delete();
        return redirect()->route('admin.contests');
    }

    public function currentContest()
    {
        $currentContest = Contest::where("status" , '=', "running")->first();
        if (!$currentContest)
        {
            return 0;
        } //else
        return $currentContest->id;
    }
    public function participationsRemaining($userid)
    {
        $amountAllowed = Contest::find($this->currentContest())->participations_allowed_daily;
        $amountOfParticipations = Participation::where([['user_id', '=', $userid],['contest_id', '=', $this->currentContest()],['created_at', '>', Carbon::now()->subDay()]])->count();

        return $amountAllowed - $amountOfParticipations;
    }
}
