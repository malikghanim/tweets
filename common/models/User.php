<?php
namespace common\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use yii2tech\authlog\AuthLogIdentityBehavior;
use yii\authclient\clients\Google;
use yii\authclient\clients\Facebook;
use common\components\BoxAuth;
use linslin\yii2\curl\Curl;
use yii\filters\RateLimitInterface;
use \OAuth2\Storage\UserCredentialsInterface;

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
 * @property integer $phone_number
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $password write-only password
 */
class User extends ActiveRecord implements IdentityInterface, RateLimitInterface,
    UserCredentialsInterface
{
    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 10;
    const STATUS_SUSPENDED = 20;
    const ROLE_USER = 10;
    const ROLE_ADMIN = 11;
    const ROLE_USER_STRING ='admin';
    const ROLE_ADMIN_STRING ='user';

    //to be used only for new social users
    public $social_new;
    public $access_token;
    public $new_record;

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
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_DELETED, self::STATUS_SUSPENDED]],
            ['role', 'default', 'value' => self::ROLE_USER],
            ['role', 'in', 'range' => [self::ROLE_USER, self::ROLE_ADMIN,self::ROLE_ADMIN_STRING,self::ROLE_USER_STRING]],
            ['is_system_password', 'boolean'],
            ['is_system_password', 'default', 'value' => false]
        ];
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * Implemented for Oauth2 Interface
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return null;
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByEmail($email)
    {
        return static::findOne(['email' => $email, 'status' => self::STATUS_ACTIVE]);
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
        $resetToken = static::isPasswordResetTokenValid($token);
        if (!$resetToken['status']) {
            return $resetToken;
        }

        $userData = static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);

        return ['status' => true, 'error' => Yii::$app->params['token_status']['valid'], 'data' => $userData];
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
            return ['status' => false, 'error' => Yii::$app->params['token_status']['empty']];
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];

        if ($timestamp + $expire < time()) {
            return ['status' => false, 'error' => Yii::$app->params['token_status']['expired']];
        }else{
            return ['status' => true, 'error' => Yii::$app->params['token_status']['valid']];
        }
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
     * @return bool if password provided is valid for current user
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
    public function removePasswordResetTokenf()
    {
        $this->password_reset_token = null;
    }

    // Rate Limiting Control
    const RATE_LIMIT_PREFIX = 'userApi';
    const RATE_LIMIT = 10000; // Number of allowed requests
    const RATE_LIMIT_PERIOD = 3600; // Period in seconds

    private $allowance;
    private $allowance_updated_at;
    public function getApiRateInfo()
    {
        /*$redis = Yii::$app->redis;
        $redisApiRate = $redis->hgetall(self::RATE_LIMIT_PREFIX . ':' . (string)$this->id);
        $apiRate = [];
        for ($i=0; $i < count($redisApiRate); $i+=2) {
            $apiRate[$redisApiRate[$i]] = $redisApiRate[$i+1];
        }
        return $apiRate;*/
        return false;
    }

    public function getRateLimit($request, $action)
    {
        return [self::RATE_LIMIT, self::RATE_LIMIT_PERIOD];
    }

    public function loadAllowance($request, $action)
    {
        $apiRate = $this->getApiRateInfo();

        $this->allowance = isset($apiRate['allowance'])?(int)$apiRate['allowance']: self::RATE_LIMIT;
        $this->allowance_updated_at = isset($apiRate['allowance_updated_at'])?(int)$apiRate['allowance_updated_at']:0;
        return [$this->allowance, $this->allowance_updated_at];
    }

    public function saveAllowance($request, $action, $allowance, $timestamp)
    {
        /*$redis = Yii::$app->redis;
        $rateHashId = self::RATE_LIMIT_PREFIX . ':' . (string)$this->id;

        $redis->hmset($rateHashId, 'rateLimit', self::RATE_LIMIT, 'allowance', $allowance, 'allowance_updated_at', $timestamp);*/
        return false;
    }
    /////////////////////////

    /**
     * Implemented for Oauth2 Interface
     */
    public function checkUserCredentials($username, $password)
    {
        $user = static::findByUsername($username);
        if (empty($user)) {
            return null;
        }

        if ($user->validatePassword($password)) {
            return $user;
        }
    }

    /**
     * Implemented for Oauth2 Interface
     */
    public function getUserDetails($username)
    {
        $user = static::findByUsername($username);
        return ['user_id' => $user->getId()];
    }

    public function toJson() {
        $out = [];
        $out['id'] = $this->getId();
        $out['first_name'] = $this->first_name;
        $out['last_name'] = $this->last_name;
        $out['username'] = $this->username;
        $out['email'] = $this->email;
        $out['phone_number'] = $this->phone_number;
        return $out;
    }
}
