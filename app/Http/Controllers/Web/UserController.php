<?php

namespace App\Http\Controllers\Web;

use App\Models\User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controller;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Carbon\Carbon;
use Laravel\Socialite\Facades\Socialite;

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

    /**
     * @return RedirectResponse|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    function create_git() {
        return Socialite::driver('github')->redirect();
    }

    /**
     * @return Application|\Illuminate\Foundation\Application|RedirectResponse|Redirector
     */
    function callback_git ()
    {
        $githubUser = Socialite::driver('github')->user();

        $user = User::where('email', $githubUser->email)->first();

        $name = $githubUser->name ?? ($user->name ?? $githubUser->email);

        $user = User::updateOrCreate(
            [
                'email' => $githubUser->email,
            ],
            [
                'name' => $name,
                'password' => $user->password ?? Str::random(8), // Это значение пароля вряд ли подходит, лучше использовать безопасное значение или зашифрованное
                'github_id' => $githubUser->id,
                'github_token' => $githubUser->token,
                'github_refresh_token' => $githubUser->refreshToken,
            ]
        );
        Auth::login($user);

        return redirect('/paste');
    }
}
