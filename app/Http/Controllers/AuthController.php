<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\ResetpasswordRequest;
use App\Http\Requests\Auth\sendMailRequest;
use App\Http\Requests\Auth\SignupRequest;
use App\Http\Services\MailService;
use App\Resetpassword;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AuthController extends Controller
{


  public function login(LoginRequest $request)
  {
    $credentials = $request->only(['email', 'password']);
    if (!$token = auth()->setTTL(525600)->attempt($credentials)) {
      return response()->json(['error' => 'Unauthorized'], 401);
    }
    $user = User::where('id', auth()->user()->id)->first();
    $data =  [
      'user' => $user,
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
      'user' => auth()->user(),
      'token' => $token,
    ];
    return response()->json($data, 200);
  }


  public function sendMail(sendMailRequest $request)
  {
    try{
      $user = User::where('email', $request->email)->firstOrFail();
    }catch(ModelNotFoundException $e){
      return response()->json(['error' => $e->getMessage()]);
    }
    $token = Str::orderedUuid();
    Resetpassword::create([
      'token' =>  $token,
      'user_id' => $user->id
    ]);
    $link = env('APP_URL').'/auth/resetpassword?token=' . $token;
    (new MailService)->sendResetPasswordMail($user, $link);
    return true;
  }


  public function resetPassword(ResetpasswordRequest $request)
  {
    $user = Resetpassword::where('token', $request->token)->first()->user;
    $user->password = Hash::make($request->password);
    $user->save();
    return $user;
  }

  public function getCurrentUser()
  {
    $user = User::where('id', auth()->user()->id)->first();
    return $user;
  }


  public function viewResetPassword(Request $req){
    $token = $req->token;
    return view('passwords-form', compact('token'));
  }
}
