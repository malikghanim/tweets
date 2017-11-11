<?php
namespace common\models;

use Yii;
use yii\base\Model;
/**
 * Login form
 */
class LoginForm extends Model
{
    public $email;
    public $password;
    public $rememberMe = true;
    public $first_name;
    public $last_name;
    public $company;
    public $username;
    private $_user;
    public $verifyCode;
    public $suspended = false;
    public $captcha;

    public function behaviors()
    {
        return [

        ];
    }




    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'password'], 'required'],
            ['username', 'safe'],
            ['email', 'email'],
            ['password', 'validatePassword']
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, 'Incorrect username or password.');
            }
        }
    }

    /**
     * Logs in a user using the provided username and password.
     *
     * @return boolean whether the user is logged in successfully
     */
    public function login()
    {
        // $this->validate();
        // var_dump($this->attributes);die;
        // var_dump($this->getErrors());die;
        if ($this->validate()) {
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0);
        } else {
            return false;
        }
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    protected function getUser() {
        $this->username = strtolower($this->username);
        $this->email = strtolower($this->email);
        if ($this->_user === null) {
            $this->_user = User::findByUsername(strtolower($this->username));
            if (empty($this->_user))
                $this->_user = User::findByEmail(strtolower($this->email));

            if (!empty($this->user) && $this->user->role != 11)
                return null;

            if ($this->_user !== Null) {
                $this->first_name = !is_null($this->_user->first_name) ? $this->_user->first_name : NULL;
                $this->last_name = !is_null($this->_user->last_name) ? $this->_user->last_name : NULL;
                $this->_user->ip_address=  !is_null(\common\helpers\IpAddress::get_ip()) ? \common\helpers\IpAddress::get_ip() : NULL;
                $this->_user->save();
            }
        }

        return $this->_user;
    }
}
