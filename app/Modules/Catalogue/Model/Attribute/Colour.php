<?php

namespace ChingShop\Modules\Catalogue\Model\Attribute;

use ChingShop\Modules\Catalogue\Model\Product\ProductOption;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

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
