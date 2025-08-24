<?php

namespace common\models;

use Yii;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use common\modules\backoffice\models\Partners;
use common\components\geo\IsoHelper;
use common\models\Content;

/**
 * This is the model class for table "menu".
 *
 * @property integer $id
 * @property integer $category_id
 * @property integer $parent_id
 * @property integer $controller_id
 * @property integer $content_id
 * @property string $name
 * @property string $url
 */
class Menu extends \yii\db\ActiveRecord
{
	const STATUS_NOT_PUBLISH = 0;
    const STATUS_PUBLISH = 1;
    const PARTNER_STATUS = 0;
    
	public $submenu_id;
	public $content_name;
	public $menu_name;
	public $controller_submenu_id;
	
	/**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'menu';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['category_id', 'name', 'url', 'controller_id', 'partner_status'], 'required', 'message' => Yii::t('form', 'Это поле должно быть заполнено!')],
            [['backoffice', 'partner_status'], 'integer'],
            ['partner_status', 'default', 'value' => self::PARTNER_STATUS],
            ['status', 'default', 'value' => self::STATUS_NOT_PUBLISH],
            //['name', 'unique', 'targetAttribute' => 'name'],
            [['name', 'url'], 'string', 'max' => 100, 'message' => Yii::t('form', 'В этом поле максимально допустимо 100 символов!')],
            [['name', 'url'], 'string', 'min' => 2, 'message' => Yii::t('form', 'В этом поле минимально допустимое количество символов 2!')],
            [['iso'], 'string', 'max' => 2],
            //['url', 'unique', 'targetAttribute' => 'url'],
            [['category_id', 'controller_id', 'controller_submenu_id', 'content_id', 'parent_id', 'submenu_id'], 'integer'],
		];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('form', 'ID'),
            'category_id' => Yii::t('form', 'Тип меню'),
            'parent_id' => Yii::t('form', 'Меню'),
            'name' => Yii::t('form', 'Название меню'),
            'url' => Yii::t('form', 'Url'),
            'backoffice' => Yii::t('form', 'Личный кабинет'),
            'partner_status' => Yii::t('form', 'Статус партнера'),
            'controller_id' => Yii::t('form', 'Модуль'),
            'submenu_id' => Yii::t('form', 'Контент модуля'),
            'iso' => Yii::t('form', 'Страна'),
        ];
    }
    
    /**
     * Add menu id before user insert or update
     *
     * @return mixed
     */
    public function beforeSave($insert) 
    {
		if(parent::beforeSave($insert)) 
		{	
			if($this->isNewRecord) 
			{	
				$this->parent_id = ($this->parent_id === '' || $this->parent_id == NULL) ? 0 : $this->parent_id;
				$this->parent_id = ($this->submenu_id === '' || $this->submenu_id == NULL) ? $this->parent_id : $this->submenu_id;
				$this->content_id = ($this->content_id === '' || $this->content_id == NULL) ? 0 : $this->content_id;
				$this->content_id = ($this->controller_submenu_id === '' || $this->controller_submenu_id == NULL) ? $this->content_id : $this->controller_submenu_id;
			}
		}
		
		return parent::beforeSave($insert);
	}
	
	/**
     * Scenarios
     *
     * @return mixed
     */
    public function scenarios()
	{
		$scenarios = parent::scenarios();
		
        $scenarios['create'] = ['category_id', 'parent_id', 'name', 'url', 'iso', 'controller_id', 'content_id', 'backoffice', 'partner_status'];
		
		$scenarios['update'] = ['name', 'url', 'backoffice', 'partner_status'];
        
		return $scenarios;
    }
	
	/**
     * Relation with content table by content_id field
     */
    public function getContent()
    {
        return $this->hasOne(Content::className(), ['content_id' => 'id']);
    }
    
