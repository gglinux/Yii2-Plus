<?php
/**
 * Created by PhpStorm.
 * User: guojiawei
 * Date: 2017/3/26
 * Time: 下午6:46
 */

namespace common\components;


use common\base\Exception;
use Firebase\JWT\SignatureInvalidException;

class Jwt extends \Firebase\JWT\JWT
{
    /**
     * JWT 加密类型
     */
    const JWT_ALGORITHM_METHOD = "HS256";
    /**
     * JWT 有效期
     * 单位：天
     */
    const JWT_EXPIRESIN = 90;

    /**
     * JWT更新频率
     * 单位：天
     */
    const JWT_UPDATE = 30;

    /**
     * @param $token
     * token {
     *  'info'=>[
     *          'uid' => $uid,
     *          'other'=> $other
     *      ]
     *  'iss'=>'hjsk'
     *  'iat'=> 颁发时间
     *  'exp'=> 失效时间
     * }
     */
    private static $info;

    /**
     * @param $token
     * @return mixed|null
     */
    public static function getJwtInfo($token)
    {
        if ( !empty(self::$info) ) {
            return self::$info;
        }
        $jwt_key = \Yii::$app->params['jwtKey'];
        $jwt_algorithm = self::JWT_ALGORITHM_METHOD;
        $jwt_expiresIn = self::JWT_EXPIRESIN;
        $decode_array = (array)self::decode($token, $jwt_key, [$jwt_algorithm]);
        if (empty($decode_array['exp']) || $decode_array['exp'] < time()) {
            return null;
        }
        self::$info = $decode_array['info'];
        return (array)$decode_array['info'];
    }

    public static function updateJwt($token)
    {
        if ( !empty(self::$info) ) {
            return false;
        }
        $jwt_key = \Yii::$app->params['jwtKey'];
        $jwt_algorithm = self::JWT_ALGORITHM_METHOD;
        $jwt_expiresIn = self::JWT_EXPIRESIN;
        $decode_array = (array) self::decode($token, $jwt_key, [$jwt_algorithm]);
        if (empty($decode_array['exp']) || $decode_array['exp'] < time()) {
            throw new Exception('Jwt token已经过期！');
        }
        if ((time()-$decode_array['iat']) > self::JWT_UPDATE * 86400) {
            $info = $decode_array['info'];
            return self::createJwt($info);
        }
        return false;

    }

    /**
     * @param $info
     * @return string
     * @throws Exception
     */
    public static function createJwt($info)
    {
        $jwt_key = \Yii::$app->params['jwtKey'];
        $jwt_algorithm = self::JWT_ALGORITHM_METHOD;
        if (empty($info['uid'])) {
            throw new Exception('生成Jwt失败');
        }
        $token_info = [
            'info' => $info,
            'iss' => 'hjsk',
            'iat' => time(),
            'exp' => (time() + self::JWT_EXPIRESIN*86400)
        ];
        return self::encode($token_info,$jwt_key,$jwt_algorithm);
    }


}