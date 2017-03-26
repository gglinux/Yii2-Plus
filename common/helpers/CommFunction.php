<?php

namespace common\helpers;

use common\base\Exception;

/**
 * 公共方法包装
 */
class CommFunction
{

    const EARTH_RADIUS = 6378.137;

    /**
     * 把驼峰命名的字符串分割成数组
     * @param string 驼峰字符串
     * @return array
     */
    public static function explodeCamel($camel)
    {
        return preg_split('/([A-Z][a-z]+)/', $camel, -1, PREG_SPLIT_NO_EMPTY|PREG_SPLIT_DELIM_CAPTURE);
    }


    /**
     * 把下划线命名的字符串分解成数组
     * @param string 下划线字符串
     * @return array
     * @author zhengxing
     * @since 2013-8-7 21:03:50
     */
    public static function explodeUnderline($underline)
    {
        return explode('_', $underline);
    }

    /**
     * 字符串数组合并成驼峰
     * @param array 字符串数组
     */
    public static function implodeCamel(array $strings)
    {
        foreach($strings as $i=>$string)
        {
            if($i!=0) $strings[$i] = ucfirst($strings[$i]);
        }
        return implode('', $strings);
    }

    /**
     * 字符串数组合并成下划线命名
     * @param array 字符串数组
     * @return string 下划线字符串
     */
    public static function implodeUnderline(array $strings)
    {
        foreach($strings as $i=>$string)
        {
            $strings[$i] = strtolower($strings[$i]);
        }
        return implode('_', $strings);
    }


    /**
     * 长文本处理
     * @param string $content
     * @param int $lenght 0表示不限制
     * @param string $suffix
     */
    public static function processLongText($content, $length=0, $suffix='...')
    {
        if($length>0)
        {
            //这一步去除所有不可见字符
            $clean = str_replace(array(chr(13), chr(10), "\n", "\r", "\t", ' ','　','&nbsp;'), '', strip_tags($content));
            return (mb_strlen($clean, 'utf-8') > $length)  ?  mb_substr($clean, 0, $length, 'utf-8').$suffix : $clean;
        }
        return str_replace("\r\n", "<br/>", $content);
    }


    /**
     * 通过两个点的经纬度计算距离
     * From Google Maps
     * @param float $lat1
     * @param float $lng1
     * @param float $lat2
     * @param float $lng2
     * $return number km
     */
    public static function getDistance($lat1, $lng1, $lat2, $lng2)
    {
        $radLat1 = self::rad($lat1);
        $radLat2 = self::rad($lat2);
        $a = $radLat1 - $radLat2;
        $b = self::rad($lng1) - self::rad($lng2);
        $s = 2 * asin(sqrt(pow(sin($a/2),2)+cos($radLat1)*cos($radLat2)*pow(sin($b/2),2)));
        $s *= self::EARTH_RADIUS;
        $s = round($s * 10000) / 10000;
        return $s;
    }

    /**
     * 生成短链接
     * From Weibo
     * @param string $url
     * @return 6位url
     */
    public static function shortUrl($url)
    {
        $output = array();
        static $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $hex = md5($url);
        for ($i=0, $len=4; $i<$len; $i++) {
            $subHex = substr($hex, $i * 8, 8);
            // 取30位
            $subHex = 0x3FFFFFFF & (1 * ("0x" . $subHex));
            $out = '';
            for ($j=0; $j<6; $j++) {
                $int_val = 0x0000003D & $subHex;
                $out .= $chars[$int_val];
                $subHex = $subHex >> 5;
            }
            $output[] = $out;
        }
        return $output;
    }

    private static function rad($double)
    {
        return $double * M_PI / 180.0;
    }

    /**
     * 对象转数组
     */
    public static function objectToArray($object){
        if(!is_object($object) && !is_array($object)){
            return $object;
        }
        $data = array();
        foreach($object as $key=>$value){
            $data[$key] = self::objectToArray($value);
        }
        return $data;
    }

    /**
     * 生成随机key
     */
    public static function generateRandKey($dataKey = NULL)
    {
        $randomString = self::randomString(32);
        $key = md5(md5($randomString).time().$dataKey);
        return $key;
    }

