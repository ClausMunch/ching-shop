<?php

namespace Testing\Unit\ChingShop\Catalogue\Tag;

use ChingShop\Modules\Catalogue\Domain\Tag\Tag;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Testing\Unit\UnitTest;

class TagTest extends UnitTest
{
    /** @var Tag */
    private $tag;

    /**
     * Set up tag for each test.
     */
    public function setUp()
    {
        parent::setUp();

        $this->tag = new Tag();
    }

    /**
     * Sanity check for instantiation.
     */
    public function testConstruct()
    {
        $this->assertInstanceOf(Tag::class, $this->tag);
    }

    /**
     * Should have a relation to products.
     */
    public function testProducts()
    {
        $this->assertInstanceOf(BelongsToMany::class, $this->tag->products());
    }

    /**
     * Should have a presenter class.
     */
    public function testPresenterClass()
    {
        $this->assertTrue(class_exists($this->tag->getPresenterClass()));
    }
}
