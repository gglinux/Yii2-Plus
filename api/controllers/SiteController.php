<?php

namespace api\controllers;

use service\base\ServiceException;
use \Exception;
use Yii;
use yii\filters\AccessControl;
use api\base\ApiController;
use yii\filters\VerbFilter;
use api\models\LoginForm;
use api\models\ContactForm;

use \Hprose\Http\Client;
use Hprose\InvokeSettings;
use Hprose\ResultMode;
use Firebase\JWT\JWT;
use Firebase\JWT\SignatureInvalidException;


class SiteController extends ApiController
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }


    /**
     * @desc
     * APP可访问的接口 demo
     *
     * 已配置路由：请访问 http://api.dev.dabaozha.com/user
     *
     * 一般返回Json格式！！！
     *
     * 看这里！！！！！！！！！
     *
     *
     * @author guojiawei
     * @update ${date}
     * @access public
     * @param void
     * @return mixed
     */
    public function actionIndex()
    {

        $key = "example_key";
        $token = array(
            "iss" => "http://example.org",
            "aud" => "http://example.com",
            "iat" => 1356999524,
            "nbf" => 1357000000
        );

        /**
         * IMPORTANT:
         * You must specify supported algorithms for your application. See
         * https://tools.ietf.org/html/draft-ietf-jose-json-web-algorithms-40
         * for a list of spec-compliant algorithms.
         */
        $jwt = JWT::encode($token, $key);
//        var_dump($jwt);
        try{
            $decoded = JWT::decode($jwt, 'example', array('HS256'));
        } catch (SignatureInvalidException $exception) {
            echo "gggggg";exit();
        }
//        $decoded = JWT::decode($jwt, 'example', array('HS256'));

//        print_r($decoded);

        /*
         NOTE: This will now be an object instead of an associative array. To get
         an associative array, you will need to cast it as such:
        */

        $decoded_array = (array) $decoded;

        var_dump($decoded_array);


        /**
         * You can add a leeway to account for when there is a clock skew times between
         * the signing and verifying servers. It is recommended that this leeway should
         * not be bigger than a few minutes.
         *
         * Source: http://self-issued.info/docs/draft-ietf-oauth-json-web-token.html#nbfDef
         */
        JWT::$leeway = 60; // $leeway in seconds
        $decoded = JWT::decode($jwt, $key, array('HS256'));

        var_dump($decoded);exit();

        //通过RPC，调用service代码
        $client = Client::create('http://service.dev.dabaozha.com/test', false);
        //调用hello函数
        $user = $client->hello('Word');
        //输出：string(11) "Hello Word!"
        var_dump($user);

        echo "<br>";
        echo "<br>";

        //调用getAll方法
        $userAll = $client->getAll();
        var_dump($userAll);
        //$client->TestException();
        exit();
        //return $this->render('index');
    }


    /**
     * Login action.
     *
     * @return string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }
}
