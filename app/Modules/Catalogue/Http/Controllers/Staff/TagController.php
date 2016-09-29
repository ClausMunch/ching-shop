<?php

namespace ChingShop\Modules\Catalogue\Http\Controllers\Staff;

use ChingShop\Http\Controllers\Controller;
use ChingShop\Http\Requests\Staff\Catalogue\NewTagRequest;
use ChingShop\Http\WebUi;
use ChingShop\Modules\Catalogue\Domain\Product\ProductRepository;
use ChingShop\Modules\Catalogue\Domain\Tag\Tag;
use ChingShop\Modules\Catalogue\Domain\Tag\TagRepository;
use Illuminate\Http\Request;
use McCool\LaravelAutoPresenter\Exceptions\NotFoundException;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * Class TagController.
 */
class TagController extends Controller
{
    /** @var TagRepository */
    private $tagRepository;

    /** @var ProductRepository */
    private $productRepository;

    /** @var WebUi */
    private $webUi;

    /**
     * ImageController constructor.
     *
     * @param TagRepository     $tagRepository
     * @param ProductRepository $productRepository
     * @param WebUi             $webUi
     */
    public function __construct(
        TagRepository $tagRepository,
        ProductRepository $productRepository,
        WebUi $webUi
    ) {
        $this->tagRepository = $tagRepository;
        $this->productRepository = $productRepository;
        $this->webUi = $webUi;
    }

    /**
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        $tags = $this->tagRepository->loadAll();
        $newTag = new Tag();

        return $this->webUi->view(
            'catalogue::staff.tags.index',
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
            $this->webUi->successMessage("Created new tag `{$tag->name}`");
        } else {
            $this->webUi->errorMessage('Failed to create new tag');
        }

        return $this->redirectToTagsIndex();
    }

    /**
     * @param int $tagId
     *
     * @throws \Exception
     *
     * @return RedirectResponse
     */
    public function destroy(int $tagId)
    {
        $deleted = $this->tagRepository->deleteById($tagId);

        if ($deleted) {
            $this->webUi->successMessage("Deleted tag with ID `{$tagId}`");
        } else {
            $this->webUi->errorMessage(
                "Failed to delete tag with ID `{$tagId}`"
            );
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
        $product = $this->productRepository->loadBySku($sku);
        $tagIds = (array) $request->get('tag-ids');
        $this->tagRepository->syncProductTagIds($product, $tagIds);

        $this->webUi->successMessage("Tags updated for `{$product->sku}`");

        return $this->webUi->redirect('products.show', [$product->sku]);
    }

    /**
     * @return RedirectResponse
     */
    private function redirectToTagsIndex(): RedirectResponse
    {
        return $this->webUi->redirect('tags.index');
    }
}
