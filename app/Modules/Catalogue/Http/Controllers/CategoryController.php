<?php

namespace ChingShop\Modules\Catalogue\Http\Controllers;

use ChingShop\Http\Controllers\Controller;
use ChingShop\Http\WebUi;
use ChingShop\Modules\Catalogue\Domain\Category;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

/**
 * Customer category viewing actions.
 */
class CategoryController extends Controller
{
    /** @var Category */
    private $category;

    /** @var WebUi */
    private $webUi;

    /**
     * @param Category $category
     * @param WebUi    $webUi
     */
    public function __construct(Category $category, WebUi $webUi)
    {
        $this->category = $category;
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
        $category = $this->category->with(['products'])->findOrFail(
            Category::privateId($id)
        );

        if ($category->slug() !== $slug) {
            return $this->webUi->redirect(
                'categories.view',
                [$category->id, $category->slug()]
            );
        }

        return $this->webUi->view(
            'customer.category.view',
            compact('category')
        );
    }
}
