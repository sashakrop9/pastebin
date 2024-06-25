<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Paste extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'hash',
        'title',
        'paste_content',
        'language',
        'expires_at',
        'access',
    ];

    protected $attributes = [
        'user_id' => null, // Значение по умолчанию для user_id
    ];

    /**
     * @return BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
