<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Role;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\SignupRequest;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{


  public function login(LoginRequest $request)
  {
    $credentials = $request->only(['email', 'password']);
    if (!$token = auth()->setTTL(525600)->attempt($credentials)) {
      return response()->json(['error' => 'Unauthorized'], 401);
    }
    $data =  [
      'user' => $this->getCurrentUser(),
      'token' => $token,
      'expire' => auth()->factory()->getTTL() * 60
    ];
    return response()->json($data);
  }






  public function signup(SignupRequest $request)
  {
    $password = $request->password;
    $request->merge(['password' => Hash::make($password), 'role_id' => 1]);
    $user = new User();
    $request->merge(['role_id' => Role::where('slug', Role::USER)->first()->id]);
    $user->fill($request->all());
    $user->save();
    $credentials = [
      'email' => $request->email,
      'password' =>  $password
    ];
    if (!$token = auth()->setTTL(525600)->attempt($credentials)) {
      return response()->json(['error' => 'Unauthorized'], 401);
    }
    $data =  [
      'user' => $this->getCurrentUser(),
      'token' => $token,
    ];
    return response()->json($data, 200);
  }






  public function getCurrentUser()
  {
    $user = User::where('id', auth()->user()->id)->with('role')->first();
    return $user;
  }
}
