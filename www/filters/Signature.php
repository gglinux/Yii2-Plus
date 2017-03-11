<?php
/**
 * 签名过滤
 */
namespace app\filters;
use app\config\Error;
use app\helpers\client\AppClient;
use app\helpers\EncryptTool;
use yii\base\ActionFilter;
use Yii;
use yii\helpers\VarDumper;

defined('YII_REQUEST_START_TIME') or define('YII_REQUEST_START_TIME','');

class Signature extends ActionFilter
{
    public function beforeAction($action)
    {
        return $this->filterSign();
    }
    /**
     * 用以做签名检查
     */
    public function filterSign(){
        Yii::beginProfile('FILTER-SIGN-'.YII_REQUEST_START_TIME);
        $host = Yii::$app->request->getHostInfo();
        $method = Yii::$app->request->getIsPost()?'post':'get';
        $uri = Yii::$app->request->getPathInfo();
        $params = $_REQUEST;
        Yii::info("accept request:METHOD[$method],URI[$uri],params is \r\n ".VarDumper::dumpAsString($params)."]", 'application.service.request');
        if(!isset($params['sign'])) {
            Yii::error("sign check error,no sign data found for uri[$uri]", 'application.service');
            Yii::endProfile('FILTER-SIGN-'.YII_REQUEST_START_TIME);
            Yii::$app->response->error(Error::COMMON_SIGN,'缺少数据签名');
        }
        $secretKey = AppClient::getInstance()->getSecretKey();
        if(is_null($secretKey)) {
            Yii::error("client type not allowed,client info:".VarDumper::dumpAsString(AppClient::getInstance()->info()), 'application.service');
            Yii::endProfile('FILTER-SIGN-'.YII_REQUEST_START_TIME);
            Yii::$app->response->error(Error::COMMON_ILLEGAL_CLIENT,'非法客户端类型');
        }
        $sign = $params['sign'];
        unset($params['sign']);
        ksort($params);
        $signString = $host.'/'.$uri.'&'.$method;
        foreach($params as $key => $value) {
            $signString .= '&'.$key.'='.$value;
        }
        $signString = strtolower($signString);
        \Yii::info('signString:'.$signString.' key:'.$key);
        if(EncryptTool::signIt($signString, $secretKey) != $sign) {
            Yii::error("sign check error,signString:{$signString},secretKey:{$secretKey},{$sign}", 'application.service');
            Yii::endProfile('FILTER-SIGN-'.YII_REQUEST_START_TIME);
            Yii::$app->response->error(Error::COMMON_SIGN,'签名错误');
        }
        Yii::endProfile('FILTER-SIGN-'.YII_REQUEST_START_TIME);
        return true;
    }
}