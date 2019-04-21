<?php

namespace common\modules\landings\controllers;

use Yii;
use common\modules\landings\models\Landings;
use common\modules\backoffice\models\Partners;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * FrontendLandingsController implements the CRUD actions for Pages model.
 */
class FrontendLandingsController extends Controller
{
	public $layout = 'front';
	
	/**
     * Display page.
     * @return mixed
     */
    public function actionIndex($id, $login)
    {
		$model = Landings::find()->select(['styles', 'inner_style', 'js', 'body'])->where('id =:id', [':id'=>$id])->one();
		$partnersModel = Partners::find()->select(['id', 'login', 'first_name', 'last_name', 'created_at'])->where('login =:login', [':login'=>$login])->one();
		
		if($model != null && $partnersModel != null)
		{
			$sourcePath = Landings::getSourcePath('css_path', $id);
			
			if($sourcePath != '')
			{
				$this->view->params['source_path'] = $sourcePath;
				$this->view->params['styles'] = ($model->styles != '') ? explode(';', $model->styles) : [];
				$this->view->params['js'] = ($model->js != '') ? explode(';', $model->js) : [];
				$this->view->params['inner_style'] = $model->inner_style;
				$this->view->params['inner_js'] = $model->inner_js;
				$model->body = Landings::parseTags($id, $model->body, $partnersModel);
				
				return $this->render('index', [
					'model' => $model
				]);
			}
		}
		
		throw new NotFoundHttpException(Yii::t('messages', 'Такой страницы нет!'));
	}
}
