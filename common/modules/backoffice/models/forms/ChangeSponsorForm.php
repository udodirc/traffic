<?php
namespace common\modules\backoffice\models\forms;

use Yii;
use yii\base\Model;
use common\modules\backoffice\models\Partners;
use common\models\DbBase;

/**
 * Change sponsor form
 */
class ChangeSponsorForm extends Model
{
	public $sponsor_login;
	
	/**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sponsor_login'], 'required'],
            [['sponsor_login'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'sponsor_login' => Yii::t('form', 'Логин спонсора')
        ];
    }
    
    public function changeSponsor($id)
    {
        if(!$this->validate())
        {	
			return false;
        }
		
		$result = false;
		
		if($id > 0)
		{
			$dbModel = new DbBase();
			$procedureInData = [$this->sponsor_login, $id, '@p2'];
			$procedureOutData = ['@p2'=>'VAR_OUT_RESULT'];
			$procedureResult = $dbModel->callProcedure('change_partners_sponsor', $procedureInData, $procedureOutData);
			
			if(!empty($procedureResult))
			{
				$result = ((isset($procedureResult['output']['VAR_OUT_RESULT'])) && $procedureResult['output']['VAR_OUT_RESULT'] > 0) ? true : false;
			}
		}
		
        return $result;
    }
}
