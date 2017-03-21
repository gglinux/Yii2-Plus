<?php

namespace service\models;

use service\base\ServiceModel;

class User extends ServiceModel
{
    /**
     * 创建用户
     * @param array $data
     * @return int
     */
    public function hello($name)
    {
        return "Hello $name!";
    }
}