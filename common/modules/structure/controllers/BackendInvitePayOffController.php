<?php
namespace common\modules\structure\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\modules\structure\models\DemoInvitePayOffSearch;
use common\modules\structure\models\InvitePayOff;
use common\modules\structure\models\InvitePayOffSearch;
use common\modules\structure\models\Payment;
use common\models\Service;

/**
 * BackendInvitePayOffController implements the CRUD actions for InvitePayOff model.
 */
class BackendInvitePayOffController extends Controller
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
     * Lists all InvitePayOff models.
     * @return mixed
     */
    public function actionIndex()
    {
		$this->permission = 'view';
		
        $searchModel = new InvitePayOffSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $structuresList = (isset(\Yii::$app->params['structures'])) ? \Yii::$app->params['structures'] : [];

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'structuresList' => $structuresList,
        ]);
    }
    
    /**
     * Mark the payment in InvitePayOff models.
     * @return mixed
     */
    public function actionMarkPayOff($id)
    {
		$this->permission = 'view';
		
		//Initialization
		$result = true;
		$class = 'success';
		$msg = Yii::t('messages', 'Запись обновлена!');
		
		//Set mode
		$model = InvitePayOff::find()->where('id=:id', [':id' => $id])->one();
		
		if($model->paid_off == 0)
		{
			$model->paid_off = 1;
			$result = $model->save(false);
		}
		
		if(!$result)
		{
			$class = 'error';
			$msg = Yii::t('messages', 'Ошибка!');
		}
		
		\Yii::$app->getSession()->setFlash($class, Yii::t('messages', $msg));
				
		return $this->redirect(['index']);
	}
	
	/**
     * Make the pay off through wallet.
     * @return mixed
     */
    public function actionMakePayOff($id, $type)
    {
		$this->permission = 'view';
		
		//Initialization
		$url = '';
		$paymentsTypes = (isset(Yii::$app->params['payments_types'])) ? Yii::$app->params['payments_types'] : [];
		
		if(!empty($paymentsTypes) && $id > 0)
		{
			$paymentType = Payment::getPaymentType();
			
			if(isset($paymentsTypes[$paymentType][1]))
			{
				switch($paymentsTypes[$paymentType][1])
				{
					case 'payeer_wallet':
					
						$url = 'payeer/make-pay-off?id='.$id.'&type='.$type;
						
					break;
						
					default:
	
						$url = '';
						
					break;
				}
			}
		}

		if($url != '')
		{
			return $this->redirect(\Yii::$app->request->BaseUrl.'/'.$url);
		}
		else
		{
			return $this->redirect(['index']);
		}
	}
}
