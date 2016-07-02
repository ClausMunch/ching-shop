<?php

namespace Testing\Unit\ChingShop\Http\Controller\Staff;

use ChingShop\Catalogue\Product\Product;
use ChingShop\Catalogue\Tag\Tag;
use ChingShop\Catalogue\Tag\TagRepository;
use ChingShop\Http\Controllers\Staff\TagController;
use ChingShop\Http\Requests\Staff\Catalogue\NewTagRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Testing\Unit\ChingShop\Http\Controller\ControllerTest;
use Testing\Unit\MockObject;

class TagControllerTest extends ControllerTest
{
    /** @var TagController */
    private $tagController;

    /** @var TagRepository|MockObject */
    private $tagRepository;

    /**
     * Set up tag controller with mock dependencies.
     */
    public function setUp()
    {
        parent::setUp();

        $this->tagRepository = $this->makeMock(TagRepository::class);

        $this->tagController = new TagController(
            $this->tagRepository,
            $this->productRepository(),
            $this->webUi()
        );
    }

    /**
     * Sanity check for instantiation.
     */
    public function testConstruct()
    {
        $this->assertInstanceOf(TagController::class, $this->tagController);
    }

    /**
     * Should be able to get a view for the tag index.
     */
    public function testIndex()
    {
        $this->webUi()->expects($this->atLeastOnce())->method('view');

        $this->tagController->index();
    }

    /**
     * Should be able to store a new tag.
     */
    public function testStore()
    {
        $newTagAttributes = ['name' => str_random()];

        $this->tagRepository
            ->expects($this->atLeastOnce())
            ->method('create')
            ->with($newTagAttributes)
            ->willReturn(new Tag());

        /** @var NewTagRequest|MockObject $request */
        $request = $this->makeMock(NewTagRequest::class);
        $request->expects($this->atLeastOnce())
            ->method('all')
            ->willReturn($newTagAttributes);

        $this->responseFactoryWillMakeRedirect();

        $this->tagController->store($request);
    }

    /**
     * Should be able to delete a tag.
     */
    public function testDestroy()
    {
        $tagId = $this->generator()->anyInteger();
        $this->tagRepository
            ->expects($this->atLeastOnce())
            ->method('deleteById')
            ->with($tagId);

        $this->responseFactoryWillMakeRedirect();

        $this->tagController->destroy($tagId);
    }

    /**
     * Should be able to set the tags for a product.
     */
    public function testPutProductTags()
    {
        $this->productRepository()
            ->expects($this->atLeastOnce())
            ->method('loadBySku')
            ->willReturn($this->makeMock(Product::class));

        /** @var Request $request */
        $request = $this->makeMock(Request::class);

        $this->tagController->putProductTags(
            $request,
            $this->generator()->anySlug()
        );
    }

    /**
     * Expect the response factory to make a redirect response.
     */
    private function responseFactoryWillMakeRedirect()
    {
        $this->webUi()
            ->expects($this->atLeastOnce())
            ->method('redirect')
            ->with($this->isType('string'))
            ->willReturn($this->makeMock(RedirectResponse::class));
    }
}
