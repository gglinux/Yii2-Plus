<?php
return [
    'ShumiThriftUserService' => [
        'class'             => '\shumiuser\ShumiThriftUserServiceClient',
        'serverHost'        => '192.168.1.251',
        'serverPort'        => '50215',
        'sendTimeout'       => 30,
        'recvTimeout'       => 30,
        'maxConnectTimes'   => 2,
    ],
    'GxqThriftReportService' => [
        'class'             => '\gxqreport\GxqThriftReportServiceClient',
        'serverHost'        => '192.168.1.251',
        'serverPort'        => '50210',
        'sendTimeout'       => 30,
        'recvTimeout'       => 30,
        'maxConnectTimes'   => 2,
    ],
    'ShumiFundProductCommonService' => [
        'class'             => '\shumifundproductcommon\ShumiFundProductCommonServiceClient',
        'serverHost'        => '192.168.1.251',
        'serverPort'        => '50216',
        'sendTimeout'       => 30,
        'recvTimeout'       => 30,
        'maxConnectTimes'   => 2,
    ],
    'ShumiThriftTradeInfoService' => [
        'class'             => '\shumitradeinfo\ShumiThriftTradeInfoServiceClient',
        'serverHost'        => '192.168.1.251',
        'serverPort'        => '50214',
        'sendTimeout'       => 30,
        'recvTimeout'       => 30,
        'maxConnectTimes'   => 2,
    ],
];