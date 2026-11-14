<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Report extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'title',
        'category',
        'description',
        'color',
        'location',
        'event_date',
        'image_path',
        'status',
        'is_hidden',
    ];

    protected function casts(): array
    {
        return [
            'event_date' => 'date',
            'is_hidden' => 'boolean',
        ];
    }

    // Pemilik laporan
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Komentar pada laporan
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }
}
