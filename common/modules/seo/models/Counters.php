<?php
namespace common\modules\seo\models;

use Yii;
use yii\base\Model;
use yii\helpers\Html;
use common\models\Settings;
use common\modules\uploads\models\Files;

/**
 * Counters model
 */
class Counters extends Model
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
    
    public function updateCounters($model)
    {
		$result = false;
		$connection = \Yii::$app->db;
		
		$sql = "INSERT IGNORE INTO `settings` (`name`, `value`) VALUES ('liveinternet', '".$model->liveinternet."') ON DUPLICATE KEY UPDATE `value` = '".$model->liveinternet."'";
		$command = $connection->createCommand($sql);
		$command->execute();
		$sql = "INSERT IGNORE INTO `settings` (`name`, `value`) VALUES ('yandex', '".$model->yandex."') ON DUPLICATE KEY UPDATE `value` = '".$model->yandex."'";
		$command = $connection->createCommand($sql);
		$command->execute();
		
		$file = Yii::getAlias('@root_dir').DIRECTORY_SEPARATOR.'analyticstracking.php';
		
		if(Files::createTextFile($file, $model->google))
		{
			$result = true;
		}
		
		return $result;
	}
	
	public function getCountersData()
    {
		$result = [];
		
		if(Settings::find()->select('value')->where(['name'=>'liveinternet'])->one() !== null)
		{	
			$liveinternet = Settings::find()->select('value')->where(['name'=>'liveinternet'])->one();
			$result['liveinternet'] = $liveinternet->value;
		}
		
		if(Settings::find()->select('value')->where(['name'=>'yandex'])->one() !== null)
		{
			$yandex = Settings::find()->select('value')->where(['name'=>'yandex'])->one();
			$result['yandex'] = $yandex->value;
		}
		
		$file = Yii::getAlias('@root_dir').DIRECTORY_SEPARATOR.'analyticstracking.php';
		$google = Files::readTextFile($file);
		
		if($google != '')
		{
			$result['google'] = Files::readTextFile($file);
		}
		
		return $result;
	}
}
