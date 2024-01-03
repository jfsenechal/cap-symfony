<?php

namespace Cap\Commercio\Wallet;

enum EciEnum: int
{
    case UNSPECIFIED = 0;
    case AUTHENTICATED = 1;
    case NO_3DS = 2;
    case ATTEMPT = 3;

    public function getLabel(): string
    {
        return match ($this) {
            self::UNSPECIFIED => 'Unspecified',
            self::AUTHENTICATED => 'Authenticated',
            self::NO_3DS => 'No 3DS',
            self::ATTEMPT => 'Attempt or not enrolled',
        };
    }
}
