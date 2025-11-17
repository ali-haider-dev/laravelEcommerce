<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('user.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {

        $request->authenticate();

        $request->session()->regenerate();

 

        $user = Auth::user();

        if (trim(strtolower($user->designation)) == 'admin') {
            return redirect()->route('admin.dashboard');
        } else {
            return redirect()->route('user');
        }


    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $user_id = Auth::id();
        $user_desg = DB::table('users')->where('id', $user_id)->first();

        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        if ($user_desg->designation == 'admin') {
            
          return  redirect('/admin/login');

        } else {
           
            return redirect('/');
        }
    }
}
