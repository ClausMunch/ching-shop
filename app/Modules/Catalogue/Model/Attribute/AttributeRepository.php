<?php

namespace ChingShop\Modules\Catalogue\Model\Attribute;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class AttributeRepository.
 */
class AttributeRepository
{
    /** @var Colour|Builder */
    private $colourResource;

    /**
     * AttributeRepository constructor.
     *
     * @param Colour $colourResource
     */
    public function __construct(Colour $colourResource)
    {
        $this->colourResource = $colourResource;
    }

    /**
     * @return Collection|Colour[]
     */
    public function loadAllColours()
    {
        return $this->colourResource->all();
    }
}
