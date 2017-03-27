<?php

namespace service\modules\match\controllers;


use Yii;
use service\base\ServiceController;
use Hprose\Http\Server;

use yii\web\Response;
use service\modules\match\services\MatchService;
use service\modules\room\models\ar\PreRoom;
use service\modules\room\models\ar\IdAlloc;
use service\modules\room\models\ar\Room;


Yii::$app->response->format=Response::FORMAT_JSON;
/**
 * 请继承 ServiceController
 * Class SiteController
 * @package service\controllers
 */
class MatchController extends ServiceController
{

    public $enableCsrfValidation = false;

    public static $defaultIntValue = 1;

    /**
     * @inheritdoc
     */
    public function actions()
    {

    }

    /**
     *
     * @return string
     */
    public function actionIndex()
    {
        $server = new Server();
        $service = new MatchService();
        $server->addMethods([
            'joinMatch',
            'cancelMatch'
        ], $service);
        return $server->start();
    }
    public function actionTest()
    {
        return MatchService::sayHi();
    }


    public function actionSendMessage(){

        $client = new \Hprose\Http\Client(Yii::$app->params['HproseNodeServiceHost'], false);
        $arrMsgList = [
            [
                "userId" => 123,
                "cmd"  => "Msg",
                "data" => [
                    'hh'=> 'aaaa',
                ]
            ]
        ];
        $ret = $client->commitMsgToClients($arrMsgList);
        return $ret;

    }


    /**
     * @brif 队列增加人数
     * @return string
     */
    public function actionAddUserToQueue($sex)
    {
       $ret = MatchService::pushUserToQueue([
           'user_id' => IdAlloc::allocId(IdAlloc::USER_ID_ALLOC_KEY),
           'sex' => $sex
       ]);
       return $ret;

    }

    /**
     * @brif 队列增加人数
     * @return string
     */
    public function actionGetUserPreRoomInfo(array $user_ids)
    {
       $ret = PreRoom::getBatchUserPreRoomId($user_ids);
    
       $ret = PreRoom::getBatchPreRoomInfo($ret);
        
       return $ret;

    }

    /**
     * @brif 创建房间
     * @return string
     */

    public function actionMatchRoom()
    {
        MatchService::matchRoom();
    }

}
