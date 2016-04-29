<?php
namespace rest\models;
use yii\base\Model;
use Yii;

/**
 * This is the model class for table "user".
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
 * @property string $password
 * @property integer $roleId
 * @property string $createdDate
 * @property string $modifiedDate
 */
class UserMaster extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username',  'email','status', 'created_at', 'updated_at', 'password', 'roleId', 'createdDate', 'modifiedDate'], 'safe'],
            [['status', 'created_at', 'updated_at', 'roleId'], 'integer'],
            [['createdDate', 'modifiedDate'], 'safe'],
        	
            [['username', 'password_hash', 'password_reset_token', 'email', 'auth_key', 'password'], 'string', 'max' => 200]
        		
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Username',
            'password_hash' => 'Password Hash',
            'password_reset_token' => 'Password Reset Token',
            'email' => 'Email',
            'auth_key' => 'Auth Key',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'password' => 'Password',
            'roleId' => 'Role ID',
            'createdDate' => 'Created Date',
            'modifiedDate' => 'Modified Date',
        ];
    }
}
