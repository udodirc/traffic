<?php
namespace common\modules\structure\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\modules\structure\models\GoldToken;
use common\modules\structure\models\GoldTokenSearch;

/**
 * BackendGoldTokenController implements the CRUD actions for GoldToken model.
 */
class BackendGoldTokenController extends Controller
{
    public $layout = 'backend';
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
     * Lists all GoldToken models.
     * @return mixed
     */
    public function actionIndex()
    {
		$this->permission = 'view';
		
        $searchModel = new GoldTokenSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
         $structuresList = (isset(\Yii::$app->params['structures'])) ? \Yii::$app->params['structures'] : [];

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'structuresList' => $structuresList,
        ]);
    }
}
