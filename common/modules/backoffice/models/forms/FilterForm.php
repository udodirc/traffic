<?php
namespace common\modules\backoffice\models\forms;

use Yii;
use yii\base\Model;
use common\modules\backoffice\models\Partners;

/**
 * Filter form
 */
class FilterForm extends Model
{
	public $country;
	
	/**
     * @inheritdoc
     */
    public function rules()
    {
        return [
			[['country'], 'integer'],
        ];
    }
    
    /**
     * @inheritdoc
    */
    public function attributeLabels()
    {
        return [
			'country' => Yii::t('form', 'Страна')
        ];
    }
}
