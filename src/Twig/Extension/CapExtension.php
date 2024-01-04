<?php

namespace Cap\Commercio\Twig\Extension;

use Cap\Commercio\Twig\Runtime\CapExtensionRuntime;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class CapExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            // If your filter generates SAFE HTML, you should add a third
            // parameter: ['is_safe' => ['html']]
            // Reference: https://twig.symfony.com/doc/3.x/advanced.html#automatic-escaping
            new TwigFilter(
                'transaction_status',
                [CapExtensionRuntime::class, 'transactionStatus'],
                ['is_safe' => ['html']]
            ),
        ];
    }
}
