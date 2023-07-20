<?php

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $parameters = $containerConfigurator->parameters();
    $parameters->set('MANDRILL_API', '%env(MANDRILL_API)%');
    $parameters->set('CAP_PATH', '%env(CAP_PATH)%');
    $parameters->set('CAP_FOLDER_IMAGE', 'media');

    $services = $containerConfigurator->services();

    $services
        ->defaults()
        ->autowire()
        ->autoconfigure()
        ->private();

    $services->load('Cap\\Commercio\\', __DIR__.'/../src/*')
        ->exclude([__DIR__.'/../src/{Entity,Tests}']);


};
