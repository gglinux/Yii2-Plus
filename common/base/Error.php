<?php
/**
 * 大爆炸 公共错误码定义
 */
namespace common\base;

class Error
{

    static $error_array = [

        //////////// 公共错误100XX /////////

        10000 => [
            'zh'=>'参数类型错误',
            'en'=>'params error'
        ],
        10001 => [
            'zh'=>'部分参数为空',
            'en'=>'params empty'
        ],
        10002 => [
            'zh'=>'数据库错误',
            'en'=>'db error'
        ],
        10003 => [
            'zh'=>'签名错误',
            'en'=>'sign error'
        ],
        10004 => [
            'zh'=>'非法客户端类型',
            'en'=>'client error'
        ],
        10005 => [
            'zh'=>'cache写失败',
            'en'=>'cache write error'
        ],
        10006 => [
            'zh'=>'cache读错误',
            'en'=>'cache read error'
        ],
        10007 => [
            'zh'=>'参数为空',
            'en'=>'params empty'
        ],
        10008 => [
            'zh'=>'参数为空',
            'en'=>'params empty'
        ],
        10009 => [
            'zh'=>'参数为空',
            'en'=>'params empty'
        ],
        10010 => [
            'zh'=>'参数为空',
            'en'=>'params empty'
        ],

    ///////////////// 业务类型错误 200XX ////////////////////
        20000 => [
            'zh'=>'参数为空',
            'en'=>'params empty'
        ],
        20001 => [
            'zh'=>'参数为空',
            'en'=>'params empty'
        ],
        20002 => [
            'zh'=>'参数为空',
            'en'=>'params empty'
        ],

    ];

    /**
     * 获取欢聚时刻常用错误信息
     * @param int $code
     * @return mixed
     * @throws Exception
     */
    public function getErrorMsg($code = 10000)
    {
        $msg = self::$error_array[$code];
        if ( empty($msg) ) {
            throw new Exception('未定义的错误类型！');
        }
        return $msg;
    }
} 