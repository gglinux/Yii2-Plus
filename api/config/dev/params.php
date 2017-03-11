<?php

return [
    'passportUrl'=>'https://passport.jinfuzi.dz',
    'nonceTime' => 300,
    'appClient'=>array(
        '^1_1\.[0-9]+\.[0-9]+$'=>array(
            'secretKey'=>'dwZgNb7W9NCx8hHA3yBxxLEzpUk=',
        ),
        '^2_1\.[0-9]+\.[0-9]+$'=>array(
            'secretKey'=>'dwZgNb7W9NCx8hHA3yBxxLEzpUk=',
        ),
        '^3_1\.[0-9]+\.[0-9]+$'=>array(
            'secretKey'=>'dwZgNb7W9NCx8hHA3yBxxLEzpUk=',
        )
    ),
    'payments' => array(
        'BL' => array(        //余额支付

        ),
        'QP'=> array(       //快捷支付
            'callback'=>'http://m.peizi.jinfuzi.net/account/payment/callback',
            'rechargecb'=>'http://m.peizi.jinfuzi.net/account/deposit/callback' //充值回调地址
        ),
    ),
    'bankIcon'=>array(
        'url'    => 'http://m.peizi.jinfuzi.net/static/img/ico_bank',
        'prefix' => 'ico_',
        'ext'    => 'png'
    ),
    'wapDomain'=>'http://m.peizi.jinfuzi.net',
    'mobileDomain'=>'http://m.jinfuzi.net',
    'equityfundDomain'=>'http://h5.jfz.net',
];
