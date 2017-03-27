<?php

namespace api\modules\config\controllers;

use common\base\Error;
use common\components\Jwt;
use yii\web\Controller;

/**
 * Default controller for the `config` module
 */
class JwtController extends Controller
{
    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            'nonce' => [
                'class' => \api\filters\Nonce::className(),
            ],
            'login' =>[
                'class' => \api\filters\AccessToken::className()
            ]
        ]);
    }
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionRefresh()
    {
        $old_token = \Yii::$app->request->get('access_token');
        if ( empty($old_token) ) {
            $old_token = \Yii::$app->request->post('access_token');
        }
        $new_token = Jwt::updateJwt($old_token);
        if (!$new_token) {
            \Yii::$app->response->success($new_token);
        }
        \Yii::$app->response->error(Error::ACCESS_TOKEN_ERROR,'更新失败！');

    }
}
