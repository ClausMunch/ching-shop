<?php

namespace ChingShop\Database;

use Illuminate\Database\Migrations\Migration as IlluminateMigration;
use Illuminate\Database\Schema\Builder;

/** @noinspection PhpIllegalPsrClassPathInspection */
class Migration extends IlluminateMigration
{
    /** @var Builder */
    private $builder;

    /**
     * @return Builder
     */
    protected function builder(): Builder
    {
        if ($this->builder === null) {
            $this->builder = app(Builder::class);
        }

        return $this->builder;
    }
}
