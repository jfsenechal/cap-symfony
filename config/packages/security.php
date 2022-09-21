<?php

use Cap\Commercio\Entity\RightAccess;
use Cap\Commercio\Security\CapAuthenticator;
use Cap\Commercio\Security\Md5VerySecureHasher;
use Symfony\Config\SecurityConfig;

return static function (SecurityConfig $security) {
    $security->passwordHasher('cap_hasher')
        ->id(Md5VerySecureHasher::class);
    $security->provider('cap_user_provider', [
        'entity' => [
            'class' => RightAccess::class,
            'property' => 'email',
        ],
    ]);

    // @see Symfony\Config\Security\FirewallConfig
    $main = [
        'provider' => 'cap_user_provider',
        'logout' => ['path' => 'app_logout'],
        'form_login' => [],
        'entry_point' => CapAuthenticator::class,
        'custom_authenticators' => [CapAuthenticator::class],
    ];

    $security->firewall('main', $main);
};