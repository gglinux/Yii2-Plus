<?php

namespace service\models;

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
     * 不使用DB
     * 看这里1！！！！！！！！
     * @param array $data
     * @return int
     */
    public function hello($name)
    {
        return "Hello $name!";
    }

    /**
     * @desc
     * 测试 DB demo
     * 看这里！！！！！！
     * @author guojiawei
     * @update ${date}
     * @access public
     * @param void
     * @return mixed
     */
    public function getAll()
    {
        //具体数据库操作方法，见Yii指南
        $posts = Yii::$app->db->createCommand('SELECT * FROM hjsk_user_base')->queryAll();
        return $posts;
    }
}