<?php
/**
 * 登录状态过滤
 */
namespace api\filters;

use Yii;
use common\components\SessionCan;
use common\base\Error;
use common\helpers\client\AppClient;
use Common\components\Jwt;
use yii\base\ActionFilter;
use yii\helpers\VarDumper;
use Firebase\JWT\SignatureInvalidException;

class AccessToken extends ActionFilter
{
    public function beforeAction($action)
    {
        $this->filterAccessToken();
        return parent::beforeAction($action);
    }

    /**
     * 用以检查用户access_token有效
     */
    public function filterAccessToken()
    {
        $token = isset($_REQUEST['access_token']) ? $_REQUEST['access_token'] : null;

        Yii::info("access_token check for toekn[{$token}]", 'application.service');
        //从token中恢复信息
        $userinfo = null;
        //session信息还在
        AppClient::getInstance();
        //尝试从token中恢复用户信息
        try {
            $userinfo = Jwt::getJwtInfo($token);
        } catch (SignatureInvalidException $exception) {
            Yii::$app->response->error(Error::COMMON_SIGN,'签名错误');
        }
        if ( empty($userinfo) ||empty($userinfo['uid']) ) {
            Yii::endProfile('FILTER-SESSION-'.YII_REQUEST_START_TIME);
            Yii::$app->response->error(Error::USER_NEED_LOGIN,'请先登录');
        }
        SessionCan::init($userinfo);

        Yii::info("{$token} check for uid[{$userinfo['uid']}], complete,
                userInfo from passport:\r\n".VarDumper::dumpAsString($userinfo),
            'application.service'
        );
        return true;
    }
}