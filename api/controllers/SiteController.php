<?php

namespace api\controllers;

use Yii;
use yii\filters\AccessControl;
use api\base\ApiController;
use yii\filters\VerbFilter;
use api\models\LoginForm;
use api\models\ContactForm;

use \Hprose\Http\Client;
use Hprose\InvokeSettings;
use Hprose\ResultMode;


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
        //通过RPC，调用service代码
        $client = Client::create('http://service.dev.dabaozha.com/user', false);
        //调用hello函数
        $user = $client->hello('Word');
        //输出：string(11) "Hello Word!"
        var_dump($user);

        echo "<br>";
        echo "<br>";

        //调用getAll方法
        $userAll = $client->getAll();
        var_dump($userAll);
        exit();

//        return $this->render('index');
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
