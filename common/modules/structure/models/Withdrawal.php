<?php

namespace common\modules\structure\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use common\modules\backoffice\models\Partners;
use common\modules\structure\models\InvitePayOff;
use common\models\DbBase;

/**
 * This is the model class for table "withdrawal".
 *
 * @property integer $id
 * @property integer $partner_id
 * @property string $amount
 *
 * @property Partners $partner
 */
class Withdrawal extends \yii\db\ActiveRecord
{
	const STATUS_REJECT = 0;
    const STATUS_SUSPEND = 1;
    const STATUS_CONFIRM = 2;
    
    public $partner;
    public $benefitPartner;
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'withdrawal';
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
            [['id', 'item_id', 'type', 'paid_off', 'created_at'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('form', 'ID'),
            'item_id' => Yii::t('form', 'ID заппроса')
        ];
    }
    
	public static function makeWithdrawalRequest($id, $type)
    {
		$model = new Withdrawal();
		$model->item_id = $id;
		$model->type = $type;
		
		if($model->save(false))
		{
			return true;
		}
		
		return false;
	}
	
	public function makeMoneyWithdrawal($id, $status, $partnerID, $amount)
    {
		$result = false;
		$dbModel = new DbBase();
		$procedureInData = [$id, $status, $partnerID, $amount, '@p1'];
		$procedureOutData = ['@p1'=>'VAR_OUT_RESULT'];
		
		$procedureResult = $dbModel->callProcedure('money_withdrawal', $procedureInData, $procedureOutData);
			
		if(!empty($procedureResult))
		{
			$result = ((isset($procedureResult['output']['VAR_OUT_RESULT'])) && $procedureResult['output']['VAR_OUT_RESULT'] > 0) ? true : false;
		}
		
		return $result;
	}
}
