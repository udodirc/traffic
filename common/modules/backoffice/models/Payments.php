<?php

namespace common\modules\backoffice\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "payments".
 *
 * @property integer $id
 * @property integer $partner_id
 * @property integer $refferal_id
 * @property integer $level
 * @property integer $payment_type
 * @property string $amount
 * @property integer $created_at
 *
 * @property Partners $refferal
 * @property Partners $partner
 */
class Payments extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'payments';
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
            [['partner_id', 'refferal_id', 'level', 'payment_type', 'amount', 'created_at'], 'required'],
            [['partner_id', 'refferal_id', 'level', 'payment_type', 'created_at'], 'integer'],
            [['amount'], 'number']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('form', 'ID'),
            'partner_id' => Yii::t('form', 'Partner ID'),
            'refferal_id' => Yii::t('form', 'Refferal ID'),
            'level' => Yii::t('form', 'Level'),
            'payment_type' => Yii::t('form', 'Payment Type'),
            'amount' => Yii::t('form', 'Amount'),
            'created_at' => Yii::t('form', 'Created At'),
        ];
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRefferal()
    {
        return $this->hasOne(Partners::className(), ['id' => 'refferal_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPartner()
    {
        return $this->hasOne(Partners::className(), ['id' => 'partner_id']);
    }
    
    public function createInvoice($partnerID, $paymentType, $amount)
    {
		$model = new Payments;
		$model->partner_id = $partnerID;
		$model->payment_type = $paymentType;
		$model->amount = $amount;
		
		if($model->save(false))
		{
			return true;
		}
		
		return false;
	}
	
	public function calculateLevelsPercentage($id)
    {
		$result = [];
		
		if(($partnerModel = Partners::findOne($id)) !== null) 
        {
            if(((isset($partnerModel->left_key)) && $partnerModel->left_key > 0) && ((isset($partnerModel->right_key)) && $partnerModel->right_key > 0) && ((isset($partnerModel->level)) && $partnerModel->level > 0))
			{
				$structureLevels = (isset(Yii::$app->params['structure_levels'])) ? Yii::$app->params['structure_levels'] : 0;
				
				if($structureLevels > 0)
				{
					$levelTo = (($partnerModel->level - 5) > 0) ? $partnerModel->level - 5 : 1;
				
					if($levelTo > 0)
					{
						$result = Partners::find()
						->select('`id`, `level`')
						->from('`partners`')
						->where('`left_key` <= :left_key AND `right_key` >= :right_key AND `id` != :id AND `level` BETWEEN :level_from AND :level_to', [':left_key' => $partnerModel->left_key, ':right_key' => $partnerModel->right_key, ':id' => $id, ':level_from' => $levelTo, ':level_to' => $partnerModel->level])
						->orderBy('`left_key`')
						->asArray()
						->all();
					}
				}
			}
        }
        
        return $result;
	}
}
