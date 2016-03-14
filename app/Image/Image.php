<?php

namespace ChingShop\Image;

use ChingShop\Catalogue\Product\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * ChingShop\Image\Image.
 *
 * @property int $id
 * @property string $filename
 * @property string $alt_text
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 *
 * @method static \Illuminate\Database\Query\Builder|\ChingShop\Image\Image whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\ChingShop\Image\Image whereFilename($value)
 * @method static \Illuminate\Database\Query\Builder|\ChingShop\Image\Image whereAltText($value)
 * @method static \Illuminate\Database\Query\Builder|\ChingShop\Image\Image whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\ChingShop\Image\Image whereUpdatedAt($value)
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|Product[] $products
 * @property string $url
 * @property string $deleted_at
 *
 * @method static \Illuminate\Database\Query\Builder|\ChingShop\Image\Image whereUrl($value)
 * @method static \Illuminate\Database\Query\Builder|\ChingShop\Image\Image whereDeletedAt($value)
 * @mixin \Eloquent
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
        return isset($this->attributes['filename']) ?
            $this->safeFilename((string) $this->attributes['filename']) : '';
    }

    /**
     * @return string
     */
    public function url(): string
    {
        if ($this->isInternal()) {
            return secure_asset('filesystem/image/'.$this->filename());
        }

        return isset($this->url) ? (string) $this->url : '';
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
     *
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
