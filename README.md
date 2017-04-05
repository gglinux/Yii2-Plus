Yii2  脚手架
====================
Yii2 + Hprose 搭建的脚手架项目。集成API接口，Web前台，管理后台，Service

## API
APP 数据接口层，提供统一RESTful 调用
1. Response组件，Json结构化
2. [JWT](https://tools.ietf.org/html/rfc7519)( JSON Web Token (JWT))组件 
3. 签名验证过滤
4. 防重放攻击过滤
5. [Hprose](https://github.com/hprose/hprose-php)数据层RPC框架

service
------------
APP服务层，提供独立模块给API调用
1. [Hprose](https://github.com/hprose/hprose-php)服务层RPC框架
2. 日志监控报警
3. 个推[推送服务](doc/guide/getui.md)
4. Yii-queue[队列服务](doc/guide/queue.md)

admin
------------
网站管理后台项目
1. Element UI框架搭建（暂未实现）


www
------------
网站项目（H5）,基于Yii-basic，直接访问www.xxx.com

Installation
------------
1. 配置lnmp坏境，导入[nginx配置](doc/nginx_conf)
2. ``` git clone git@github.com:gglinux/Yii2-Plus.git ```
3. 根目录，composer update


