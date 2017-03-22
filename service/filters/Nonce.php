<?php
/**
 * 防重放攻击过滤
 */
namespace app\filters;

use app\config\Error;
use app\helpers\client\AppClient;
use yii\base\ActionFilter;
use Yii;

class Nonce extends ActionFilter
{
    public function beforeAction($action)
    {
        return $this->filterNonce();
    }

    public function filterNonce()
    {
        if(AppClient::getInstance()->getVersion() == '1.0.0') {
            return true;
        }
        if(Yii::$app->request->getIsPost()) {
            $nonce = Yii::$app->request->post('c_nonce',null);
            if($nonce == null) {
                Yii::$app->response->error(Error::COMMON_NONCE_ERROR,'请求已过期，请重试!');
            }
            if((time()-$nonce) > Yii::$app->params['nonceTime']) {
                Yii::$app->response->error(Error::COMMON_NONCE_ERROR,'请求已过期，请重试!');
            }
        }
        return true;
    }
}