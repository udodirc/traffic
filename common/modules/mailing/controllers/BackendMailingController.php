<?php
namespace common\modules\mailing\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\modules\mailing\models\forms\MailingForm;
use common\modules\mailing\models\forms\UpdateEmailForm;
use common\modules\mailing\models\Mailing;
use common\components\geo\IsoHelper;

/**
 * BackendMailingController implements the CRUD actions for Content model.
 */
class BackendMailingController extends Controller
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
     * Lists all Partners models.
     * @return mixed
     */
    public function actionIndex()
    {
		$this->permission = 'view';
		
		$model = new MailingForm();
		
		if($model->load(Yii::$app->request->post()))
        {
			$class = 'error';
			$msg = Yii::t('messages', 'Ошибка!');
		
			if($model->sendMessage($model))
			{
				$class = 'success';
				$msg = Yii::t('messages', 'Рассылка создана!');
			}
			
			\Yii::$app->getSession()->setFlash($class, Yii::t('messages', $msg));
				
			return $this->redirect(['index']);
		}
		
		return $this->render('index', [
			'model' => $model,
		]);
	}
	
	public function actionUpdateEmailFiles($type)
    {
		$this->permission = 'create';
		
		$leader = ($type > 1) ? true : false;
		$class = 'error';
		$msg = Yii::t('messages', 'Ошибка!');
		
 		if(Mailing::updatePartnersEmails($leader))
		{
			$class = 'success';
			$msg = Yii::t('messages', 'Файл создан!');
		}
			
		\Yii::$app->getSession()->setFlash($class, Yii::t('messages', $msg));
				
		return $this->redirect(['index']);
	}
	
	public function actionUpdateEmailFileByFilters()
    {
		$this->permission = 'create';
		
		$model = new UpdateEmailForm();
		$countryList = IsoHelper::getIsoList('en');
		
		if($model->load(Yii::$app->request->post()))
		{
			$class = 'error';
			$msg = Yii::t('messages', 'Ошибка!');
			
			if($model->updateFile($model))
			{
				$file = Yii::getAlias('@backend_upload_dir').DIRECTORY_SEPARATOR.'email'.DIRECTORY_SEPARATOR.'email_list.txt';
				\Yii::$app->response->sendFile($file);
				
				$class = 'success';
				$msg = Yii::t('messages', 'Файл обновлен!');
			}
				
			//\Yii::$app->getSession()->setFlash($class, Yii::t('messages', $msg));
				
			//return $this->redirect(['index']);
		}
		
		return $this->render('update_emails_by_filters', [
			'model' => $model,
			'country_list' => $countryList,
			'statuses_list' => (isset(Yii::$app->params['update_emails_statuses_list'])) ? Yii::$app->params['update_emails_statuses_list'] : [],
		]);
	}
	
	public function actionDownloadEmailFiles($type)
    {
		$this->permission = 'view';
		
		$file = ($type > 1) ? 'leaders_email_list.txt' : 'email_list.txt';
		$file = Yii::getAlias('@backend_upload_dir').DIRECTORY_SEPARATOR.'email'.DIRECTORY_SEPARATOR.$file;
		header('Content-Type: application/octet-stream');
		header("Content-Transfer-Encoding: Binary"); 
		header("Content-disposition: attachment; filename=\"" . basename($file) . "\""); 
		
		readfile($file);
	}
}
