<?php

namespace Testing\Unit\ChingShop\Catalogue\Tag;

use ChingShop\Catalogue\Product\Product;
use ChingShop\Catalogue\Tag\Tag;
use ChingShop\Catalogue\Tag\TagRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Mockery\MockInterface;
use Testing\Unit\Behaviour\MocksModel;
use Testing\Unit\MockObject;
use Testing\Unit\UnitTest;

class TagRepositoryTest extends UnitTest
{
    use MocksModel;

    /** @var TagRepository */
    private $tagRepository;

    /** @var Tag|MockInterface */
    private $tagModel;

    /**
     * Set up tag repository with mock tag model.
     */
    public function setUp()
    {
        parent::setUp();

        $this->tagModel = $this->mockery(Tag::class);
        $this->setMockModel($this->tagModel);

        $this->tagRepository = new TagRepository($this->tagModel);
    }

    /**
     * Sanity check for instantiation.
     */
    public function testConstruct()
    {
        $this->assertInstanceOf(TagRepository::class, $this->tagRepository);
    }

    /**
     * Should be able to load all tags.
     */
    public function testLoadAll()
    {
        $tags = new Collection();
        $this->tagModel->shouldReceive('orderBy->with->get')->andReturn($tags);

        $this->assertSame($tags, $this->tagRepository->loadAll());
    }

    /**
     * Should be able to load a tag by id.
     */
    public function testLoadById()
    {
        $this->tagModel
            ->shouldReceive('where->with->firstOrFail')
            ->andReturnSelf();

        $this->tagRepository->loadById(5);
    }

    /**
     * Should be able to create a tag.
     */
    public function testCreate()
    {
        $tagAttributes = ['name' => str_random()];
        $this->tagModel
            ->shouldReceive('create')
            ->with($tagAttributes)
            ->andReturnSelf();

        $this->tagRepository->create($tagAttributes);
    }

    /**
     * Should be able to delete a tag by id.
     */
    public function testDeleteById()
    {
        $this->tagModel
            ->shouldReceive('where->first->delete')
            ->andReturnTrue();

        $this->assertTrue($this->tagRepository->deleteById(5));
    }

    /**
     * Should be able to set the tag ids for a product.
     */
    public function testSyncProductTagIds()
    {
        /** @var Product|MockObject $product */
        $product = $this->makeMock(Product::class);
        $tagsRelation = $this->makeMock(BelongsToMany::class);
        $product->expects($this->atLeastOnce())
            ->method('tags')
            ->willReturn($tagsRelation);

        $tagIds = [1, 2, 3];
        $tagsRelation->expects($this->atLeastOnce())
            ->method('sync')
            ->with($tagIds);

        $this->tagRepository->syncProductTagIds($product, $tagIds);
    }
}
