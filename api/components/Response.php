<?php

/**
 * Created by PhpStorm.
 * User: guojiawei
 * Date: 2017/3/26
 * Time: 下午4:48
 */
namespace api\components;

use Yii;
use yii\base\Exception;

class Response extends \yii\web\Response
{
    public function init()
    {
        parent::init();
    }
    /**
     * API 返回错误信息
     * @param string $code
     * @param string $message
     * @return $this
     */
    public function error($code, $message = null)
    {
        $this->format = self::FORMAT_JSON;
        if(empty($code)) {
            throw new Exception('错误码参数为空！');
        }

        $res = [
            'errno' => $code,
            'errmsg' => $message,
            'timestamp' => time()
        ];

        array_walk_recursive($res, function(&$v){
            $v = strval($v);
        });

        $this->data = $res;

        Yii::$app->end();
    }

    /**
     * API 返回成功信息<有返回值>
     * @param string $data
     * @return $this
     */
    public function success($data = '')
    {
        $this->format = self::FORMAT_JSON;
        $res = [];
        if(is_array($data) || is_object($data)){
            $res['data'] = \yii\helpers\ArrayHelper::toArray($data);
        } elseif(!empty($data)){
            $res['data'] = $data;
        }

        $res['errno'] = 0;
        $res['errmsg'] = 'success';
        $res['timestamp'] = time();

        array_walk_recursive($res, function(&$v){
            $v = strval($v);
        });

        $this->data = $res;

        Yii::$app->end();
    }

    /**
     * API 操作成功信息《无返回值》
     * @param string $data
     * @return $this
     */
    public function ok()
    {
        $this->format = self::FORMAT_JSON;
        $res = [];

        $res['errno'] = 0;
        $res['errmsg'] = 'ok';
        $res['timestamp'] = time();

        array_walk_recursive($res, function(&$v){
            $v = strval($v);
        });

        $this->data = $res;

        Yii::$app->end();
    }



}