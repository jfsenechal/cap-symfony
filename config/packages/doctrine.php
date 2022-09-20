<?php

use Symfony\Config\DoctrineConfig;

return static function (DoctrineConfig $doctrine) {

    $em = $doctrine->orm()->entityManager('default');
    $em->connection('default');

    $em->mapping('CapCommercio')
        ->isBundle(false)
        ->type('annotation')
        ->dir('%kernel.project_dir%/src/Cap/Commercio/src/Entity')
        ->prefix('Cap\Commercio')
        ->alias('CapCommercio');
};
