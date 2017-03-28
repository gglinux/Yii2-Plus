<?php

namespace service\modules\match\services;

use Yii;


use service\modules\room\models\PreRoom;
use service\modules\match\models\Match as ModelMatch;
use service\modules\room\services\RoomService;
use service\modules\common\services\CommonService;
use common\base\Exception;
use service\base\BaseService;

/**
 * Class Match
 * 匹配策略
 * @package service\models
 */
class MatchService extends BaseService
{
    public static function sayHi(){
        echo 'hi';
    }
    /**
     * 将用户添加到匹配队列
     * @param int $userId
     */
    public static function joinMatch ($intUserId) {
        if(empty($intUserId)) {
            throw new Exception('参数错误');
        }

        //TODO 需要获取用户信息

        $arrUserInfo = [
            'user_id' => $intUserId,
            'sex' => $intUserId == 123 || $intUserId == 234 ? 1 : 2,
            'is_master' => 1,
        ];

        return ModelMatch::pushUserToQueue($arrUserInfo);
    }

    /**
     * @param array $arrUserInfo
     * @return \service\modules\match\models\ar\arran
     * @throws Exception
     */
    public static function cancelMatch ($intUserId) {
        if(empty($intUserId)) {
            throw new Exception('参数错误');
        }


        //获取用户id
        $intPreRoomId = PreRoom::getBatchUserPreRoomId([$intUserId]);
        $intPreRoomId = $intPreRoomId[0];
        //TODO 需要获取用户信息
        $liteUserInfo = [];
        if ($intPreRoomId) {
            $arrPreRoomInfo = PreRoom::getPreRoomInfo($intPreRoomId);
            Yii::warning(serialize($arrPreRoomInfo));
            if($arrPreRoomInfo['user_list']) {
                foreach($arrPreRoomInfo['user_list'] as $item) {
                    if($item['user_id'] == $intUserId){
                        $liteUserInfo = $item;
                        break;
                    }
                }
            }

            PreRoom::rmBatchUserPreRoomId([$intUserId], $intPreRoomId);
            ModelMatch::rmUserFromPreRoom($intUserId, $intPreRoomId);

        } else {

            ModelMatch::rmBatchUserFromQueue([
                [
                    'user_id' => $intUserId,
                    'sex' => 1
                ]
            ]);
        }

        return true;
    }
    /**
     * 匹配主方法
     * @return string
     */

