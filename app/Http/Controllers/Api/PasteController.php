<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\AccessDeniedException;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreatePasteRequest;
use App\Http\Resources\PasteResource;
use App\Models\Paste;
use App\Repositories\PasteRepository;
use App\Services\PasteService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Random\RandomException;

class PasteController extends Controller
{
    protected $pasteRepository;
    protected $pasteService;

    public function __construct(PasteRepository $pasteRepository, PasteService $pasteService)
    {
        $this->pasteRepository = $pasteRepository;
        $this->pasteService = $pasteService;
    }

    /**
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        $pastes = $this->pasteService->getNumberLatestPublicPastes(10);
        return PasteResource::collection($pastes);
    }

    /**
     * @param $hash
     * @return JsonResponse
     */
    public function show($hash)
    {
        try {
            $paste = $this->pasteRepository->findByHash($hash);
            $this->pasteService->checkExpiration($paste);
            $this->pasteService->checkAccess($paste);

            return response()->json($paste);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 404);
        }
    }

    /**
     * @param CreatePasteRequest $request
     * @return PasteResource
     * @throws RandomException
     */
    public function store(CreatePasteRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = Auth::id();
        $data['hash'] = bin2hex(random_bytes(5)); // генерируем случайный хеш

        $data['expires_at'] = match ($request->input('expires_at')) {
            '10min' => Carbon::now()->addMinutes(10),
            '1hour' => Carbon::now()->addHour(),
            '3hours' => Carbon::now()->addHours(3),
            '1day' => Carbon::now()->addDay(),
            '1week' => Carbon::now()->addWeek(),
            '1month' => Carbon::now()->addMonth(),
            default => null,
        };

        $paste = Paste::create($data);

        return new PasteResource($paste);
    }

    /**
     * @param CreatePasteRequest $request
     * @param $hash
     * @return PasteResource
     * @throws AccessDeniedException
     */
    public function update(CreatePasteRequest $request, $hash)
    {
        $data = $request->validated();

        $paste = Paste::where('hash', $hash)->firstOrFail();
        // Дополнительная проверка доступа, если необходимо
        $this->pasteService->checkAccess($paste);

        $data['expires_at'] = match ($request->input('expires_at')) {
            '10min' => Carbon::now()->addMinutes(10),
            '1hour' => Carbon::now()->addHour(),
            '3hours' => Carbon::now()->addHours(3),
            '1day' => Carbon::now()->addDay(),
            '1week' => Carbon::now()->addWeek(),
            '1month' => Carbon::now()->addMonth(),
            default => null,
        };

        $paste->update($data);

        return new PasteResource($paste);
    }

    /**
     * @param $hash
     * @return JsonResponse
     * @throws AccessDeniedException
     */
    public function destroy($hash)
    {
        $paste = $this->pasteRepository->findByHash($hash);
        $this->pasteService->checkAccess($paste);

        $paste->delete();

        return response()->json(null, 204);
    }
}
