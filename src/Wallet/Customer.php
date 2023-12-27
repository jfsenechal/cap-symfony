<?php

namespace Cap\Commercio\Wallet;

class Customer
{
    public string $email;
    public string $fullName;
    public string $phone;
    public string $countryCode = "BE";
    public string $requestLang = "FR-BE";

    public function __construct(string $email, string $fullName)
    {
        $this->email = $email;
        $this->fullName = $fullName;
    }
}