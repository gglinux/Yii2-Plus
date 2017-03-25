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
     * @brif 队列增加人数
     * @return string
     */
    public function actionAddUserToQueue($sex)
    {
       $ret = MatchStrategy::pushUserToQueue([
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
        //clear redis key 
        //del pre_room_info user_pre_room match_queue_man match_queue_woman match_queue_pre_room
        //目前是单进程模式
        
        //获取队列中的预备房间ID
        $intPreRoomId =  MatchStrategy::getPreRoomIdFromQueue();
        
        //获取等待队列长度
        $intPreRoomCount = MatchStrategy::getPreRoomQueueLength(MatchStrategy::REDIS_KEY_MATCH_QUEUE_PRE_ROOM);
        $intManWaitCount = MatchStrategy::getMatchQueueLength(MatchStrategy::REDIS_KEY_MATCH_QUEUE_MAN);
        $intWomanWaitCount = MatchStrategy::getMatchQueueLength(MatchStrategy::REDIS_KEY_MATCH_QUEUE_WOMAN);
        
        var_dump("current pre room id $intPreRoomId");

        var_dump("current intPreRoomCount $intPreRoomCount");
        var_dump("current intManWaitCount $intManWaitCount");
        var_dump("current intWomanWaitCount $intWomanWaitCount");
        if($intPreRoomId) {
            $arrPreRoomInfo = PreRoom::getPreRoomInfo($intPreRoomId);
            var_dump('当前预备房间匹配信息');
            var_dump($arrPreRoomInfo);
        }
        if($intWomanWaitCount == 0 && $intManWaitCount == 0) {
            //no user wait
            var_dump("no user wait");
            return true;
        }
        //init a room
        if(empty($intPreRoomId)) {
            //need create room
            var_dump("pre room is empty");
            var_dump("create pre room");
            $arrPreRoomInfo = MatchStrategy::createNewPreRoom();
            $intPreRoomId = $arrPreRoomInfo['pre_room_id'];
            var_dump($arrPreRoomInfo);
        } 
        //start
        $arrPreRoomInfo = PreRoom::getPreRoomInfo($intPreRoomId);
        var_dump($arrPreRoomInfo);
        
        if(empty($arrPreRoomInfo)) {
            $strLog = __CLASS__ . "::". __FUNCTION__ . " PreRoom::getPreRoomInfo error. ". serialize(compact('arrPreRoomInfo'));
            Yii::error($strLog);
            $ret = MatchStrategy::rmPreRoomIdFromQueue($intPreRoomId);
            return false;
        }
        var_dump("current pre room info");
        var_dump($arrPreRoomInfo);
        $intRetry = 0;
        while(1) {
            if ($intRetry > 7) {
                var_dump("重试次数达到7次，进行下一个预备房间处理");
                break;
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
            $intMWCount = $intMc + $intWc;
            $arrUserIds = [];
            if ($intMWCount == 5 && $intMc == 0) {
                var_dump("need man");
                //need man
                if ($intManWaitCount > 0) {
                    $arrUserIds = MatchStrategy::getUserFromQueue(MatchStrategy::REDIS_KEY_MATCH_QUEUE_MAN);
                    $intSex = 1;
                } else {
                    // man is leak
                    var_dump("man is leak");
                    MatchStrategy::createNewPreRoom();
                    Yii::warning("Man is not enough");
                }
                
            } else if($intMWCount == 5 && $intWc == 0) {
                // need woman
                var_dump("need woman");
                if ($intWomanWaitCount > 0) {
                    $intSex = 2;
                    $arrUserIds = MatchStrategy::getUserFromQueue(MatchStrategy::REDIS_KEY_MATCH_QUEUE_WOMAN);
                } else {
                    // woman is leak
                    var_dump("woman is leak");
                    MatchStrategy::createNewPreRoom();
                    Yii::warning("Woman is not enough");
                }
            } else {
                //need everyone
                var_dump("need everyone");
                if ($intManWaitCount > $intWomanWaitCount) {
                    $intSex = 1;
                    $arrUserIds = MatchStrategy::getUserFromQueue(MatchStrategy::REDIS_KEY_MATCH_QUEUE_MAN);
                } else {
                    $intSex = 2;
                    $arrUserIds = MatchStrategy::getUserFromQueue(MatchStrategy::REDIS_KEY_MATCH_QUEUE_WOMAN);
                }
                
            }
            var_dump("match uids");
            var_dump($arrUserIds);
            var_dump("match sex $intSex");
            
            $arrUserInfos = [];
            foreach($arrUserIds as $item) {
                $arrUserInfos[] = [
                    'user_id' => $item,
                    'sex' => $intSex,
                ];
            }
            var_dump('MatchStrategy::addUsersToPreRoom');
            if (count($arrUserInfos) === 0) {
                var_dump("没有匹配符合条件的用户，结束此次匹配");
                break;
            }
            $arrPreRoomInfo = MatchStrategy::addUsersToPreRoom($arrUserInfos, $intPreRoomId);
            if(false === $arrPreRoomInfo) {
                $strLog = __CLASS__ . "::". __FUNCTION__ . " MatchStrategy::addUsersToPreRoom error. ". serialize(compact('arrUsersInfo', 'intPreRoomId'));
                Yii::error($strLog);
                return false;
            }
            var_dump($arrPreRoomInfo);
            MatchStrategy::rmUserFromQuere($arrUserInfos);
            if(count($arrPreRoomInfo['user_list']) == 6) {
                //success
                $strLog = 'pre room reach 6 persion success';
                Yii::info($strLog);
                //push message
                $arrUserIds= [];
                foreach($arrPreRoomInfo['user_list'] as $item) {
                    $arrUserIds[] = $item['user_id'];
                }
                var_dump("人数达标，删除预备房间，开始正式群聊，结束此次匹配");
                MatchStrategy::rmPreRoomIdFromQueue($intPreRoomId);
                PreRoom::rmBatchUserPreRoomId($arrUserIds);
                PreRoom::rmPreRoomInfo($intPreRoomId);
                // create room
                break;
            }  else {
                foreach($arrUserInfos as $item) {
                    $arrUserIds[] = $item['user_id'];
                }
                if (count($arrUserIds) > 0) {
                    PreRoom::setBatchUserPreRoomId($arrUserIds, $intPreRoomId);
                }
                $intRetry ++;
                var_dump("人数不够，进行再次取队列 $intRetry");
                continue;
            }
        }
        

        
        Yii::warning(var_export($arrPreRoomInfo,true));
        return $arrPreRoomInfo;

    }
   



   
}
