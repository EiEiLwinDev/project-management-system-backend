<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait CommonQueryScopes
{
    public function scopeFilterByStatus(Builder $query, ?string $status)
    {
        if ($status) {
            return $query->where('status', $status);
        }
        return $query;
    }

    public function scopeSearchByTitle(Builder $query, ?string $title)
    {
        if ($title) {
            return $query->where('title', 'like', "%{$title}%");
        }
        return $query;
    }
}