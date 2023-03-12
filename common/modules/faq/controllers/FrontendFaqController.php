<?php
namespace common\modules\faq\controllers;

use Yii;
use common\modules\faq\models\Faq;
use common\models\StaticContent;
use common\models\Menu;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * FrontendFaqControllerController implements the CRUD actions.
 */
class FrontendFaqController extends Controller
{
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
     * Renders the index view for the module
     * @return string
    */
    public function actionIndex()
    {
	    if (!Yii::$app->user->isGuest)
	    {
		    return $this->goHome();
	    }

		$this->layout = 'main';
		$faqList = Faq::find()->where(['type'=>1])->all();
		$content = (!is_null(StaticContent::find()->where(['name'=>'faq']))) ? StaticContent::find()->where(['name'=>'faq'])->one() : null;
		$this->view->params['brand_slogan'] = (isset(Yii::$app->params['brand_slogan'])) ? Yii::$app->params['brand_slogan'] : '';
		$this->view->params['phone'] = (!is_null(StaticContent::find()->where(['name'=>'phone'])->one())) ? StaticContent::find()->where(['name'=>'phone'])->one()->content : '';
		$this->view->params['location'] = (!is_null(StaticContent::find()->where(['name'=>'location'])->one())) ? StaticContent::find()->where(['name'=>'location'])->one()->content : '';
		$url = pathinfo(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), PATHINFO_BASENAME);
		$this->view->params['bread_crumbs'] = Menu::createBreadCrumbs(null, $url, Yii::t('form', 'F.A.Q'));
		
        return $this->render('index', [
            'faqList'=>$faqList,
            'content'=>$content
        ]);
    }
}
