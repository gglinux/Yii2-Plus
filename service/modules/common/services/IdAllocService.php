<?php

namespace service\modules\common\services;


use service\modules\common\models\ar\IdAlloc;
use common\base\Exception;
use service\base\BaseService;


/**
 * Class Room
 * 房间服务层代码
 * @package service\models
 */
class  IdAllocService extends BaseService
{


    /**
     * id 发号器
     * @param $strIdAllocType
     * @return number
     * @throws Exception
     */
    public static function allocId($strIdAllocType){

        if(empty($strIdAllocType)){
            throw new Exception('参数错误;'.serialize($strIdAllocType));
        }
        $intId =  IdAlloc::allocId($strIdAllocType);
        if(false == $intId) {
            throw new Exception('执行失败');
        }
        return $intId;
    }

    /**
     * 获取id
     * @param $strIdAllocType
     * @return number
     * @throws Exception
     */
    public static function getCurrentId($strIdAllocType){

        if(empty($strIdAllocType)){
            throw new Exception('参数错误');
        }
        $intId =  IdAlloc::getCurrentId($strIdAllocType);
        if(false == $intId) {
            throw new Exception('执行失败');
        }
        return $intId;
    }



    

}