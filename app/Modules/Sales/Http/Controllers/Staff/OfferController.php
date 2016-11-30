<?php

namespace ChingShop\Modules\Sales\Http\Controllers\Staff;

use ChingShop\Http\Controllers\Controller;
use ChingShop\Http\WebUi;
use ChingShop\Modules\Catalogue\Domain\Product\Product;
use ChingShop\Modules\Sales\Domain\Offer\Offer;
use ChingShop\Modules\Sales\Http\Requests\Staff\PersistOfferRequest;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

/**
 * Staff offer management actions.
 */
class OfferController extends Controller
{
    /** @var Offer */
    private $offer;

    /** @var WebUi */
    private $webUi;

    /**
     * OfferController constructor.
     *
     * @param Offer $offer
     * @param WebUi $webUi
     */
    public function __construct(Offer $offer, WebUi $webUi)
    {
        $this->offer = $offer;
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
        return $this->buildView(
            'index',
            [
                'offers'   => $this->offer
                    ->orderBy('created_at', 'desc')
                    ->paginate(),
                'products' => Product::all(),
            ]
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return $this->buildView('create', ['offer' => new Offer([])]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param PersistOfferRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(PersistOfferRequest $request)
    {
        $offer = Offer::create($request->all());

        $this->webUi->successMessage("Created new offer `{$offer->name}`.");

        return $this->webUi->redirect('offers.index');
    }

    /**
     * Display the specified resource.
     */
    public function show()
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return $this->buildView(
            'edit',
            ['offer' => $this->offer->findOrFail($id)]
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param PersistOfferRequest $request
     * @param int                 $id
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     * @throws \Illuminate\Database\Eloquent\MassAssignmentException
     *
     * @return \Illuminate\Http\Response
     */
    public function update(PersistOfferRequest $request, int $id)
    {
        /** @var Offer $offer */
        $offer = $this->offer->findOrFail($id);
        $offer->fill($request->all());
        $offer->save();

        $this->webUi->successMessage("Updated offer `{$offer->name}`.");

        return $this->webUi->redirect('offers.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        /** @var Offer $offer */
        $offer = $this->offer->findOrFail($id);

        $offer->delete();

        $this->webUi->warningMessage(
            "Deleted offer `{$offer->name}`."
        );

        return $this->webUi->redirect('offers.index');
    }

    /**
     * @param Request $request
     * @param int     $id
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function putProducts(Request $request, int $id)
    {
        /** @var Offer $offer */
        $offer = $this->offer->findOrFail($id);
        $offer->products()->sync((array) $request->get('product-ids'));

        $this->webUi->successMessage(
            "Set products for offer `{$offer->name}`."
        );

        return $this->webUi->redirect('offers.index');
    }

    /**
     * @param Request $request
     * @param string  $sku
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function putProductOffers(Request $request, string $sku)
    {
        $product = Product::where('sku', '=', $sku)->firstOrFail();
        $product->offers()->sync((array) $request->get('offer-ids'));

        $this->webUi->successMessage(
            "Set offers for product `{$product->sku}`."
        );

        return $this->webUi->redirect('products.show', ['sku' => $sku]);
    }

    /**
     * @param string $name
     * @param array  $bindData
     *
     * @return View
     */
    private function buildView(string $name, array $bindData = []): View
    {
        return $this->webUi->view("sales::staff.offers.{$name}", $bindData);
    }
}
