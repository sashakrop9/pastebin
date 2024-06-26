<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Complaint extends Model
{
    protected $fillable = [
        'user_id', 'paste_id', 'reason', 'status',
    ];

    // Определение связи с пользователем
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Определение связи с пастой
    public function paste(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Paste::class);
    }
}

