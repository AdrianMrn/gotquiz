<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use Mailgun\Mailgun;

use App\Contest, App\Participation, App\User;
use App\Http\Controllers\ContestController;

class exportexcel extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'do:exportexcel';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Export and send an excel file of all of today\'s participations.';

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
        $currentContest = (new ContestController)->currentContest();
        if ($currentContest) {
            $contest = Contest::find($currentContest);
            $admin = User::find($contest->contest_admin_id);

            /* $mgClient = new Mailgun(env('MAILGUN_APIKEY', '')); */
            $mg = Mailgun::create(env('MAILGUN_APIKEY', ''));
            $domain = env('MAILGUN_DOMAIN', '');

            $to = $admin->name . "<" . $admin->email . ">";
            $subject = "GoTQuiz Season " . $contest->id . " daily export";
            $text = "Hi " . $admin->name . ",\n\nYou are receiving this email because you are the contest admin for this season of GoTQuiz.\n\n" . 
                    "Attached you can find an excel file of all the participations in the past 24 hours.";

            $participations = Participation::where('created_at', '>', Carbon::now()->subDay())->get();
    
            $exportFile = \Excel::create('GoTQuiz Export Season ' . $currentContest . "_" . Carbon::now()->timestamp, function($excel) use($participations) {
                $excel->setTitle('GoTQuiz Export')->setCreator('GoTQuiz')->setCompany('GoTQuiz')->setDescription('Export of GoTQuiz');
                $excel->sheet('Export', function($sheet) use($participations) {
                    $sheet->fromArray($participations, null, 'A1', true);
                });
            })->store('xls', storage_path('excelexports'), true);

            $fileFull = $exportFile['full'];
            $fileName = $exportFile['file'];

            $mg->messages()->send("$domain", [
                'from'    => 'GoTQuiz <postmaster@' . $domain . '>', 
                'to'      => $to,
                'subject' => $subject,
                'text'    => $text,
                'attachment' => [
                    ['filePath'=>"$fileFull", 'filename'=>"$fileName"]
                ]
            ]);

            echo "mail sent\n";

        } //else do nothing
    }
}
