<?php
/**
 * Created by PhpStorm.
 * User: guojiawei
 * Date: 2017/3/25
 * Time: 下午3:01
 */

namespace common\base;

use common\base\Error;
use yii\helpers\VarDumper;
use Yii;

defined('YII_REQUEST_START_TIME') or define('YII_REQUEST_START_TIME','');


class BaseService
{
    private static $_INSTANCE = array();

    //私有化构造函数
    private function __construct()
    {

    }

    //禁止克隆
    public function __clone(){
        throw new \yii\base\Exception('Clone is not allow!');
    }

    /**
     * @return $this
     */
    public static function instance()
    {
        $class = get_called_class();
        if(!isset(self::$_INSTANCE[$class])) {
            self::$_INSTANCE[$class] = new $class();
        }
        return self::$_INSTANCE[$class];
    }

    /**
     *
     * 1. 记录service请求日志
     * 2. 报警提示信息
     * 3. 记录服务端接口性能
     *
     * @param $name
     * @param $parameter
     * @return mixed
     * @throws \hjsk\base\Exception
     */
    public function __call($name,$parameter)
    {
        Yii::beginProfile('SERVICE-'.get_class($this).'-'.$name.'-'.YII_REQUEST_START_TIME);
        //记录日志
        $serStr = get_class($this).'/'.$name;

        try {
            $reqStr = '['.VarDumper::dumpAsString($parameter).']';
        } catch (\Exception $e) {
            $reqStr = 'data can\'t be serialized';
        }

        try{
            $serMethod = 'ser'.$name;
            if(method_exists($this,$serMethod)) {
                $data = call_user_func_array(array($this,$serMethod),$parameter);
            } else {
                throw new \hjsk\base\Exception('请求的方法不存在');
            }
            $this->info($serStr.' '.$reqStr.' ['.VarDumper::dumpAsString($data).']');
        } catch (\hjsk\base\Exception $e) {
            Yii::endProfile('SERVICE-'.get_class($this).'-'.$name.'-'.YII_REQUEST_START_TIME);
            $this->error($serStr.' '.$reqStr.' message: '.$e->getMessage(). ' '.$e->getCode());
            throw $e;
        } catch(\yii\db\Exception $e) {
            Yii::endProfile('SERVICE-'.get_class($this).'-'.$name.'-'.YII_REQUEST_START_TIME);
            $this->error($serStr.' '.$reqStr.' message: 数据库故障！');
            throw new \hjsk\base\Exception('My God！数据库故障！');
        } catch(\yii\base\Exception $e) {
            $this->error($serStr.' '.$reqStr.' message: 服务器故障！！');
            Yii::endProfile('Service-'.get_class($this).'-'.$name);
            throw new \hjsk\base\Exception('My Word！！服务器故障！');
        }
        Yii::endProfile('SERVICE-'.get_class($this).'-'.$name.'-'.YII_REQUEST_START_TIME);
        return $data;
    }

    protected function error($msg){
        Yii::error($msg, 'service.'.get_class($this));
    }

    protected function info($msg){
        Yii::info($msg, 'service.'.get_class($this));
    }

    protected function trace($msg){
        Yii::trace($msg, 'service.'.get_class($this));
    }

    protected function warn($msg){
        Yii::warning($msg, 'service.'.get_class($this));
    }
}