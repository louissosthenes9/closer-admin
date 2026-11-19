<?php

namespace App\Models;

use App\Enums\Intensity;
use App\Enums\PromptKind;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * Game prompts. Table is owned by closer-api migrations.
 */
class Prompt extends Model
{
    protected $guarded = ['id'];

    protected static function booted(): void
    {
        static::creating(function (Prompt $prompt) {
            $prompt->ulid ??= (string) Str::ulid();
            $prompt->key ??= 'adm'.Str::lower(Str::random(8));
            $prompt->tags ??= [];
        });
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'kind' => PromptKind::class,
            'intensity' => Intensity::class,
            'options' => 'array',
            'tags' => 'array',
            'modes' => 'array',
            'anonymous' => 'boolean',
            'published' => 'boolean',
            'min_players' => 'integer',
            'max_players' => 'integer',
        ];
    }
}
