<?php
/**
 * 错误码定义
 */
namespace app\config;
class Error {
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

    /****************用户系统相关101XXX*************************/

    const USER_INVALID_SMS_BUSI         = 101000; //不存在的短信业务编码
    const USER_SMS_SEND_FAIL            = 101001; //短信返送失败
    const USER_PASSWORD_INVALID         = 101002; //密码格式不正确
    const USER_ACCOUNT_TYPE             = 101003; //账号类型
    const USER_INFO                     = 101004; //用户信息不全
    const USER_ACCOUNT_EXISTS           = 101005; //手机号已经存在
    const USER_MODIFY_PASSWORD          = 101006; //密码修改失败
    const USER_NEED_LOGIN               = 101007; //需要先登录信息
    const USER_VERIFYREALNAME_FAIL      = 101008; //实名认证失败
    const USER_HAS_SET_DRAW_PIN         = 101009; //提款密码已经设置
    const USER_SET_DRAW_PIN_FAIL        = 101010; //提款密码设置或修改失败
    const USER_NO_BANKCARD              = 101011; //未绑定银行卡
    const USER_NO_VERIFY                = 101012; //未完成实名认证
    const USER_DRAW_PIN_WRONG           = 101013; //提款米啊吗错误
    const USER_ID_CARD_USED             = 101014; //身份证已经被使用了
    const USER_GESTURE_FAILD            = 101015; //手势密码操作失败

    /***************订单系统相关102XXX************************************/
    const ORDER_INVALID_AMOUNT          = 102000; //错误的金额
    const ORDER_INVALID_ID              = 102001; //订单id错误
    const ORDER_END_FAILED              = 102002; //终止订单失败
    const ORDER_PAY_CHANNEL             = 102003; //无可用订单支付方式
    const ORDER_AUTO_RENEW_FAILED       = 102004; //自动续约操作失败
    const ORDER_ADDUP_ENSUREAMOUNT_FAIL = 102005; //追加保证金失败
    const ORDER_ENDED                   = 102006; //订单已终结
    const ORDER_EXPIRED                 = 102007; //订单到期
    const ORDER_ADDUP_ENSUREAMOUNT_LIMIT= 102008; //追加保证金次数超过限制
    const ORDER_ADDUP_ENSUREAMOUNT_LOW  = 102009; //追加保证金额小于最低追加保证金额
    const ORDER_BEYOND_LIMIT            = 102010; //超出每日人数限制
    const ORDER_EXPERIENCED             = 102011; //已体验过此活动,只能购买一次
    const ORDER_OVER_LIMIT             = 102011; //已体验过此活动,只能购买一次

    /***************众筹项目相关102XXX************************************/
    const PROJECT_OUTLINE               = 103000; //项目未上线
    const PROJECT_NOT_EXIST             = 103001; //项目不存在

} 