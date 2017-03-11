<?php
/**
 * 登录状态过滤
 */
namespace app\filters;
use app\components\SessionCan;
use app\config\Error;
use app\helpers\client\AppClient;
use app\service\UserService;
use yii\base\ActionFilter;
use Yii;
use yii\helpers\VarDumper;

defined('YII_REQUEST_START_TIME') or define('YII_REQUEST_START_TIME','');

class Session extends ActionFilter
{
    public function beforeAction($action)
    {
        return $this->filterSession();
    }

    /**
     * 用以检查用户session时候有效
     */
    public function filterSession()
    {
        Yii::beginProfile('FILTER-SESSION-'.YII_REQUEST_START_TIME);
        $uid = isset($_REQUEST['uid']) ? $_REQUEST['uid'] : null;
        $sid = isset($_REQUEST['sid']) ? $_REQUEST['sid'] : null;

        Yii::info("session check for uid[{$uid}],sid[{$sid}]", 'application.service');
        //从ocs里面取出session信息
        $userInfo = Yii::$app->ocs->get($sid);
        $sessionInfo = null;
        do{
            //session信息还在
            if(!empty($userInfo) && isset($userInfo['uid']) && $userInfo['uid'] == $uid) {
                break;
            }
            $client = AppClient::getInstance();
            //尝试从数据库中恢复
            if($client->getPlatForm() != AppClient::PL_WAP &&
                !is_null($sessionInfo = UserService::instance()->restoreSession($uid, $sid, $client))
            ) {
                $userInfo = Yii::$app->ocs->get($sessionInfo);
                Yii::$app->response->session = $sessionInfo;
                Yii::info(
                    "get new sid[{$sessionInfo}] for uid[{$uid}],userinfo are:\r\n ".VarDumper::dumpAsString($userInfo),
                    'applicaiton.service'
                );
                break;
            }
            Yii::endProfile('FILTER-SESSION-'.YII_REQUEST_START_TIME);
            Yii::$app->response->error(Error::USER_NEED_LOGIN,'请先登录');
            return false;
        }while(False);
        SessionCan::init($userInfo);
        Yii::info("session check for uid[{$uid}],sid[{$sid}] complete,
                new sid [{$sessionInfo}],userInfo from passport:\r\n".VarDumper::dumpAsString($userInfo),
            'application.service'
        );
        //配资需要注册用户
        if(isset(Yii::$app->controller->module->module->id) && Yii::$app->controller->module->module->id === 'withfunding')
            UserService::instance()->newUser($uid, $userInfo['phone'], $userInfo['email'], $userInfo['refer']);
        Yii::endProfile('FILTER-SESSION-'.YII_REQUEST_START_TIME);
        return true;
    }
}