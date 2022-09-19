<?php

namespace App\Http\Controllers\Admin\User;

use App\Http\Controllers\Controller;
use App\Models\User;

class IndexController extends Controller
{
    public function __invoke()
    {
        $users = User::all();
        $roles = User::getRoles();
        foreach ($users as &$user) {
            $user->role_text = $roles[$user->role];
        }
        return view('admin.users.index', compact('users'));
    }
}
