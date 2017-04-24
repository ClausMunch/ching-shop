<?php

namespace ChingShop\Modules\Sales\Domain;

use ChingShop\Modules\Sales\Domain\Basket\Basket;
use ChingShop\Modules\Sales\Domain\Order\Order;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;

/**
 * @mixin \Eloquent
 *
 * @property int             $id
 * @property \Carbon\Carbon  $created_at
 * @property \Carbon\Carbon  $updated_at
 * @property \Carbon\Carbon  $deleted_at
 * @property string          $name
 * @property string          $line_one
 * @property string          $line_two
 * @property string          $city
 * @property string          $post_code
 * @property string          $country_code
 * @property-read Order|null $order
 */
class Address extends Model
{
    const LINES = [
        'name',
        'line_one',
        'line_two',
        'city',
        'post_code',
        'country_code',
    ];

    use SoftDeletes;

    /** @var string[] */
    protected $fillable = self::LINES;

    /**
     * @param string $raw
     *
     * @return Address
     */
    public static function fromString(string $raw): Address
    {
        return new self(
            collect(explode("\n", $raw))
                ->take(count(self::LINES))
                ->mapWithKeys(
                    function (string $line, int $index) {
                        return [self::LINES[$index] => $line];
                    }
                )
                ->all()
        );
    }

    /**
     * @return HasOne
     */
    public function basket(): HasOne
    {
        return $this->hasOne(Basket::class);
    }

    /**
     * @return HasOne
     */
    public function order(): HasOne
    {
        return $this->hasOne(Order::class);
    }

    /**
     * @param string $value
     *
     * @return string
     */
    public function getNameAttribute($value): string
    {
        return title_case($value);
    }

    /**
     * @param string $value
     *
     * @return string
     */
    public function getLineOneAttribute($value): string
    {
        return title_case($value);
    }

    /**
     * @param string $value
     *
     * @return string
     */
    public function getLineTwoAttribute($value): string
    {
        return title_case($value);
    }

    /**
     * @param string $value
     *
     * @return string
     */
    public function getCityAttribute($value): string
    {
        return title_case($value);
    }

    /**
     * @param string $value
     *
     * @return string
     */
    public function getPostCodeAttribute($value): string
    {
        return mb_strtoupper($value);
    }

    /**
     * @param string $value
     *
     * @return string
     */
    public function getCountryCodeAttribute($value): string
    {
        return mb_strtoupper($value);
    }

    /**
     * @return int
     */
    public function orderId(): int
    {
        if (isset($this->order->id)) {
            return $this->order->publicId();
        }

        return 0;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (new Collection(
            [
                $this->name,
                $this->line_one,
                $this->line_two,
                $this->city,
                $this->post_code,
                $this->country_code,
            ]
        ))->filter()->implode(', ');
    }
}
