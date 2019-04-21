<?php
namespace common\modules\advertisement\controllers;

use Yii;
use common\modules\advertisement\models\forms\AddBannerForm;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * BackendBannersController.
 */
class BackendBannersController extends Controller
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
		
		$model = new AddBannerForm();
		$category = 'banners_advert';
		$url = (isset(Yii::$app->params['upload_dir'][$category])) ? Yii::getAlias('@banners_advert').DIRECTORY_SEPARATOR : '';
		$thumbnail = (isset(Yii::$app->params['upload_dir'][$category])) ? Yii::$app->params['upload_dir'][$category]['image_sizes']['thumbnail'] : [];
		
		return $this->render('index', [
			'model' => $model,
			'category' => $category,
			'url' => $url,
			'thumbnail' => $thumbnail
		]);
	}
}
