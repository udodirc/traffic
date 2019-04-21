<?php

namespace common\modules\structure\models;

use Yii;

/**
 * This is the model class for table "gold_token_settings".
 *
 * @property integer $id
 * @property string $amount
 */
class GoldTokenSettings extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'gold_token_settings';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
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
            'amount' => Yii::t('form', 'Сумма'),
        ];
    }
}
