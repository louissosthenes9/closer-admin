<?php

namespace App\Models;

use App\Enums\QuestionDepth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

/**
 * Table is owned by closer-api migrations.
 */
class Question extends Model
{
    protected $guarded = ['id'];

    protected static function booted(): void
    {
        static::creating(function (Question $question) {
            $question->ulid ??= (string) Str::ulid();
            $question->key ??= 'ADM-'.Str::upper(Str::random(8));
            $question->tags ??= [];
        });
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'depth' => QuestionDepth::class,
            'tags' => 'array',
            'min_age' => 'integer',
            'sensitive' => 'boolean',
            'active' => 'boolean',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(QuestionCategory::class, 'category_id');
    }

    public function subcategory(): BelongsTo
    {
        return $this->belongsTo(QuestionSubcategory::class, 'subcategory_id');
    }

    public function mode(): BelongsTo
    {
        return $this->belongsTo(QuestionMode::class, 'mode_id');
    }
}
