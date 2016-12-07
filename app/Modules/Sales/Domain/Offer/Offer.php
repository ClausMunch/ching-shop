<?php

namespace ChingShop\Modules\Sales\Domain\Offer;

use ChingShop\Domain\Colour;
use ChingShop\Http\View\Staff\HttpCrudInterface;
use ChingShop\Modules\Catalogue\Domain\Product\Product;
use ChingShop\Modules\Sales\Domain\Money;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use League\Uri\Schemes\Http;

/**
 * @mixin \Eloquent
 *
 * @property int                       $id
 * @property \Carbon\Carbon            $created_at
 * @property \Carbon\Carbon            $updated_at
 * @property \Carbon\Carbon            $deleted_at
 * @property OfferName                 $name
 * @property string                    $code
 * @property Money                     $price
 * @property int                       $percentage
 * @property int                       $quantity
 * @property string                    $effect
 * @property Colour                    $colour
 * @property-read Collection|Product[] $products
 */
class Offer extends Model implements HttpCrudInterface
{
    const ABSOLUTE = 'absolute';
    const RELATIVE = 'relative';
    const EFFECTS = [self::ABSOLUTE, self::RELATIVE];

    /** @var array */
    protected $casts = [
        'percentage'       => 'integer',
        'minimum_quantity' => 'integer',
    ];

    /** @var string[] */
    protected $fillable = [
        'name',
        'effect',
        'quantity',
        'price',
        'percentage',
        'colour',
    ];

    /**
     * @return BelongsToMany
     */
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class);
    }

    /**
     * @return string
     */
    public function amount(): string
    {
        if ($this->price->amount()) {
            return $this->price->formatted();
        }

        return "{$this->percentage}%";
    }

    /**
     * @return bool
     */
    public function isAbsolute(): bool
    {
        return $this->effect === self::ABSOLUTE;
    }

    /**
     * @return OfferName
     */
    public function getNameAttribute(): OfferName
    {
        if (array_key_exists('name', $this->attributes)) {
            return new OfferName(
                $this, (string) $this->attributes['name'] ?? ''
            );
        }

        return new OfferName($this, '');
    }

    /**
     * @param string $value
     */
    public function setNameAttribute($value)
    {
        if ((string) $this->name === (string) $value) {
            return;
        }

        $this->attributes['name'] = (string) $value;
    }

    /**
     * @return string
     */
    public function preSetName(): string
    {
        if (array_key_exists('name', $this->attributes)) {
            return (string) $this->attributes['name'];
        }

        return '';
    }

    /**
     * @throws \InvalidArgumentException
     *
     * @return Money
     */
    public function getPriceAttribute()
    {
        if (array_key_exists('price', $this->attributes)) {
            return Money::fromInt((int) $this->attributes['price'] ?? 0);
        }

        return Money::fromInt(0);
    }

    /**
     * @param int $value
     */
    public function setPriceAttribute($value)
    {
        $this->attributes['price'] = $value;
        $this->attributes['percentage'] = null;
    }

    /**
     * @param $value
     */
    public function setPercentageAttribute($value)
    {
        $this->attributes['price'] = null;
        $this->attributes['percentage'] = $value;
    }

    /**
     * @return Colour
     */
    public function getColourAttribute()
    {
        if (array_key_exists('colour', $this->attributes)) {
            return Colour::fromHex($this->attributes['colour']);
        }

        return Colour::brand()->pastel();
    }

    /**
     * @param string $colour
     */
    public function setColourAttribute(string $colour)
    {
        $this->attributes['colour'] = substr(
            str_replace_first('#', '', $colour),
            0,
            6
        );
    }

    /**
     * @return bool
     */
    public function isPriced(): bool
    {
        return isset($this->attributes['price'])
            && (bool) $this->attributes['price'];
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
        return 'offers';
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

    /**
     * @return Http
     */
    public function url(): Http
    {
        return Http::createFromString(
            route('offers.view', [$this->id, $this->slug()])
        );
    }

    /**
     * @return string
     */
    public function slug(): string
    {
        return str_slug($this->name);
    }
}
