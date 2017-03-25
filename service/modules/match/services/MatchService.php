<?php

namespace service\modules\match\services;

use Yii;

use yii\base\Model;

use service\modules\room\models\ar\PreRoom;
use service\modules\match\models\ar\Match;

use common\base\Exception;
use common\base\BaseService;

/**
 * Class Match
 * 匹配策略
 * @package service\models
 */
class MatchService extends BaseService
{
    /**
     * @brif 创建房间
     * @return string
     */

    public function matchRoom()
    {
        //clear redis key 
        //del pre_room_info user_pre_room match_queue_man match_queue_woman match_queue_pre_room
        //目前是单进程模式
        
        //获取队列中的预备房间ID
        $intPreRoomId =  Match::getPreRoomIdFromQueue();
        
        //获取等待队列长度
        $intPreRoomCount = Match::getPreRoomQueueLength(Match::REDIS_KEY_MATCH_QUEUE_PRE_ROOM);
        $intManWaitCount = Match::getMatchQueueLength(Match::REDIS_KEY_MATCH_QUEUE_MAN);
        $intWomanWaitCount = Match::getMatchQueueLength(Match::REDIS_KEY_MATCH_QUEUE_WOMAN);
        
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
            $arrPreRoomInfo = Match::createNewPreRoom();
            $intPreRoomId = $arrPreRoomInfo['pre_room_id'];
            var_dump($arrPreRoomInfo);
        } 
        //start
        $arrPreRoomInfo = PreRoom::getPreRoomInfo($intPreRoomId);
        var_dump($arrPreRoomInfo);
        
        if(empty($arrPreRoomInfo)) {
            $strLog = __CLASS__ . "::". __FUNCTION__ . " PreRoom::getPreRoomInfo error. ". serialize(compact('arrPreRoomInfo'));
            Yii::error($strLog);
            $ret = Match::rmPreRoomIdFromQueue($intPreRoomId);
            return false;
        }
        var_dump("current pre room info");
        var_dump($arrPreRoomInfo);
        
        $intRetry = 0;
        while(1) {
            $arrUserInfos = [];
            $arrUserIds= [];
            if ($intRetry > 7) {
                var_dump("重试次数达到7次，进行下一个预备房间处理");
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
                        $arrUserIds = Match::getUserFromQueue(Match::REDIS_KEY_MATCH_QUEUE_MAN);
                        $intSex = 1;
                    } else {
                        // man is leak
                        var_dump("man is leak");
                        Match::createNewPreRoom();
                        Yii::warning("Man is not enough");
                    }
                    
                } else if($intMWCount == 5 && $intWc == 0) {
                    // need woman
                    var_dump("need woman");
                    if ($intWomanWaitCount > 0) {
                        $intSex = 2;
                        $arrUserIds = Match::getUserFromQueue(Match::REDIS_KEY_MATCH_QUEUE_WOMAN);
                    } else {
                        // woman is leak
                        var_dump("woman is leak");
                        Match::createNewPreRoom();
                        Yii::warning("Woman is not enough");
                    }
                } else {
                    //need everyone
                    var_dump("need everyone");
                    if ($intManWaitCount > $intWomanWaitCount) {
                        $intSex = 1;
                        $arrUserIds = Match::getUserFromQueue(Match::REDIS_KEY_MATCH_QUEUE_MAN);
                    } else {
                        $intSex = 2;
                        $arrUserIds = Match::getUserFromQueue(Match::REDIS_KEY_MATCH_QUEUE_WOMAN);
                    }
                    
                }
                var_dump("match uids");
                var_dump($arrUserIds);
                var_dump("match sex $intSex");
                
                
                foreach($arrUserIds as $item) {
                    $arrUserInfos[] = [
                        'user_id' => $item,
                        'sex' => $intSex,
                    ];
                }
                var_dump('Match::addUsersToPreRoom');
                if (count($arrUserInfos) === 0) {
                    var_dump("没有匹配符合条件的用户，结束此次匹配");
                    break;
                }
                $arrPreRoomInfo = Match::addUsersToPreRoom($arrUserInfos, $intPreRoomId);
                if(false === $arrPreRoomInfo) {
                    $strLog = __CLASS__ . "::". __FUNCTION__ . " Match::addUsersToPreRoom error. ". serialize(compact('arrUsersInfo', 'intPreRoomId'));
                    Yii::error($strLog);
                    return false;
                }
                var_dump($arrPreRoomInfo);
                Match::rmUserFromQuere($arrUserInfos);
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
                $ret = Room::createNewRoom($arrPreRoomInfo);
                if(false === $ret) {
                    $strLog = __CLASS__ . "::". __FUNCTION__ . " call Room createNewRoom error. ". serialize(compact('ret', 'arrPreRoomInfo'));
                    Yii::error($strLog);
                    return false;
                }
                Match::rmPreRoomIdFromQueue($intPreRoomId);
                PreRoom::rmBatchUserPreRoomId($arrUserIds);
                PreRoom::rmPreRoomInfo($intPreRoomId);
                // create room
                break;
            }  else {
                $arrUserIds= [];
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