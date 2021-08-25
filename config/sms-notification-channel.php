<?php

return [
    'provider' => env('SMS_PROVIDER'), // 短信服务商：yunpian
    'signature' => env('SMS_SIGNATURE'), // 签名文字

    'yunpian' => [
        'key' => env('SMS_YUNPIAN_KEY'),
    ]
];
