<?php
namespace rest\models;

use common\models\User;
use yii\base\InvalidParamException;
use yii\base\Model;
use Yii;

/**
 * Password reset form
 */
class ResetPasswordForm extends Model
{
    public $password;
    public $confirmPassword;
    public $email;

    /**
     * @var \common\models\User
     */
    private $_user;


 

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['password', 'required'],
            ['password', 'string', 'min' => 6],
        	['email', 'email'],
        ];
    }

    /**
     * Resets password.
     *
     * @return boolean if password was reset.
     */
	   public function resetPassword()
	    {
	        $user = $this->_user;
	        $user->setPassword($this->password);
	        $user->removePasswordResetToken();
			return $user->save(false);
	    }
    
  
}
