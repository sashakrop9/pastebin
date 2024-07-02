<?php

namespace App\Http\Controllers\Web;

use App\Models\User;
use App\Services\UserService;
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
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

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
    public function create_git()
    {
        return Socialite::driver('github')->redirect();
    }

    /**
     * @return Application|\Illuminate\Foundation\Application|RedirectResponse|Redirector
     */
    public function callback_git()
    {
        $githubUser = Socialite::driver('github')->user();
        $user = $this->userService->handleGitHubCallback($githubUser);

        Auth::login($user);

        return redirect(route('user.profile'));
    }
}
