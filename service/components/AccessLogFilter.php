<?php
/**
 * Created by PhpStorm.
 * User: guojiawei
 * Date: 2017/5/19
 * Time: 下午3:00
 */
namespace service\components;

use Hprose\Filter;
use stdClass;

/**
 * 访问日志记录 和 服务性能记录
 * Class LogFilter
 * @package service\components
 */
class AccessLogFilter implements Filter {

    private function stat(stdClass $context)
    {
        if (isset($context->userdata->starttime)) {
            $timeused = microtime(true) - $context->userdata->starttime;
            return $timeused;
        } else {
            $context->userdata->starttime = microtime(true);
            return 0;
        }
    }

    public function inputFilter($data, stdClass $context)
    {
        $timeused = $this->stat($context);
        \Yii::info($data,'service');
        \Yii::info("the request takes $timeused s.");
        return $data;
    }


    public function outputFilter($data, stdClass $context)
    {
        $timeused = $this->stat($context);
        \Yii::info($data,'service');
        \Yii::info("the request takes $timeused s.");
        return $data;
    }
}