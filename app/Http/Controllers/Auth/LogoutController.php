<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LogoutController extends Controller
{
    public function __invoke(Request $request)
    {
        //logout user
        \Illuminate\Support\Facades\Auth::logout();

        //invalidate session
        $request->session()->invalidate();

        //session regenerate
        $request->session()->regenerateToken();

        //redirect login page
        return redirect('/login');
    }
}
