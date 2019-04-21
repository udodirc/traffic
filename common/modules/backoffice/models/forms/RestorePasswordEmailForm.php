<?php
namespace common\modules\backoffice\models\forms;

use yii\base\Model;
use Yii;

class RestorePasswordEmailForm extends Model
{
    public $email;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
           ['email', 'required'],
           ['email', 'email']
        ];
    }

    public function attributeLabels()
    {
        return [
            'email' => Yii::t('form', 'Email'),
        ];
    }
}
