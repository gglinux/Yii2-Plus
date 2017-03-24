<?php

namespace service\controllers;

use Yii;
use yii\filters\AccessControl;
use service\base\ServiceController;
use yii\filters\VerbFilter;
use Hprose\Http\Server;

use yii\web\Response;
use service\models\MatchStrategy;
use service\models\PreRoom;
use service\models\IdAlloc;

Yii::$app->response->format=Response::FORMAT_JSON;
/**
 * 服务层对外服务 控制器层（HTTP协议）
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
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     *
     * @return string
     */
    public function actionIndex()
    {
        // $server = new Server();
        // $anObject = new User();

        // $server->addInstanceMethods($anObject);
        // return $server->start();
    }


    /**
     * @brif 创建房间
     * @return string
     */
    public function actionTest()
    {
    //    $ret = MatchStrategy::pushUserToQueue([
    //        'user_id' => IdAlloc::allocId(IdAlloc::USER_ID_ALLOC_KEY),
    //        'sex' => 1
    //    ]);

    //     $ret = MatchStrategy::rmUserFromQuere(
    //         [ 
    //             [
    //             'user_id' => 1233,
    //             'sex' => 1
    //         ]
    //    ]);

       return $ret;

    }

    /**
     * @brif 创建房间
     * @return string
     */
    public function actionMatchRoom()
    {
        
        $preRoomId =  MatchStrategy::getPreRoomIdFromQueue();
        //init a room
        if(empty($preRoomId)) {
            //need create room
            var_dump("create pre room");
            $arrPreRoomInfo = MatchStrategy::createNewPreRoom();
             
        } 
        //start
        $arrPreRoomInfo = [
            'pre_room_id' => $preRoomId ,
        ];
        $arrPreRoomInfo = PreRoom::getPreRoomInfo($preRoomId);
        //return 1;
        if(empty($arrPreRoomInfo)) {
            $strLog = __CLASS__ . "::". __FUNCTION__ . " PreRoom::getPreRoomInfo error. ". serialize(compact('arrPreRoomInfo'));
            Yii::error($strLog);
            $ret = MatchStrategy::rmPreRoomIdFromQueue($preRoomId);
            return false;
        }

        $intMc = 0;
        $intWc =0;
        $intSex = 0;
        foreach ($arrPreRoomInfo['user_list'] as $item) {
            if ($item['sex'] == 1) {
                $intMc ++;
            }else {
                $intWc ++;
            }
        }
        if($intMc + $intWc == 5) {
            if ($intMc == 0) {
                //need man
                $intWaitCount = MatchStrategy::getMatchQueueLength(MatchStrategy::REDIS_KEY_MATCH_QUEUE_MAN);
                if ($intWaitCount > 0) {
                    $arrUserIds = MatchStrategy::getUserFromQueue(MatchStrategy::REDIS_KEY_MATCH_QUEUE_MAN);
                    $intSex = 1;
                } else {
                    // man is leak
                    MatchStrategy::createNewPreRoom();
                    Yii::warning("Man is not enough");
                }
                
            } else if($intWc == 0) {
                // need woman
                $intWaitCount = MatchStrategy::getMatchQueueLength(MatchStrategy::REDIS_KEY_MATCH_QUEUE_WOMAN);
                if ($intWaitCount > 0) {
                    $intSex = 2;
                    $arrUserIds = MatchStrategy::getUserFromQueue(MatchStrategy::REDIS_KEY_MATCH_QUEUE_WOMAN);
                } else {
                    // woman is leak
                    MatchStrategy::createNewPreRoom();
                    Yii::warning("Woman is not enough");
                }
            }
        } else {
            //need everyone
            $intManWaitCount = MatchStrategy::getMatchQueueLength(MatchStrategy::REDIS_KEY_MATCH_QUEUE_MAN);
            $intWomanWaitCount = MatchStrategy::getMatchQueueLength(MatchStrategy::REDIS_KEY_MATCH_QUEUE_WOMAN);
            if ($intManWaitCount > $intWomanWaitCount) {
                $intSex = 1;
                $arrUserIds = MatchStrategy::getUserFromQueue(MatchStrategy::REDIS_KEY_MATCH_QUEUE_MAN);
            } else {
                $intSex = 2;
                $arrUserIds = MatchStrategy::getUserFromQueue(MatchStrategy::REDIS_KEY_MATCH_QUEUE_WOMAN);
            }
            var_dump($arrUserIds);
        }
        
        $arrUsersInfo = [];
        foreach($arrUserIds as $item) {
            $arrUsersInfo[] = [
                'user_id' => $item,
                'sex' => $intSex
            ];
        }
        $ret = MainStrategy::addUsersToPreRoom($arrUsersInfo, $preRoomId);
        if(empty($ret)) {
            $strLog = __CLASS__ . "::". __FUNCTION__ . " MainStrategy::addUsersToPreRoom error. ". serialize(compact('arrUsersInfo', 'preRoomId'));
            Yii::error($strLog);
            $ret = MatchStrategy::rmPreRoomIdFromQueue($preRoomId);
            return false;
        }
        var_dump($arrPreRoomInfo);
        Yii::warning(var_export($arrPreRoomInfo,true));

       return $arrPreRoomInfo;

    }



   
}
