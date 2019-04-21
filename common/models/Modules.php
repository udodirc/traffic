<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "modules".
 *
 * @property integer $id
 * @property string $data
 */
class Modules extends \yii\db\ActiveRecord
{
	public $module_id;
	
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'modules';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['data', 'module_id'], 'required'],
            [['data'], 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('form', 'ID'),
            'data' => Yii::t('form', 'Data'),
            'module_id' => Yii::t('form', 'ID Модуля'),
        ];
    }
    
    public static function fieldsType()
    {
        return [
			1 => ['type'=>'text', 'column_type'=>'varchar(100)', 'name'=>Yii::t('form', 'Текстовое поле'), 'field_data'=>
				[
					['type'=>'text', 'label'=>Yii::t('form', 'Максимальное кол-во символов'), 'name'=>'max_number_symbols'],
					['type'=>'text', 'label'=>Yii::t('form', 'Минимальное кол-во символов'), 'name'=>'min_number_symbols'],
					['type'=>'radio', 'label'=>Yii::t('form', 'Обязательное поле'), 'name'=>'required'],
				]
			],
            2 => ['type'=>'text_area', 'column_type'=>'text', 'name'=>Yii::t('form', 'Область текста'), 'field_data'=>
				[
					['type'=>'text', 'label'=>Yii::t('form', 'Минимальное кол-во символов'), 'name'=>'min_number_symbols'],
					['type'=>'radio', 'label'=>Yii::t('form', 'Обязательное поле'), 'name'=>'required'],
					['type'=>'radio', 'label'=>Yii::t('form', 'WYSIWG'), 'name'=>'wsywig'],
				]
            ],
            3 => ['type'=>'checkbox', 'column_type'=>'string', 'name'=>Yii::t('form', 'Галочка')],
            4 => ['type'=>'radio', 'column_type'=>'smallint', 'name'=>Yii::t('form', 'Радио кнопка')],
        ];
    }
    
    public function createModuleTable($data)
    {
		$result = false;
		$connection = \Yii::$app->db;
		$tableName = $data['Modules']['module_id'];
		$fieldsType = self::fieldsType();
		$columns = '(`id` int(10) NOT NULL AUTO_INCREMENT';
		
		foreach($data['field_name'] as $i => $name)
		{
			$columnType = (isset($data['field_type'][$i])) ? $fieldsType[$data['field_type'][$i]]['column_type'] : '';
			
			if($columnType != '')
			{
				$columns.= ', `'.$name.'` '.$columnType;
			}
		}
		
		$columns.= ', PRIMARY KEY (`id`)) ENGINE=InnoDB  DEFAULT CHARSET=utf8';
		$query = "CREATE TABLE `".$tableName."` ".$columns.";";
		$command = $connection->createCommand($query);
		$result = $command->execute();
		$result = true;
		
		return $result;
	}
}
