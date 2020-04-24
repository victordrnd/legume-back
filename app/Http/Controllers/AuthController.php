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
use App\Mail\Auth\ResetpasswordMail;
use App\Resetpassword;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;

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
    $token = Str::orderedUuid();
    $user_id = User::where('email', $request->email)->first()->id;
    Resetpassword::create([
      'token' =>  $token,
      'user_id' => $user_id
    ]);
    $details = [
      'email' => $request->email,
      'link' => 'localhost:8000/auth/resetpassword/token/' . $token
    ];
    Mail::to($request->email)->send(new ResetpasswordMail($details));
    return response()->json('un email vous a été envoyé merci de vérifier votre boite mail', 200);
  }

  public function checkToken($token)
  {
    if ((Resetpassword::where('token', $token)->first() != null)) {
      return response()->json('le token est valie', 200);
    }
    return response()->json('le token resnseigné est erroné', 401);
  }

  public function resetPassword(ResetpasswordRequest $request)
  {
    $user = User::where('id', Resetpassword::where('token', $request->token)->first()->user_id)->get();
    $user->password = Hash::make($request->password);
    $user->save();
    return response()->json('Votre nouveau de mot de passe a bien été enregistré', 200);
  }

  public function getCurrentUser()
  {
    $user = User::where('id', auth()->user()->id)->first();
    return $user;
  }
}
