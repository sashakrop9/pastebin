<?php

namespace App\Services;

use App\DataTransferObjects\PasteData;
use App\Exceptions\AccessDeniedException;
use App\Exceptions\PasteExpiredException;
use App\Models\Paste;
use App\Repositories\PasteRepository;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;

class PasteService
{
    protected $pasteRepository;

    /**
     * @param PasteRepository $pasteRepository
     */
    public function __construct(PasteRepository $pasteRepository)
    {
        $this->pasteRepository = $pasteRepository;
    }


    /**
     * @param int $limit
     * @return Collection
     */
    public function getNumberLatestPublicPastes(int $limit): Collection
    {
        return $this->pasteRepository->getNumberLatestPublicPastes($limit);
    }

    /**
     * @param int $userId
     * @param int $limit
     * @return Collection
     */
    public function getUserPastes(int $userId, int $limit = 10): Collection
    {
        return $this->pasteRepository->getUserPastes($userId, $limit);
    }

    /**
     * @param string $hash
     * @return Collection
     * @throws PasteExpiredException
     * @throws AccessDeniedException
     */
    public function findByHash(string $hash)
    {
        $paste = $this->pasteRepository->findByHash($hash)->firstOrFail();
        $this->checkExpiration($paste);
        $this->checkAccess($paste);
        return $paste;
    }


    /**
     * @param $paste
     * @return void
     * @throws PasteExpiredException
     */
    public function checkExpiration($paste)
    {
        if ($paste->expires_at && Carbon::now()->gt($paste->expires_at)) {
            throw new PasteExpiredException();
        }
    }

    /**
     * @throws AccessDeniedException
     */
    public function checkAccess($paste)
    {
        if ($paste->access === 'private' && (!Auth::check() || Auth::id() !== $paste->user_id)) {
            throw new AccessDeniedException();
        }
    }

    /**
     * @param string|null $expiresAt
     * @return Carbon|null
     */
    public function determineExpirationDate(string $expiresAt = null)
    {
        return match ($expiresAt) {
            '10min' => Carbon::now()->addMinutes(10),
            '1hour' => Carbon::now()->addHour(),
            '3hours' => Carbon::now()->addHours(3),
            '1day' => Carbon::now()->addDay(),
            '1week' => Carbon::now()->addWeek(),
            '1month' => Carbon::now()->addMonth(),
            default => null,
        };
    }

    /**
     * @param PasteData $pasteData
     * @return Paste
     */
    public function createPaste(PasteData $pasteData)
    {
        return $this->pasteRepository->createPaste($pasteData);
    }
}
