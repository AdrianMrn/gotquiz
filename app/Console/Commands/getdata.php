<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Schema;
use App\Character, App\House, App\Title, App\Alias, App\Seat, App\LinkGotCharactersGotHouses;

class getdata extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'do:getdata';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Yummy I love data om nom nom';

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

        echo "Starting command getData\n";
        
        function getCharacters($apiUrl) {
            echo "Starting getCharacters\n";
            $apiOption = 'characters?pageSize=50&page=';
            $url = $apiUrl . $apiOption;
    
            $page = 1;
            $gotAllElements = false;
            while (!$gotAllElements) {
                $url = $apiUrl . $apiOption . $page;
                
                $ch = curl_init($url);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
                curl_setopt($ch, CURLOPT_URL,$url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                $output = curl_exec ($ch);
                $info = curl_getinfo($ch);
                $http_result = $info ['http_code'];
                curl_close ($ch);
    
                $data = json_decode($output);
                
                //if this page of data has less than 50 elements, this is the last page.
                if (sizeof($data) < 50) {
                    $gotAllElements = true;
                }
    
                foreach ($data as $character) {
                    $id = preg_replace('/[^0-9]+/', '', $character->url);
    
                    $newChar = Character::firstOrNew(['id' => $id]);
    
                    $newChar->id = $id;
                    $newChar->name = $character->name;
                    $newChar->culture = $character->culture;
                    
                    $newChar->save();
                    echo $id . "\n";
                }
                $page++;
            }
            echo "Got all characters!\n";
        }

        function fkCharacters($apiUrl) {
            echo "Starting fkCharacters\n";
            $apiOption = 'characters?pageSize=50&page=';
            $url = $apiUrl . $apiOption;
    
            $page = 1;
            $gotAllElements = false;
            while (!$gotAllElements) {
                $url = $apiUrl . $apiOption . $page;
                
                $ch = curl_init($url);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
                curl_setopt($ch, CURLOPT_URL,$url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                $output = curl_exec ($ch);
                $info = curl_getinfo($ch);
                $http_result = $info ['http_code'];
                curl_close ($ch);
    
                $data = json_decode($output);
                
                //if this page of data has less than 50 elements, this is the last page.
                if (sizeof($data) < 50) {
                    $gotAllElements = true;
                }
    
                //echo dd($data);
    
                foreach ($data as $character) {
                    $id = preg_replace('/[^0-9]+/', '', $character->url);
    
                    $char = Character::find($id);
    
                    $father = preg_replace('/[^0-9]+/', '', $character->father);
                    if ($father) { $char->father = $father; }
                    $mother = preg_replace('/[^0-9]+/', '', $character->mother);
                    if ($mother) { $char->mother = $mother; }
                    $spouse = preg_replace('/[^0-9]+/', '', $character->spouse);
                    if ($spouse) { $char->spouse = $spouse; }
                    
                    $char->save();
                    echo $id . "\n";
                }
                $page++;
            }
            echo "Got all spouses, fathers, mothers!\n";
        }

        function getTitlesAndAliases($apiUrl) {
            echo "Starting getTitlesAndAliases\n";
            $apiOption = 'characters?pageSize=50&page=';
            $url = $apiUrl . $apiOption;

            Title::truncate();
            Alias::truncate();
    
            $page = 1;
            $gotAllElements = false;
            while (!$gotAllElements) {
                $url = $apiUrl . $apiOption . $page;
                
                $ch = curl_init($url);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
                curl_setopt($ch, CURLOPT_URL,$url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                $output = curl_exec ($ch);
                $info = curl_getinfo($ch);
                $http_result = $info ['http_code'];
                curl_close ($ch);
    
                $data = json_decode($output);
                
                //if this page of data has less than 50 elements, this is the last page.
                if (sizeof($data) < 50) {
                    $gotAllElements = true;
                }
    
                foreach ($data as $character) {
                    $id = preg_replace('/[^0-9]+/', '', $character->url);

                    foreach ($character->titles as $title) {
                        if ($title) {
                            $newTitle = new Title;
                            $newTitle->title = $title;
                            $newTitle->character_id = $id;
                            $newTitle->save();
                        }
                    }

                    foreach ($character->aliases as $alias) {
                        if ($alias) {
                            $newAlias = new Alias;
                            $newAlias->alias = $alias;
                            $newAlias->character_id = $id;
                            $newAlias->save();
                        }
                    }
                    
                    echo $id . "\n";
                }
                $page++;
            }
            echo "Got all characters' titles and aliases!\n";
        }

        function getHouses($apiUrl) {
            echo "Starting getHouses\n";
            $apiOption = 'houses?pageSize=50&page=';
            $url = $apiUrl . $apiOption;

            Seat::truncate();
    
            $page = 1;
            $gotAllElements = false;
            while (!$gotAllElements) {
                $url = $apiUrl . $apiOption . $page;
                
                $ch = curl_init($url);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
                curl_setopt($ch, CURLOPT_URL,$url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                $output = curl_exec ($ch);
                $info = curl_getinfo($ch);
                $http_result = $info ['http_code'];
                curl_close ($ch);
    
                $data = json_decode($output);
                
                //if this page of data has less than 50 elements, this is the last page.
                if (sizeof($data) < 50) {
                    $gotAllElements = true;
                }
    
                foreach ($data as $house) {
                    $id = preg_replace('/[^0-9]+/', '', $house->url);
    
                    $newHouse = House::firstOrNew(['id' => $id]);
    
                    $newHouse->id = $id;
                    $newHouse->name = $house->name;
                    $newHouse->region = $house->region;
                    $newHouse->coatOfArms = $house->coatOfArms;
                    $newHouse->words = $house->words;

                    $heir = preg_replace('/[^0-9]+/', '', $house->heir);
                    if ($heir) { $newHouse->heir = $heir; }
                    $founder = preg_replace('/[^0-9]+/', '', $house->founder);
                    if ($founder) { $newHouse->founder = $founder; }
                    $currentLord = preg_replace('/[^0-9]+/', '', $house->currentLord);
                    if ($currentLord) { $newHouse->currentLord = $currentLord; }

                    $newHouse->save();

                    //getting seats
                    foreach ($house->seats as $seat) {
                        if ($seat) {
                            $newSeat = new Seat;
                            $newSeat->seat = $seat;
                            $newSeat->house_id = $id;
                            $newSeat->save();
                        }
                    }

                    echo $id . "\n";
                }
                $page++;
            }
            echo "Got all houses!\n";
        }

        function getAllegiances($apiUrl) {
            echo "Starting getCharacters\n";
            $apiOption = 'characters?pageSize=50&page=';
            $url = $apiUrl . $apiOption;

            LinkGotCharactersGotHouses::truncate();
    
            $page = 1;
            $gotAllElements = false;
            while (!$gotAllElements) {
                $url = $apiUrl . $apiOption . $page;
                
                $ch = curl_init($url);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
                curl_setopt($ch, CURLOPT_URL,$url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                $output = curl_exec ($ch);
                $info = curl_getinfo($ch);
                $http_result = $info ['http_code'];
                curl_close ($ch);
    
                $data = json_decode($output);
                
                //if this page of data has less than 50 elements, this is the last page.
                if (sizeof($data) < 50) {
                    $gotAllElements = true;
                }
    
                foreach ($data as $character) {
                    $id = preg_replace('/[^0-9]+/', '', $character->url);

                    foreach ($character->allegiances as $allegiance) {
                        if ($allegiance) {
                            $newAllegiances = new LinkGotCharactersGotHouses;
                            $newAllegiances->character_id = $id;
                            $newAllegiances->house_id = preg_replace('/[^0-9]+/', '', $allegiance);
                            $newAllegiances->save();
                        }
                    }

                    echo $id . "\n";
                }
                $page++;
            }
            echo "Got all characters' allegiances!\n";
        }

        $apiUrl = 'https://anapioficeandfire.com/api/';
        /* getCharacters($apiUrl);
        fkCharacters($apiUrl); */

        /* getTitlesAndAliases($apiUrl); */

        /* getHouses($apiUrl); */

        getAllegiances($apiUrl);

    }
}