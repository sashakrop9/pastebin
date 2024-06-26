<?php
namespace App\Repositories;

use App\Models\Paste;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Prettus\Repository\Eloquent\BaseRepository;

class PasteRepository extends BaseRepository
{
    /**
     * @return string
     */
    public function model(): string
    {
        return Paste::class;
    }


    /**
     * @param int $limit
     * @return Collection
     */
    public function getNumberLatestPublicPastes(int $limit): Collection
    {
        return $this->model -> Newquery()
            ->where('access', 'public')
                ->where(function ($query) {
                    $query->where('expires_at', '>', Carbon::now())
                        ->orWhereNull('expires_at');
                })
                ->latest()
                ->take($limit)
                ->get();
    }

    /**
     * @param int $userId
     * @param int $limit
     * @return Collection
     *
     */
    public function getUserPastes(int $userId, int $limit): Collection
    {
        return $this->model->newQuery()
            ->where('user_id', $userId)
            ->where('expires_at', '>', Carbon::now())
                    ->orWhereNull('expires_at')
                ->latest()->take($limit)
                ->get();
    }

    /**
     * @param string $hash
     * @return mixed
     */
    public function findByHash(string $hash): mixed
    {
        return $this->findWhere(['hash' => $hash])->first();
    }
}
