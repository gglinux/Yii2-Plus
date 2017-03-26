<?php
/**
 * Created by PhpStorm.
 * User: guojiawei
 * Date: 2017/3/25
 * Time: 下午8:27
 */

namespace service\base;


use yii\base\Component;

class BaseService extends Component
{
    /**
     * 由于Hprose区分具体的异常
     * 需要返回给客户端的信息：请调用此方法！
     * @param string $msg
     * @param int $code
     * @return array
     */
    public function error($msg = '', $code = 0)
    {
        return ['msg' => $msg, 'code' => $code];
    }
}