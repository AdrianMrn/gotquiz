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
            $contest->winner_points = 0;
    
            $contest->save();

            return redirect()->route('admin.contestdetail', ['id' => $contest->id]);
        } else {
            //Validation
            $this->validate($request, [
                'winner_id' => 'max:255|numeric',
                'start' => 'required|date_format:Y-m-d H:i:s',
                'end' => 'required|date_format:Y-m-d H:i:s',
                'status' => 'required|string|max:15',
                'participations_allowed_daily' => 'required|numeric',
                'contest_admin_id' => 'required|numeric'
            ]);

            if ($request->end <= $request->start) {
                return redirect()->back()->withErrors("The end date has to be after the start date")->withInput();
            }
            if ($this->currentContest() && $request->status == 'running' && $this->currentContest() != $request->id) {
                return redirect()->back()->withErrors("There cannot be more than 1 contest running at the same time")->withInput();
            }
            $contests = Contest::all();
            foreach ($contests as $o_contest) {
                if ($request->id != $o_contest->id) {
                    if ($o_contest->start > $request->start && $o_contest->start < $request->end) {
                        return redirect()->back()->withErrors("Your contest period (start and/or end) overlaps with another contest")->withInput();
                    }
                    if ($o_contest->end > $request->start && $o_contest->end < $request->end) {
                        return redirect()->back()->withErrors("Your contest period (start & end) overlaps with another contest")->withInput();
                    }
                }
            }
            //request is valid (I hope)

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

    public function exportExcel($id) {
        $contest = Contest::find($id);
        //$participations = Participation::where([['contest_id', $id], ['created_at', '>', Carbon::now()->subDay()]])->get();
        $participations = Participation::where('contest_id', $id)->get();

        \Excel::create('GoTQuiz Export Season ' . $id, function($excel) use($participations) {
            $excel->setTitle('GoTQuiz Export')->setCreator('GoTQuiz')->setCompany('GoTQuiz')->setDescription('Export of GoTQuiz');
            $excel->sheet('Export', function($sheet) use($participations) {
                $sheet->fromArray($participations, null, 'A1', true);
            });
        })->download('xls');
    }
}
