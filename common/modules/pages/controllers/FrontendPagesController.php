<?php

namespace common\modules\pages\controllers;

use Yii;
use common\modules\pages\models\Pages;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * FrontendPagesController implements the CRUD actions for Pages model.
 */
class FrontendPagesController extends Controller
{
	public $layout = 'front';
	
	/**
     * Display page.
     * @return mixed
     */
    public function actionIndex($url)
    {
		$model = Pages::find()->select(['body'])->where('url =:url', [':url'=>'page/'.$url])->one();
		
        return $this->render('index', [
            'model' => $model
        ]);
	}
}
