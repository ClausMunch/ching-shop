<?php

namespace Testing\Unit\ChingShop\Http\Controller\Customer;

use ChingShop\Catalogue\Tag\Tag;
use ChingShop\Catalogue\Tag\TagRepository;
use ChingShop\Http\Controllers\Customer\TagController;
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
            $this->viewFactory(),
            $this->responseFactory()
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
     * Should be able to get a view response for a product.
     */
    public function testViewAction()
    {
        $tag = new Tag;
        $tag->id = $this->generator()->anyInteger();
        $tag->name = $this->generator()->anyString();
        $this->tagRepository->expects($this->atLeastOnce())
            ->method('loadById')
            ->with($tag->id)
            ->willReturn($tag);

        $this->tagController->viewAction($tag->id, $tag->name);
    }
}
