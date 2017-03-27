<?php
/**
 * Created by PhpStorm.
 * User: guojiawei
 * Date: 2017/3/26
 * Time: 下午6:39
 */

namespace common\helpers\client;

/**
 * 客户端类型
 * Class Client
 * @package common\helpers\client
 */
abstract class Client {

    private  static $_INSTANCE;

    const IOS           = 1;
    const ANDROID       = 2;
    const WP            = 3;
    const WINDOWS       = 4;

    const PL_IOS        = 1;
    const PL_ANDROID    = 2;
    const PL_WAP        = 3;


    /**
     * 隐藏构造方法
     */
    protected  function __construct($param = array())
    {
        $this->init($param);
    }

    /**
     * 隐藏克隆方法
     */
    private function __clone()
    {

    }

    /**
     * 单例模式
     * @param $param
     * @return mixed
     */
    public static function getInstance($param=array()){
        do {
            if(self::$_INSTANCE != null) {
                break;
            }
            $class = get_called_class();
            self::$_INSTANCE = new $class($param);
        }while(False);
        return self::$_INSTANCE;
    }

    /**
     * 初始化函数
     * @return mixed
     */
    abstract public function init($param = array());
    /**
     * @return mixed 获取操作系统
     */
    abstract public function getSystem();

    /**
     * @return mixed 获取客户端唯一id
     */
    abstract public function getIdentity();


    /**
     * @return mixed 获取客户端版本号
     */
    abstract public function getVersion();

    /**
     * @return mixed 获取客户端平台(pc,android,ios,wap)
     */
    abstract public function getPlatForm();


}