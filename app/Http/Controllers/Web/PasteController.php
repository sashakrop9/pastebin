<?php

namespace App\Http\Controllers\Web;

use App\DataTransferObjects\PasteData;
use App\Exceptions\AccessDeniedException;
use App\Exceptions\PasteExpiredException;
use App\Http\Requests\CreatePasteRequest;
use App\Models\Paste;
use App\Services\PasteService;
use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

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

    /**
     * @param $hash
     * @return Application|Factory|View|\Illuminate\Foundation\Application|Response|\Illuminate\View\View
     */
    public function show($hash)
    {
        $paste = $this->pasteService->findByHash($hash); // Получаем пасту через сервис
        $this->pasteService->checkExpiration($paste); // Проверяем срок действия через сервис
        $this->pasteService->checkAccess($paste); // Проверяем доступ через сервис

        $pastes = $this->pasteService->getNumberLatestPublicPastes(10); // Получаем последние публичные пасты через сервис

        $userPastes = Auth::check() ? $this->pasteService->getUserPastes(Auth::id()) : []; // Получаем пасты пользователя через сервис

        return view('paste.show', compact('paste', 'pastes', 'userPastes'));
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
     * @param CreatePasteRequest $request
     * @return Application|\Illuminate\Foundation\Application|RedirectResponse|Redirector
     */
    public function store(CreatePasteRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = Auth::id();
        $data['hash'] = bin2hex(random_bytes(5)); // генерируем случайный хеш

        $data['expires_at'] = $this->pasteService->determineExpirationDate($data['expires_at']);

        $pasteData = PasteData::fromArray($data);

        $paste = $this->pasteService->createPaste($pasteData);

        $paste->save();

        return redirect(route('paste.show',[$paste->hash]));
    }


}
