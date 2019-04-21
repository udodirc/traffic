<?php
namespace common\modules\payeer\controllers;

use Yii;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use common\modules\payeer\models\PayeerPayments;
use common\modules\payeer\models\PayeerPaymentsSearch;

/**
 * PayeerPayments controller for the `payeer` module
 */
class PayeerPaymentsController extends Controller
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
		
        $searchModel = new PayeerPaymentsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $structuresList = (isset(\Yii::$app->params['structures'])) ? \Yii::$app->params['structures'] : [];
        $paymentTypeList = PayeerPayments::getPaymentTypeList();
        $currencyList = PayeerPayments::getCurrencyList();

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'structuresList' => $structuresList,
            'paymentTypeList' => $paymentTypeList,
            'currencyList' => $currencyList,
        ]);
    }
}
