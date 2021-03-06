<?php

namespace ChingShop\Image;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Interface ImageOwner.
 */
interface ImageOwner
{
    /**
     * @return BelongsToMany
     */
    public function images(): BelongsToMany;

    /**
     * @return Collection|Image[]
     */
    public function imageCollection(): Collection;
}
