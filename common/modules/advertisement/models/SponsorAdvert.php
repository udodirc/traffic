<?php

namespace common\modules\advertisement\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use common\modules\uploads\models\Files;
use yii\helpers\FileHelper;
use yii\db\Expression;

/**
 * This is the model class for table "sponsor_advert".
 *
 * @property integer $id
 * @property integer $partner_id
 * @property string $name
 * @property string $desc
 * @property integer $created_at
 *
 * @property Partners $partner
 */
class SponsorAdvert extends \yii\db\ActiveRecord
{
	public $file;
	public $sponsor_id;
	
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sponsor_advert';
    }
    
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
			[
				'class' => TimestampBehavior::className(),
				'createdAtAttribute' => 'created_at',
				'updatedAtAttribute' => false,
			],
		];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['partner_id', 'link', 'name', 'desc', 'created_at'], 'required'],
            [['partner_id', 'created_at'], 'integer'],
            [['desc'], 'string'],
            [['name'], 'string', 'max' => 100],
            ['link', 'url', 'defaultScheme' => 'http'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('form', 'ID'),
            'partner_id' => Yii::t('form', 'ID партнера'),
            'name' => Yii::t('form', 'Название проекта'),
            'link' => Yii::t('form', 'Ссылка'),
            'desc' => Yii::t('form', 'Описание'),
            'created_at' => Yii::t('form', 'Создан'),
        ];
    }
    
    /**
     * Add partner id before user insert or update
     *
     * @return mixed
     */
    public function beforeSave($insert) 
    {
		if(parent::beforeSave($insert)) 
		{	
			if($this->isNewRecord) 
			{	
				$this->partner_id = (!is_null(\Yii::$app->user->identity)) ? \Yii::$app->user->identity->id : 0;
			}
		}
		
		return parent::beforeSave($insert);
	}
	
	public function afterSave($insert, $changedAttributes)
	{
		$result = Files::uploadImageFromTmpDir('sponsor_advert', $this->id, true, true);
		
		parent::afterSave($insert, $changedAttributes);
	}
	
	public function afterDelete()
    {	
		if(isset(Yii::$app->params['upload_dir']['sponsor_advert']))
		{	
			$dir = \Yii::getAlias('@backend_upload_dir').DIRECTORY_SEPARATOR.Yii::$app->params['upload_dir']['sponsor_advert']['dir'].DIRECTORY_SEPARATOR.Yii::$app->params['upload_dir']['sponsor_advert']['uploads'].DIRECTORY_SEPARATOR.$this->id;
			
			if(is_dir($dir))
			{	
				$result = FileHelper::removeDirectory($dir);
			}
		}
		
        parent::afterDelete();
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPartner()
    {
        return $this->hasOne(Partners::className(), ['id' => 'partner_id']);
    }
    
    public function getSponsorAdvertByPartnerIDInRandomMode($id)
    {
		return self::find()
		->where(['partner_id' => $id])
		->orderBy(new Expression('rand()'))
		->limit(1)
		->one();
	}
	
	public function getSponsorAdvert($id)
    {
		$result = null;
		
		if((\Yii::$app->session->get('user.sponsor_advert')) && \Yii::$app->session->get('user.sponsor_advert') > 0)
		{	
			$result = $this->getSponsorAdvertByPartnerIDInRandomMode($id);
		}
		
		return $result;
	}
}
