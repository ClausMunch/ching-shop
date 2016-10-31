<?php

namespace ChingShop\Modules\Catalogue\Domain\Product;

use ChingShop\Image\Image;
use ChingShop\Image\ImageOwner;
use ChingShop\Modules\Catalogue\Domain\Attribute\Colour;
use ChingShop\Modules\Catalogue\Domain\Inventory\StockItem;
use ChingShop\Modules\Catalogue\Domain\Price\Price;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use McCool\LaravelAutoPresenter\HasPresenter;

/**
 * @property int                         $id
 * @property string                      $label
 * @property string                      $supplier_number
 * @property int                         $product_id
 * @property-read Product                $product
 * @property-read Collection|Image[]     $images
 * @property-read Collection|Colour[]    $colours
 * @property-read Collection|StockItem[] $stockItems
 * @property-read Collection|StockItem[] $availableStock
 *
 * @method Builder inStock()
 *
 * @mixin \Eloquent
 */
class ProductOption extends Model implements HasPresenter, ImageOwner
{
    use SoftDeletes;

    /** @var string[] */
    protected $fillable = ['label', 'supplier_number'];

    /** @var string[] */
    protected $touches = ['product'];

    /**
     * @return BelongsTo
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * @return BelongsToMany
     */
    public function colours(): BelongsToMany
    {
        return $this->belongsToMany(Colour::class);
    }

    /**
     * @return HasMany|StockItem
     */
    public function stockItems(): HasMany
    {
        return $this->hasMany(StockItem::class);
    }

    /**
     * @return HasMany|StockItem
     */
    public function availableStock(): HasMany
    {
        return $this->stockItems()->available();
    }

    /**
     * @param Builder $query
     *
     * @return Builder
     */
    public function scopeInStock(Builder $query): Builder
    {
        return $query->has('availableStock');
    }

    /**
     * @return bool
     */
    public function isInStock(): bool
    {
        return (bool) count($this->availableStock);
    }

    /**
     * @param int $colourId
     *
     * @return bool
     */
    public function hasColour(int $colourId): bool
    {
        return $this->colours->contains('id', $colourId);
    }

    /**
     * @return BelongsToMany
     */
    public function images(): BelongsToMany
    {
        return $this->belongsToMany(Image::class)
            ->withPivot('position')
            ->orderBy('pivot_position', 'asc');
    }

    /**
     * @param int        $productId
     * @param int|string $optionId
     *
     * @return array
     */
    public static function validationRules(int $productId, $optionId = 'NULL')
    {
        return [
            'label' => [
                'required',
                'string',
                'min:3',
                'max:127',
                // label must be unique for this product
                sprintf(
                    'unique:product_options,label,%d,id,product_id,%d',
                    $optionId,
                    $productId
                ),
            ],
        ];
    }

    /**
     * Get the presenter class.
     *
     * @return string
     */
    public function getPresenterClass()
    {
        return ProductOptionPresenter::class;
    }

    /**
     * @return Collection|Image[]
     */
    public function imageCollection(): Collection
    {
        return $this->images;
    }

    /**
     * @return string
     */
    public function fullName(): string
    {
        return "{$this->product->name} ({$this->label})";
    }

    /**
     * @return float
     */
    public function priceAsFloat(): float
    {
        $price = $this->product->prices->first();
        if ($price instanceof Price) {
            return $price->asFloat();
        }

        return 0.0;
    }
}
