<?php

namespace Cap\Commercio\Wallet;

enum OrderStatusEnum: int
{
    case PENDING = 0;
    case EXPIRED = 1;
    case CANCELED = 2;
    case PAID = 3;
}
