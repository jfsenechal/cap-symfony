<?php

namespace Cap\Commercio\Helper;

class PriceCalculator
{
    public static function calculateTvaAmount(float $priceEvat, float $vat): float
    {
        return ($priceEvat / 100) * $vat;
    }

    public static function calculatePriceVat(float $priceEvat, float $vatAmount): float
    {
        return number_format($priceEvat + $vatAmount,2);
    }
}