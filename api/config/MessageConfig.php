<?php
/**
 * 所有跟短信，邮件,其他信息相关的配置类型
 *
 */
namespace app\config;

class MessageConfig{

    const SIGN_IN = 1000;               //注册验证码
    const FIND_LOGIN_PASSWORD = 1001;   //找回登陆密码
    const FIND_DRAW_PIN = 1002;         //找回提款密码

    private static $config = array(
        /****************用户相关*******************/
        //注册
        '1000' => array(
            'smsTpl'        => '133',
            'emailTpl'      => '206',
            'type'          => 1,   //是否是验证码类型
            'biz'           => 1,
            'timeout'       => 600
        ),

        //找回登陆密码密码
        '1001' => array(
            'smsTpl'        => '133',
            'emailTpl'      => '206',
            'type'          => 1,   //是否是验证码类型
            'biz'           => 1,
            'timeout'       => 600
        ),

        //找回提款密码
        '1002' => array(
            'smsTpl'        => '171',
            'emailTpl'      => '',
            'type'          => 1,
            'biz'           => 2,
            'timeout'       => 600
        ),
        /****************交易相关*******************/
        '2001' => array(

        )
    );


    public static function getConfig($busi)
    {
        return isset(self::$config[$busi])?self::$config[$busi]:array();
    }
}