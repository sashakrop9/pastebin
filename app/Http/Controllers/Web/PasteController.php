<?php

namespace App\Http\Controllers\Web;

use App\Exceptions\AccessDeniedException;
use App\Exceptions\PasteExpiredException;
use App\Http\Requests\PasteRequest;
use App\Models\Paste;
use App\Services\PasteService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
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
     * @return Application|Factory|View|\Illuminate\Foundation\Application|Response|\Illuminate\View\View
     */
    public function show($hash)
    {
        try {
            $paste = $this->pasteService->findByHash($hash); // Получаем пасту через сервис
            $this->pasteService->checkExpiration($paste); // Проверяем срок действия через сервис
            $this->pasteService->checkAccess($paste); // Проверяем доступ через сервис

            $pastes = $this->pasteService->getNumberLatestPublicPastes(10); // Получаем последние публичные пасты через сервис
            $userPastes = auth()->check() ? $this->pasteService->getUserPastes(auth()->id(),10) : []; // Получаем пасты пользователя через сервис

            return view('paste.show', compact('paste', 'pastes', 'userPastes'));
        } catch (PasteExpiredException $e) {
            return response()->view('errors.paste_expired', [], 404); // Возвращаем кастомное представление ошибки
        } catch (AccessDeniedException $e) {
            return response()->view('errors.access_denied', [], 403); // Возвращаем кастомное представление ошибки
        } catch (\Exception $e) {
            return response()->view('errors.general', [], 500); // Общая обработка ошибок
        }
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
