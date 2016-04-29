<?php
namespace rest\models;

use common\models\User;
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
    public $OtpNumber;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['username', 'filter', 'filter' => 'trim'],
            ['username', 'required'],
            ['username', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This username has already been taken.'],
            ['username', 'string', 'min' => 2, 'max' => 255],

            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This email address has already been taken.'],

            ['password', 'required'],
            ['password', 'string', 'min' => 6],
        		['OtpNumber', 'number'],
        ];
    }
    

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function signup()
    {
        if ($this->validate()) {
            $user = new User();
            $user->username = $this->username;
            $user->email = $this->email;
            $user->roleId = 3;
            $user->setPassword($this->password);
            $user->generateAuthKey();
          
            
            if ($user->save()) {
            	$result = array();
            	foreach ($user as $key => $val)
            	{
            		$result['userId'] = $user->id;
            		$result['userName'] = $user->username;
            		$result['userEmail'] = $user->email;
            		//$result['userRoleId'] = $user->roleId;
            		//$result['userRoleName'] = $user->getroleName();
            	
            	}
            	//print_r($result);exit();
                return $result;
            }
        }

        return null;
    }
    public function sendEmail()
    {
    	/* @var $user User */
    	$user = User::findOne([
    			'status' => User::STATUS_ACTIVE,
    			'email' => $this->email,
    	]);
    
    	if ($user) {
    
    
    		if ($user->save()) {
    			return \Yii::$app->mailer->compose(['text' => 'registerSuccess-text'], ['user' => $user])
    			->setFrom([\Yii::$app->params['supportEmail'] => \Yii::$app->name . ' robot'])
    			->setTo($this->email)
    			->setSubject('Successfully Registered ' . \Yii::$app->name)
    			->send();
    		}
    	}
    
    	return false;
    }
}
