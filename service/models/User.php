<?php

namespace service\models;

use common\base\Exception;
use service\base\ServiceException;
use Yii;
//请继承，serviceModel
use service\base\ServiceModel;

/**
 * Class User
 * 用户服务层代码
 * @package service\models
 */
class User extends ServiceModel
{
    /**
     * 测试RPC方法一
     * 非DB demo
     * 看这里1！！！！！！！！
     * @param array $data
     * @return int
     */
    public function hello($name)
    {
        return "Hello $name!";
    }

    /**
     * 测试RPC方法二
     * DB demo
     * @author guojiawei
     * @update ${date}
     * @access public
     * @param void
     * @return mixed
     */
    public function getAll()
    {
        //具体数据库操作方法，见Yii指南
        $posts = Yii::$app->db_user->createCommand('SELECT * FROM XXX')->queryAll();
        return $posts;
    }

    /**
     * 测试RPC方法三
     * 抛异常
     * @throws ServiceException
     */
    public function TestException()
    {
        throw new ServiceException('测试异常调用',1000);
    }
}