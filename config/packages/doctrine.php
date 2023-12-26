<?php

use DoctrineExtensions\Query\Postgresql\Year;
use Symfony\Config\DoctrineConfig;

return static function (DoctrineConfig $doctrine) {
    $em = $doctrine->orm()->entityManager('default');
    $em->connection('default');
    $em->dql(['string_functions' => ['YEAR' => Year::class]]);

    $em->mapping('CapCommercio')
        ->isBundle(false)
        ->type('attribute')
        ->dir('%kernel.project_dir%/src/Cap/Commercio/src/Entity')
        ->prefix('Cap\Commercio')
        ->alias('CapCommercio');
};
