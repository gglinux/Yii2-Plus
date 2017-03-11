<?php
/**
 * 大爆炸 公共错误码定义
 */
namespace dbz\base;
class Error {
    /**********************公共错误码100XXX**************************/
    const COMMON_UNKOWN                 = 100000; //未知错误
    const COMMON_DB                     = 100001; //数据库错误
    const COMMON_SIGN                   = 100002; //签名错误
    const COMMON_ILLEGAL_CLIENT         = 100003; //非法客户端类型
    const COMMON_METHOD                 = 100004; //非法的http请求方法
    const COMMON_INVALID_PHONE          = 100005; //手机号格式不正确
    const COMMON_MIS_CONF               = 100006; //缺少配置
    const COMMON_RPC                    = 100007; //RPC 服务出错
    const COMMON_INVALID_EMAIL          = 100008; //邮箱格式不正确
    const COMMON_CACHE_W                = 100009; //cache写失败
    const COMMON_VERIFY_CODE_ERR        = 100010; //验证码错误
    const COMMON_UNKOWN_TYPE            = 100011; //未知类型
    const COMMON_IDCARD_ERR             = 100012; //身份证错误
    const COMMON_MISS_PARAM             = 100013; //缺少参数
    const COMMON_NONCE_ERROR            = 100014; //检测到可能的重放攻击
    const COMMON_INVALID_PARAM          = 100015; //参数不合法
    const COMMON_LIMIT_BEYOND           = 100016; //超过业务限制
    const COMMON_NO_SUCH_OBJECT         = 100017; //对象不存在
} 