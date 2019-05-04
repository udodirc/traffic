<?php
namespace common\modules\backoffice\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use yii\db\Expression;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use app\models\UserGroups;
use common\components\geo\Sypexgeo;
use common\modules\structure\models\TopReferals;
use common\models\DbBase;
use common\modules\structure\models\Matrix;
use common\modules\structure\models\Payment;
use common\modules\structure\models\MatrixPayments;
use common\modules\structure\models\InvitePayOff;

/**
 * User model
 *
 * @property integer $id
 * @property integer $sponsor_id
 * @property string $login
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property string $auth_key
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $password write-only password
 */
class Partners extends ActiveRecord implements IdentityInterface
{
    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 1;
    
    public $partner_id;
    public $matrix_number;
    public $sponsor_name;
    public $sponsor_login;
    public $structure_level;
    public $phone;
    public $password;
	public $re_password;
	public $referals_count;
	public $invoices_count;
	public $country;
	public $region;
	public $withdrawal_amount;
	public $total_payment_sum;
	public $top_leader;
	public $percent_amount;
	public $activation_date;
	public $paid_date;
	public $matrix_payments_amount;
	public $referral_balls;
	public $close_balls;
	public $wallet;
	public $open_date;
	public $ref_count;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%partners}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
			[['login', 'first_name', 'last_name', 'email', 'group_id'], 'required', 'message' => Yii::t('form', 'Это поле должно быть заполнено!')],
            [['sponsor_id', 'mailing'], 'integer'],
            [['withdrawal_amount', 'demo_total_amount', 'total_amount', 'demo_total_credit', 'total_credit'], 'double'],
            [['withdrawal_amount'], 'checkAmount', 'skipOnEmpty' => false, 'skipOnError' => false],
            //[['payeer_wallet'], 'checkRequiredWallet', 'skipOnEmpty' => false, 'skipOnError' => false],
            [['iso', 'geo', 'ip', 'payeer_wallet', 'payeer_wallet'], 'string'],
            //[['payeer_wallet'], 'checkWallet','except'=>['backend_update']],
            [['withdrawal_amount'], 'required', 'on' => 'withdrawal'],
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            //['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_DELETED]],
            [['password', 're_password'],'required','except'=>['update', 'backend_update']],
            ['email','email'],
            [['email'],'unique', 'except' => ['update', 'backend_update']],
            [['login'], 'unique', 'targetAttribute' => 'login', 'except' => ['update', 'backend_update']],
            [['payeer_wallet', 'payeer_wallet'], 'unique', 'targetAttribute' => 'payeer_wallet', 'except' => ['update', 'withdrawal', 'backend_update']],
            [['login'], 'match', 'pattern' => '/^[a-z0-9-_\s,]+$/u', 'message' => Yii::t('form', 'Введенны неправильные символы!')],
            [['password', 're_password'], 'match', 'pattern' => '/^[A-Za-z0-9_\s,]+$/u', 'message' => Yii::t('form', 'Введенны неправильные символы!')],
            ['re_password', 'compare', 'compareAttribute' => 'password'],
            [['login', 'first_name', 'last_name', 'email', 'payeer_wallet', 'password', 're_password'], 'filter', 'filter' => '\yii\helpers\HtmlPurifier::process']
        ];
    }
    
    /**
     * @inheritdoc
    */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('form', 'ID'),
            'country' => Yii::t('form', 'ID'),
            'sponsor_id' => Yii::t('form', 'ID спонсора'),
            'login' => Yii::t('form', 'Логин'),
            'ref_count' => Yii::t('form', 'Кол-во рефералов'),
            'matrix_1' => Yii::t('form', 'Матрица'),
            'mailing' => Yii::t('form', 'Рассылка'),
            'sponsor_name' => Yii::t('form', 'Логин спонсора'),
            'first_name' => Yii::t('form', 'Имя'),
            'last_name' => Yii::t('form', 'Фамилия'),
            'password' => Yii::t('form', 'Пароль'),
            're_password' => Yii::t('form', 'Повторить пароль'),
            'email' => Yii::t('form', 'Email'),
            'matrix' => Yii::t('form', 'Матрица'),
            'matrix_1' => Yii::t('form', 'Матрица'),
            'total_amount' => Yii::t('form', 'Сумма выплат'),
            'payeer_wallet' => Yii::t('form', 'Payeer кошелек'),
            'withdrawal_amount' => Yii::t('form', 'Сумма вывод денег - в биткоинах'),
            'structure_level' => Yii::t('form', 'Уровень структуры'),
            'status' => Yii::t('form', 'Статус'),
            'iso' => Yii::t('form', 'ISO'),
            'ip' => Yii::t('form', 'IP - адрес'),
            'group_id' => Yii::t('form', 'Группа'),
            'group' => Yii::t('form', 'Группа'),
            'created_at' => Yii::t('form', 'Создан'),
            'referals_count' => Yii::t('form', 'Кол-во рефералов'),
            'ref_count' => Yii::t('form', 'Кол-во рефералов'),
            'invoices_count' => Yii::t('form', 'Кол-во оплат'),
            'percent_amount' => Yii::t('form', 'Сумма заработка'),
            'matrix_payments_amount' => Yii::t('form', 'Сумма оплаты'),
            'referral_balls' => Yii::t('form', 'Бонус за приглашения'),
            'close_balls' => Yii::t('form', 'Бонус за закрытие матрицы'),
        ];
    }
    
    /**
     * Scenarios
     *
     * @return mixed
     */
    public function scenarios()
	{
		$scenarios = parent::scenarios();
		
        $scenarios['register'] = ['login', 'email', 'group_id', 'password', 're_password'];
		
		$scenarios['update'] = ['first_name', 'last_name', 'email', 'mailing', 'payeer_wallet', 'password', 're_password'];
		
		$scenarios['backend_update'] = ['login', 'first_name', 'last_name', 'email', 'status', 'mailing', 'payeer_wallet', 'password', 're_password'];
		
		$scenarios['withdrawal'] = ['payeer_wallet', 'withdrawal_amount'];
        
		return $scenarios;
    }
    
    public function checkRequiredWallet($attribute, $param)
    {
		$post = Yii::$app->request->post();
		$paymentsTypes = (isset(Yii::$app->params['payments_types'])) ? Yii::$app->params['payments_types'] : [];
		
		if(!empty($paymentsTypes))
		{
			foreach($paymentsTypes as $key => $walletData)
			{
				if(isset($post['Partners'][$walletData[1]]))
				{	
					if($post['Partners'][$walletData[1]] == '')
					{
						$this->addError($attribute, Yii::t('form', 'Заполните поле '.$walletData[0].'!'));
					}
				}
			}
		}
	}
	
    public function checkAmount($attribute, $param)
    {	
		$id = (!is_null(\Yii::$app->user->identity)) ? \Yii::$app->user->identity->id : 0;
		$result = false;
		
		if($id > 0)
		{
			$data = (new \yii\db\Query())
			->select('SUM(`amount`) AS `total_amount`')
			->from('matrix_payments')
			->where(['partner_id' => $id, 'type' => 2])
			->one();
			
			if($data['total_amount'] !== null && $data['total_amount'] > 0 && $this->withdrawal_amount <= $data['total_amount'])
			{
				$result = true;
			}
		}
		
		if(!$result)
		{
			$this->addError($attribute, Yii::t('form', 'У вас недостаточно средств на балансе!'));
		}
	}
	
	public function checkWallet($attribute, $param)
    {
		$valid = false;
		$id = (!is_null(\Yii::$app->user->identity)) ? \Yii::$app->user->identity->id : 0;
		/*$paymentType = Payment::getPaymentType();
		
		if($paymentType > 0)
		{
			$paymentName = (isset(Yii::$app->params['payments_types'][$paymentType][1])) ? Yii::$app->params['payments_types'][$paymentType][1] : '';
			$currency = (isset(Yii::$app->params['payments_types'][$paymentType][5])) ? Yii::$app->params['payments_types'][$paymentType][5] : '';
			$walletNumber = $this->$paymentName;
			
			if($paymentName != '' && $currency != '')
			{
				$valid = Payment::checkWallet($walletNumber, $paymentName, $currency);
			}
		}*/
		
		if(!$valid)
		{
			$this->addError($attribute, Yii::t('form', 'У вас неправильный формат кошелька!'));
		}
		$walletNumber = '';
		if($walletNumber != '')
		{
			if(($partnerData = self::find()->where('id != :id AND `payeer_wallet` = :wallet', [':id'=>$id, ':wallet'=>$walletNumber])->one()) !== null)
			{
				$this->addError($attribute, Yii::t('form', 'Такой кошелек уже есть!'));
			}
		}
	}

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        /*return static::findOne([
			'id' => $id, 
			'status' => self::STATUS_ACTIVE
       ]);*/
       return self::find()->where('id = :id AND status >= :status', [':id'=>$id, ':status'=>self::STATUS_ACTIVE])->one();
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by login
     *
     * @param string $login
     * @return static|null
     */
    public static function findByUsername($login)
    {
        /*return static::findOne([
			'login' => $login, 
			'status' => self::STATUS_ACTIVE
		]);*/
		return self::find()->where('login = :login AND status >= :status', [':login'=>$login, ':status'=>self::STATUS_ACTIVE])->one();
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        /*return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);*/
        
        return self::find()->where('password_reset_token = :password_reset_token AND status >= :status', [':password_reset_token'=>$token, ':status'=>self::STATUS_ACTIVE])->one();
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return boolean
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        $parts = explode('_', $token);
        $timestamp = (int) end($parts);
        return $timestamp + $expire >= time();
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }
    
    /**
     * @inheritdoc
     */
    public function getLogin()
    {
        return $this->login();
    }
    
    /**
     * @inheritdoc
     */
    public function getStatus()
    {
        return $this->status();
    }
    
    /**
     * @inheritdoc
     */
    public function getFirstName()
    {
        return $this->first_name;
    }
    
    /**
     * @inheritdoc
     */
    public function getLastName()
    {
        return $this->last_name;
    }
    
    /**
     * @inheritdoc
     */
    public function getRegisterDate()
    {
        return $this->created_at;
    }
    
    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }
    
    /**
     * @inheritdoc
     */
    public function getIso()
    {
		$geoData = unserialize($this->geo);
		$iso = (isset($geoData['country']['iso'])) ? $geoData['country']['iso'] : '';
		
        return $iso;
    }
    
    /**
     * @inheritdoc
     */
    public function getTopLeader()
    {
        return $this->top_leader;
    }
    
    /**
     * @inheritdoc
     */
    public function setTopLeader($id)
    {
		$this->top_leader = false;
		$count = (isset(Yii::$app->params['top_leader_limit'])) ? Yii::$app->params['top_leader_limit'] : 100;
		
		if($count > 0 && $id > 0)
		{
			$topReferalsData = TopReferals::find()->where('count >= :count', [':count'=>$count])->asArray()->all();
			$topReferalsData = ArrayHelper::index($topReferalsData, 'partner_id');
			
			if(isset($topReferalsData[$id]))
			{
				$this->top_leader = true;
			}
		}
		
		\Yii::$app->session->set('user.top_leader', $this->top_leader);
    }
    
    public function setMainModalWindow()
    {
		\Yii::$app->session->set('user.main_modal_window', 1);
	}

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
		return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
		$this->password_hash = Yii::$app->security->generatePasswordHash($password); 
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }
    
    /**
     * Add hash password before user insert or update
     *
     * @return mixed
     */
    public function beforeSave($insert) 
    {
		if(isset($this->password)) 
		{
			if($this->password !== '')
			{
				$this->password = self::setPassword($this->password);
			}
		}
		
		return parent::beforeSave($insert);
	}
	
	public function afterValidate()
    {
        if($this->scenario == "update" && $this->password) $this->setPassword($this->password);
        parent::afterValidate();
    }
	
	/**
     * Get status list of users
     *
     * @return array
     */
	public static function getStatusList()
    {
		return array('0'=>Yii::t('form', 'Не активен'), '1'=>Yii::t('form', 'Активен'));
	}
	
	/**
     * Relation with user_groups table by id field
     */
	public function getGroups()
    {
        return $this->hasOne(UserGroups::className(), ['id' => 'group_id']);
    }
    
    public function getMatrixPayments()
	{
		return $this->hasMany(MatrixPayments::className(), ['partner_id' => 'id']);
	}
    
    public function getInvitePayOff()
	{
		return $this->hasMany(InvitePayOff::className(), ['partner_id' => 'id']);
	}
    
    public function registerPartner($structureNumber, $model, $demo_structure = true)
    {
		$dbModel = new DbBase();
		
		$demoActivation = (isset(\Yii::$app->params['demo_structure_activation']) && (\Yii::$app->params['demo_structure_activation'])) ? 1 : 0;
		$procedureInData = [$structureNumber, $model->sponsor_id, $model->login, $model->first_name, $model->last_name, $model->email, $model->phone, $model->password_hash, $model->created_at, $model->auth_key, $model->status, $demoActivation, '@p7'];
		$procedureOutData = ['@p7'=>'VAR_OUT_RESULT'];
		$procedureResult = $dbModel->callProcedure('add_partner_in_structure', $procedureInData, $procedureOutData);
		$partnerID = ((isset($procedureResult['output']['VAR_OUT_RESULT'])) && $procedureResult['output']['VAR_OUT_RESULT'] > 0) ? $procedureResult['output']['VAR_OUT_RESULT'] : 0;
		$result = (!empty($procedureResult) && $partnerID > 0) ? true : false;
		
		return $result;
	}
	
	public function getPartnersList($structure = false)
    {
		$select = ($structure) ? 'partners_1.login AS sponsor_name, partners_2.id, partners_2.login, partners_2.created_at' : 'partners_1.login AS sponsor_name, partners_2.id, partners_2.login, partners_2.first_name, partners_2.last_name, partners_2.status, partners_2.matrix_1, partners_2.email, partners_2.created_at';
		$order = ($structure) ? 'DESC' : 'ASC';
		
		return self::find($select)
		->select('partners_1.login AS sponsor_name, partners_2.id, partners_2.login, partners_2.first_name, partners_2.last_name, partners_2.status, partners_2.matrix_1, partners_2.email, partners_2.created_at')
		->from('partners partners_1')
		->rightJoin('partners partners_2', 'partners_1.id = partners_2.sponsor_id')
		->orderBy('partners_2.created_at '.$order);
	}
	
	public function getPartnerStructureByID($id)
    {
		$result = null;
		$select = self::getStructuresList(['matrix']);
		
		if($select != '')
		{
			$result = self::find()->select($select)->where(['id'=>$id])->asArray()->one();
		}
		
		return $result;
	}
	
	public static function getStructuresList($fields, $fieldName = '')
    {
		$result = '';
		$structuresList = (isset(\Yii::$app->params['structures'])) ? \Yii::$app->params['structures'] : [];
		
		if(!empty($structuresList) && !empty($fields))
		{
			$structuresList = array_keys($structuresList);
			$fieldName = ($fieldName != '') ? '`'.$fieldName.'`.': '';
			
			foreach($structuresList as $i=>$number)
			{
				foreach($fields as $j=>$field)
				{
					$result.= $fieldName.'`demo_'.$field.'_'.$number.'`, '.$fieldName.'`'.$field.'_'.$number.'`, ';
				}
			}
			
			$result = substr($result, 0, -2);
		}
		
		return $result;
	}
	
	public function getPartnersListByPartnerID($id)
    {
		$result = [];
		$select = self::getStructuresList(['matrix'], 'partners_2');
		
		if($select != '')
		{
			$createdAt = 0;
			
			if($id > 0)
			{
				$partnerModel = self::findOne($id);
				$createdAt = (!is_null($partnerModel)) ? $partnerModel->created_at : 0;
			}
		
			$result = (new \yii\db\Query())
			->select('partners_1.login AS sponsor_name, partners_2.id, partners_2.login, partners_2.iso, partners_2.first_name, partners_2.last_name, partners_2.status, partners_2.email, partners_2.created_at, '.$select)
			->from('partners partners_1')
			->rightJoin('partners partners_2', 'partners_1.id = partners_2.sponsor_id')
			->where('partners_2.created_at > :created_at', [':created_at' => $createdAt])
			->orderBy('partners_2.created_at DESC');
		}
		
		return $result;
	}
	
	public static function getIDByWallet($wallet)
    {
		$result = self::find()
		->select('`id`')
		->where('`qiwi_wallet` = :wallet OR `blockchain` = :wallet OR `partners`.`perfect_wallet` = :wallet OR `partners`.`payeer_wallet` = :wallet', [':wallet' => $wallet])
		->asArray()
		->all();
		
		return $result;
	}
	
	public static function getCompareWalletList($defaultWallet)
    {
		$result = null;
		
		if($defaultWallet != '')
		{	$defaultWallet.= '_wallet';
			$result = self::find()
			->select('`'.$defaultWallet.'`')
			->groupBy('`'.$defaultWallet.'`')
			->having(['>', 'COUNT('.$defaultWallet.')', '1']);
		}
		
		return $result;
	}
	
	public static function getTotalPaymentsListByPartners()
    {
		$result = [];
		$select = self::getStructuresList(['matrix', 'total_amount'], 'partners');
		
		if($select != '')
		{	
			$result = (new \yii\db\Query())
			->select('SUM(`payments_invoices`.`amount`) as `total_payment_sum`, `payments_invoices`.`structure_number`, `partners`.`id`, `partners`.`login`, `partners`.`email`, '.$select)
			->from('partners')
			->leftJoin('`payments_invoices`', '`payments_invoices`.`partner_id` = `partners`.`id`')
			->where('`payments_invoices`.`account_type` = :account_type', [':account_type' => 2])
			->groupBy('`partners`.`id`');
		}
		
		return $result;
	}
	
	public static function compareTotalPaymentsListByPartners()
    {
		$result = self::find()
		->select('SUM(`payments_invoices`.`amount`) as `total_payment_sum`, `partners`.`id`, `partners`.`login`, `partners`.`matrix`, `partners`.`email`, `partners`.`total_amount`')
		->leftJoin('`payments_invoices`', '`payments_invoices`.`partner_id` = `partners`.`id`')
		->where('`payments_invoices`.`account_type` = :account_type', [':account_type' => 2])
		->groupBy('`partners`.`id`')
		->having('SUM(`payments_invoices`.`amount`) != `partners`.`total_amount`');
		
		return $result;
	}
	
	public function getPaidPartnersList()
    {
		$result = Partners::find()
		->select('DISTINCT `partners`.`id`, `partners`.`login`, `partners`.`email`, `payments_invoices`.`created_at` AS `paid_date`, COUNT(`payments_invoices`.`partner_id`) AS `invoices_count`')
		->leftJoin('`payments_invoices`', '`payments_invoices`.`partner_id` = `partners`.`id`')
		->where('`payments_invoices`.`account_type` = :account_type', [':account_type' => 1])
		->groupBy('`payments_invoices`.`partner_id`')
		->orderBy('`payments_invoices`.`created_at`');
		
		return $result;
	}
	
	public static function getTotalMatricesByPartner($partnerID)
    {
		$result = [];
		
		$structuresList = (isset(\Yii::$app->params['structures'])) ? \Yii::$app->params['structures'] : [];
		
		if(!empty($structuresList))
		{
			$matricesSettingsList = Matrix::getAllMatricesSettingsInProject();
			$list_view_count = (isset(\Yii::$app->params['list_view_count'])) ? \Yii::$app->params['list_view_count'] : 0;
			$structuresList = array_keys($structuresList);
			$structureNumber = 0;
			
			foreach($structuresList as $key=>$number)
			{	
				$connection = \Yii::$app->db;
				$sql = "SELECT `id`, `matrix_".$number."` 
				FROM `partners` 
				WHERE `id` = '".$partnerID."'";
				$model = $connection->createCommand($sql)->queryOne();
				$structureNumber = 0;
				
				if(!empty($model))
				{
					$levels = (isset($matricesSettingsList[$number])) ? $matricesSettingsList[$number] : [];
					
					if($structureNumber != $number)
					{
						$result[] = [
							'attribute' => 'matrix',
							'label' => '<span style="font-size:16px;">'.Yii::t('form', 'Структура').' - '.$number.'</span>',
							'format'=> 'raw',
							'value' => '',
						];
					}
					
					for($i=1; $i<=$model['matrix_'.$number]; $i++) 
					{
						$level = (isset($levels[$i])) ? $levels[$i] : 0;
						$result[] = [
							'attribute' => 'matrix',
							'label' => Yii::t('form', 'Матрицы').' - '.$i,
							'format'=> 'raw',
							'value' => Html::a(Yii::t('form', 'Смотреть'), \Yii::$app->request->BaseUrl.'/backoffice/backend-partners/partners-matrix/'.$model['id'].'/'.$number.'/'.$i.'/0/'.(($level['levels'] > $list_view_count) ? 1 : 0), ['target'=>'blank']),
						];
					}
					
					$structureNumber = $number;
				}
			}
		}
		
		return $result;
	}
	
	public static function getPartnerStructure($partnerID, $level = 0)
    {
		$partnerModel = self::findOne($partnerID);
		
		if(($partnerModel->left_key > 0 && $partnerModel->right_key > 0)) 
        {
			$condition = '';
			$conditionArr = [':left_key' => $partnerModel->left_key, ':right_key' => $partnerModel->right_key, ':partner_id' => $partnerID];
		
			if($level > 0)
			{
				$condition = ' AND (partners_2.level <= :limit_level)';
				$conditionArr[':limit_level'] = $partnerModel->level + $level; 
			}
		
			return self::find()
			->select('partners_1.login AS sponsor_name, partners_2.id, partners_2.login, partners_2.first_name, partners_2.last_name, partners_2.email, partners_2.level, (partners_2.level - '.$partnerModel->level.') as structure_level')
			->from('partners partners_1')
			->rightJoin('partners partners_2', 'partners_1.id = partners_2.sponsor_id')
			->where('partners_2.left_key >= :left_key AND partners_2.right_key <= :right_key AND partners_2.id != :partner_id'.$condition, $conditionArr);
		}
		
		return null;
	}
	
	public function getPartnerInfo($partnerID, $front = false)
    {
		$result = [];
		$select = ($front) ? self::getStructuresList(['matrix']) : self::getStructuresList(['matrix', 'total_amount', 'total_balls'], 'partners_2');
		
		if($select != '')
		{	
			if($front)
			{
				$result = Partners::find()->select($select)->where(['id'=>$partnerID])->asArray()->one();
			}
			else
			{
				$defaultWallet = (isset(\Yii::$app->params['default_payment_wallet'])) ? \Yii::$app->params['default_payment_wallet'] : [];
				$defaultWallet = (!empty($defaultWallet)) ? '`partners_2`.`'.$defaultWallet['index'].'_wallet`' : '';
				$connection = \Yii::$app->db;
				$sql = "SELECT `partners_1`.`login` AS `sponsor_name`, `partners_2`.`id`, `partners_2`.`sponsor_id`, `partners_2`.`login`, 
				`partners_2`.`first_name`, `partners_2`.`last_name`, `partners_2`.`status`, `partners_2`.`email`, ".$defaultWallet.", `partners_2`.`mailing`, 
				`partners_2`.`geo`, `partners_2`.`ip`, `partners_2`.`created_at`, `partners_2`.`updated_at`, ".$select." 
				FROM `partners` AS `partners_1` 
				RIGHT JOIN `partners` AS `partners_2` ON `partners_1`.`id` = `partners_2`.`sponsor_id`
				WHERE `partners_2`.`id` = '".$partnerID."'";
				$result = $connection->createCommand($sql)->queryOne();
			}
		}
		
		return $result;
	}
	
	public static function getPartnerEarningsInfo($partnerData, $demoInvitePayOff, $demoMatrixPayments, $invitePayOff, $matrixPayments, $admin = false)
    {
		$result = [];
		$structuresList = (isset(\Yii::$app->params['structures'])) ? \Yii::$app->params['structures'] : [];
		
		if(!empty($structuresList))
		{
			$url = ($admin) ? 'backoffice/backend-partners' : 'partners';
			
			foreach($structuresList as $number=>$data)
			{
				if(isset($partnerData['demo_total_amount_'.$number]))
				{
					$result[] = [
						'attribute' => 'demo_total_amount_'.$number, 
						'label' => Yii::t('form', 'Всего заработано по структуре'). '&nbsp;'.$number.' - '.Yii::t('form', 'DEMO ЗАРАБОТОК'),
						'format'=>'raw',//raw, html
						'value'=>Html::encode($partnerData['demo_total_amount_'.$number]),
					];
				}
				
				if(isset($partnerData['demo_total_balls_'.$number]))
				{
					$result[] = [
						'attribute' => 'demo_total_balls_'.$number, 
						'label' => Yii::t('form', 'Всего заработано баллов по структуре'). '&nbsp;'.$number.' - '.Yii::t('form', 'DEMO ЗАРАБОТОК'),
						'format'=>'raw',//raw, html
						'value'=>Html::encode($partnerData['demo_total_balls_'.$number]),
					];
				}
				
				if(isset($demoInvitePayOff[$number]))
				{
					$result[] = [
						'attribute' => 'demo_total_amount_'.$number, 
						'label' => Yii::t('form', 'Всего заработано за личные приглашения по структуре '.$number.' - DEMO ЗАРАБОТОК'),
						'format'=>'raw',//raw, html
						'value'=>Html::encode($demoInvitePayOff[$number]).' - '.Html::a(Yii::t('form', 'Смотреть'), \Yii::$app->request->BaseUrl.'/'.$url.'/invite-payoff-list?structure='.$number.'&id='.$partnerData['id'].'&demo=1', ['target'=>'blank']),
					];
				}
				
				if(isset($demoMatrixPayments[$number]))
				{
					$result[] = [
						'attribute' => 'demo_total_amount_'.$number, 
						'label' => Yii::t('form', 'Всего заработано за выплаты по матрицам - структура '.$number.' - DEMO ЗАРАБОТОК'),
						'format'=>'raw',//raw, html
						'value'=>Html::encode($demoMatrixPayments[$number]).' - '.Html::a(Yii::t('form', 'Смотреть'), \Yii::$app->request->BaseUrl.'/'.$url.'/matrix-payments-list?structure='.$number.'&id='.$partnerData['id'].'&demo=1', ['target'=>'blank']),
					];
				}
				
				if(isset($partnerData['total_amount_'.$number]))
				{
					$result[] = [
						'attribute' => 'total_amount_'.$number, 
						'label' => Yii::t('form', 'Всего заработано по структуре'). '&nbsp;'.$number.' - '.Yii::t('form', 'РЕАЛЬНЫЙ ЗАРАБОТОК'),
						'format'=>'raw',//raw, html
						'value'=>Html::encode($partnerData['total_amount_'.$number]),
					];
				}
				
				if(isset($partnerData['total_balls_'.$number]))
				{
					$result[] = [
						'attribute' => 'total_balls_'.$number, 
						'label' => Yii::t('form', 'Всего заработано баллов по структуре'). '&nbsp;'.$number.' - '.Yii::t('form', 'РЕАЛЬНЫЙ ЗАРАБОТОК'),
						'format'=>'raw',//raw, html
						'value'=>Html::encode($partnerData['total_balls_'.$number]).' - '.Html::a(Yii::t('form', 'Смотреть'), \Yii::$app->request->BaseUrl.'/'.$url.'/balls?structure='.$number.'&id='.$partnerData['id'].'&demo=0', ['target'=>'blank']),
					];
				}
				
				if(isset($invitePayOff[$number]))
				{
					$result[] = [
						'attribute' => 'total_amount_'.$number, 
						'label' => Yii::t('form', 'Всего заработано за личные приглашения по структуре'). '&nbsp;'.$number.' - '.Yii::t('form', 'РЕАЛЬНЫЙ ЗАРАБОТОК'),
						'format'=>'raw',//raw, html
						'value'=>Html::encode($invitePayOff[$number]).' - '.Html::a(Yii::t('form', 'Смотреть'), \Yii::$app->request->BaseUrl.'/'.$url.'/invite-payoff-list?structure='.$number.'&id='.$partnerData['id'].'&demo=0', ['target'=>'blank']),
					];
				}
				
				if(isset($matrixPayments[$number]))
				{
					$result[] = [
						'attribute' => 'total_amount_'.$number, 
						'label' => Yii::t('form', 'Всего заработано за выплаты по матрицам - структура'). '&nbsp;'.$number.' - '.Yii::t('form', 'РЕАЛЬНЫЙ ЗАРАБОТОК'),
						'format'=>'raw',//raw, html
						'value'=>Html::encode($matrixPayments[$number]).' - '.Html::a(Yii::t('form', 'Смотреть'), \Yii::$app->request->BaseUrl.'/'.$url.'/matrix-payments-list?structure='.$number.'&id='.$partnerData['id'].'&demo=0', ['target'=>'blank']),
					];
				}
			}
		}
		
		return $result;
	}
	
	public function getRefferalListByPartnerID($structureNumber, $partnerID)
    {
		$result = (new \yii\db\Query())
		->select('`partners`.`id`, partners.login, partners.status, partners.matrix_1, partners.email, partners.iso, partners.created_at, 
		matrix_'.$structureNumber.'_1.open_date, COUNT(DISTINCT `ref_partners`.`id`) AS `ref_count`')
		->from('partners')
		->leftJoin('`matrix_'.$structureNumber.'_1`', '`matrix_'.$structureNumber.'_1`.`partner_id` = `partners`.`id`')
		->leftJoin('`partners` `ref_partners`', '`ref_partners`.`sponsor_id` = `partners`.`id`')
		->where('`partners`.`sponsor_id` = :id', [':id' => $partnerID])
		->groupBy('`partners`.`id`');
		
		return $result;
	}
	
	public function getPartnerListByEmailFilters($country, $status, $referalsCount, $offset, $limit, $mailing, $login, $first_name, $last_name)
    {
		$result = null;
		$sql = "";
		$where = "";
		$select = (($login) ? "`partners`.`login`, " : "").(($first_name) ? "`partners`.`first_name`, " : "").(($last_name) ? "`partners`.`last_name`, " : "");
		$offset = ($offset != '') ? $offset : 0;
		$limit = ($limit > 0) ? "LIMIT ".$offset.", ".$limit : "";
			
		if($status != '')
		{
			$where.= ($status > 0) ? "`partners`.`matrix_1` > 0" : "`partners`.`matrix` = 0";
		}
			
		if($country != '')
		{
			$where.= (($status != '') ? " AND " : "")."`partners`.`iso` = '".$country."'";
		}
		
		if($where != '')
		{
			$where = "WHERE ".$where." AND `partners`.`mailing` ".(($mailing > 0) ? "= 0" : "> 0");
		}
		else
		{
			$where = "WHERE `partners`.`mailing` ".(($mailing > 0) ? "= 0" : "> 0");
		}
		
		if($referalsCount > 0)
		{
			$sql = "SELECT `partners`.`id`, ".$select." `partners`.`email`, COUNT(DISTINCT `ref_partners`.`id`) AS `ref_count`
			FROM `partners`
			LEFT JOIN `partners` AS `ref_partners` ON `ref_partners`.`sponsor_id` = `partners`.`id`
			".$where."
			GROUP BY `partners`.`id`
			HAVING COUNT(`ref_count`) >= '".$referalsCount."' ".$limit.";";
		}
		else
		{
			$sql = "SELECT ".$select." `email`
			FROM `partners`
			".$where." ".$limit.";";
		}
		
		if($sql != '')
		{
			$connection = \Yii::$app->db;
			$result = $connection->createCommand(trim($sql))->queryAll();
		}
		
		return $result;
	}
	
	public static function getPartnersCountByCurrentDay()
    {
		$connection = \Yii::$app->db;
		$result = $connection->createCommand("SELECT count(`id`) AS `register_count`
		FROM `partners` 
		WHERE DATE_FORMAT(FROM_UNIXTIME(`created_at`), '%Y-%m-%d') = CURDATE();")->queryOne();
		
		return $result;
	}
	
    public function SetGeoData($id)
    {
		$result = false;
		$geo = new Sypexgeo();
		$geoData = $geo->get();
		
		$model = self::findOne($id);
		$model->geo = serialize($geoData);
		$model->iso = $geoData['country']['iso'];
		$model->ip = $geo->getIP();
		$result = $model->save(false);
		
		return $result;
	}
	
	public function getGeoDataList()
    {
		$geoData = self::find()->select(['id', 'iso', 'geo'])->where('geo != ""')->all();
		$geoData = ArrayHelper::toArray($geoData, [
			'common\modules\backoffice\models\Partners' => [
				'id',
				'iso',
				'geo' => function ($geoData) {
					return unserialize($geoData->geo);
				},
			],
		]);
		//$result = ArrayHelper::map($geoData, 'id', 'iso', 'geo');
		$result = ArrayHelper::index($geoData, 'id');
		//$result = array_filter($result);
		
		return $result;
	}
	
	public static function getRandGeoDataList($limit = 100)
    {
		$result = [];
		$geoData = self::find()
		->select(['login', 'created_at', 'geo'])
		->where("`geo` != ''")
		->orderBy('`id` DESC')
		->limit($limit)
		->all();
		
		if($geoData !== null)
		{
			$result = ArrayHelper::toArray($geoData, [
				'common\modules\backoffice\models\Partners' => [
					'login',
					'created_at',
					'geo' => function ($geoData) 
					{
						$geo = unserialize($geoData->geo);
						$iso = $geo['country']['iso'];
						$country = $geo['country']['name_ru'];
						
						return [$iso, $country];
					},
				],
			]);
		}
		
		return $result;
	}
	
	public static function makeBan($id)
    {
		$result = false;
		$model = self::findOne($id);
		
		if($model !== null) 
        {
			$model->status = ($model->status >= 0) ? -1 : 1;
			$result = $model->save(false);
		}
		
		return $result;
	}
}
