<?php

namespace App\Http\Controllers\Web;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Carbon\Carbon;

class UserController extends Controller
{
    /**
     * Показать профиль пользователя.
     *
     * @return View
     */
    public function profile()
    {
        $user = Auth::user();
        $userPastes = $user->pastes()->latest()->take(10)->get();

        return view('paste.userPastes', compact('user', 'userPastes'));
    }
}
