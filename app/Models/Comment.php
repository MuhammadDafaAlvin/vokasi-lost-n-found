<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'report_id',
        'user_id',
        'body',
    ];

    // Laporan yang dikomentari
    public function report(): BelongsTo
    {
        return $this->belongsTo(Report::class);
    }

    // Pengguna yang memberikan komentar
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
