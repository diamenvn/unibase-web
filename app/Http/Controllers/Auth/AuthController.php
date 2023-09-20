<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Socialite;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Model\Mysql\CustomerModel;

class AuthController extends Controller
{
    use AuthenticatesUsers;
    public function __construct()
    {
      $this->userTableName = 'customer';
    }

    public function Login(){
      return view('home.comment.index');
    }

    public function LoginToFacebook(){
      return Socialite::driver('facebook')->scopes([
            "manage_pages", "publish_pages"])->redirect();
    }

    public function CallbackLoginFacebook()
    {
        $auth_user = Socialite::driver('facebook')->user();
        // dd($auth_user);
        $user = CustomerModel::updateOrCreate(
            [
              'email' => $auth_user->email
            ],
            [
              'uid' => $auth_user->id,
              'name' => $auth_user->name,
              'token' => $auth_user->token,
              'name'  =>  $auth_user->name
            ]
        );
        Auth::guard($this->userTableName)->login($user);
        return redirect()->route('all-page'); // Redirect to a secure page
    }

}
