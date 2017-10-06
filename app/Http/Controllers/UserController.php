<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class UserController extends Controller
{
    public function destroy($id)
    {
        User::find($id)->delete();
        return redirect()->route('admin.dashboard');
    }

    public function update($id)
    {
        $user = User::find($id);
        $user->isAdmin ^= 1;
        $user->save();
        return redirect()->route('admin.dashboard');
    }
}
