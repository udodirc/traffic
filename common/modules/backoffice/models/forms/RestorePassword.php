<?php

namespace common\modules\backoffice\models\forms;

use Yii;

/**
 * This is the model class for table "restore_password".
 *
 * @property integer $user_id
 * @property string $hash
 * @property string $created_at
 */
class RestorePassword extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'restore_password';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'hash', 'created_at'], 'required'],
            [['user_id'], 'integer'],
            [['created_at'], 'safe'],
            [['hash'], 'string', 'max' => 50],
            [['user_id'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'user_id' => Yii::t('form', 'ID'),
            'hash' => Yii::t('form', 'Hash'),
            'created_at' => Yii::t('form', 'Создано'),
        ];
    }
}