    /**
     * 生成随机字符串
     * @param int $length 字符串长度
     * @param string $type 字符串类型,格式为[xxx],分别表示[数字,小写字母,大写字母],x取值为[0,1]
     * @return string
     */
    public static function randomString($length,$type='111'){
        try{
            $type = str_split($type);
        }catch(Exception $e){
            return NULL;
        }

        if($type == '' || count($type) != 3 || $length <= 0){
            return NULL;
        }
        $stringArray = array('0123456789','abcdefghijklmnopqrstuvwxyz','ABCDEFGHIJKLMNOPQRSTUVWXYZ');
        $string = '';
        //生成需要的字符串集
        foreach($type as $key=>$value){
            if($value != '1'){
                continue;
            }
            $string .= $stringArray[$key];
        }
        if($string == ''){
            return NULL;
        }
        //将字符集合变成数组
        $string = str_split($string);
        $targetStr = '';
        $length = (int)$length;
        while($length >count($string)){
            shuffle($string);
            $targetStr .= implode('', $string);
            $length -= $length;
        }
        shuffle($string);
        $targetStr .= implode('',array_slice($string,0,$length));
        return $targetStr;
    }

    /**
     * 检测文件的类型
     *
     * @param  string $bin 数据流
     * @return string      文件类型
     */
    public static function getFileType(&$bin)
    {
        $str_info = @unpack("C2chars", $bin);

        $type_code = intval($str_info['chars1'].$str_info['chars2']);

        $file_type = '';
        switch ($type_code) {
            case 7790:
                $file_type = 'exe';
                break;
            case 7784:
                $file_type = 'midi';
                break;
            case 8075:
                $file_type = 'zip';
                break;
            case 8297:
                $file_type = 'rar';
                break;
            case 255216:
                $file_type = 'jpg';
                break;
            case 7173:
                $file_type = 'gif';
                break;
            case 6677:
                $file_type = 'bmp';
                break;
            case 13780:
                $file_type = 'png';
                break;
            default:
                $file_type = 'unknown';
                break;
        }
        return $file_type;
    }

    /**
     * @desc 获取操作系统类型
     * @author guojiawei
     * @update ${date}
     * @access public
     * @param void
     * @return mixed
     */
    public static function getOS($user_agent) {
        $os_platform = "Unknown OS Platform";
        $os_array = array(
            '/windows nt 6.2/i'     =>  'Windows 8',
            '/windows nt 6.1/i'     =>  'Windows 7',
            '/windows nt 6.0/i'     =>  'Windows Vista',
            '/windows nt 5.2/i'     =>  'Windows Server 2003/XP x64',
            '/windows nt 5.1/i'     =>  'Windows XP',
            '/windows xp/i'         =>  'Windows XP',
            '/windows nt 5.0/i'     =>  'Windows 2000',
            '/windows me/i'         =>  'Windows ME',
            '/win98/i'              =>  'Windows 98',
            '/win95/i'              =>  'Windows 95',
            '/win16/i'              =>  'Windows 3.11',
            '/macintosh|mac os x/i' =>  'Mac OS X',
            '/mac_powerpc/i'        =>  'Mac OS 9',
            '/linux/i'              =>  'Linux',
            '/ubuntu/i'             =>  'Ubuntu',
            '/iphone/i'             =>  'iPhone',
            '/ipod/i'               =>  'iPod',
            '/ipad/i'               =>  'iPad',
            '/android/i'            =>  'Android',
            '/blackberry/i'         =>  'BlackBerry',
            '/webos/i'              =>  'Mobile'
        );

        foreach ($os_array as $regex => $value) {

            if (preg_match($regex, $user_agent)) {
                $os_platform = $value;
                break;
            }

        }

        return $os_platform;
    }

    /**
     * 匹配是否为 手机号码
     * @param $mobile
     * @return bool
     */
    public static function isPhoneNum($mobile)
    {
        if( preg_match("/^1[34578]\d{9}$/", $mobile) ) {
            return true;
        }
        return false;
    }

    /**
     * 匹配是否为邮箱地址
     * @param $email
     * @return bool
     */
    public static function isEmailAddress($email)
    {
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return true;
        }
        return false;
    }

    /**
     * 匹配是否是欢聚时刻账号
     * @param $account
     * @return bool
     */
    public static function isHjskAccout($account)
    {
        if (preg_match("^[_a-zA-Z][_a-zA-Z0-9]*$", $account)) {
            return true;
        }
        return false;
    }

}
