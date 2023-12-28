<?php

namespace Cap\Commercio\Wallet;

class WalletOrder
{
    public int $amount;
    /**
     * This is a description displayed to the customer
     */
    public string $customerTrns;
    /**
     *
     */
    public Customer $customer;
    /**
     * if you need to allow more than the default 30 minutes for your customer to pay
     */
    public int $paymentTimeout = 1800;
    /**
     * if doing pre-authorizations (authorize now, capture later via app/API)
     */
    public bool $preauth = true;
    /**
     *if doing recurring payments (fixed or variable amount)
     */
    public bool $allowRecurring = true;
    /**
     *if supporting installments (customer selects number of installments)
     */
    public int $maxInstallments = 0;
    /**
     *if doing payment notification (Viva Wallet sends email to customer)
     */
    public bool $paymentNotification = true;
    /**
     *if supporting tip amount (included in amount paid by customer)
     */
    public int $tipAmount = 1;
    /**
     *if supporting open amount (the customer can specify what they pay)
     */
    public bool $disableExactAmount = true;
    /**
     *
     */
    public bool $disableCash = false;
    /**
     *
     */
    public bool $disableWallet = false;
    /**
     *
     */
    public string $sourceCode = 'Default';
    /**
     * This is a short description that helps you uniquely identify the transaction
     */
    public string $merchantTrns;
    public array $tags = [];

    public function __construct(int $amount, Customer $customer, string $customerTrns)
    {
        $this->amount = $amount;
        $this->customer = $customer;
        $this->customerTrns = $customerTrns;
    }

}