<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;

use App\Contest;

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
            /* echo "\nContest id " . $contest->id . ":\n";
            echo "now: " . Carbon::now() . "\n";
            echo "start: " . $contest->start . "\n";
            echo "end: " . $contest->end . "\n"; */

            if (Carbon::now() > $contest->start && Carbon::now() < $contest->end) {
                /* echo "Running.\n"; */
                $contest->status = "running";
            } elseif (Carbon::now() < $contest->end) {
                /* echo "Upcoming.\n"; */
                $contest->status = "upcoming";
            } else {
                /* echo "Finished.\n"; */
                $contest->status = "finished";
            }
            $contest->save();
        }

        echo "\nCheck contests finished\n";
    }
}
