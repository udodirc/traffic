<?php

namespace app\models;

use Yii;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use app\models\Pagination;
use app\models\Permissions;
use common\models\Service;
use common\modules\structure\models\Matrix;

/**
 * This is the model class for table "admin_menu".
 *
 * @property integer $id
 * @property integer $parent_id
 * @property string $name
 * @property string $url
 * @property string $css
 */
class AdminMenu extends \yii\db\ActiveRecord
{
	public $menu_name;
	public $menu_id;
	
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'admin_menu';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'url', 'css'], 'required'],
            ['name', 'unique', 'targetAttribute' => 'name', 'on'=>'insert'],
            ['parent_id', 'integer'],
            [['name', 'url', 'css'], 'string', 'max' => 100],
            ['url', 'unique', 'targetAttribute' => 'url', 'on'=>'insert'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('form', 'ID'),
            'parent_id' => Yii::t('form', 'Меню'),
            'name' => Yii::t('form', 'Название меню'),
            'url' => Yii::t('form', 'Url'),
            'css' => Yii::t('form', 'Css'),
        ];
    }
    
    /**
     * Relation wit pagination table by menu_id field
     */
	public function getPagination()
    {
        return $this->hasOne(Pagination::className(), ['id' => 'menu_id']);
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
			}
		}
		
		return parent::beforeSave($insert);
	}
	
	public function getMenuDataByID($id)
    {
		return self::find()
		->select('menu_1.name AS menu_name, menu_2.id, menu_2.name AS name, menu_2.url, menu_2.css')
		->from('admin_menu menu_1')
		->rightJoin('admin_menu menu_2', 'menu_1.id = menu_2.parent_id')
		->where('menu_2.id=:id', [':id' => $id])
		->one();
	}
	
	public function getAdminTopMenuList()
    {
		$userID = Yii::$app->user->id;
		$url = explode('/', Url::to(""));
		$url = end($url);
		$result = array();
		$menuList = self::find()->select(['id', 'name', 'url', 'css'])->where('parent_id =:id', [':id' => 0])->orderBy('id DESC')->asArray()->all();
		$urlBase = substr(URL::base(), 0, -6).DIRECTORY_SEPARATOR.Yii::$app->params['backend_dir'];
		$permissionModel = new Permissions();
		$permittedMenuList = $permissionModel->getPermittedMenuList($userID);
		
		foreach($menuList as $i => $menu)
		{	
			if($userID == '1')
			{
				$result[] = ['label' => Yii::t('menu', $menu['name']), 'url' => $menu['url'], 'image' => Html::img($urlBase.\Yii::getAlias('@theme').DIRECTORY_SEPARATOR.'css'.DIRECTORY_SEPARATOR.'_layout/images/icons/'.$menu['css'].'.png', ['alt'=>''])];
			}
			else
			{
				if(isset($permittedMenuList[ucfirst(Service::dashesToCamelCase($menu['url']))]))
				{
					$result[] = ['label' => Yii::t('menu', $menu['name']), 'url' => $menu['url'], 'image' => Html::img($urlBase.\Yii::getAlias('@theme').DIRECTORY_SEPARATOR.'css'.DIRECTORY_SEPARATOR.'_layout/images/icons/'.$menu['css'].'.png', ['alt'=>''])];
				}
			}
		}
		
		return $result;
	}
	
	public function getAdminLeftMenuList()
    {
		$result = array();
		$url = (strpos($_SERVER['REQUEST_URI'], 'matrix-structure')) ? 'backoffice/backend-partners' : self::parseUrl();
		
		if($url != '')
		{	
			$parentMenu = self::find()->select(['id', 'parent_id'])->where('url =:url', [':url' => $url])->one();
			
			if($parentMenu !== NULL)
			{	
				$model = self::find()->select('name, url')->where('parent_id = :id', [':id' => ($parentMenu->parent_id > 0) ? $parentMenu->parent_id : $parentMenu->id])->all();
				$result[] = ['label' => Yii::t('menu', 'Панель администратора').'<span class="icon-dashboard"></span>', 'url' => [$url.'/index']];
				
				if($model !== NULL)
				{
					$userID = Yii::$app->user->id;
					$permissionModel = new Permissions();
					$permittedMenuList = $permissionModel->getPermittedMenuList($userID);
					
					foreach($model as $i => $menu)
					{	
						if($userID == '1')
						{
							$result[] = ['label' => Yii::t('menu', $menu['name']).'<span class="icon-tables"></span>', 'url' => ['/'.$menu['url'].'/index']];
						}
						else
						{
							if(isset($permittedMenuList[ucfirst(Service::dashesToCamelCase($menu['url']))]))
							{
								$result[] = ['label' => Yii::t('menu', $menu['name']).'<span class="icon-tables"></span>', 'url' => ['/'.$menu['url'].'/index']];
							}
						}
					}
					
					if((strpos($_SERVER['REQUEST_URI'], 'backend-partners')) || (strpos($_SERVER['REQUEST_URI'], 'structure')) || (strpos($_SERVER['REQUEST_URI'], 'tickets')))
					{
						$maxStructureNumber = 1;
						
						for($i=1; $i<=$maxStructureNumber; $i++)
						{						
							$maxMatrixNumber = Matrix::getMaxMatrix($maxStructureNumber);
							
							for($j=1; $j<=$maxMatrixNumber; $j++)
							{
								$result[] = ['label' => Yii::t('menu', 'Матрица').'_'.$i.'_'.$j.'<span class="icon-tables"></span>', 'url' => ['/backoffice/backend-partners/matrix-structure/'.$i.'/'.$j.'/0']];
							}						
						}
					}
				}
			}
		}
		
		return $result;
	}
	
	public static function parseUrl()
    {
		$result = '';
		$url = mb_substr($_SERVER['REQUEST_URI'], -(mb_strlen($_SERVER['REQUEST_URI']) - mb_strlen(Url::base())));
		$urlParse = explode("/", $url);
		
		if(!empty($urlParse))
		{
			for($i=0; $i<count($urlParse); $i++)
			{	
				if(!strstr($urlParse[$i], 'index') && $urlParse[$i] != '')
				{	
					$result.=$urlParse[$i].'/';
				}
			}
			
			$result = mb_substr($result, 0, -1);
		}
		
		return $result;
	}
}
