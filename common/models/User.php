<?php
namespace common\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use app\models\UserGroups;
use common\components\geo\Sypexgeo;

/**
 * User model
 *
 * @property integer $id
 * @property string $username
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property string $auth_key
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $password write-only password
 */
class User extends ActiveRecord implements IdentityInterface
{
    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 1;
    
    public $password;
	public $re_password;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user}}';
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
			[['username', 'email', 'group_id'], 'required', 'message' => Yii::t('form', 'Это поле должно быть заполнено!')],
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_DELETED]],
            [['password', 're_password'],'required','except'=>'update'],
            ['email','email'],
            ['email','unique'],
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['username', 'unique', 'targetAttribute' => 'username'],
            [['password', 're_password'], 'match', 'pattern' => '/^[A-Za-z0-9_\s,]+$/u', 'message' => Yii::t('form', 'Введенны неправильные символы!')],
            ['re_password', 'compare', 'compareAttribute' => 'password'],
        ];
    }
    
    /**
     * @inheritdoc
    */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('form', 'ID'),
            'username' => Yii::t('form', 'Логин'),
            'password' => Yii::t('form', 'Пароль'),
            're_password' => Yii::t('form', 'Повторить пароль'),
            'email' => Yii::t('form', 'Email'),
            'status' => Yii::t('form', 'Статус'),
            'group_id' => Yii::t('form', 'Группа'),
            'group' => Yii::t('form', 'Группа'),
            'created_at' => Yii::t('form', 'Создан'),
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
		
        $scenarios['register'] = ['username', 'email', 'group_id', 'password', 're_password'];
		
		$scenarios['update'] = ['username', 'email', 'group_id', 'password', 're_password'];
        
		return $scenarios;
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
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

        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
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
    public function getAuthKey()
    {
        return $this->auth_key;
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
			$this->password = self::setPassword($this->password);
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
}
