<?php

namespace App\Services;

use App\Models\Paste;
use App\Repositories\PasteRepository;

class PasteService
{
    protected $pasteRepository;

    public function __construct(PasteRepository $pasteRepository)
    {
        $this->pasteRepository = $pasteRepository;
    }

    public function getNumberLatestPublicPastes(int $limit)
    {
        return $this->pasteRepository->getNumberLatestPublicPastes($limit);
    }

    public function getUserPastes(int $userId, int $limit = 10)
    {
        return $this->pasteRepository->getUserPastes($userId, $limit);
    }
}
