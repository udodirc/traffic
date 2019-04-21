<?php
namespace common\modules\seo\models\forms;

use Yii;
use yii\base\Model;
use yii\helpers\Html;
use common\models\Settings;
use common\modules\uploads\models\Files;

/**
 * Counters form
 */
class CountersForm extends Model
{
	public $liveinternet;
	public $yandex;
	public $google;
	
	/**
     * @inheritdoc
    */
    public function rules()
    {
        return [
			[['liveinternet', 'yandex', 'google'], 'string'],
        ];
	}
	
	/**
     * @inheritdoc
    */
    public function attributeLabels()
    {
        return [
			'liveinternet' => Yii::t('form', 'Liveinternet'),
			'yandex' => Yii::t('form', 'Yandex'),
			'google' => Yii::t('form', 'Google'),
        ];
    }
}
