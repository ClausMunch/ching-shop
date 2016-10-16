<?php

namespace ChingShop\Http\Requests\Staff\Catalogue;

use ChingShop\Http\Requests\Staff\StaffRequest;
use Symfony\Component\HttpFoundation\FileBag;

/**
 * Class NewImagesRequest.
 */
class NewImagesRequest extends StaffRequest
{
    const PARAMETER = 'new-image';

    /**
     * @return bool
     */
    public function hasNewImages(): bool
    {
        return $this->hasFile(self::PARAMETER)
        || $this->hasFile(self::PARAMETER . '[0]');
    }

    /**
     * @return FileBag
     */
    public function newImages(): FileBag
    {
        $images = $this->file(self::PARAMETER);
        if (!$images) {
            $images = $this->file(self::PARAMETER . '[0]');
        }

        if ($images instanceof FileBag) {
            return $images;
        }

        return new FileBag((array) $images);
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'new-image.*' => 'max:10000|mimes:jpeg,jpg',
        ];
    }
}
