<?php

namespace ChingShop\Catalogue\Attribute;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

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
