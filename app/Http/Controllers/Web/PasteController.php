<?php

namespace App\Http\Controllers\Web;

use App\Http\Requests\PasteRequest;
use App\Models\Paste;
use App\Services\PasteService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controller;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class PasteController extends Controller
{
//    protected $pasteService;

    /**
     * @param PasteService $pasteService
     */
    public function __construct(
        protected PasteService $pasteService
    ) {
//        $this->pasteService = $pasteService;
    }

    /**
     * @return Application|Factory|View|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function index()
    {
        $pastes = $this->pasteService->getNumberLatestPublicPastes(10);
        $userPastes = Auth::check() ? $this->pasteService->getUserPastes(Auth::id()) : [];


        return view('paste.index', compact('pastes', 'userPastes'));
    }
// пакет спайк спар для ларки DTO
    /**
     * @param $hash
     * @return Application|Factory|View|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function show($hash)
    {
        $paste = Paste::where('hash', $hash)->firstOrFail(); // в репозиторий

        if ($paste->expires_at && Carbon::now()->gt($paste->expires_at)) { // в сервис
            abort(404); // в exepcion
        }

        if ($paste->access === 'private' && (!Auth::check() || Auth::id() !== $paste->user_id)) {
            abort(403); //в exepcion
        }

        $pastes = $this->pasteService->getNumberLatestPublicPastes(10);
        $userPastes = Auth::check() ? $this->pasteService->getUserPastes(Auth::id()) : [];

        if ($userPastes->expires_at && Carbon::now()->gt($userPastes->expires_at)) { // в сервис
            abort(404); // в экзепшн
        }

        return view('paste.show', compact('pastes', 'userPastes', 'paste'));
    }

    /**
     * @return Application|Factory|View|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function create()
    {
        $pastes = $this->pasteService->getNumberLatestPublicPastes(10);
        $userPastes = Auth::check() ? $this->pasteService->getUserPastes(Auth::id()) : [];


        return view('paste.create', compact('pastes', 'userPastes'));
    }

    /**
     * @param PasteRequest $request
     * @return Application|\Illuminate\Foundation\Application|RedirectResponse|Redirector
     */
    public function store(PasteRequest $request)
    {
        //todo сделать проверку чтобы анонимные пользователи не могли делать приватную пасту
        $paste = new Paste;
        $paste->title = $request->title;
        $paste->paste_content = $request->paste_content;
        $paste->access = $request->access;
        $paste->language = $request->language;
        $paste->hash = Str::random(8);

        $paste->expires_at = match ($request['expires_at']) {
            '10min' => Carbon::now()->addMinutes(10),
            '1hour' => Carbon::now()->addHour(),
            '3hours' => Carbon::now()->addHours(3),
            '1day' => Carbon::now()->addDay(),
            '1week' => Carbon::now()->addWeek(),
            '1month' => Carbon::now()->addMonth(),
            'never' => null,
        };

        if (Auth::check()) {
            $paste->user_id = Auth::id();
        }

        $paste->save();

        return redirect('/paste/' . $paste->hash);
    }


}
