<?php

namespace Cap\Commercio\Twig\Runtime;

use Cap\Commercio\Wallet\TransactionStatusEnum;
use Twig\Extension\RuntimeExtensionInterface;

class CapExtensionRuntime implements RuntimeExtensionInterface
{
    public function transactionStatus($value): string
    {
        $transactionStatusEnum = TransactionStatusEnum::from($value);

        return $transactionStatusEnum->getLabel().' <br />'.$transactionStatusEnum->getDescription();
    }
}
