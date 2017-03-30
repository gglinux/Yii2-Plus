<?php
/**
 * Created by PhpStorm.
 * User: guojiawei
 * Date: 2017/3/28
 * Time: 下午5:53
 */

namespace common\othersdk\getui;

require_once(dirname(__FILE__) . '/' . 'IGt.Push.php');
/**
 * 欢聚时刻 推送服务
 * Class Push
 * @package common\othersdk\getui
 */
class Push
{
    const GETUI_HOST = 'http://sdk.open.api.igexin.com/apiex.htm';

    /**
     * 个推 key
     * @var string
     */
    private $appkey = '';

    /**
     * 个推 appid
     * @var string
     */
    private $appid = '';

    /**
     * app secret
     * @var string
     */
    private $appsecret = '';

    protected function __construct($appkey, $appid, $appsecret)
    {
        $this->appid = $appid;
        $this->appkey = $appkey;
        $this->appsecret = $appsecret;
    }

    /**
     * 单例模式
     * @param $appkey
     * @param $appid
     * @param $appsecret
     * @return Push
     */
    public static function getinstance($appkey = '', $appid = '', $appsecret ='')
    {
        static $_push;
        if ( empty($_push) ) {
            $_push = new Push($appkey, $appid, $appsecret);
        }
        return $_push;
    }


    /**
     * @desc 批量推送接口封装
     * @access public
     * @param array $clients
     * @param string $msg
     * @param string $appid
     * @param string $appkey
     * @param string $mastersecret
     * @return string
     */
    public function batch($clientids, $msg)
    {
        $igt = new \IGeTui(self::GETUI_HOST, $this->appkey, $this->appsecret);
        $template =  new \IGtTransmissionTemplate();

        $type = isset($msg['type']) ? $msg['type'] : -1;
        $msg = self::format($msg);

        //收到消息是否立即启动应用，1为立即启动，2则广播等待客户端自启动
        $template ->set_transmissionType(2);
        $template ->set_appId($this->appid);
        $template ->set_appkey($this->appkey);
        $template ->set_transmissionContent($msg);//透传内容

        //个推信息体
        $message = new \IGtSingleMessage();

        $message->set_isOffline(true);//用户不在线，是否离线存储

        $offlinetime =  ($type==51) ? 1800*1000 : 3600*24*1000;
        $message->set_offlineExpireTime($offlinetime);//离线有效时间
        $message->set_data($template);//设置推送消息类型
        //$message->set_PushNetWorkType(0);

        $rep = '';
        if (is_array($clientids))
        {
            $contentId = $igt->getContentId($message);

            //群发接收方,一次上限是500个人
            for ($i = 0; $i < count($clientids); $i += 500)
            {
                $slice = array();
                $targetList = array();

                $slice = array_slice($clientids, $i, 500);

                foreach ($slice as $client) {
                    $target = new \IGtTarget();
                    $target->set_appId($this->appid);
                    $target->set_clientId($client);
                    $targetList[] = $target;
                }

                $rep = $igt->pushMessageToList($contentId, $targetList);
            }
        } else {
            $target = new \IGtTarget();
            $target->set_appId($this->appid);
            $target->set_clientId($clientids);
            try {

                $rep = $igt->pushMessageToSingle($message, $target);
            } catch (\RequestException $exception) {
                $requstId = $exception->getRequestId();
                //失败时重发
                $rep = $igt->pushMessageToSingle($message, $target,$requstId);
            }
        }
        return $rep;
    }

    /**
     * @desc 格式化推送信息
     * @access public
     * @param array $map array('data'=>'', 'id'=>'', 'type'=>'')
     * @param string $s 分隔符
     * @param string $g
     * @return string
     */
    public static function format($map, $s='&', $g='=')
    {
        $str = "";
        foreach ($map as $key => $value)
        {
            if (!empty ($str))
            {
                $str .= $s;
            }

            $str .= $key . $g . urlencode ($value);
        }
        return $str;
    }
}