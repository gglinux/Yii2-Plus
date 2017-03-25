<?php
/**
 * Created by PhpStorm.
 * User: guojiawei
 * Date: 2017/3/25
 * Time: 下午1:25
 * 欢聚时刻 service 响应组件
 */

namespace common\components;

use Yii;
use yii\web\Response;
use common\base\Error;
use common\base\Exception;


class ServiceResponse extends Response
{
    /**
     * RPC 返回错误信息
     * @param string $code
     * @param string $message
     * @return $this
     */
    public function error($code, $message = null)
    {
        if (empty($message)) {
            $message = (new Error())->getErrorMsg($code);
        }
        $this->format = self::FORMAT_JSON;
        if(empty($code)) {
            throw new Exception('错误码参数为空！');
        }
        $res = [
            'errorCode'=>$code,
            'errorMsg'=>$message,
            'nonce'=>time()
        ];

        array_walk_recursive($res, function(&$v){
            $v = strval($v);
        });

        $this->data = [
            'ret' => false,
            'res' => $res
        ];

        Yii::$app->end();
    }

    /**
     * RPC 返回成功信息
     * @param string $data
     * @return $this
     */
    public function success($data='')
    {
        $this->format = self::FORMAT_JSON;
        $res = [];
        if(is_array($data) || is_object($data)){
            $res['data'] = \yii\helpers\ArrayHelper::toArray($data);
        }elseif(!empty($data)){
            $res['data'] = $data;
        }
        $res['nonce'] = time();

        array_walk_recursive($res, function(&$v){
            $v = strval($v);
        });

        $this->data = [
            'ret' => true,
            'res' => $res
        ];

        Yii::$app->end();
    }

}