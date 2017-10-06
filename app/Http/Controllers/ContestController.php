<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;

use App\Contest;

class ContestController extends Controller
{
    public function update($id, Request $request)
    {
        if (!$id) {
            $contest = new Contest;
            $contest->start = Carbon::now();
            $contest->end = Carbon::now()->addDays(1);
    
            $contest->save();
    
            return redirect()->route('admin.dashboard');
        } else {
            //future: validation for contest->end > contest->start
            //future: validation for no intersections with other contests
            $contest = Contest::find($id);
            $contest->winner_id = $request->winner;
            $contest->start = $request->start;
            $contest->end = $request->end;
            $contest->status = $request->status;
            $contest->save();
            return redirect()->route('admin.dashboard');
        }
    }

    public function detail($id) {
        $contest = Contest::find($id);
        return view('admin.contestDetail', ['contest' => $contest]);
    }

    public function destroy($id)
    {
        Contest::find($id)->delete();
        return redirect()->route('admin.dashboard');
    }
}
