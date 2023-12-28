<?php

namespace Cap\Commercio\Wallet;

class WalletTransaction
{
    public readonly string $email;
    public readonly int $amount;
    public readonly string $orderCode;
    public readonly string $statusId;
    public readonly string $fullName;
    public readonly string $insDate;
    public readonly string $cardNumber;
    public readonly string $currencyCode;
    public readonly string $customerTrns;
    public readonly string $merchantTrns;
    public readonly string $cardUniqueReference;
    public readonly int $transactionTypeId;
    public readonly bool $recurringSupport;
    public readonly int $totalInstallments;
    public readonly ?string $cardCountryCode;
    public readonly ?string $cardIssuingBank;
    public readonly int $currentInstallment;
    public readonly int $cardTypeId;
}