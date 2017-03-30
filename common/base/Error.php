<?php
/**
 * 大爆炸 公共错误码定义
 */
namespace common\base;

class Error
{

    /**********************公共错误码100XXX**************************/
    const COMMON_UNKOWN                 = 100000; //未知错误
    const COMMON_DB                     = 100001; //数据库错误
    const COMMON_SIGN                   = 100002; //签名错误
    const COMMON_ILLEGAL_CLIENT         = 100003; //非法客户端类型
    const COMMON_METHOD                 = 100004; //非法的http请求方法
    const COMMON_INVALID_PHONE          = 100005; //手机号格式不正确
    const COMMON_MIS_CONF               = 100006; //缺少配置
    const COMMON_THRIFT                 = 100007; //thrift 服务出错
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
    const UPDATE_FAILED                 = 100018; //更新失败

    const ACCESS_TOKEN_ERROR            = 100019;//access_token错误


    //////////////////////////用户异常错误码////////////////////////////////

    const ACCOUNT_EMPTY                 = 20000;//手机号码或者账号为空
    const LOGIN_TYPE_ERROR              = 20001;//非账号，邮箱，手机号码方式注册
    const ACCOUNT_REPEAT                = 20002;//已经存在该注册方式下的唯一账号
    const ACCOUNT_PASSWORD_ERROR        = 20003;//账号或者密码错误
    const USER_NEED_LOGIN               = 20004;//需要登陆

    ////////////////////////////关系/////////////////////////////

    const USER_IN_BLACKLIST             = 30001;//好友被拉黑
    const USER_ADD_ERROR                = 30002;//添加好友错误



} 