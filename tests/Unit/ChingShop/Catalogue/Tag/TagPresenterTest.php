<?php

namespace Testing\Unit\ChingShop\Catalogue\Tag;

use ChingShop\Modules\Catalogue\Model\Tag\Tag;
use ChingShop\Modules\Catalogue\Model\Tag\TagPresenter;
use Mockery\MockInterface;
use Testing\Unit\Behaviour\MocksModel;
use Testing\Unit\UnitTest;

class TagPresenterTest extends UnitTest
{
    use MocksModel;

    /** @var TagPresenter */
    private $tagPresenter;

    /** @var Tag|MockInterface */
    private $tag;

    /**
     * Set up tag presenter with mock tag.
     */
    public function setUp()
    {
        parent::setUp();

        $this->tag = $this->mockery(Tag::class);
        $this->setMockModel($this->tag);

        $this->tagPresenter = new TagPresenter($this->tag);
    }

    /**
     * Sanity check for instantiation.
     */
    public function testConstruct()
    {
        $this->assertInstanceOf(TagPresenter::class, $this->tagPresenter);
    }

    /**
     * Tag should be considered stored if it has an id.
     */
    public function testIsStored()
    {
        $this->mockModelAttribute('id', 5);

        $this->assertTrue($this->tagPresenter->isStored());
    }

    /**
     * Should be able to get a route path.
     */
    public function testRoutePath()
    {
        $this->assertInternalType('string', $this->tagPresenter->routePath());
    }

    /**
     * Should use the tag's id as the CRUD id.
     */
    public function testCrudId()
    {
        $tagId = 5;
        $this->mockModelAttribute('id', $tagId);

        $this->assertEquals($tagId, $this->tagPresenter->crudId());
    }
}
