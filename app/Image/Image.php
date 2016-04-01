<?php

namespace ChingShop\Image;

use ChingShop\Catalogue\Product\Product;
use ChingShop\Http\View\Staff\HttpCrudInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder;

/**
 * ChingShop\Image\Image.
 *
 * @property int $id
 * @property string $filename
 * @property string $alt_text
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 *
 * @method static Builder|Image whereId($value)
 * @method static Builder|Image whereFilename($value)
 * @method static Builder|Image whereAltText($value)
 * @method static Builder|Image whereCreatedAt($value)
 * @method static Builder|Image whereUpdatedAt($value)
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|Product[] $products
 * @property string $url
 * @property string $deleted_at
 *
 * @method static Builder|Image whereUrl($value)
 * @method static Builder|Image whereDeletedAt($value)
 * @mixin \Eloquent
 */
class Image extends Model implements HttpCrudInterface
{
    const SIZES = [
        'tiny'      => 64,
        'thumbnail' => 128,
        'small'     => 512,
        'medium'    => 768,
        'large'     => 1024,
    ];

    use SoftDeletes;

    const DIR = 'filesystem/image/';
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
     * @param string $fileName
     */
    public function setFilenameAttribute(string $fileName)
    {
        $fileName = $this->safeFilename($fileName);
        $this->attributes['filename'] = $fileName ?: null;
    }

    /**
     * @return string
     */
    public function getFilenameAttribute(): string
    {
        if (empty($this->attributes['filename'])) {
            return '';
        }

        return $this->safeFilename((string) $this->attributes['filename']);
    }

    /**
     * @param string $size
     *
     * @return string
     */
    public function url(string $size = 'large'): string
    {
        if ($this->isInternal()) {
            return secure_asset(self::DIR.$this->filename());
        }

        $url = isset($this->url) ? (string) $this->url : '';
        $path = pathinfo($url);
        if (empty($path) || empty($path['extension'])) {
            return $url;
        }

        return sprintf(
            '%s/%s-%s.%s',
            $path['dirname'],
            $path['filename'],
            $size,
            $path['extension'] ?? ''
        );
    }

    /**
     * @return string
     */
    public function srcSet(): string
    {
        return implode(
            ',',
            array_map(
                function ($size, $width) {
                    return "{$this->url($size)} {$width}w";
                },
                array_keys(self::SIZES),
                array_values(self::SIZES)
            )
        );
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
    public function locationGlyph(): string
    {
        return $this->isInternal() ? 'hdd' : 'cloud';
    }

    /**
     * @return string
     */
    public function storageLocation(): string
    {
        return storage_path("image/{$this->filename()}");
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
        return 'staff.products.images';
    }

    /**
     * Identifier used when persisting this resource.
     *
     * @return string
     */
    public function crudId(): string
    {
        return (string) $this->id;
    }
}
