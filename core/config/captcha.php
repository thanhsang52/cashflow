<?php

return [
    'secret' => env('NOCAPTCHA_SECRET'),
    'sitekey' => env('NOCAPTCHA_SITEKEY'),
    'options' => [
        'timeout' => 30,
    ],
    'default'   => [
        'length'    => 5,
        'width'     => 200,
        'height'    => 36,
        'quality'   => 100,
        'math'      => false,  //Enable Math Captcha
        'expire'    => 60,    //Captcha expiration
    ],
];
