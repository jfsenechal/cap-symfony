<?php

namespace Cap\Commercio\Wallet;

use Symfony\Component\Validator\Constraints\GreaterThan;

/**
 * https://developer.vivawallet.com/apis-for-payments/payment-api/#tag/Payments/paths/~1checkout~1v2~1orders/post
 */
class WalletOrder
{
    /**
     * cents of a euro
     */
    #[GreaterThan(30)]
    public int $amount;
    /**
     * This is a description displayed to the customer
     */
    public ?string $customerTrns = null;
    public Customer $customer;
    /**
     * The time given to the customer to complete the payment.
     * If the customer does not complete the payment within this time frame, the Payment Order is automatically cancelled
     */
    public int $paymentTimeout = 1800;
    /**
     * When creating a Smart Checkout payment order with the preauth parameter set to true,
     * only payment methods which support pre-authorizations will be displayed to your customers
     */
    public bool $preauth = false;
    /**
     * If this parameter is set to true, recurring payments are enabled so that the initial transaction ID can be used for subsequent payment
     */
    public bool $allowRecurring = false;
    /**
     * If this parameter is set to true, the customer will be forced to pay with installments and
     * with the specific number indicated in maxInstallments parameter.
     */
    public int $maxInstallments = 0;
    /**
     * If this parameter is set to true, the customer will be forced to pay with installments and
     * with the specific number indicated in maxInstallments parameter.
     */
    public bool $forceMaxInstallments = false;
    /**
     * If you wish to create a payment order, and then send out an email to the customer to request payment,
     * rather than immediately redirect the customer to the payment page to pay now, set the value to true
     */
    public bool $paymentNotification = true;
    /**
     * The tip value (if applicable for the customer's purchase) which is already included
     * in the amount of the payment order and marked as tip
     */
    public int $tipAmount = 0;
    /**
     * If this parameter is set to true, then any amount specified in the payment order is ignored (although still mandatory),
     * and the customer is asked to indicate the amount they will pay
     */
    public bool $disableExactAmount = false;
    /**
     * if this parameter is set to true, the customer will not have the option to pay in cash
     */
    public bool $disableCash = false;
    /**
     * If this parameter is set to true, the customer will not have the option to pay using their Viva Wallet personal account,
     * and the checkout page will not display the Viva Wallet option
     */
    public bool $disableWallet = false;
    /**
     * This is the code of the payment source associated with the merchant.
     * If the merchant has defined multiple payment sources in their account
     */
    public string $sourceCode = 'Default';
    /**
     * This can be either an ID or a short description that helps you uniquely identify the transaction in the Viva Wallet banking app
     */
    public ?string $merchantTrns = null;
    /**
     * You can add several tags to a transaction that will help in grouping and filtering
     */
    public array $tags = [];
    /**
     * You can provide the card tokens you have saved on your backend for this customer
     */
    public ?array $cardTokens = null;
    /**
     * Applies an additional 'Service fee' to be added on top of the order amount if the customer chooses to pay with specific payment methods.
     */
    public ?array $paymentMethodFees = null;
    /**
     * This parameter should be set to true to trigger a verification using Card Verification functionality
     * (and the amount parameter should be set to '0').
     */
    public bool $isCardVerification = false;

    public function __construct(int $amount, Customer $customer, string $customerTrns)
    {
        $this->amount = $amount;
        $this->customer = $customer;
        $this->customerTrns = $customerTrns;
    }

}