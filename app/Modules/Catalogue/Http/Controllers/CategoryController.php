<?php

namespace ChingShop\Modules\Catalogue\Http\Controllers;

use ChingShop\Http\Controllers\Controller;
use ChingShop\Http\WebUi;
use ChingShop\Modules\Catalogue\Domain\Category;
use ChingShop\Modules\Catalogue\Domain\Product\Product;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

/**
 * Customer category viewing actions.
 */
class CategoryController extends Controller
{
    /** @var Category */
    private $category;

    /** @var Product */
    private $product;

    /** @var WebUi */
    private $webUi;

    /**
     * @param Category $category
     * @param Product  $product
     * @param WebUi    $webUi
     */
    public function __construct(
        Category $category,
        Product $product,
        WebUi $webUi
    ) {
        $this->category = $category;
        $this->product = $product;
        $this->webUi = $webUi;
    }

    /**
     * @param int    $id
     * @param string $slug
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     *
     * @return View|RedirectResponse
     */
    public function viewCategory(int $id, string $slug)
    {
        /** @var Category $category */
        $category = $this->category->findOrFail(Category::privateId($id));

        if ($category->slug() !== $slug) {
            return $this->webUi->redirect(
                'categories.view',
                [$category->id, $category->slug()]
            );
        }

        $tree = $category->getDescendantsAndSelf()
            ->load(
                [
                    'products' => function ($query) {
                        /* @var Product $query */
                        $query->with(Product::standardRelations());
                    },
                ]
            );

        return $this->webUi->view(
            'customer.category.view',
            compact('category', 'tree')
        );
    }
}
