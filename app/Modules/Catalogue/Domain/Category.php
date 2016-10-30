<?php

namespace ChingShop\Modules\Catalogue\Domain;

use Baum\Node;
use ChingShop\Domain\PublicId;
use ChingShop\Http\View\Staff\HttpCrudInterface;
use ChingShop\Modules\Catalogue\Domain\Product\Product;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;
use League\Uri\Schemes\Http;

/**
 * @property int                        $id
 * @property string                     $name
 * @property \Carbon\Carbon             $created_at
 * @property \Carbon\Carbon             $updated_at
 * @property Collection|Product[]       $products
 * @property-read Category|null         $parent
 * @property-read Collection|Category[] $children
 *
 * @mixin \Illuminate\Database\Eloquent\Builder
 * @mixin \Illuminate\Database\Query\Builder
 */
class Category extends Node implements HttpCrudInterface
{
    use PublicId;

    /** @var string[] */
    protected $fillable = ['name'];

    /** @var string[] */
    protected $guarded = ['id', 'parent_id', 'lft', 'rgt', 'depth'];

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
     * @return string
     */
    public function slug(): string
    {
        return str_slug($this->name);
    }

    /**
     * @throws \RuntimeException
     *
     * @return Http
     */
    public function url(): Http
    {
        return Http::createFromString(
            route('category.view', [$this->publicId(), $this->slug()])
        );
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