    public static function matchRoom()
    {
        //clear redis key 
        //del pre_room_info user_pre_room match_queue_man match_queue_woman match_queue_pre_room
        //目前是单进程模式
        
        //获取队列中的预备房间ID pop
        $intPreRoomId =  ModelMatch::popPreRoomIdFromQueue();
        
        //获取等待队列长度
        $intPreRoomCount = ModelMatch::getQueueLength(ModelMatch::REDIS_KEY_MATCH_QUEUE_PRE_ROOM);
        $intManWaitCount = ModelMatch::getQueueLength(ModelMatch::REDIS_KEY_MATCH_QUEUE_MAN);
        $intWomanWaitCount = ModelMatch::getQueueLength(ModelMatch::REDIS_KEY_MATCH_QUEUE_WOMAN);
        
        var_dump("current pre room id $intPreRoomId");

        var_dump("current intPreRoomCount $intPreRoomCount");
        var_dump("current intManWaitCount $intManWaitCount");
        var_dump("current intWomanWaitCount $intWomanWaitCount");
        if($intPreRoomId) {
            //获取预备房间信息
            $arrPreRoomInfo = PreRoom::getPreRoomInfo($intPreRoomId);

            //var_dump($arrPreRoomInfo);
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
            //如果队列中没有预备房间,则创建一个
            $arrPreRoomInfo = ModelMatch::createNewPreRoom();
            $intPreRoomId = $arrPreRoomInfo['pre_room_id'];
            //var_dump($arrPreRoomInfo);
        } 
        //start
        //获取预备房间的信息
        // $arrPreRoomInfo = PreRoom::getPreRoomInfo($intPreRoomId);
        var_dump('当前预备房间匹配信息');
        var_dump($arrPreRoomInfo);
        
        if(empty($arrPreRoomInfo)) {
            $strLog = __CLASS__ . "::". __FUNCTION__ . " PreRoom::getPreRoomInfo error. ". serialize(compact('arrPreRoomInfo'));
            Yii::error($strLog);
            //如果获取到的预备房间是无效的,则把预备房间从匹配队列删除
            $ret = ModelMatch::rmPreRoomIdFromQueue($intPreRoomId);
            return false;
        }
        var_dump("current pre room info");
        var_dump($arrPreRoomInfo);
        $intRetry = 0;
        while(1) {
            $arrUserInfos = [];
            $intUserId= 0;
            if ($intRetry > 7) {
                var_dump("重试次数达到7次，进行下一个预备房间处理");
                ModelMatch::pushPreRoomIdToQueue($intPreRoomId);
                break;
            }
            if(count($arrPreRoomInfo['user_list']) < 6) {
                
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
                        //获取一个队列中的用户
                        $intUserId = ModelMatch::popUserFromQueue(ModelMatch::SEX_MAN);
                        $intSex = 1;
                    } else {
                        // man is leak
                        var_dump("man is lack");
                        // 当前匹配的预备房间已经放不下了,需要重新建立预备房间
                        ModelMatch::createNewPreRoom();
                        Yii::warning("Man is not enough");
                    }
                    
                } else if($intMWCount == 5 && $intWc == 0) {
                    // need woman
                    var_dump("need woman");
                    if ($intWomanWaitCount > 0) {
                        $intSex = 2;
                        //获取一个队列中的用户
                        $intUserId = ModelMatch::popUserFromQueue(ModelMatch::SEX_WOMAN);
                    } else {
                        // woman is leak
                        var_dump("woman is lack");
                        // 当前匹配的预备房间已经放不下了,需要重新建立预备房间
                        ModelMatch::createNewPreRoom();
                        Yii::warning("Woman is not enough");
                    }
                } else {
                    //need everyone
                    var_dump("need everyone");
                    //获取一个队列中的用户
                    if ($intManWaitCount > $intWomanWaitCount) {
                        $intSex = 1;
                        $intUserId = ModelMatch::popUserFromQueue(ModelMatch::SEX_MAN);
                    } else {
                        $intSex = 2;
                        $intUserId = ModelMatch::popUserFromQueue(ModelMatch::SEX_WOMAN);
                    }
                }
                var_dump("match uid");
                var_dump($intUserId);
                var_dump("match sex $intSex");




                if ($intUserId == 0) {
                    var_dump("没有匹配符合条件的用户，结束此次匹配");
                    ModelMatch::pushPreRoomIdToQueue($intPreRoomId);
                    break;
                }

                //需要获取用户信息
                $arrUserInfo = [
                    'user_id' => $intUserId,
                    'sex' => $intSex,
                    'is_master' => 1,
                ];

                var_dump($arrUserInfos);

                //将匹配到的用户存储到预备房间信息里
                $arrPreRoomInfo = ModelMatch::addUsersToPreRoom([$arrUserInfo], $intPreRoomId);

                if(false === $arrPreRoomInfo) {
                    $strLog = __CLASS__ . "::". __FUNCTION__ . " ModelMatch::addUsersToPreRoom error. ". serialize(compact('arrUsersInfo', 'intPreRoomId'));
                    Yii::error($strLog);
                    ModelMatch::pushPreRoomIdToQueue($intPreRoomId);
                    ModelMatch::pushUserToQueue($arrUserInfo);
                    return false;
                }
                $arrMsgList = [];
                foreach($arrPreRoomInfo['user_list'] as $item) {
                    $arrMsgList[] = [
                        'userId' => $item['user_id'],
                        'cmd'   => "matchUser",
                        'data'  => $arrPreRoomInfo['user_list']
                    ];
                }
                //向客户端发送匹配到用户的消息
                $client = CommonService::serviceClient("/", 'node');
                $ret = $client->commitMsgToClients($arrMsgList);
                if(false == $ret) {
                    $strLog = __CLASS__ . "::". __FUNCTION__ . " Hprose call commitMsgToClients error. ". serialize(compact('arrMsgList', 'ret'));
                    Yii::error($strLog);
                    //return false;
                }
                var_dump($arrPreRoomInfo);
                //将匹配到的用户从匹配队列里删除 不用了,已经pop出来了
                //ModelMatch::rmBatchUserFromQueue([$arrUserInfo]);
            }
            
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
                //创建房间
                $intRoomId = RoomService::createNewRoom($arrPreRoomInfo);
                if(false === $ret) {
                    $strLog = __CLASS__ . "::". __FUNCTION__ . " call Room createNewRoom error. ". serialize(compact('ret', 'arrPreRoomInfo'));
                    Yii::error($strLog);
                    ModelMatch::pushPreRoomIdToQueue($intPreRoomId);
                    return false;
                }
                $arrMsgList = [];
                foreach($arrPreRoomInfo['user_list'] as $item) {
                    $arrMsgList[] = [
                        'userId' => $item['user_id'],
                        'cmd'   => "readyRoom",
                        'data'  => [
                            'roomId' => $intRoomId,
                            'userList' => $arrPreRoomInfo['user_list']
                        ]
                    ];
                }
                //向客户端发送匹配成功,进入房间的信息
                $client = CommonService::serviceClient("/", 'node');
                $ret = $client->commitMsgToClients($arrMsgList);
                if(false == $ret) {
                    $strLog = __CLASS__ . "::". __FUNCTION__ . " Hprose call commitMsgToClients error. ". serialize(compact('arrMsgList', 'ret'));
                    Yii::error($strLog);
                    //return false;
                }

                //把预备房间从队列里删除 已经pop出去了,就不用rm了
                //ModelMatch::rmPreRoomIdFromQueue($intPreRoomId);
                //删除用户的匹配房间信息
                PreRoom::rmBatchUserPreRoomId($arrUserIds);
                //删除预备房间信息
                PreRoom::rmPreRoomInfo($intPreRoomId);
                // create room
                break;
            }  else {
                $arrUserIds = [$arrUserInfo['user_id']];
                if (count($arrUserIds) > 0) {
                    //设置用户的预备房间信息
                    PreRoom::setBatchUserPreRoomId($arrUserIds, $intPreRoomId);
                }
                $intRetry ++;
                var_dump("人数不够，进行再次取队列 $intRetry");
                ModelMatch::pushPreRoomIdToQueue($intPreRoomId);
                continue;
            }
        }
        

        
        Yii::warning(var_export($arrPreRoomInfo,true));
        return $arrPreRoomInfo;

    }
   
}