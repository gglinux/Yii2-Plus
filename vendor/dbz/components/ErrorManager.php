<?php
/**
 * 大爆炸 错误管理器
 */
namespace dbz\components;

class ErrorManager extends \yii\base\Component {
    private $errorCode = null;
    private $errorMessage = null;

    public function init()
    {

    }

    public function getCode()
    {
        return $this->errorCode;
    }

    public function getMessage()
    {
        return $this->errorMessage;
    }

    public function hasError()
    {
        return $this->errorCode != null;
    }


    public function push($error,$message=null)
    {
        if(is_object($error) && $error instanceof \Exception) {
            $this->errorCode = $error->getCode();
            $this->errorMessage = $error->getMessage();
        } else {
            $this->errorCode = $error;
            $this->errorMessage = $message;
        }

        return true;
    }
}