<?php
/**
 * Created by PhpStorm.
 * User: guojiawei
 * Date: 2015/7/20
 * Time: 11:26
 */

$rules = [
    '/'=>'/jid',
    'jid/default/login' => 'jid/default/login',
    'jid/default/index' => 'jid/default/index',
    'jid/default/signature' => 'jid/default/signature',
    'jid/default/userlogin' => 'jid/default/userlogin',
    'jid/default/userlogout' => 'jid/default/userlogout',
    'site/test' => 'site/test',

    /*********************三板斧-用户************************/
    '/user/user/info'                           =>'/user/user/info',
    '/user/user/investorinfo'                   =>'/user/user/investorinfo',
    '/user/certify/certification'               =>'/user/certify/certification',
    '/user/certify/beinvestor'                  =>'/user/certify/beinvestor',

    /*********************三板斧-项目************************/
    '/project/project/invest'                   =>'/project/project/invest',
    '/project/project/projectinfo'              =>'/project/project/projectinfo',
    '/project/project/projectlist'              =>'/project/project/projectlist',

    /*********************三板斧-订单************************/
    '/order/order/repeal'                       =>'/order/order/repeal',

    /*********************三板斧公用服务************************/
    '/common/common/sendfile'                   =>'/common/common/sendfile',
];

return $rules;