    public function getMenuDataByID($id)
    {
		$controllerID = Yii::$app->params['content_controller'];
		
		return self::find()
		->select('menu_1.name AS menu_name, content.title as content_name, menu_2.id, menu_2.controller_id, menu_2.name AS name, menu_2.url, menu_2.backoffice, menu_2.partner_status')
		->from('menu menu_1')
		->rightJoin('menu menu_2', 'menu_1.id = menu_2.parent_id')
		->leftJoin('content', 'content.id = menu_2.content_id AND menu_2.controller_id = :controller_id', [':controller_id' => $controllerID])
		->where('menu_2.id=:id', [':id' => $id])
		->one();
	}
	
	public static function getSubmenuList($categoryID, $backoffice)
    {
		$result = array();
		$submenuList = self::find()->select(['parent_id', 'name', 'url'])->where('parent_id > 0 AND category_id = :category_id AND status != :status', [':category_id'=>$categoryID, ':status'=>0])->asArray()->all();
		
		foreach($submenuList as $i=>$submenu)
		{
			$result[$submenu['parent_id']][] = [
				'label' => $submenu['name'], 
				'url' => Url::base().DIRECTORY_SEPARATOR.$submenu['url'], 
				'template' => ($backoffice) ? '
				<a href="{url}">
					{label}
				</a>' : 
				'<li class="iceMenuLiLevel_2">
					<a href="{url}" class="iceMenuTitle">
						<span class="icemega_title icemega_nosubtitle">{label}</span>
					</a>
				</li>',
			];
		}
		
		return $result;
	}
	
	public static function getSubmenuListByParentID($parentID)
    {
		$result = array();
		$submenuList = self::find()->select(['name', 'url'])->where('parent_id = :parent_id', [':parent_id'=>$parentID])->asArray()->all();
		
		foreach($submenuList as $i=>$submenu)
		{
			$result[$submenu['name']] = Url::base().DIRECTORY_SEPARATOR.$submenu['url'];
		}
		
		return $result;
	}
	
	public static function getFrontMenuNav($categoryID, $topMenu = true)
    {
		$result = array();
		$submenuList = self::getSubmenuList($categoryID);
		$menuList = self::find()->select(['id', 'name', 'url'])->where('parent_id = 0 AND category_id = :category_id AND status != :status', ['category_id'=>$categoryID, 'status'=>0])->asArray()->all();
		
		foreach($menuList as $i => $menu)
		{
			$result[$i] = ['label' => Yii::t('menu', $menu['name']), 'url' => Url::base().DIRECTORY_SEPARATOR.$menu['url'], 'options'=>['class'=>'li-menu'], 'template' => '<a href="{url}" class="nav-menu-link">{label}</a>'];
		}
		
		return $result;
	}
	
	public static function getFrontMenuByCategory($categoryID)
    {
		return ArrayHelper::map(self::find()->select(['name', 'url'])->where('category_id = :category_id AND status != :status', ['category_id'=>$categoryID, 'status'=>0])->asArray()->all(), 'name', 'url');
	}
	
	public function getMenuDataByName($name, $admin = false)
    {
		$data = self::find()
		->select('id')
		->from(($admin) ? 'admin_menu' : 'menu')
		->where('name=:name', [':name' => $name])
		->one();
		
		return ($data !== null) ? $data->id : 0;
	}
	
