<?php
/**
 * Created by PhpStorm.
 * User: guojiawei
 * Date: 2017/5/19
 * Time: 下午3:37
 */

namespace service\components;

use stdClass;

use Closure;
use Yii;

$logHandler = function($name, array &$args, stdClass $context, Closure $next)
{

    try{
        $result = $next($name, $args, $context);
    }catch(\yii\db\Exception $e) {
        Yii::error(['name'=>'DB error!','function'=>$name,'intput'=>$args,'exception'=>var_export($e, true)]);
        //todo 发送短信，邮件
        return $e;
    }catch (\service\base\ServiceException $e) {
        Yii::error(['name'=>'service error!','function'=>$name,'intput'=>$args,'exception'=>var_export($e, true)]);
        return $e;
    }catch(\yii\base\Exception $e) {
        Yii::error(['name'=>'PHP error!','function'=>$name,'intput'=>$args,'exception'=>var_export($e, true)]);
        //todo 根据发生频率，发送邮件和短信
        return $e;
    }
    return $result;
};