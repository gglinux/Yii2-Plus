<?php
namespace common\helpers\client;

use common\base\Error;
use common\base\Exception;
use Yii;


/**
 * 客户端模型
 * Class AppClient
 * @package common\helpers\client
 */
class AppClient extends Client
{

    private static $_INSTANCE;
    private $platForm;  //平台
    private $version;   //型号
    private $ip;        //ip地址
    private $sysVer;    //系统版本
    private $mmodel;   //手机生产型号
    private $cidentity; //手机唯一标识符
    private $network;   //手机网络类型
    private $business;  //业务类型 脸趴

    /**
     * @param array $param
     * @return $this
     */
    public static function getInstance($param=array())
    {
        return parent::getInstance($param);
    }

    public function init($param = null)
    {
        if($param == null) {
            $param = $_REQUEST;
        }
        $this->platForm     = isset($_REQUEST['c_platform'])? $_REQUEST['c_platform']:null;
        $this->version      = isset($_REQUEST['c_version'])? $_REQUEST['c_version']:null;
        $this->ip           = isset($_REQUEST['c_ip'])? $_REQUEST['c_ip'] : \Yii::$app->request->getUserIP();
        $this->sysVer       = isset($_REQUEST['c_sysVer'])? $_REQUEST['c_sysVer']:null;
        $this->mmodel       = isset($_REQUEST['c_mmodel'])? $_REQUEST['c_mmodel']:null;
        $this->cidentity    = isset($_REQUEST['c_identity'])? $_REQUEST['c_identity']:null;
        $this->network      = isset($_REQUEST['c_network'])? $_REQUEST['c_network']:null;
        $this->business     = isset($_REQUEST['c_business'])? $_REQUEST['c_business']:'lianpa';
    }

    public function getPlatAndVersion()
    {
        return $this->getPlatForm().'_'.$this->getVersion();
    }

    public function getPlatForm()
    {
        return is_null($this->platForm)? 0:(int)$this->platForm;
    }

    public function getPlatFormStr()
    {
        $config = array(
            self::PL_IOS => 'ios',
            self::PL_ANDROID => 'android',
            self::PL_WAP => 'wap'
        );
        $plat = $this->getPlatForm();
        return isset($config[$plat])?$config[$plat]:'';
    }

    public function getIP()
    {
        return $this->ip;
    }

    public function getVersion() {
        return preg_match('/^[1-9]+\.[0-9]+\.[0-9]+$/',$this->version)?$this->version:0;
    }

    public function getMmodel()
    {
        return $this->mmodel;
    }

    public function getIdentity()
    {
        $plat = $this->getPlatForm();

        if($plat == 0 || is_null($this->cidentity)) {
            return null;
        }
        $identity = null;
        if($plat == self::PL_WAP) {
            $identity = $this->getIP();
        }else{
            $identity = md5($this->cidentity);
        }
        return $this->getPlatForm().'_'.$identity;
    }

    public function getNetwork()
    {
        return $this->network;
    }

    public function getSecretKey($client = '')
    {
        if ( empty($client) ) {
            $client = $this->business;
        }
        $appSecret = Yii::$app->params['secretKey'][$client];
        if ( empty($appSecret) ) {
            throw new Exception('缺少配置', Error::COMMON_MIS_CONF);
        }
        return $appSecret;
    }

    public function getConfig()
    {
        $appClientConfig = Yii::$app->params['appClient'];
        $platAndVersion = $this->getPlatAndVersion();
        foreach($appClientConfig as $key=>$config) {
            if(preg_match("/$key/", $platAndVersion)) {
                return $config;
            }
        }
        throw new Exception('缺少配置', Error::COMMON_MIS_CONF);
    }

    public function getAccountSessionTime()
    {
        $config = $this->getConfig();
        return $config['accountTime'];
    }
    public function getSessionTime()
    {
        $config = $this->getConfig();
        return $config['tokenTime'];
    }

    public function getRefer()
    {
        $config = $this->getConfig();
        if(empty($config)) {
            return 0;
        }
        return $config['refer'];
    }
    public function getBusiness()
    {
        return $this->business;
    }

    /**
     * @return mixed 获取操作系统
     */
    public function getSystem()
    {
        return $this->sysVer;
    }

    public function info()
    {
        return array(
            'c_platform' => $this->platForm,
            'c_version'  => $this->version,
            'sysVer'     => $this->sysVer,
            'c_mmodel'   => $this->mmodel,
            'c_identity' => $this->cidentity,
            'c_network'  => $this->network,
            'c_business' => $this->business
        );
    }
}