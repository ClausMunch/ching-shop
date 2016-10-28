<?php

namespace ChingShop\Modules\Catalogue\Http\Controllers\Staff;

use ChingShop\Http\Controllers\Controller;
use ChingShop\Http\WebUi;
use ChingShop\Modules\Catalogue\Domain\Category;
use ChingShop\Modules\Catalogue\Domain\Product\Product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

/**
 * Category entity staff interactions.
 */
class CategoryController extends Controller
{
    /** @var Category|Builder */
    private $category;

    /** @var Product|Builder */
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
     * Display a listing of the resource.
     *
     * @throws \InvalidArgumentException
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->webUi->view(
            'catalogue::staff.categories.index',
            [
                'categories' => $this->category
                    ->roots()
                    ->with(['products', 'children'])
                    ->orderBy('depth', 'asc')
                    ->orderBy('created_at', 'desc')
                    ->paginate(),
            ]
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        /** @var Category $category */
        $category = $this->category->create(['name' => $request->get('name')]);

        $this->webUi->successMessage(
            "Created new category `{$category->name}`."
        );

        return $this->webUi->redirect('categories.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        $category = $this->category->where('id', '=', $id)->first();
        $category->delete();

        $this->webUi->successMessage(
            "Deleted category `{$category->name}`."
        );

        return $this->webUi->redirect('categories.index');
    }

    /**
     * @param string  $sku
     * @param Request $request
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function putProductCategory(string $sku, Request $request)
    {
        /** @var Product $product */
        $product = $this->product->where('sku', '=', $sku)->firstOrFail();
        $category = $this->category->findOrFail($request->get('category-id'));

        $product->category()->associate($category);
        $product->save();

        $this->webUi->successMessage(
            "Set category for `{$product->sku}` to `{$category->name}`."
        );

        return $this->webUi->redirect('products.show', [$product->sku]);
    }

    /**
     * @param int     $id
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function putCategoryParent(int $id, Request $request)
    {
        /** @var Category $category */
        $category = $this->category->findOrFail($id);

        if ((int) $request->get('parent-id') === -1) {
            $category->makeRoot();
            $this->webUi->successMessage(
                "Made `{$category->name}` a root-level category."
            );

            return $this->webUi->redirect('categories.index');
        }

        /** @var Category $parent */
        $parent = $this->category->findOrFail($request->get('parent-id'));
        $category->makeChildOf(
            $parent
        );

        $this->webUi->successMessage(
            "Made `{$category->name}` a child of `{$parent->name}`."
        );

        return $this->webUi->redirect('categories.index');
    }
}
