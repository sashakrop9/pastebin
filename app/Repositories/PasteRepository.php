<?php
namespace App\Repositories;

use App\DataTransferObjects\PasteData;
use App\Models\Paste;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Validator\Exceptions\ValidatorException;

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
            ->where(function ($query) {
                $query->where('expires_at', '>', Carbon::now())
                    ->orWhereNull('expires_at');
                })
                ->latest()
                ->take($limit)
                ->get();
    }

    /**
     * @param string $hash
     * @return mixed
     */
    public function findByHash(string $hash): mixed
    {
        return $this->findWhere(['hash' => $hash]);
    }

    /**
     * @param PasteData $data
     * @return Paste
     * @throws ValidatorException
     */
    public function createPaste(PasteData $pasteData): Paste
    {
        return $this->create([
            'title' => $pasteData->title,
            'paste_content' => $pasteData->paste_content,
            'access' => $pasteData->access,
            'expires_at' => $pasteData->expires_at,
            'language' => $pasteData->language,
            'user_id' => $pasteData->user_id,
            'hash' => bin2hex(random_bytes(5)),
        ]);
    }
}
