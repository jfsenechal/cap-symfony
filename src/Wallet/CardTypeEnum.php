<?php

namespace Cap\Commercio\Wallet;

enum CardTypeEnum: int
{
    case Visa = 0;
    case Mastercard = 1;
    case Diners = 2;
    case Amex = 3;
    case Invalid = 4;
    case Unknown = 5;
    case Maestro = 6;
    case Discover = 7;
    case JCB = 8;

    public function getLabel(): string
    {
        return $this->name;
    }
}
