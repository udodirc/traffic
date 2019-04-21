<?php
namespace common\modules\mailing\models;

use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use common\modules\backoffice\models\Partners;
use common\modules\mailing\models\MailingBase;
use common\modules\uploads\models\Files;
use common\modules\structure\models\TopReferals;

/**
 * Mailing model
 */
class Mailing extends Model
{
	public function makeMailingAllPartners($baseType, $message, $subject)
    {
		$result = false;
		$partnersList = ($baseType > 1) ? MailingBase::find()->select(['id', 'login', 'first_name', 'last_name', 'email', 'mailing'])->asArray()->all() : Partners::find()->select(['id', 'login', 'first_name', 'last_name', 'email', 'mailing'])->asArray()->all();
		$partnersList = ArrayHelper::index($partnersList, 'login');
			
		if(!empty($partnersList))
		{
			$mailResult = false;
			
			foreach($partnersList as $login => $partnerData)
			{
				if(isset($partnersList[$login]) && (isset($partnersList[$login]['mailing']) && $partnersList[$login]['mailing'] > 0) && (isset($partnersList[$login]['email']) && $partnersList[$login]['email'] != ''))
				{	
					$mailResult = $this->sendMail($partnersList[$login], $message, $subject);
					
					if(!$mailResult)
					{
						break;
					}
				}
			}
			
			$result = $mailResult;
		}
		
		return $result;
	}
	
	public function makeMailingByLogins($baseType, $message, $loginList, $subject)
    {
		$result = false;
		
		if(!empty($loginList))
		{
			$mailResult = false;
			$partnersList = ($baseType > 1) ? MailingBase::find()->select(['id', 'login', 'first_name', 'last_name', 'email', 'mailing'])->asArray()->all() : Partners::find()->select(['id', 'login', 'first_name', 'last_name', 'email', 'mailing'])->asArray()->all();
			$partnersList = ArrayHelper::index($partnersList, 'login');
			$loginList = explode(",", $loginList);
			
			foreach($loginList as $i => $login)
			{
				$login = trim($login);
			
				if(isset($partnersList[$login]) && (isset($partnersList[$login]['mailing']) && $partnersList[$login]['mailing'] > 0) && (isset($partnersList[$login]['email']) && $partnersList[$login]['email'] != ''))
				{	
					$mailResult = $this->sendMail($partnersList[$login], $message, $subject);
					
					if(!$mailResult)
					{
						break;
					}
				}
			}
			
			$result = $mailResult;
		}
		
		return $result;
	}
	
	public function makeMailingByIDs($baseType, $beginID, $endID, $message, $subject)
    {
		$result = false;
		
		if($beginID > 0 && $endID > 0)
		{
			$partnersList = ($baseType > 1) ? MailingBase::find()->select(['id', 'login', 'first_name', 'last_name', 'email', 'mailing'])->where(['between', 'id', $beginID, $endID])->asArray()->all() : Partners::find()->select(['id', 'login', 'first_name', 'last_name', 'email', 'mailing'])->where(['between', 'id', $beginID, $endID])->asArray()->all();
			$partnersList = ArrayHelper::index($partnersList, 'login');
			
			if(!empty($partnersList))
			{
				$mailResult = false;
				
				foreach($partnersList as $login => $partnerData)
				{
					if(isset($partnersList[$login]) && (isset($partnersList[$login]['mailing']) && $partnersList[$login]['mailing'] > 0) && (isset($partnersList[$login]['email']) && $partnersList[$login]['email'] != ''))
					{	
						$mailResult = $this->sendMail($partnersList[$login], $message, $subject);
					
						if(!$mailResult)
						{
							break;
						}
					}
				}
				
				$result = $mailResult;
			}
		}
		
		return $result;
	}
	
	public function sendMail($partnerData, $message, $subject)
    {	
		$result = false;
		//$siteUrl = substr(Url::base(true), 0, -6);
		$siteUrl = (isset(\Yii::$app->params['site_url'])) ? \Yii::$app->params['site_url'] : '';
		$siteUrl = 'http://perfectmoneyprofit.com/';
		//$adminEmail = \Yii::$app->params['supportEmail'];
		$adminEmail = 'perfectmoneyprofit.com@gmail.com';
		
		if(isset(\Yii::$app->params['supportEmail']))
		{
			$emailFrom = (isset(\Yii::$app->params['email_from'])) ? \Yii::$app->params['email_from'] : '';
			$result = \Yii::$app->mailer->compose(['html' => 'mailing-html-ru'], ['user_id' => $partnerData['id'], 'username' => $partnerData['first_name'].'&nbsp;'.$partnerData['last_name'], 'email' => $partnerData['email'], 'login' => $partnerData['login'], 'admin_email' => $adminEmail, 'site' => $siteUrl, 'message' => $message])
			->setFrom([\Yii::$app->params['supportEmail'] => $emailFrom])
			->setTo($partnerData['email'])
			->setSubject($subject)
			->send();
		}
		
		return $result;
	}
	
	public static function updatePartnersListByFilters($partnersList, $login, $firstName, $lastName, $separator = ', ')
    {
		$result = false;
		
		if(!empty($partnersList))
		{
			$text = self::getPartnersListForFile($partnersList, $login, $firstName, $lastName, $separator);
			$file = Yii::getAlias('@backend_upload_dir').DIRECTORY_SEPARATOR.'email'.DIRECTORY_SEPARATOR.'email_list.txt';
		
			if($text != '' && $file != '')
			{
				$result = Files::createTextFile($file, $text);
			}
		}
		
		return $result;
	}
	
	public static function getPartnersListForFile($partnersList, $login, $firstName, $lastName, $separator)
    {
		$result = '';
		
		if(!empty($partnersList))
		{	
			foreach($partnersList as $key=>$partner)
			{	
				$partnerData = ((($login > 0) ? $separator.$partner['login'] : '').(($firstName > 0) ? $separator.$partner['first_name'] : '').(($lastName > 0) ? $separator.$partner['last_name'] : ''));
				$result.= ($partnerData != '') ? $partner['email'].PHP_EOL : $partner['email'].$partnerData.PHP_EOL;
			}
		}
		
		return $result;
	}
	
	public static function updatePartnersEmails($leaders = false)
    {
		$result = false;
		$file = ($leaders) ? 'leaders_email_list.txt' : 'email_list.txt';
		$file = Yii::getAlias('@backend_upload_dir').DIRECTORY_SEPARATOR.'email'.DIRECTORY_SEPARATOR.$file;
		$text = self::getPartnersEmails($leaders);
		
		if($text != '' && $file != '')
		{
			$result = Files::createTextFile($file, $text);
		}
		
		return $result;
	}
	
	public static function getPartnersEmails($leaders)
    {
		$counter = 1;
		$result = '';
		
		if($leaders)
		{
			$model = new TopReferals;
			$partnersList = $model->getTopLeaders(false, 300);
			$partnersList = ($partnersList !== null) ? $partnersList->all() : null;
		}
		else
		{
			$partnersList = Partners::find()->select(['email'])->all();
		}
		
		if($partnersList !== null)
		{
			foreach($partnersList as $key=>$partner)
			{
				if($counter % 100 == 0)
				{
					$result.= ($leaders) ? $partner['email'].';'.'\n' : $partner->email.';'.'\n';
				}
				else
				{
					$result.= ($leaders) ? $partner['email'].', ' : $partner->email.', ';
				}
				
				$counter++;
			}
		}
		
		return $result;
	}
}
