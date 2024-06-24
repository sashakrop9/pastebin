<?php
namespace App\Repositories;

use App\Models\Paste;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

class PasteRepository
{
    public function getNumberLatestPublicPastes(int $limit): Collection
    {
        return Paste::where('access', 'public')
            ->where(function ($query) {
                $query->where('expires_at', '>', Carbon::now())
                    ->orWhereNull('expires_at');
            })
            ->latest()
            ->take($limit)
            ->get();
    }

    public function getUserPastes(int $userId, int $limit): Collection
    {
        return Paste::where('user_id', $userId)->latest()->take($limit)->get();
    }
}
