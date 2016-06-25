<?php

namespace ChingShop\Catalogue\Attribute;

use ChingShop\Catalogue\Product\ProductOption;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class Colour.
 *
 *
 * @mixin \Eloquent
 *
 * @property int $id
 * @property string $name
 * @property-read Collection|ProductOption[] $productOptions
 */
class Colour extends Model
{
    use SoftDeletes;

    /** @var string[] */
    protected $fillable = ['name'];
}
