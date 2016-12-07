<?php

namespace ChingShop\Modules\Sales\Http\Controllers\Customer;

use ChingShop\Http\Controllers\Controller;
use ChingShop\Http\WebUi;
use ChingShop\Modules\Catalogue\Domain\Product\Product;
use ChingShop\Modules\Sales\Domain\Offer\Offer;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\RedirectResponse;

/**
 * Customer special offer viewing actions.
 */
class OffersController extends Controller
{
    /** @var WebUi */
    private $webUi;

    /**
     * OffersController constructor.
     *
     * @param WebUi $webUi
     */
    public function __construct(WebUi $webUi)
    {
        $this->webUi = $webUi;
    }

    /**
     * @return View
     */
    public function products()
    {
        return $this->webUi->view(
            'sales::offer.products',
            [
                'products' => Product::with(Product::standardRelations())
                    ->has('offers')
                    ->orderBy('updated_at', 'desc')
                    ->paginate(),
            ]
        );
    }

    /**
     * @param int    $offerId
     * @param string $slug
     *
     * @throws ModelNotFoundException
     *
     * @return View|RedirectResponse
     */
    public function view(int $offerId, string $slug)
    {
        /** @var Offer $offer */
        $offer = Offer::findOrFail($offerId);
        if ($offer->slug() !== $slug) {
            return $this->webUi->redirect(
                'offer.show',
                [$offerId, $offer->slug()]
            );
        }

        $products = Product::whereHas(
            'offers',
            function ($query) use ($offer) {
                /* @var Builder $query */
                $query->where('id', '=', $offer->id);
            }
        )->with(Product::standardRelations())->paginate();

        return $this->webUi->view(
            'sales::offer.view',
            compact('offer', 'products')
        );
    }
}
