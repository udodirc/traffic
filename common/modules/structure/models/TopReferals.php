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
	public $referals_count;
	public $active_partners_count;
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
    
    public function getTopLeaders($front = false, $limit = 100)
    {
		$select = ($front) ? '`partners`.`id`, `partners`.`login`, `partners`.`iso`, `top_referals`.`count` AS `referals_count`' : '`partners`.`id`, `partners`.`login`, `partners`.`iso`, `partners`.`email`, `top_referals`.`count` AS `referals_count`';
		$select.= ', (select count(`partners`.`id`) as `active_partners`
        from `partners`
        where `sponsor_id` = `top_referals`.`partner_id` and `matrix_1` > 0) as `active_partners_count`';
		return self::find()
		->select($select)
		->leftJoin('partners', 'partners.id = top_referals.partner_id')
		->orderBy('`top_referals`.`count` DESC')
		->limit($limit);
	}
}
