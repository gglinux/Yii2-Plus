<?php
/**
 * Created by PhpStorm.
 * User: guojiawei
 * Date: 2017/3/26
 * Time: 下午6:51
 */

namespace common\components;

/**
 * 全局用户信息管理
 * Class SessionCan
 * @package common\components
 */

class SessionCan
{
    /**
     * @var float|null
     */
    private static $uid;

    private static $phone;

    private function __construct(){}

    private function __clone(){}

    public static function init($session)
    {
        self::$uid = isset($session['uid'])?floatval($session['uid']):null;
        self::$phone = isset($session['phone'])?$session['phone']:null;
    }

    public static function getUid()
    {
        return self::$uid;
    }

    public static function getPhone()
    {
        return self::$phone;
    }

}