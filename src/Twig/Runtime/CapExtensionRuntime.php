<?php

namespace Cap\Commercio\Twig\Runtime;

use Cap\Commercio\Wallet\CardTypeEnum;
use Cap\Commercio\Wallet\TransactionStatusEnum;
use Twig\Extension\RuntimeExtensionInterface;

class CapExtensionRuntime implements RuntimeExtensionInterface
{
    public function transactionStatus($value): string
    {
        $transactionStatusEnum = TransactionStatusEnum::from($value);

        return $transactionStatusEnum->getLabel().' <br />'.$transactionStatusEnum->getDescription();
    }

    public function cardType($value): string
    {
        return CardTypeEnum::from($value)->getLabel();
    }
}
