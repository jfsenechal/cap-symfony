<?php

use Cap\Commercio\Entity\RightAccess;
use Cap\Commercio\Security\CapAuthenticator;
use Cap\Commercio\Security\Md5VerySecureHasher;
use Symfony\Config\SecurityConfig;

return static function (SecurityConfig $security) {
    /* $security->passwordHasher(RightAccess::class, [
         'algorithm' => 'md5',
         'encode_as_base64' => false,
         'iterations' => 1,
     ]);*/

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
        'login_throttling' => [
            'max_attempts' => 6, //per minute...
        ],
    ];

    $security->firewall('main', $main);
};
