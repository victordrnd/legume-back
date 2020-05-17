<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Role;
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
use App\Http\Requests\Auth\UpdateUserRequest;
use Illuminate\Support\Facades\Input;

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
      'expire' => \Carbon\Carbon::now()->addMinutes(auth()->factory()->getTTL())->format('Y-m-d H:i:s')
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
    $customer = \Stripe\Customer::create([
      "email" => $user->email,
      "name" => $user->lastname . ' ' . $user->firstname,
      "phone" => $user->phone,
    ]);
    $user->stripe_id = $customer->id;
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

  public function updateUser(User $user, UpdateUserRequest $req)
  {
    try {
      $changes = $req->only('phone', 'lastname', 'firstname');
      $user->fill($changes);
      $user->save();
    } catch (Exception $e) {
      return response()->json(['error' => $e->getMessage()], 422);
    }
    return $user->load('role');
  }


  public function getCurrentUser()
  {
    return auth()->user()->load('role');    
  }


  public function viewResetPassword(Request $req){
    $token = $req->token;
    return view('passwords-form', compact('token'));
  }
}
