<?php

namespace backend\controllers;

use Yii;
use common\models\Partners;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\ActiveDataProvider;

/**
 * PartnersController implements the CRUD actions for Content model.
 */
class PartnersController extends Controller
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
            ]
        ];
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
     * Lists all Content models.
     * @return mixed
     */
    public function actionIndex()
    {
		$this->permission = 'view';
		
		$model = new Partners;
        $dataProvider = new ActiveDataProvider([
            'query' => $model->getPartnersList(),
            'pagination' => [
                'pageSize' => 50,
            ],
        ]);
        
		return $this->render('index', [
			'dataProvider' => $dataProvider
		]);
    }
    
    public function actionPartnerInfo($id)
    {
		$this->permission = 'view';
		
		$model = new Partners;
		$partnerModel = $model->getPartnerStructure($id);
		$partnerInfo = $model->getPartnerInfo($id);
		
		if($partnerModel !== null && $partnerInfo !== null) 
        {
			$dataProvider = new ActiveDataProvider([
				'query' => $partnerModel,
				'pagination' => [
					'pageSize' => 50,
				],
			]);
        
            return $this->render('partner_info', [
				'dataProvider' => $dataProvider,
				'model' => $partnerInfo
			]);
        } 
        else 
        {
            throw new NotFoundHttpException(Yii::t('messages','Страница не найдена!'));
        }
	}
	
	/**
     * Deletes an existing Partner model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
		$this->permission = 'delete';
		
        $result = true;
		\Yii::$app->getSession()->setFlash(($result) ? 'success' : 'error', Yii::t('messages', ($result) ? 'Запись удалена!' : 'Ошибка!'));

        return $this->redirect(['index']);
    }
}
