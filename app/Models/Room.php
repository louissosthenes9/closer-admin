<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Read-only view of closer-api rooms. Table is owned by closer-api migrations.
 */
class Room extends Model
{
    protected $guarded = ['id'];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'comfort_excluded_tags' => 'array',
            'comfort_allow_spicy' => 'boolean',
            'expires_at' => 'datetime',
        ];
    }

    public function participants(): HasMany
    {
        return $this->hasMany(Participant::class);
    }

    public function isOpen(): bool
    {
        return $this->expires_at->isFuture();
    }
}
