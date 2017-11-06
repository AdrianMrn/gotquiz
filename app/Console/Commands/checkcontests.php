<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;

use App\Contest, App\Participation, App\User;

use App\Http\Controllers\ContestController;

require 'vendor/autoload.php';
use Mailgun\Mailgun;

class checkcontests extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'do:checkcontests';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Checking if contests should begin, end, ...';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        echo "Check contests started\n";

        $contests = Contest::all();

        foreach ($contests as $contest) {
            if (Carbon::now() > $contest->start && Carbon::now() < $contest->end) {
                $contest->status = "running";
            } elseif (Carbon::now() < $contest->end) {
                $contest->status = "upcoming";
            } else {
                if ($contest->status == "running")
                {
                    //this is run the moment a contest goes from running to finished (end-of-contest)
                    $contest = $this->endOfContest($contest);
                }
                $contest->status = "finished";
            }
            $contest->save();
        }

        echo "\nCheck contests finished\n";
    }

    public function endOfContest($contest)
    {
        echo "\nContest ending!\n";
        //counting the users' total points for this contest
        $points = [];
        $participations = Participation::where('contest_id', $contest->id)->get();
        if (sizeof($participations)) { //future: test the contest end if there were no participations at all. && send a different mail in the else
            foreach ($participations as $participation) {
                $user_id = $participation->user_id;
                if (array_key_exists($user_id, $points)) {
                    $points[$user_id] += $participation->points;
                } else {
                    $points[$user_id] = $participation->points;
                }
            }
    
            //picking a winner
            $highestPoints = max($points);
            $winner_ids = array_keys($points, $highestPoints);
            $winner_id = $winner_ids[rand(0,sizeof($winner_ids)-1)]; //if there's a tie, pick a random winner from the tied users
    
            $contest->winner_id = $winner_id;
            $contest->winner_points = $points[$winner_id];
    
            //future: send email to contest_admin_id with the message that contest id x has ended + the contest's winner's info
            $winner = User::find($winner_id);
            $admin = User::find($contest->contest_admin_id);
    
            $mgClient = new Mailgun('key-7b0ef6e57e20a734a5fe69c8d7ee8915');
            $domain = "sandbox1130ed18768546ab8261e91ef48d6275.mailgun.org";
    
            $to = $admin->name . "<" . $admin->email . ">";
            $subject = "GoTQuiz Season " . $contest->id . " ending";
            $text = "Hi " . $admin->name . ",\n\nYou are receiving this email because you are the contest admin for this season of GoTQuiz. A winner has been chosen, here's their information:\n\n" .
            "User id: " . $winner_id . "\nScore: " . $points[$winner_id] . "\nName: " . $winner->name . "\nEmail address: " . $winner->email . "\nAddress: " . $winner->address . ", " . $winner->town;
    
            $result = $mgClient->sendMessage("$domain",
                    array('from'    => 'Mailgun Sandbox <postmaster@sandbox1130ed18768546ab8261e91ef48d6275.mailgun.org>',
                            'to'      => $to,
                            'subject' => $subject,
                            'text'    => $text));
    
            return $contest;
        }
    }
}
