<?php

namespace App\Services;

use App\Models\Paste;
use App\Repositories\PasteRepository;
use Illuminate\Database\Eloquent\Collection;

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
    public function getNumberLatestPublicPastes(int $limit)
    {
        return $this->pasteRepository->getNumberLatestPublicPastes($limit);
    }

    /**
     * @param int $userId
     * @param int $limit
     * @return Collection
     */
    public function getUserPastes(int $userId, int $limit = 10)
    {
        return $this->pasteRepository->getUserPastes($userId, $limit);
    }
}
