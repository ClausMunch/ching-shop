<?php

namespace ChingShop\Http\Controllers\Staff;

use ChingShop\Catalogue\Product\ProductRepository;
use ChingShop\Catalogue\Tag\Tag;
use ChingShop\Catalogue\Tag\TagRepository;
use ChingShop\Http\Controllers\Controller;
use ChingShop\Http\Requests\Staff\Catalogue\NewTagRequest;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Contracts\View\Factory as ViewFactory;
use Illuminate\Http\Request;
use Laracasts\Flash\FlashNotifier;
use McCool\LaravelAutoPresenter\Exceptions\NotFoundException;
use Symfony\Component\HttpFoundation\RedirectResponse;

class TagController extends Controller
{
    /** @var ViewFactory */
    private $viewFactory;

    /** @var ResponseFactory */
    private $responseFactory;

    /** @var FlashNotifier */
    private $notifier;

    /** @var TagRepository */
    private $tagRepository;

    /** @var ProductRepository */
    private $productRepository;

    /**
     * ImageController constructor.
     *
     * @param ViewFactory       $viewFactory
     * @param ResponseFactory   $responseFactory
     * @param FlashNotifier     $notifier
     * @param TagRepository     $tagRepository
     * @param ProductRepository $productRepository
     */
    public function __construct(
        ViewFactory $viewFactory,
        ResponseFactory $responseFactory,
        FlashNotifier $notifier,
        TagRepository $tagRepository,
        ProductRepository $productRepository
    ) {
        $this->viewFactory = $viewFactory;
        $this->responseFactory = $responseFactory;
        $this->notifier = $notifier;
        $this->tagRepository = $tagRepository;
        $this->productRepository = $productRepository;
    }

    /**
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        $tags = $this->tagRepository->loadAll();
        $newTag = new Tag();

        return $this->viewFactory->make(
            'staff.tags.index',
            compact('tags', 'newTag')
        );
    }

    /**
     * @param NewTagRequest $request
     *
     * @return RedirectResponse
     */
    public function store(NewTagRequest $request)
    {
        $tag = $this->tagRepository->create($request->all());

        if (isset($tag->id)) {
            $this->notifier->success("Created new tag `{$tag->name}`");
        } else {
            $this->notifier->error('Failed to create new tag');
        }

        return $this->redirectToTagsIndex();
    }

    /**
     * @param int $tagId
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(int $tagId)
    {
        $deleted = $this->tagRepository->deleteById($tagId);

        if ($deleted) {
            $this->notifier->success("Deleted tag with ID `{$tagId}`");
        } else {
            $this->notifier->error("Failed to delete tag with ID `{$tagId}`");
        }

        return $this->redirectToTagsIndex();
    }

    /**
     * @param Request $request
     * @param string  $sku
     *
     * @throws NotFoundException
     *
     * @return RedirectResponse
     */
    public function putProductTags(Request $request, string $sku)
    {
        $product = $this->productRepository->mustLoadBySku($sku);
        $tagIds = (array) $request->get('tag-ids');
        $this->tagRepository->syncProductTagIds($product, $tagIds);

        $this->notifier->success("Tags updated for `{$product->sku}`");

        return $this->responseFactory->redirectToRoute(
            'staff.products.show',
            ['sku' => $product->sku]
        );
    }

    /**
     * @return RedirectResponse
     */
    private function redirectToTagsIndex(): RedirectResponse
    {
        return $this->responseFactory->redirectToRoute('staff.tags.index');
    }
}
