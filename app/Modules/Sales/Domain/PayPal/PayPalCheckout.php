<?php

namespace ChingShop\Modules\Sales\Domain\PayPal;

use ChingShop\Modules\Sales\Domain\Basket\Basket;
use ChingShop\Modules\Sales\Domain\Basket\BasketItem;
use ChingShop\Modules\Sales\Domain\Offer\PotentialOffer;
use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\RedirectUrls;
use PayPal\Api\ShippingAddress;
use PayPal\Api\Transaction;
use PayPal\Rest\ApiContext;

/**
 * Wrapper around initial set-up for a PayPal checkout payment.
 *
 * Class PayPalCheckout.
 */
class PayPalCheckout
{
    const DEFAULT_CURRENCY = 'GBP';

    const RETURN_ROUTE = 'sales.customer.paypal.return';
    const CANCEL_ROUTE = 'sales.customer.paypal.cancel';

    /** @var Basket */
    private $basket;

    /** @var ApiContext */
    private $apiContext;

    /** @var Payment */
    private $payment;

    /**
     * @param Basket     $basket
     * @param ApiContext $apiContext
     */
    public function __construct(Basket $basket, ApiContext $apiContext)
    {
        $this->basket = $basket;
        $this->apiContext = $apiContext;
    }

    /**
     * @throws \InvalidArgumentException
     *
     * @return string
     */
    public function approvalUrl(): string
    {
        return (string) $this->payment()->getApprovalLink();
    }

    /**
     * @throws \InvalidArgumentException
     *
     * @return string
     */
    public function paymentId(): string
    {
        return (string) $this->payment()->id;
    }

    /**
     * @return string
     */
    public function basketId(): string
    {
        return (string) $this->basket->id;
    }

    /**
     * @throws \InvalidArgumentException
     *
     * @return float
     */
    public function amountTotal(): float
    {
        return (float) $this->amount()->getTotal();
    }

    /**
     * @throws \InvalidArgumentException
     *
     * @return Payer
     */
    protected function payer(): Payer
    {
        return (new Payer())->setPaymentMethod('paypal');
    }

    /**
     * @throws \InvalidArgumentException
     *
     * @return ItemList
     */
    private function itemList(): ItemList
    {
        return (new ItemList())->setItems(
            $this->basket->basketItems
                // Purchases
                ->map(
                    function (BasketItem $basketItem) {
                        $item = new Item();
                        $item->setName($basketItem->productOption->fullName())
                            ->setQuantity(1)
                            ->setCurrency(self::DEFAULT_CURRENCY)
                            ->setSku($basketItem->productOption->id)
                            ->setPrice(
                                $basketItem->productOption->priceAsFloat()
                            );

                        return $item;
                    }
                )
                // Discounts from offers
                ->merge(
                    $this->basket->offers()->collection()->map(
                        function (PotentialOffer $offer) {
                            $item = new Item();
                            $item->setName((string) $offer->offer()->name)
                                ->setDescription(
                                    "Discount: {$offer->description()}"
                                )
                                ->setQuantity(1)
                                ->setCurrency(self::DEFAULT_CURRENCY)
                                ->setSku(str_slug($offer->offer()->name))
                                ->setPrice($offer->linePrice()->asFloat());

                            return $item;
                        }
                    )
                )
                ->all()
        )->setShippingAddress($this->shippingAddress());
    }

    /**
     * @throws \InvalidArgumentException
     *
     * @return ShippingAddress
     */
    private function shippingAddress(): ShippingAddress
    {
        return (new ShippingAddress())
            ->setRecipientName($this->basket->address->name)
            ->setLine1($this->basket->address->line_one)
            ->setLine2($this->basket->address->line_two)
            ->setCity($this->basket->address->city)
            ->setPostalCode($this->basket->address->post_code)
            ->setCountryCode($this->basket->address->country_code);
    }

    /**
     * @throws \InvalidArgumentException
     *
     * @return Details
     */
    private function details(): Details
    {
        return (new Details())
            ->setShipping(0)
            ->setTax(0)
            ->setSubtotal($this->basket->totalPrice()->asFloat());
    }

    /**
     * @throws \InvalidArgumentException
     *
     * @return Amount
     */
    private function amount(): Amount
    {
        return (new Amount())
            ->setCurrency(self::DEFAULT_CURRENCY)
            ->setTotal($this->basket->totalPrice()->asFloat())
            ->setDetails($this->details());
    }

    /**
     * @throws \InvalidArgumentException
     *
     * @return Transaction
     */
    private function transaction(): Transaction
    {
        return (new Transaction())
            ->setAmount($this->amount())
            ->setItemList($this->itemList())
            ->setDescription('Ching Shop Purchase')
            ->setInvoiceNumber(uniqid($this->basket->id, false));
    }

    /**
     * @throws \InvalidArgumentException
     *
     * @return RedirectUrls
     */
    private function redirectUrls(): RedirectUrls
    {
        return (new RedirectUrls())
            ->setReturnUrl(route(self::RETURN_ROUTE))
            ->setCancelUrl(route(self::CANCEL_ROUTE));
    }

    /**
     * @throws \InvalidArgumentException
     *
     * @return Payment
     */
    private function payment(): Payment
    {
        if ($this->payment === null) {
            $this->payment = app(Payment::class);
            $this->payment->setIntent('sale');
            $this->payment->setPayer($this->payer());
            $this->payment->setRedirectUrls($this->redirectUrls());
            $this->payment->setTransactions([$this->transaction()]);

            $this->payment->create($this->apiContext);
        }

        return $this->payment;
    }
}
