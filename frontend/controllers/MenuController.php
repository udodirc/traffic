<?php
namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use common\models\Menu;
use common\models\Content;
use common\models\StaticContent;
use common\modules\backoffice\models\Partners;
use common\modules\structure\models\RegisterStats;
use common\modules\backoffice\models\forms\LoginForm;
use common\modules\structure\models\DemoMatrixPayments;
use frontend\models\FeedbackForm;

class MenuController extends \yii\web\Controller
{
	public function actionIndex()
    {
		$this->layout = (!\Yii::$app->user->isGuest) ? 'back_office' : 'page';
		
		if(\Yii::$app->user->isGuest)
		{
			$this->view->params['model'] = new LoginForm();
			$this->view->params['feedbackModel'] = new FeedbackForm();
			$this->view->params['contacts'] = (!is_null(StaticContent::find()->where(['name'=>'contacts']))) ? StaticContent::find()->where(['name'=>'contacts'])->one() : '';
			$this->view->params['brand_slogan'] = (isset(Yii::$app->params['brand_slogan'])) ? Yii::$app->params['brand_slogan'] : '';
			$this->view->params['phone'] = (!is_null(StaticContent::find()->where(['name'=>'phone'])->one())) ? StaticContent::find()->where(['name'=>'phone'])->one()->content : '';
			$this->view->params['location'] = (!is_null(StaticContent::find()->where(['name'=>'location'])->one())) ? StaticContent::find()->where(['name'=>'location'])->one()->content : '';
		}
		
		$content = null;
		$backoffice = false;
		$url = pathinfo(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), PATHINFO_BASENAME);
		$title = '';
		$metaTitle = '';
		$metaDesc = '';
		$metaKeys = '';
		$data = Menu::find()->select(['id', 'parent_id', 'category_id', 'content_id', 'controller_id', 'name', 'backoffice', 'partner_status'])->where('url =:url AND status != :status', [':url'=>$url, 'status'=>Menu::STATUS_NOT_PUBLISH])->one();
		
		if($data !== null)
		{
			$id = (!is_null(\Yii::$app->user->identity)) ? \Yii::$app->user->identity->id : 0;
			$nowTime = strtotime('now');
			$partnerData = Partners::find()->where(['id'=>$id])->one();
			$registerDate = (!is_null($partnerData)) ? ($partnerData->created_at + ((isset(Yii::$app->params['count_down_days'])) ? (Yii::$app->params['count_down_days'] * 86400) : 0)) : 0;
			$status = 0;
			
			if(($data->backoffice > 0))
			{	
				$backoffice = true;
				$status = ($partnerData !== null) ? $partnerData->status : 0;
				/*$this->view->params['register_bonus'] = (isset(Yii::$app->params['register_bonus'])) ? Yii::$app->params['register_bonus'] : 0;
				$this->view->params['register_tokens'] = (isset(Yii::$app->params['register_tokens'])) ? Yii::$app->params['register_tokens'] : 0;
				$this->view->params['curr_day_register'] = (!is_null(RegisterStats::getRegisterCountByCurrentDay())) ? RegisterStats::getRegisterCountByCurrentDay()->register_stats : 0; 
				$this->view->params['total_register'] = (!is_null(Partners::find())) ? Partners::find()->count() : 0;*/
				
				if($id <= 0)
				{
					throw new NotFoundHttpException(Yii::t('messages', 'У вас нет прав!'));
				}
			} 
			
			//$this->view->params['total_amount'] = (!is_null(DemoMatrixPayments::find()->where(['partner_id'=>$id, 'type'=>2])->sum('amount'))) ? DemoMatrixPayments::find()->where(['partner_id'=>$id, 'type'=>2])->sum('amount') : 0;
			
			$menuCategoryList = array_flip(Yii::$app->params['menu_category']);
			$controllersList = Yii::$app->params['controllers'];
			
			if(isset($menuCategoryList[$data->category_id]) && isset($controllersList[$data->controller_id]))
			{
				$className = 'common\models\\'.$controllersList[$data->controller_id]['model'];
				$dataModel = new $className();
				$breadCrumbs = [];
				$title = $data->name;
				$metaTitle = '';
				$metaDesc = '';
				$metaKeys = '';
				
				if($backoffice && ($status < $data->partner_status))
				{
					$content = (!is_null(StaticContent::find()->where(['name'=>'purchase_content']))) ? StaticContent::find()->where(['name'=>'purchase_content'])->one() : null;
				}
				else
				{
					$breadCrumbs = Menu::createBreadCrumbs($data, $url);
					$content = $dataModel::find()->where('id =:id AND status != :status', ['id'=>$data->content_id, 'status'=>Content::STATUS_NOT_PUBLISH])->one();
					$title = $data->name;
					$metaTitle = (!is_null($content)) ? $content->meta_title : '';
					$metaDesc = (!is_null($content)) ? $content->meta_description : '';
					$metaKeys = (!is_null($content)) ? $content->meta_keywords : '';
				}
				
				$this->view->params['title'] = $title;
				$this->view->params['bread_crumbs'] = $breadCrumbs;
			}
		}
		
		return $this->render(($backoffice) ? 'backoffice_index' : 'index', [
			'title'=>$title, 
			'data'=>$content,
			'meta_title'=>$metaTitle,
			'meta_desc'=>$metaDesc,
			'meta_keys'=>$metaKeys,
		]);
    }
}
