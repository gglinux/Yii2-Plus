<?php
/**********************************************************\
|                                                          |
|                          hprose                          |
|                                                          |
| Official WebSite: http://www.hprose.com/                 |
|                   http://www.hprose.org/                 |
|                                                          |
\**********************************************************/

/**********************************************************\
 *                                                        *
 * Hprose/Yii/Service.php                                 *
 *                                                        *
 * hprose yii http service class for php 5.3+             *
 *                                                        *
 * LastModified: Jul 26, 2016                             *
 * Author: Ma Bingyao <andot@hprose.com>                  *
 *                                                        *
\**********************************************************/

namespace Hprose\Yii;

class Service extends \Hprose\Http\Service {
    const ORIGIN = 'Origin';
    public function header($name, $value, $context) {
        $context->response->headers->set($name, $value);
    }
    public function getAttribute($name, $context) {
        return $context->request->headers->get($name);
    }
    public function hasAttribute($name, $context) {
        return $context->request->headers->has($name);
    }
    protected function readRequest($context) {
        return $context->request->rawBody;
    }
    public function writeResponse($data, $context) {
        $context->response->format = \yii\web\Response::FORMAT_RAW;
        $context->response->data = $data;
    }
    public function isGet($context) {
        return $context->request->isGet;
    }
    public function isPost($context) {
        return $context->request->isPost;
    }
}
