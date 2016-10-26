<?php

namespace ChingShop\Modules\Catalogue\Domain;

use ChingShop\Http\View\Staff\HttpCrudInterface;
use ChingShop\Modules\Catalogue\Domain\Product\Product;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int                  $id
 * @property string               $name
 * @property \Carbon\Carbon       $created_at
 * @property \Carbon\Carbon       $updated_at
 *
 * @property Collection|Product[] $products
 */
class Category extends Model implements HttpCrudInterface
{
    /** @var string[] */
    protected $fillable = ['name'];

    /**
     * A category contains many products.
     *
     * @return HasMany
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    /**
     * Whether this resource has already been persisted.
     *
     * @return bool
     */
    public function isStored(): bool
    {
        return (bool) $this->id;
    }

    /**
     * Routing name prefix for persisting this resource.
     *
     * @return string
     */
    public function routePath(): string
    {
        return 'categories';
    }

    /**
     * Identifier used when persisting this resource.
     *
     * @return string
     */
    public function crudId(): string
    {
        return $this->id;
    }
}
