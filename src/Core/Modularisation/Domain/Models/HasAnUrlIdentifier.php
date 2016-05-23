<?php

namespace MezzoLabs\Mezzo\Core\Modularisation\Domain\Models;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;

trait HasAnUrlIdentifier
{
    public function urlIdentifier()
    {
        if($this->hasAttribute('slug')){
            return $this->getAttribute('slug');
        }

        return $this->id;
    }

    public function scopeFindByUrlIdentifier(EloquentBuilder $query, $identifier)
    {
        if($this->hasAttribute('slug') && !is_numeric($identifier)){
            return $query->where('slug', '=', $identifier)->firstOrFail();
        }

        return $query->where('id', '=', $identifier)->firstOrFail();
    }
}