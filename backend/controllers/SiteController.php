<?php
namespace backend\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use app\models\LoginForm;
use yii\filters\VerbFilter;

/**
 * Site controller
 */
class SiteController extends Controller
{
	public $layout = 'admin';
	
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login', 'error'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'index'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['get'],
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
        ];
    }

    public function actionIndex()
    {
		$model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
          return $this->redirect(\Yii::$app->request->BaseUrl.'/users/profile/'.Yii::$app->user->id);
        } else {
            return $this->render('index', [
                'model' => $model,
            ]);
        }
    }

    public function actionLogin()
    {
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
           return $this->redirect(\Yii::$app->request->BaseUrl.'/users/profile/'.Yii::$app->user->id);
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
}
