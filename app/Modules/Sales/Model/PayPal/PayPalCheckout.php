<?php

namespace ChingShop\Modules\Sales\Model\PayPal;

use ChingShop\Modules\Sales\Model\Basket\Basket;
use ChingShop\Modules\Sales\Model\Basket\BasketItem;
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
 * Class PayPalCheckout
 * @package ChingShop\Modules\Sales\Model\PayPal
 */
class PayPalCheckout
{
    const DEFAULT_CURRENCY = 'GBP';

    /** @var Basket */
    private $basket;

    /** @var ApiContext */
    private $apiContext;

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
     * @return string
     * @throws \InvalidArgumentException
     */
    public function approvalUrl(): string
    {
        return (string) $this->payment()
            ->create($this->apiContext)
            ->getApprovalLink();
    }

    /**
     * @return Payer
     * @throws \InvalidArgumentException
     */
    protected function payer(): Payer
    {
        return (new Payer())->setPaymentMethod('paypal');
    }

    /**
     * @return ItemList
     * @throws \InvalidArgumentException
     */
    private function itemList(): ItemList
    {
        return (new ItemList())->setItems(
            array_map(
                function (BasketItem $basketItem) {
                    $item = new Item();
                    $item->setName($basketItem->productOption->fullName())
                        ->setQuantity(1)
                        ->setCurrency(self::DEFAULT_CURRENCY)
                        ->setSku($basketItem->productOption->id)
                        ->setPrice($basketItem->productOption->priceAsFloat());

                    return $item;
                },
                $this->basket->basketItems->all()
            )
        )->setShippingAddress($this->shippingAddress());
    }

    /**
     * @return ShippingAddress
     * @throws \InvalidArgumentException
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
     * @return Details
     * @throws \InvalidArgumentException
     */
    private function details(): Details
    {
        return (new Details())
            ->setShipping(0)
            ->setTax(0)
            ->setSubtotal($this->basket->totalPrice());
    }

    /**
     * @return Amount
     * @throws \InvalidArgumentException
     */
    private function amount(): Amount
    {
        return (new Amount())
            ->setCurrency(self::DEFAULT_CURRENCY)
            ->setTotal($this->basket->totalPrice())
            ->setDetails($this->details());
    }

    /**
     * @return Transaction
     * @throws \InvalidArgumentException
     */
    private function transaction(): Transaction
    {
        return (new Transaction())
            ->setAmount($this->amount())
            ->setItemList($this->itemList())
            ->setDescription('TODO')
            ->setInvoiceNumber($this->basket->id);
    }

    /**
     * @return RedirectUrls
     * @throws \InvalidArgumentException
     */
    private function redirectUrls(): RedirectUrls
    {
        return (new RedirectUrls())
            ->setReturnUrl(
                route('sales.customer.paypal.return')
            )
            ->setCancelUrl(
                route('sales.customer.paypal.cancel')
            );
    }

    /**
     * @return Payment
     * @throws \InvalidArgumentException
     */
    private function payment(): Payment
    {
        return (new Payment())
            ->setIntent('sale')
            ->setPayer($this->payer())
            ->setRedirectUrls($this->redirectUrls())
            ->setTransactions([$this->transaction()]);
    }
}
