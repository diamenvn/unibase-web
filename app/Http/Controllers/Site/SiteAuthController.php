<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Services\PageService;
use App\Http\Requests\LoginRequest;

class SiteAuthController extends Controller
{
    private $pageService;
    public function __construct(PageService $pageService)
    {
        $this->page = $pageService;
    }

    public function login()
    {
        if (Auth::check()){
            return redirect()->route('site.home.dashboard');
        }else{
            return view('site.auth.login');
        }
    }

    public function postLogin(LoginRequest $request)
    {
        $login = [
            'username' => $request->username,
            'password' => $request->password
        ];

        if(Auth::attempt($login)){
            if (Auth::user()->active == 0) {
                Auth::logout();
                $res['msg'] = 'Tài khoản của bạn đã bị vô hiệu hoá!';
                return view('site.auth.login')->with('error', $res['msg']);
            }

            return redirect()->route('site.home.dashboard');
        }else{
            $res['success'] = 0;
            $res['msg'] = 'Tài khoản hoặc mật khẩu không chính xác';
            return view('site.auth.login')->with('error', $res['msg']);
        }
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('home');
    }
}
