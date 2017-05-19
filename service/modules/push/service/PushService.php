<?php
/**
 * Created by PhpStorm.
 * User: guojiawei
 * Date: 2017/3/28
 * Time: 下午3:55
 */

namespace service\modules\push\service;


use common\othersdk\getui\Push;
use service\base\BaseService;
use \zhuravljov\yii\queue\Job;

class PushService extends BaseService implements Job
{
    public $pushtoken;
    public $client;
    public $data;

    /**
     * https://github.com/zhuravljov/yii2-queue/blob/master/docs/guide/driver-redis.md
     *
     * yii queue/listen
     * @return bool|string
     */
    public function run()
    {
        $pushconfig = \Yii::$app->params['getui'];

        if (empty($this->data)) {
            return true;
        }
        if (empty($this->pushtoken)) {
            return true;
        }
        $send_data = $this->_formatSendData($this->data);
        if ($this->client == 'ios') {
            // ios推送限长，消息体总长度似乎不能超过128个中文，排除自定义参数长度，取90中文长度截取
            $send_data['msg'] = $this->_substring_content($send_data['msg'], 270, '...');
        }
        $result = Push::getinstance($pushconfig['appkey'],$pushconfig['appid'],$pushconfig['appsecret'])->batch($this->pushtoken, $this->data);
        \Yii::info("PUSH-SERVICE-SINGLIE:result=" . json_encode($result) . "|pushtoken={$this->pushtoken}|".json_encode($this->data));
        return $result;
    }


    /**
     * @desc 私有方法：格式化推送数据
     * @access private
     * @param $dict
     * @return array
     */
    private function _formatSendData($data)
    {

        $send_data['msg'] = $data['msg'];

        $send_data['data'] = array(
            //push_id
            'p_id' => $this->_params_value($data, 'p_id', 'int', 0),
            //push 消息类型
            'p_msg_type' => $this->_params_value($data, 'p_msg_type', 'int', 0),
            //push 消息ID
            'p_msg_id' => $this->_params_value($data, 'p_msg_id', 'int', 0),
            //push_发送时间
            'p_time' => $this->_params_value($data, 'p_time', 'int', date('YmdHi', time())),
            //push_类型
            'p_type' => $this->_params_value($data, 'p_type', 'int', 0),
            //是否区分在线
            'is_online' => $this->_params_value($data, 'is_online', 'int', 0),
        );
        $send_data['data'] = array_merge($data, $send_data['data']);
        return $send_data;
    }


    /**
     * @desc 私有方法：参数赋值
     * @access public
     * @param $data
     * @param $param
     * @param $type
     * @param mixed $default
     * @return mixed
     */
    private function _params_value($data, $param, $type, $default=NULL)
    {
        // 未设置为NULL
        if (!isset($data[$param]))
        {
            $data[$param] = NULL;
        }

        if ($type == 'string') // 字符串
        {
            // 为空时有默认值就替换
            if (empty($data[$param]) && !is_null($default))
            {
                return $default;
            }
        }
        elseif ($type == 'int') // 整型
        {
            // 非数字时有默认值就替换
            if (!is_numeric($data[$param]) && !is_null($default))
            {
                return $default;
            }
        }

        return $data[$param];
    }

	/**
     * @desc 私有方法：截断超长内容
     * @access public
     * @param $str
     * @param $length
     * @param string $dot
     * @param int $start
     * @return string
     */
	private function _substring_content($str, $length, $dot = '', $start = 0)
    {
        $str = htmlspecialchars($str);
        $i = 0;
        // 完整排除之前的UTF8字符
        while ($i < $start)
        {
            $ord = ord($str{$i});
            if ($ord < 192)
            {
                $i++;
            }
            elseif ($ord < 224)
            {
                $i += 2;
            }
            else
            {
                $i += 3;
            }
        }
        // 开始截取
        $result = '';
        while ($i < $start + $length && $i < strlen($str))
        {
            $ord = ord($str{$i});
            if ($ord < 192)
            {
                $result .= $str{$i};
                $i++;
            }
            elseif ($ord < 224)
            {
                $result .= $str{$i} . $str{$i + 1};
                $i += 2;
            }
            else
            {
                $result .= $str{$i} . $str{$i + 1} . $str{$i + 2};
                $i += 3;
            }
        }
        if ($i < strlen($str))
        {
            $result .= $dot;
        }
        return $result;
    }

    /**
     * 根据pushtoken 发送推送
     * @param $push_token
     * @param $client
     * @param $data
     */
    public function pushByPushtoken($push_token, $client, $data)
    {
        \Yii::$app->queue->push(new PushService([
            'pushtoken' => $push_token,
            'client' => $client,
            'data' => $data
        ]));
    }

}