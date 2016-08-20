<?php

namespace ChingShop\Modules\Catalogue\Domain\Tag;

use ChingShop\Modules\Catalogue\Domain\Product\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use McCool\LaravelAutoPresenter\HasPresenter;

/**
 * Class Tag.
 *
 *
 * @mixin \Eloquent
 *
 * @property int $id
 * @property string $name
 * @property-read \Illuminate\Database\Eloquent\Collection|Product[] $products
 */
class Tag extends Model implements HasPresenter
{
    use SoftDeletes;

    /** @var array */
    protected $fillable = ['name'];

    /**
     * @return BelongsToMany
     */
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class);
    }

    /**
     * Get the presenter class.
     *
     * @return string
     */
    public function getPresenterClass(): string
    {
        return TagPresenter::class;
    }
}