	public static function getMenuList($categoryID, $backoffice = false, $front = true, $image = 'frontend_images')
    {
		$result = [];
		$menuList = self::find()->select(['id', 'parent_id', 'name', 'partner_status',  'url',  'iso'])->where('parent_id = 0 AND category_id = :category_id AND status != :status', ['category_id'=>$categoryID, 'status'=>0])->asArray()->all();
		$submenuList = self::getSubmenuList($categoryID, $backoffice);
		
		if($backoffice)
		{
			$id = (!is_null(\Yii::$app->user->identity)) ? \Yii::$app->user->identity->id : 0;		
			$partnerData = (Partners::find()->select(['status'])->where(['id'=>$id]) !== null) ? Partners::find()->select(['status'])->where(['id'=>$id])->one() : null;
			$status = ($partnerData !== null) ? $partnerData->status : 0;
			$statusList = (isset(Yii::$app->params['partner_statuses'])) ? Yii::$app->params['partner_statuses'] : [];
			
			$result = [
				[
					'url' => ['/'],
					'options'=>['class'=>'nav-item nav-profile'],
					'template' => '<div class="nav-link d-flex">
						<div class="profile-image">
							<img src="https://via.placeholder.com/37x37" alt="image">
						</div>
						<div class="profile-name">
							<p class="name">
								'.((!is_null(\Yii::$app->user->identity)) ? \Yii::$app->user->identity->login : '').'
							</p>
						</div>
					</div>',
				],
				[
					'label' => Yii::t('menu', 'Новости'),
					'url' => ['/news']
				],
				[
					'label' => Yii::t('menu', 'Профиль'),
					'url' => ['/partners/profile/'.((!is_null(\Yii::$app->user->identity)) ? \Yii::$app->user->identity->id : 0)]
				],
				/*[
					'label' => Yii::t('menu', 'Вывод'),
					'url' => ['/partners/withdrawal/'.((!is_null(\Yii::$app->user->identity)) ? \Yii::$app->user->identity->id : 0)],
					'template' => '<a href="{url}">
						<i class="fa fa-files-o" aria-hidden="true"></i>
						<span>{label}</span>
					</a>',
				],*/
				[
					'label' => Yii::t('menu', 'Структура'),
					'url' => ['/partners/structure']
				],
				[
					'label' => Yii::t('menu', 'Актив-ть место'),
					'url' => ['/partners/activation'],
				],
				[
					'label' => Yii::t('menu', 'Ваш заработок'),
					'url' => ['/partners/matrices'],
				],
				/*[
					'label' => Yii::t('menu', 'Статистика'),
					'url' => ['/partners/partner-info'],
				],
				[
					'label' => Yii::t('menu', 'Запрос на вывод'),
					'url' => ['/partners/withdrawal'],
				],*/
				[
					'label' => Yii::t('menu', 'TOP Leaders'),
					'url' => ['/partners/top-leaders'],
				],
				/*[
					'label' => Yii::t('menu', 'Ожид-ый заработок'),
					'url' => ['/partners/partners-levels/1'],
					'template' => '<a href="{url}">
						<div class="pull-left">
							<i class="zmdi zmdi-apps mr-20"></i>
							<span class="right-nav-text">{label}</span>
						</div>
						<div class="clearfix"></div>
					</a>',
				],
				[
					'label' => Yii::t('menu', 'Реал-ый заработок'),
					'url' => ['/partners/partners-levels/0'],
					'template' => '<a href="{url}">
						<div class="pull-left">
							<i class="zmdi zmdi-apps mr-20"></i>
							<span class="right-nav-text">{label}</span>
						</div>
						<div class="clearfix"></div>
					</a>',
				],*/
			];
			
			if(\Yii::$app->session->get('user.top_leader'))
			{
				$result[] = 
				[
					'label' => Yii::t('menu', 'Топ лидерам'),
					'url' => ['/top_leader_info'],
				];
			}
			
			$result[] = [
				'label' => Yii::t('menu', 'Материалы'),
				'url' => '#advert',
				'ui' => 'advert',
				'items' => [
					[
						'label' => Yii::t('menu', 'Реферальные ссылки'), 
						'url' => ['/partners/referals-links']
					],
					[
						'label' => Yii::t('menu', 'Баннерная реклама'), 
						'url' => ['/partners/banners']
					],
					/*[
						'label' => Yii::t('menu', 'Спонсорская реклама'),
						'url' => ['/advertisement/sponsor_advert'],
						'template' => (isset($submenuTemplate['itemsTemplate']) ? $submenuTemplate['itemsTemplate'] : '')
					],*/
				]
			];

			$result[] = [
				'label' => Yii::t('menu', 'Текстовая реклама'),
				'url' => '#text-advert',
				'ui' => 'text-advert',
				'items' => [
					[
						'label' => Yii::t('menu', 'Вся реклама'),
						'url' => ['/partners/text-advert-list'],
					],
					[
						'label' => Yii::t('menu', 'Моя реклама'),
						'url' => ['/partners/partner-text-advert'],
					]
				]
			];
		}
		
		$access = true;
		$isoList = IsoHelper::getIsoList('en');
		
		foreach($menuList as $i => $menu)
		{
			if($backoffice)
			{
				$access = ($status >= $menu['partner_status']) ? true : false;
			}
			
			if($access)
			{
				if(isset($submenuList[$menu['id']]))
				{	
					$options = ($backoffice) ? [
						//'class'=> 'has-child-item close-item',
					] :
					[
						'class'=> 'iceMenuLiLevel_1 mzr-drop parent',
						'data-hover'=> 'false',
					]; 
					
					$result[] = [
						'label' => Yii::t('menu', Html::encode($menu['name'])),
						'options'=> $options,
						'template' => ($backoffice) ? '<a href="javascript:void(0);" data-toggle="collapse" data-target="#'.$menu['parent_id'].'_dr">
							<div class="pull-left">
								<i class="zmdi zmdi-apps mr-20"></i><span class="right-nav-text">{label}</span>
							</div>
							<div class="pull-right">
								<i class="zmdi zmdi-caret-down"></i>
							</div>
							<div class="clearfix"></div>
						</a>' : 
						'<a class="iceMenuTitle">
							<span class="icemega_title icemega_nosubtitle">{label}</span>
						</a>',
						'submenuTemplate' => ($backoffice) ? '<ul class="nav nav-second-level collapse">{items}</ul>'
						: '<ul id="submenu" class="icesubMenu icemodules sub_level_1" style="width:188px">
							<li>
								<div style="float:left;width:188px" class="iceCols">
									<ul>
										{items}
									</ul>
								</div>
							</li>
						</ul>',
						'items' => $submenuList[$menu['id']]
					];
				}
				else
				{
					$class = ($i > 0) ? 'iceMenuLiLevel_1' : 'iceMenuLiLevel_1 current active fullwidth';
					$icon = '';
					
					if($menu['iso'] != '' && $backoffice != false)
					{
						$access = ((\Yii::$app->user->identity->iso == $menu['iso'])) ? true : false;
						$icon = (isset($isoList[$menu['iso']])) ? '&nbsp;-&nbsp;'.Html::img(\Yii::getAlias('@web').DIRECTORY_SEPARATOR.Url::to('@backoffice_images'.DIRECTORY_SEPARATOR.'flags'.DIRECTORY_SEPARATOR.strtolower($menu['iso']).'.png'), ['alt'=>$isoList[$menu['iso']], 'title'=>$isoList[$menu['iso']]]) : '';
					}
					else
					{
						$access = true;
					}
					
					if($access)
					{
						$result[] = [
							'label' => Yii::t('menu', Html::encode($menu['name'])), 
							//'icon' => $icon,
							'url' => $menu['url'],
							//'url' => Url::base().DIRECTORY_SEPARATOR.$menu['url'], 
							/*'options'=> ($backoffice) ? [] : ['class' => $class],
							'template' => ($backoffice)  ? '<a href="{url}">
								<i class="fa fa-files-o"></i>
								&nbsp;{label}'.$icon.'
							</a>' :
							'<a href="{url}" class="iceMenuTitle">
								<span class="icemega_title icemega_nosubtitle">{label}</span>
							</a>',*/
						];
					}
				}
			}
		}
		
		if($backoffice)
		{
			$result[] = 
			[
				'label' => Yii::t('menu', 'Вознаграждение'),
				'url' => ['/partners/marketing-plan']
			];
			$result[] =
			[
				'label' => Yii::t('menu', 'F.A.Q'),
				'url' => ['/partners/faq']
			];
			
			if(Service::isActionAllowed('is_tickets_allowed'))
			{
				$result[] =
				[
					'label' => Yii::t('menu', 'Напишите нам'),
					'url' => ['/tickets']
				];
			}
			
			if(Service::isActionAllowed('is_feedbacks_allowed'))
			{
				$result[] =
				[
					'label' => Yii::t('menu', 'Отзывы'),
					'url' => ['/partners/feedbacks']
				];
			}
			/*$result[] =
			[
				'label' => Yii::t('menu', 'Реклама спонсора'),
				'url' => ['/advertisement/sponsors_advert'],
				'template' => '<a href="{url}">
					<i class="fa fa-files-o"></i>
					&nbsp;{label}
				</a>',
			];
			$result[] =
			[
				'label' => Yii::t('menu', 'Вся реклама'),
				'url' => ['/advertisement/all_advert'],
				'template' => '<a href="{url}">
					<i class="fa fa-files-o"></i>
					&nbsp;{label}
				</a>',
			];*/
			
			if(Yii::$app->user->identity !== null)
			{
				$result[] = 
				[
					'label' => Yii::t('menu', 'Выход'),
					'url' => ['/logout']
				];
			}
		}
		else
		{
			if($front)
			{
				$result[] = [
					'label' => Yii::t('menu', 'F.A.Q'),
					'url' => ['/faq'],
					//'options'=> ['class' => 'iceMenuLiLevel_1'],
					'template' => '<a href="{url}">
						<span class="icemega_title icemega_nosubtitle">{label}</span>
					</a>',
				];
				$result[] = [
					'label' => Yii::t('menu', 'Контакты'),
					'url' => ['/contacts'],
					//'options'=> ['class' => 'iceMenuLiLevel_1'],
					'template' => '<a href="{url}">
					<span class="icemega_title icemega_nosubtitle">{label}</span>
					</a>',
				];
				$result[] = [
					'label' => Yii::t('menu', 'Регистрация'),
					'url' => ['/signup'],
					'template' => '<a href="#" data-toggle="modal" data-target="#authModalCenter">{label}</a>',
					//'options'=> ['class' => 'iceMenuLiLevel_1'],
					'template' => '<a href="{url}">
						<span class="icemega_title icemega_nosubtitle">{label}</span>
					</a>',
				];
				/*$result[] = [
					'label' => Yii::t('menu', 'Отзывы'),
					'url' => ['/feedbacks'],
					'options'=> ['class' => 'iceMenuLiLevel_1'],
					/*'template' => '<a href="{url}" class="iceMenuTitle">
						<span class="icemega_title icemega_nosubtitle">{label}</span>
					</a>',
				];*/
				$result[] = [
					'label' => Yii::t('menu', 'Вход'),
					'url' => '/login',
					'template' => '<a href="{url}" id="login-link">{label}</a>',
				];
			}
		}
		
		return $result;
	}
	
	public static function createBreadCrumbs($data, $url, $menuName = '')
    {
		$result = [];
		
		if($data !== null)
		{
			$menuData = (Menu::find()->select(['name', 'url'])->where('id =:id', [':id'=>$data->parent_id]) !== null) ? Menu::find()->select(['name', 'url'])->where('id =:id', [':id'=>$data->parent_id])->one() : [];
			
			if(!empty($menuData))
			{
				$result[] = [
					'template' => "<li><b>{link}</b></li>\n", //  шаблон для этой ссылки  
					'label' => $menuData->name, // название ссылки 
					'url' => ['/'.$menuData->url] // сама ссылка
				];
				$result[] = [
					'label' => $data->name, 
					'url' => ['/'.$url]
				];
			}
			else
			{
				$result[] = [
					'template' => "<li><b>{link}</b></li>\n", //  шаблон для этой ссылки  
					'label' => $data->name, 
					'url' => ['/'.$url]
				];
			}
		}
		else
		{
			$result[] = [
				'template' => "<li><b>{link}</b></li>\n", //  шаблон для этой ссылки  
				'label' => $menuName, 
				'url' => ['/'.$url]
			];
		}
		
		return $result;
	}
}
