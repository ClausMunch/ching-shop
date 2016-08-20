<?php

namespace ChingShop\Http\Controllers\Customer;

use ChingShop\Http\Controllers\Controller;
use ChingShop\Modules\Catalogue\Domain\Tag\TagRepository;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Contracts\View\Factory as ViewFactory;

/**
 * Class TagController.
 */
class TagController extends Controller
{
    /** @var TagRepository */
    private $tagRepository;

    /** @var ViewFactory */
    private $viewFactory;

    /** @var ResponseFactory */
    private $responseFactory;

    /**
     * TagController constructor.
     *
     * @param TagRepository   $tagRepository
     * @param ViewFactory     $viewFactory
     * @param ResponseFactory $responseFactory
     */
    public function __construct(
        TagRepository $tagRepository,
        ViewFactory $viewFactory,
        ResponseFactory $responseFactory
    ) {
        $this->tagRepository = $tagRepository;
        $this->viewFactory = $viewFactory;
        $this->responseFactory = $responseFactory;
    }

    /**
     * @param int    $tagId
     * @param string $tagName
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     *
     * @return \Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function viewAction(int $tagId, string $tagName)
    {
        $tag = $this->tagRepository->loadById($tagId);

        if ($tag->name !== $tagName) {
            return $this->responseFactory->redirectToRoute(
                'tag.view',
                ['id' => $tag->id, $tag->name]
            );
        }

        return $this->viewFactory->make('customer.tag.view', compact('tag'));
    }
}
