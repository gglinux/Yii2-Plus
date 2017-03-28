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

    /**
     * test
     * @return mixed
     */
    public function actionTest()
    {
        return MatchService::sayHi();
    }

    /**
     * test
     * @return mixed
     */
    public function actionSendMessage(){

        $client = new \Hprose\Http\Client(Yii::$app->params['HproseNodeServiceHost'], false);
        $arrMsgList = [
            [
                "roomId" => 39,
                "cmd"  => "showWord",
                "data" => [
                    'hh'=> 'aaaa',
                ]
            ]
        ];
        $ret = $client->commitMsgToClients($arrMsgList);
        return $ret;

    }

    /**
     * test
     * @return mixed
     */
    public function actionMatching(){
        MatchService::matchRoom();
        return true;
    }
}
