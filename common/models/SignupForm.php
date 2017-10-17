<?php
namespace common\models;

use common\models\User;
use common\models\UserEmails;
use yii\base\Model;
use Yii;

/**
 * Signup form
 */
class SignupForm extends Model
{
    public $username;
    public $email;
    public $password;
    public $first_name;
    public $last_name;
    public $company;
    public $user_group;
    public $google_id;
    public $phone_number;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['username', 'filter', 'filter' => 'trim'],
            //['username', 'required'],
          //  ['username', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This username has already been taken.'],
            ['username', 'string', 'min' => 2, 'max' => 255],

            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This email address has already been taken.'],

            ['password', 'required'],
            ['password', 'string', 'min' => 6],
            [['first_name','last_name'], 'required'],
            [['company','phone_number'],'safe']
        ];
    }

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function signup()
    {
        if (!$this->validate()) {
            return null;
        }

        $user = new User();
        $user->username = $this->username;
        $user->email = $this->email;
        $user->first_name = $this->first_name;
        $user->last_name = $this->last_name;
        $user->user_group = 10;
        $user->google_id = $this->password;
        $user->phone_number = $this->phone_number;
        $user->setPassword($this->password);
        //$user->ip_address=  !is_null(\common\helpers\IpAddress::get_ip()) ? \common\helpers\IpAddress::get_ip() : NULL; 
        $user->generateAuthKey();

        $user = $user->save() ? $user : null;
 
        return $user;
    }
}
