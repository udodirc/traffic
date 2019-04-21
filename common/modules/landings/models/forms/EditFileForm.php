<?php
namespace common\modules\landings\models\forms;

use Yii;
use yii\base\Model;
use common\modules\uploads\models\Files;
/**
 * Edit file form
 */
class EditFileForm extends Model
{
	public $file;
	
	/**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // login and password are both required
            [['file'], 'required'],
            [['file'], 'string'],
        ];
    }
    
    /**
     * @inheritdoc
    */
    public function attributeLabels()
    {
        return [
			'file' => Yii::t('form', 'Файл'),
        ];
    }
    
    public function update($filePath)
    {
		$result = false;
		
        if(!$this->validate())
        {	
			return $result;
        }
        
        if(Files::createTextFile($filePath, $this->file))
        {
			$result = true;
		}
        
        return $result;
	}
}
