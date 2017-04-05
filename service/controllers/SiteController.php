<?php

namespace service\controllers;

use service\base\ServiceException;
use service\modules\user\services\UserService;
use Yii;
use yii\filters\AccessControl;
use service\base\ServiceController;
use yii\filters\VerbFilter;
use service\models\LoginForm;
use service\models\ContactForm;
use Hprose\Http\Server;
use service\models\User;

/**
 * 服务层对外服务 控制器层（HTTP协议）
 * 请继承 ServiceController
 * Class SiteController
 * @package service\controllers
 */
class SiteController extends ServiceController
{

    public $enableCsrfValidation = false;

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
     *
     *
     * 这个是服务层service对外提供的用户接口
     *
     * 可以开启某个方法，也可以开启整个类
     *
     * https://github.com/hprose/hprose-yii/wiki/%E4%BD%BF%E7%94%A8%E6%96%B9%E6%B3%95
     *
     * 《注意：测试可以直接调用，需要配置》
     * 访问：http://service.com/user
     * 输出：Fa3{u#s5"hello"s6"getAll"}z
     * @return string
     */
    public function actionIndex()
    {
        $server = new Server();
        $anObject = new User();

        $server->addInstanceMethods($anObject);
        return $server->start();
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
