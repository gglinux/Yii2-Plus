<?php
/**
 * Created by PhpStorm.
 * User: guojiawei
 * Date: 2017/5/19
 * Time: 下午3:37
 */

namespace service\components;


use stdClass;

use Hprose\Future;
use Closure;

$logHandler = function($name, array &$args, stdClass $context, Closure $next)
{
    $requestId = microtime(true).rand(10000,99999);
    \Yii::info(['request_id' => $requestId, 'name'=>'function','body'=>$name],'rpc_request');
    \Yii::info(['request_id' => $requestId, 'name'=>'intput','body'=>var_export($args, true)],'rpc_request');
    $result = $next($name, $args, $context);
    if (Future\isFuture($result)) {
        $result->then(function($result, $requestId) {
            \Yii::info(['request_id' => $requestId, 'name'=>'output','body'=>var_export($result, true)],'rpc_request');
        });
    } else {
        \Yii::info(['request_id' => $requestId, 'name'=>'output','body'=>var_export($result, true)],'rpc_request');
    }
    return $result;
};