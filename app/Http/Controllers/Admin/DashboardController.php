<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\User;
use App\Contest;

class DashboardController extends Controller
{
    public function index()
    {
        //restore user if soft deleted
        //User::withTrashed()->find(1)->restore();

        $users = User::simplePaginate(10);
        $contests = Contest::simplePaginate(10);

        return view('admin.dashboard', ['users' => $users, 'contests' => $contests]);
    }
}
