<?php

namespace common\modules\structure\models;

use Yii;

/**
 * This is the model class for table "matrix_8".
 *
 * @property integer $id
 * @property integer $matrix_id
 * @property integer $partner_id
 * @property integer $parent_id
 * @property integer $left_key
 * @property integer $right_key
 * @property integer $level
 * @property integer $level_1
 * @property integer $level_2
 * @property integer $open_date
 * @property integer $close_date
 *
 * @property Partners $partner
 */
class Matrix8 extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'matrix_8';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['matrix_id', 'partner_id', 'parent_id', 'left_key', 'right_key', 'level', 'level_1', 'level_2', 'open_date', 'close_date'], 'required'],
            [['matrix_id', 'partner_id', 'parent_id', 'left_key', 'right_key', 'level', 'level_1', 'level_2', 'open_date', 'close_date'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('form', 'ID'),
            'matrix_id' => Yii::t('form', 'Matrix ID'),
            'partner_id' => Yii::t('form', 'Partner ID'),
            'parent_id' => Yii::t('form', 'Parent ID'),
            'left_key' => Yii::t('form', 'Left Key'),
            'right_key' => Yii::t('form', 'Right Key'),
            'level' => Yii::t('form', 'Level'),
            'level_1' => Yii::t('form', 'Level 1'),
            'level_2' => Yii::t('form', 'Level 2'),
            'open_date' => Yii::t('form', 'Open Date'),
            'close_date' => Yii::t('form', 'Close Date'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPartner()
    {
        return $this->hasOne(Partners::className(), ['id' => 'partner_id']);
    }
}
