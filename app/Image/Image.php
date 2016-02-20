<?php

namespace ChingShop\Image;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

use ChingShop\Catalogue\Product\Product;

/**
 * ChingShop\Image\Image
 *
 * @property integer $id
 * @property string $filename
 * @property string $alt_text
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\ChingShop\Image\Image whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\ChingShop\Image\Image whereFilename($value)
 * @method static \Illuminate\Database\Query\Builder|\ChingShop\Image\Image whereAltText($value)
 * @method static \Illuminate\Database\Query\Builder|\ChingShop\Image\Image whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\ChingShop\Image\Image whereUpdatedAt($value)
 * @property-read \Illuminate\Database\Eloquent\Collection|Product[] $products
 * @property string $url
 * @property string $deleted_at
 */
class Image extends Model
{
    use SoftDeletes;

    const FILENAME_UNSAFE_PATTERN = '([^a-zA-Z0-9-\.]|\.{2,})';

    /** @var array */
    protected $guarded = ['id'];

    /** @var array */
    protected $fillable = ['filename', 'alt_text', 'url'];

    /**
     * @return BelongsToMany
     */
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class);
    }

    /**
     * @param string $filename
     */
    public function setFilenameAttribute(string $filename)
    {
        $this->attributes['filename'] = $this->safeFilename($filename);
    }

    /**
     * @return string
     */
    public function getFilenameAttribute(): string
    {
        return $this->safeFilename((string) $this->attributes['filename']);
    }

    /**
     * @return string
     */
    public function url(): string
    {
        return $this->isInternal() ?
            secure_asset('filesystem/image/' . $this->filename()) : $this->url;
    }

    /**
     * @return string
     */
    public function filename(): string
    {
        return (string) $this->filename;
    }

    /**
     * @return string
     */
    public function altText(): string
    {
        return (string) $this->alt_text;
    }

    /**
     * @return bool
     */
    public function isInternal(): bool
    {
        return (bool) strlen($this->filename);
    }

    /**
     * @param string $filename
     * @return string
     */
    private function safeFilename(string $filename)
    {
        $transliterated = iconv('UTF-8', 'ASCII//TRANSLIT', $filename);
        $sanitised = mb_ereg_replace(
            self::FILENAME_UNSAFE_PATTERN,
            '-',
            $transliterated
        );
        return $sanitised;
    }
}
