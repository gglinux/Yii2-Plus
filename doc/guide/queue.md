队列服务
====================


提供基于 **DB**, **Redis**, **RabbitMQ**, **Beanstalk** and **Gearman** 的队列服务


[![最新稳定版](https://poser.pugx.org/zhuravljov/yii2-queue/v/stable.svg)](https://packagist.org/packages/zhuravljov/yii2-queue)
[![Total Downloads](https://poser.pugx.org/zhuravljov/yii2-queue/downloads.svg)](https://packagist.org/packages/zhuravljov/yii2-queue)
[![Build Status](https://travis-ci.org/zhuravljov/yii2-queue.svg)](https://travis-ci.org/zhuravljov/yii2-queue)

Demo
-----------
1. 入队列
```
    /**
     * RPC 下载服务
     * @param $url
     * @param $file
     */
    public function downloadJob($url, $file)
    {
        \Yii::$app->queue->push(new DownloadService([
            'url' => $url,
            'file' => $file
        ]));
    }
```
2. 出队列
```
    public function run()
    {
        file_put_contents($this->file, file_get_contents($this->url));
    }
```
3. RPC
```
   $client = Client::create('http://service.xxx.com/common/test/index', false);
   $test = $client->downloadJob('gglinux.com/abc.png', 'temp/abc.png');
```
4. [详细代码](service/modules/common/services/DownloadService.php)

