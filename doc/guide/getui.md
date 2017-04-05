推送服务
====================


提供基于 [个推](http://www.getui.com/cn/index.html) 的推送服务


Demo
-----------
1. 入队列
```
    public function pushByPushtoken($push_token, $client, $data)
    {
        \Yii::$app->queue->push(new PushService([
            'pushtoken' => $push_token,
            'client' => $client,
            'data' => $data
        ]));
    }
    
```
2. 出队列
```
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
    
```
3. RPC
```
   $client = Client::create('http://service.xxx.com/common/test/index', false);
   $test = $client->pushByPushtoken('gglinux.com/abc.png', 'temp/abc.png');
```
4. [详细代码](service/modules/common/services/PushService.php)

