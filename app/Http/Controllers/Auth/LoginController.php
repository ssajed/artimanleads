<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/dashboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    /**
     * Get the needed authorization credentials from the request.
     * Supports both email and mobile login.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
       protected function credentials(Request $request)
    {
        $login = $request->get('email');
        
        // تشخیص میده کاربر ایمیل زده یا موبایل
        $field = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'mobile';

        // اگر ایمیل بود، حتماً به حروف کوچک تبدیل میشه تا خطا نده
        if ($field === 'email') {
            $login = strtolower($login);
        }

        return [
            $field => $login,
            'password' => $request->get('password'),
        ];
    }
}