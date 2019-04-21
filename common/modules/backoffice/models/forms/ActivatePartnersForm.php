<?php
namespace common\modules\backoffice\models\forms;

use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use common\modules\backoffice\models\Partners;
use common\modules\structure\models\Matrix;

/**
 * Activate partner form
 */
class ActivatePartnersForm extends Model
{
	public $logins;
	
	/**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['logins'], 'required'],
            [['logins'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'logins' => Yii::t('form', 'Список логинов')
        ];
    }
    
    public function activatePartners($separator = ",")
    {
        if(!$this->validate())
        {	
			return false;
        }
		
		$result = false;
		$outResult = true;
		$model = new Partners();
		$partnersList = explode(",", $this->logins);
		$partnersList2 = ArrayHelper::index(Partners::find()->select(['id', 'LOWER(login) AS `login`', 'sponsor_id', 'email', 'created_at'])->where(['status'=>Partners::STATUS_ACTIVE, 'matrix_1'=>0])->orderBY('`created_at` ASC')->asArray()->all(), 'login');
		
		foreach($partnersList as $key => $data)
		{
			$login = '';
			$date = '';
			
			if(strpos($data, $separator))
			{
				$partnerData = explode($separator, $data);
				$date = strtotime(trim($partnerData[0]));
				$login = mb_strtolower(trim($partnerData[1]), 'UTF-8');
			}
			else
			{
				$login = mb_strtolower(trim($data), 'UTF-8');
			}
			
			if(isset($partnersList2[$login]))
			{
				$matrixModel = new Matrix();
				
				if(!$matrixModel->addPartnerInStructure($partnersList2[$login]['sponsor_id'], $partnersList2[$login]['id'], 1, 1, $date, false, 2, false, true))
				{	
					$outResult = false;
					break;
				}
				else
				{	
					$url = Url::base(true);
					$mailResult = true;
					
					/*if(!strpos($url, 'localhost'))
					{
						$emailFrom = (isset(\Yii::$app->params['email_from'])) ? \Yii::$app->params['email_from'] : '';
						$site = (isset(\Yii::$app->params['site_url'])) ? \Yii::$app->params['site_url'] : '';
						$mailResult = \Yii::$app->mailer->compose(['html' => 'activate-html-ru'], ['login' => $login, 'site' => $site])
						->setFrom([\Yii::$app->params['supportEmail'] => $emailFrom])
						->setTo($partnersList2[$login]['email'])
						->setSubject(Yii::t('messages', 'Platform activated - Активация платформы!'))
						->send();
					}*/
				}
			}
		}
		
		$result = $outResult;
        
        return $result;
    }
}
