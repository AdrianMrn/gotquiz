<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ApiController extends Controller
{
    public function test()
    {
        $apiUrl = 'https://anapioficeandfire.com/api/';
        $apiOption = 'characters/583';

        $url = $apiUrl . $apiOption;
        
        $ch = curl_init($url);
    
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec ($ch);
        $info = curl_getinfo($ch);
        $http_result = $info ['http_code'];
        curl_close ($ch);

        $data = json_decode($output);

        return dd($data->aliases);
    }
}
