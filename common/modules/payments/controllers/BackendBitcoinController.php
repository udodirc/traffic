<?php
namespace common\modules\payments\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * BackendBitcoinController implements the CRUD actions for Bitcoin model.
 */
class BackendBitcoinController extends Controller
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
     * Create an bitcoin payment.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionCreatePayment()
    {
		$secret = (isset($this->params['bitcoin_secret'])) ? $this->params['bitcoin_secret'] : '';
		$xpubAddress = (isset($this->params['xpub_address'])) ? $this->params['xpub_address'] : '';
		$apiKey = (isset($this->params['api_key'])) ? $this->params['api_key'] : '';
		$rootUrl = (isset($this->params['root_url'])) ? $this->params['root_url'] : '';
		
		if($secret != '' && $xpubAddress != '' && $apiKey != '' && $rootUrl != '')
		{	
			$myCallbackUrl = 'https://mystore.com?invoice_id=058921123&secret='.$secret;
			$parameters = 'xpub=' .$xpubAddress. '&callback=' .urlencode($myCallbackUrl). '&key=' .$apiKey;
			$response = file_get_contents($rootUrl . '?' . $parameters);
			$object = json_decode($response);
			
			echo '<pre>';
			print_r($object);
			echo '</pre>';
		}
	}
}
