# Yii2-Plus
Yii2 + Hprose 搭建的脚手架项目。集成API接口，Web前台，管理后台，Service(服务层)。

## API
1. 重写Response组件，自定义返回数据结构
2. 集成[JWT](https://tools.ietf.org/html/rfc7519)( JSON Web Token (JWT))组件 
3. 添加签名验证过滤器
4. 添加放刷过滤器
5. [Hprose](https://github.com/hprose/hprose-php)作为RPC client框架
6. 通过坏境变量，区分线上，测试，开发坏境

## Admin
1. Element UI框架搭建（暂未实现）

## Service
1. [Hprose](https://github.com/hprose/hprose-php)作为RPC server框架
2. 基于日志监控的报警系统
3. 集成个推（暂未实现）
4. 集成 yii2-queue（暂未实现）

## WWW
H5，Web前台项目

## 安装

1. 安装 composer
2. clone本项目到本地
3. composer install
4. 配置nginx虚拟主机，映射api,admin,service,www代码目录
5. 分别访问对应URL
6. Enjoy it！

