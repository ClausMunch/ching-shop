<?php

namespace ChingShop\Http\View\Staff;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;

interface RelaterInterface
{
    /**
     * @param Model $related
     *
     * @return Relation
     */
    public function relationTo(Model $related): Relation;

    /**
     * @param Model $related
     *
     * @return string
     */
    public function relationKeyTo(Model $related): string;

    /**
     * @return string
     */
    public function routePath(): string;

    /**
     * @return int
     */
    public function id(): int;
}
