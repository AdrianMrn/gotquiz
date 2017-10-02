<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\User;
use Illuminate\Support\Facades\Hash;

class testjob extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'do:testjob';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Yummy adding a user :)';

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
        echo "Starting job\n";
        $user = new User();
        $user->name = "testcron";
        $user->email = "testtest@testtest.be";
        $user->password = Hash::make('secret');
        $user->save();
        echo "Job done\n";
    }
}
