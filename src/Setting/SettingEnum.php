<?php

namespace Cap\Commercio\Setting;

enum SettingEnum: string
{
    case STRIPE_SECRET_KEY = 'STRIPE_SECRET_KEY';
    case STRIPE_PUBLIC_KEY = 'STRIPE_PUBLIC_KEY';
}
