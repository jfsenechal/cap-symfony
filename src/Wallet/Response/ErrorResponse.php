<?php

namespace Cap\Commercio\Wallet\Response;

class ErrorResponse
{
    public function __construct(public readonly string $message, public readonly int $statutCode = 0)
    {
    }
}