<?php

namespace common\modules\structure\models;

use Yii;

/**
 * This is the model class for table "top_referals".
 *
 * @property integer $partner_id
 * @property integer $count
 *
 * @property Partners $partner
 */
class TopReferals extends \yii\db\ActiveRecord
{
	public $id;
	public $login;
	public $email;
	public $iso;
	
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'top_referals';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['partner_id', 'count'], 'required'],
            [['partner_id', 'count'], 'integer'],
            [['partner_id'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'partner_id' => Yii::t('form', 'Partner ID'),
            'count' => Yii::t('form', 'Count'),
            'login' => Yii::t('form', 'Логин'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPartner()
    {
        return $this->hasOne(Partners::className(), ['id' => 'partner_id']);
    }
    
    public function getTopLeaders($front = false)
    {
		$select = ($front) ? '`partners`.`id`, `partners`.`login`, `partners`.`iso`, `top_referals`.`count` AS `referals_count`' : '`partners`.`id`, `partners`.`login`, `partners`.`iso`, `partners`.`email`, `top_referals`.`count` AS `referals_count`';

		return self::find()
		->select($select)
		->leftJoin('partners', 'partners.id = top_referals.partner_id');
	}

	public static function monthList()
	{
		return [
			0 => 'Все месяцы',
			1 => 'Январь',
			2 => 'Февраль',
			3 => 'Март',
			4 => 'Апрель',
			5 => 'Май',
			6 => 'Июнь',
			7 => 'Июль',
			8 => 'Август',
			9 => 'Сентябрь',
			10 => 'Октябрь',
			11 => 'Ноябрь',
			12 => 'Декабрь',
		];
	}
}
