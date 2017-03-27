<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace console\commands;

use service\modules\match\models\ar\Match;
use yii\console\Controller;
use service\modules\match\services\MatchService;

/**
 * This command echoes the first argument that you have entered.
 *
 * This command is provided as an example for you to learn how to create console commands.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class MatchController extends Controller
{
    /**
     * This command echoes what you have entered as the message.
     * @param string $message the message to be echoed.
     */
    public function actionIndex($message = 'hello world')
    {
        MatchService::sayHi();
        echo $message;
//        $intRoomId = 31;
//        $updateRoomInfo = RoomService::updateRoomInfo([
//            'room_id' => $intRoomId,
//            'game_status' => 1,
//        ]);
//        $roomInfo = RoomService::getBatchRoomInfo([$intRoomId]);
//        $userInfos = RoomService::getBatchRoomUsers([$intRoomId]);
//        $userInRoomInfo = RoomService::getUserInRoom([
//            'user_id' => 216,
//            'room_id' => $intRoomId
//        ]);
//        $arrRet = compact('roomInfo', 'userInfos', 'userInRoomInfo', 'updateRoomInfo');
//        return $arrRet;
    }

    public function actionMatching() {
        while(1) {
            MatchService::matchRoom();
            sleep(1);
        }

    }
}