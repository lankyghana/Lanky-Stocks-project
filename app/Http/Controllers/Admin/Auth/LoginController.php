<?php
namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    public $redirectTo = 'admin';

    /**
     * Show the application's login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLoginForm()
    {
        $pageTitle = "Admin Login";
        return view('admin.auth.login', compact('pageTitle'));
    }

    /**
     * Get the guard to be used during authentication.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return auth()->guard('admin');
    }

    public function username()
    {
        return 'username';
    }

    public function login(Request $request)
    {
        // Start debug log
        file_put_contents(storage_path('logs/admin_debug.log'), "[".date('Y-m-d H:i:s')."] Login attempt started\n", FILE_APPEND);

        try {
            $this->validateLogin($request);
            file_put_contents(storage_path('logs/admin_debug.log'), "[".date('Y-m-d H:i:s')."] Validation passed\n", FILE_APPEND);
        } catch (\Exception $e) {
            file_put_contents(storage_path('logs/admin_debug.log'), "[".date('Y-m-d H:i:s')."] Validation failed: ".$e->getMessage()."\n", FILE_APPEND);
            throw $e;
        }

        $request->session()->regenerateToken();
        file_put_contents(storage_path('logs/admin_debug.log'), "[".date('Y-m-d H:i:s')."] Token regenerated\n", FILE_APPEND);

        if(!verifyCaptcha()){
            file_put_contents(storage_path('logs/admin_debug.log'), "[".date('Y-m-d H:i:s')."] Invalid captcha\n", FILE_APPEND);
            $notify[] = ['error','Invalid captcha provided'];
            return back()->withNotify($notify);
        }

        file_put_contents(storage_path('logs/admin_debug.log'), "[".date('Y-m-d H:i:s')."] Captcha passed\n", FILE_APPEND);

        if (method_exists($this, 'hasTooManyLoginAttempts') &&
            $this->hasTooManyLoginAttempts($request)) {
            file_put_contents(storage_path('logs/admin_debug.log'), "[".date('Y-m-d H:i:s')."] Too many login attempts\n", FILE_APPEND);
            $this->fireLockoutEvent($request);
            return $this->sendLockoutResponse($request);
        }

        if ($this->attemptLogin($request)) {
            file_put_contents(storage_path('logs/admin_debug.log'), "[".date('Y-m-d H:i:s')."] Login successful\n", FILE_APPEND);
            return $this->sendLoginResponse($request);
        }

        $this->incrementLoginAttempts($request);
        file_put_contents(storage_path('logs/admin_debug.log'), "[".date('Y-m-d H:i:s')."] Login failed\n", FILE_APPEND);
        return $this->sendFailedLoginResponse($request);
    }


    public function logout(Request $request)
    {
        $this->guard()->logout(); // fixed: removed argument
        $request->session()->invalidate();
        return $this->loggedOut($request) ?: redirect($this->redirectTo);
    }
}
