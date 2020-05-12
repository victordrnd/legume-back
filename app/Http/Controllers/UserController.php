<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Role;

class UserController extends Controller
{
    public function getAll(){
        return User::with('role')->get();
    }

    public function filter(Request $req)
    {
        $users = (new User)->newQuery();

        if ($req->has('keyword') && $req->filled('keyword')) {
            $keywords = explode(" ", $req->keyword);
            foreach ($keywords as $keyword) {
                $users->orWhere('firstname', 'like', "%$keyword%")->orWhere('lastname', 'like', "%$keyword%");
            }
        }
        $users = $users->with('role')->get();
        return $users;
    }


    public function updateRole(Request $req){

    }


    public function getAllRoles(){
        return Role::all();
    }
}
