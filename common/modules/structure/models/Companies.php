<?php

namespace common\modules\structure\models;

use Yii;

/**
 * This is the model class for table "companies".
 *
 * @property int $id
 * @property int $matrix
 * @property string $name
 * @property string $description
 * @property string $amount
 */
class Companies extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'companies';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['matrix', 'name', 'description', 'amount'], 'required'],
            [['matrix'], 'integer'],
            [['description'], 'string'],
            [['amount'], 'number'],
            [['name'], 'string', 'max' => 100],
            [['matrix'], 'checkMatrix', 'skipOnEmpty' => false, 'skipOnError' => false],
        ];
    }
    
    public function checkMatrix($attribute, $param)
    {	
		$data = self::find()->where('matrix = :matrix', [':matrix'=>$this->matrix])->one();
			
		if($data !== null)
		{
			$this->addError($attribute, Yii::t('form', 'Такая матрица уже есть!'));
		}
	}
	
	/**
     * Scenarios
     *
     * @return mixed
     */
    public function scenarios()
	{
		$scenarios = parent::scenarios();
		
        $scenarios['create'] = ['matrix', 'name', 'description', 'amount'];
		
		$scenarios['update'] = ['name', 'description', 'amount'];
		
		return $scenarios;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('form', 'ID'),
            'matrix' => Yii::t('form', 'Матрица'),
            'name' => Yii::t('form', 'Название компании'),
            'description' => Yii::t('form', 'Описание'),
            'amount' => Yii::t('form', 'Сумма'),
        ];
    }
}
