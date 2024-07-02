<?php

namespace App\Http\Controllers\Api;

use App\DataTransferObjects\PasteData;
use App\Exceptions\AccessDeniedException;
use App\Exceptions\PasteExpiredException;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreatePasteRequest;
use App\Http\Resources\PasteResource;
use App\Models\Paste;
use App\Repositories\PasteRepository;
use App\Services\PasteService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;
use Random\RandomException;

class PasteController extends Controller
{

    protected $pasteService;

    public function __construct(PasteService $pasteService)
    {
        $this->pasteService = $pasteService;
    }

    /**
     * @return AnonymousResourceCollection
     */
    public function index()
    {
        $pastes = $this->pasteService->getNumberLatestPublicPastes(10);
        return PasteResource::collection($pastes);
    }

    /**
     * @param $hash
     * @return PasteResource
     * @throws AccessDeniedException
     * @throws PasteExpiredException
     */
    public function show($hash)
    {
            $paste = $this->pasteService->findByHash($hash);

            return PasteResource::make($paste);
    }

    /**
     * @param CreatePasteRequest $request
     * @return PasteResource
     */
    public function store(CreatePasteRequest $request)
    {
        $data = $request->validated();

        $data['user_id'] = Auth::id();

        $data['expires_at'] = $this->pasteService->determineExpirationDate($data['expires_at']);

        $pasteData = PasteData::fromArray($data);

        $paste = $this->pasteService->createPaste($pasteData);


        return PasteResource::make($paste);
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

        $this->pasteService->checkAccess($paste);

        $data['expires_at'] = $this->pasteService->determineExpirationDate($data['expires_at']);

        $paste->update($data);

        return PasteResource::make($paste);
    }

    /**
     * @param $hash
     * @return JsonResponse
     * @throws AccessDeniedException
     * @throws PasteExpiredException
     */
    public function destroy($hash)
    {
        $paste = $this->pasteService->findByHash($hash);

        $paste->delete();

        return response()->json(null, 204);
    }
}
