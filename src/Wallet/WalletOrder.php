<?php

namespace Cap\Commercio\Wallet;

class WalletOrder
{
    public int $amount;
    /**
     * This is a description displayed to the customer*/
    public string $customerTrns;
    public Customer $customer;
    public int $paymentTimeout = 1800;
    public bool $preauth = true;
    public bool $allowRecurring = true;
    public int $maxInstallments = 0;
    public bool $paymentNotification = true;
    public int $tipAmount = 1;
    public bool $disableExactAmount = true;
    public bool $disableCash = false;
    public bool $disableWallet = false;
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