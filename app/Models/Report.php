<?php

namespace App\Models;

use App\Enums\Status;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

/**
 * @property int $id
 * @property int $user_id
 * @property string $type
 * @property string $title
 * @property string $category
 * @property string $description
 * @property string|null $color
 * @property string $location
 * @property CarbonImmutable $event_date
 * @property string|null $image_path
 * @property string $status
 * @property bool $is_hidden
 * @property CarbonImmutable|null $created_at
 * @property CarbonImmutable|null $updated_at
 * @property-read Collection<int, Comment> $comments
 * @property-read int|null $comments_count
 * @property-read string|null $image_url
 * @property-read User $user
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Report newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Report newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Report query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Report whereCategory($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Report whereColor($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Report whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Report whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Report whereEventDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Report whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Report whereImagePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Report whereIsHidden($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Report whereLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Report whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Report whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Report whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Report whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Report whereUserId($value)
 *
 * @mixin \Eloquent
 */
#[Fillable(['type', 'title', 'category', 'description', 'color', 'location', 'event_date'])]
class Report extends Model
{
    public const int MAX_LENGTH_TITLE = 150;

    public const int MAX_LENGTH_CATEGORY = 50;

    public const int MAX_LENGTH_COLOR = 50;

    public const int MAX_LENGTH_LOCATION = 150;

    /**
     * Default values used when creating a report.
     *
     * @var array<string, mixed>
     */
    protected $attributes = [
        'status' => Status::ACTIVE->value,
        'is_hidden' => false,
    ];

    /**
     * Attributes appended when the model is serialized.
     *
     * @var list<string>
     */
    protected $appends = ['image_url'];

    /**
     * Get the attribute casts for the model.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'event_date' => 'immutable_date',
            'is_hidden' => 'boolean',
        ];
    }

    /**
     * Get the user who owns the report.
     *
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the comments for the report.
     *
     * @return HasMany<Comment, $this>
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function getImageUrlAttribute(): ?string
    {
        if (! $this->image_path) {
            return null;
        }

        return Storage::disk('public')->url($this->image_path);
    }
}
