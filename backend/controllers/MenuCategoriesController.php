<?php

namespace backend\controllers;

use Yii;
use app\models\MenuCategories;
use app\models\MenuCategoriesSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * MenuCategoriesController implements the CRUD actions for MenuCategories model.
 */
class MenuCategoriesController extends Controller
{
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

    /**
     * Lists all MenuCategories models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new MenuCategoriesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single MenuCategories model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new MenuCategories model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new MenuCategories();

        if ($model->load(Yii::$app->request->post()) && $model->save()) 
        {
			\Yii::$app->getSession()->setFlash('success', Yii::t('messages', 'Запись добавлена!'));
            return $this->redirect(['view', 'id' => $model->id]);
        } 
        else 
        {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing MenuCategories model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) 
        {
			\Yii::$app->getSession()->setFlash('success', Yii::t('messages', 'Запись обновлена!'));
            return $this->redirect(['view', 'id' => $model->id]);
        } 
        else 
        {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing MenuCategories model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $result = $this->findModel($id)->delete();
		\Yii::$app->getSession()->setFlash(($result) ? 'success' : 'error', Yii::t('messages', ($result) ? 'Запись удалена!' : 'Ошибка!'));
		 
        return $this->redirect(['index']);
    }

    /**
     * Finds the MenuCategories model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return MenuCategories the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = MenuCategories::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
