<?php

namespace ChingShop\Modules\Catalogue\Domain\Tag;

use ChingShop\Modules\Catalogue\Domain\Product\Product;
use Illuminate\Database\Eloquent\Collection;
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
 * @property \Carbon\Carbon            $created_at
 * @property \Carbon\Carbon            $updated_at
 *
 * @property int                       $id
 * @property string                    $name
 * @property-read Collection|Product[] $products
 */
class Tag extends Model implements HasPresenter
{
    use SoftDeletes;

    /** @var array */
    protected $fillable = ['name'];

    /**
     * @return string
     */
    public function url(): string
    {
        return route('tag::view', [$this->id, $this->name]);
    }

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
