<?php
namespace common\modules\messages\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\modules\messages\models\Messages;
use common\modules\messages\models\forms\MessageForm;

/**
 * BackendMessagesController implements the CRUD actions for Content model.
 */
class BackendMessagesController extends Controller
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
		
		$model = new Messages();
		
		return $this->render('index', [
			'model' => $model,
		]);
	}
	
	/**
     * Creates a new Messages model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new MessageForm();
		
        if($model->load(Yii::$app->request->post())) 
        {
			$class = 'error';
			$msg = Yii::t('messages', 'Ошибка!');
			$result = $model->createMessage($model);
			
			/*if($model->sendMessage($model))
			{
				$class = 'success';
				$msg = Yii::t('messages', 'Рассылка создана!');
			}*/
			
			\Yii::$app->getSession()->setFlash($class, Yii::t('messages', $msg));
				
			//return $this->redirect(['index']);
        } 
        else 
        {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }
}
