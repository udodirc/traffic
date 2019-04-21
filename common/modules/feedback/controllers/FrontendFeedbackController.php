<?php

namespace common\modules\feedback\controllers;

use Yii;
use common\modules\feedback\models\Feedback;
use common\models\Service;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * FrontendFeedbackController implements the CRUD actions for News model.
 */
class FrontendFeedbackController extends Controller
{
	/**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ]
        ];
    }
    
    /**
     * Renders the index view for the module
     * @return string
    */
    public function actionIndex()
    {
		//throw new NotFoundHttpException(Yii::t('messages', 'Такой страницы не существует!'));
		
		$this->view->params['brand_slogan'] = (isset(Yii::$app->params['brand_slogan'])) ? Yii::$app->params['brand_slogan'] : '';
		$this->view->params['title'] = Yii::t('form', 'Отзывы');
		
        $feedbackList = new ActiveDataProvider([
			'query' => Feedback::find()->orderBy('created_at DESC'),
			'pagination' => [
				'pageSize' => Service::getPageSize(),
			],
		]);
		
        return $this->render('index', [
            'feedbackList'=>$feedbackList
        ]);
    }
}
