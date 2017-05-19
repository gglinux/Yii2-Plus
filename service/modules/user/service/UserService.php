<?php
/**
 * Created by PhpStorm.
 * User: guojiawei
 * Date: 2017/5/19
 * Time: 下午1:44
 */

namespace service\modules\user\service;

use common\base\Error;
use service\base\BaseService;
use service\base\ServiceException;

class UserService extends BaseService
{
    private $users = [
        123 => [
            'name' =>'jack',
            'age' =>'24'
        ],
        234 => [
            'name' => 'tom',
            'age' => '26'
        ]

    ];

    /**
     *
     * @param $uid int 用户uid
     * @return array|mixed
     * @throws ServiceException
     */
    public function getUserInfo($uid)
    {
        if (empty($uid) || !is_numeric($uid)) {
            throw new ServiceException('参数错误',Error::COMMON_INVALID_PARAM);
        }
        if (is_array($this->users[$uid])) {
            return $this->users[$uid];
        }
        return [];
    }

}