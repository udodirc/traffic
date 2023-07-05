<?php

namespace common\modules\advertisement\controllers;

use common\modules\advertisement\models\TextAdvert;
use common\modules\advertisement\models\TextAdvertSearch;
use common\models\Service;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class BackendTextAdvertController  extends Controller
{
	protected $permission;

	public function behaviors()
	{
		return [
			'verbs' => [
				'class' => VerbFilter::className(),
				'actions' => [
					'delete' => ['post'],
				],
			],
		];
	}

	public function beforeAction($action)
	{
		$this->enableCsrfValidation = (($action->id !== "create"));

		return parent::beforeAction($action);
	}

	/**
	 * Check permission's rights
	 * @return mixed
	 */
	public function afterAction($action, $result)
	{
		if(!\Yii::$app->user->can($this->permission))
		{
			throw new NotFoundHttpException(Yii::t('messages', 'У вас нет прав!'));
		}

		return parent::afterAction($action, $result);
	}

	/**
	 * Lists all TextAdvert models.
	 * @return mixed
	 */
	public function actionIndex()
	{
		$this->permission = 'view';

		$searchModel = new TextAdvertSearch();
		$dataProvider = $searchModel->search(null, Yii::$app->request->queryParams, true);
		$dataProvider->pagination->pageSize = Service::getPageSize();
		$this->view->params['title'] = Yii::t('form', 'Текстовая реклама');

		return $this->render('index', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
		]);
	}

	/**
	 * Lists all TextAdvert models.
	 * @return mixed
	 */
	public function actionView($id)
	{
		$this->permission = 'view';

		return $this->render('view', [
			'model' => $this->findModel($id)
		]);
	}

	/**
	 * Publish item in base.
	 * If publish is successful, the browser will be redirected to the 'index' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionStatus($id, $status)
	{
		$this->permission = 'update';

		$model = $this->findModel($id);
		$model->status = ($status > 0) ? 0 : 1;
		$model->save(false);  // equivalent to $model->update();

		return $this->redirect(['index']);
	}

	/**
	 * Deletes an existing Content model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionDelete($id)
	{
		$this->permission = 'delete';

		$result = $this->findModel($id)->delete();
		\Yii::$app->getSession()->setFlash(($result) ? 'success' : 'error', Yii::t('messages', ($result) ? 'Запись удалена!' : 'Ошибка!'));

		return $this->redirect(['index']);
	}

	/**
	 * Finds the TextAdvert model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @param integer $id
	 * @return TextAdvert the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id)
	{
		if (($model = TextAdvert::findOne($id)) !== null) {
			return $model;
		} else {
			throw new NotFoundHttpException(Yii::t('messages', 'Эта страница не существует!'));
		}
	}
}