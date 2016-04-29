<?php

namespace rest\models;


use Yii;
use common\models\User;

/**
 * This is the model class for table "register".
 *
 * @property integer $rId
 * @property string $uid
 * @property string $firstName
 * @property string $lastName
 * @property string $middleName
 * @property string $dob
 * @property string $mobile
 * @property string $address1
 * @property string $address2
 * @property string $city
 * @property string $state
 * @property string $country
 * @property string $zip
 * @property string $createdDate
 * @property string $modifiedDate
 */
class VendorRegister extends \yii\db\ActiveRecord {
	public $file;
	public $secondLanguage;
	public $thirdLanguage;
	public $email;
	public $roleId;
	public $username;
	public $password;
	public $confirm;
	public $countriesList;
	public $statesData;
	
	
	
	/**
	 * @inheritdoc
	 */
	public static function tableName() {
		return 'vendor_register';
	}
	
	/**
	 * @inheritdoc
	 */
	public function rules() {
		return [ 
				[ 
						'username',
						'filter',
						'filter' => 'trim' 
				],
				[ 
						'username',
						'required' ,'on' => ['signup','vendor']
				],
				[ 
						'username',
						'match',
						'pattern' => '/^[a-zA-Z0-9]+$/',
						'message' => 'username can only contain alphanumeric characters.' 
				]
				,
				[
				'username',
				'unique',
				'targetClass' => '\common\models\User',
				'message' => 'User name already exists try for new'
						],
				[ 
						'username',
						'string',
						'min' => 3,
						'max' => 15 
				]
				,
				
				[ 
						'email',
						'filter',
						'filter' => 'trim' 
				],
				[ 
						'email',
						'required' ,'on' => ['signup','vendor']
				],
				[
				'email',
				'unique',
				'targetClass' => '\common\models\User',
				'message' => 'Email already exists try for new'
						],
				[ 
						'email',
						'match',
						'pattern' => '/^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/',
						'message' => 'Email can contain @ and .com characters.' 
				]
				,
				
				[ 
						'email',
						'email' 
				],
				
				[ 
						'password',
						'required' ,'on' => ['signup','vendor']
				],
				[ 
						'password',
						'string',
						'min' => 6,
						'max' => 14 
				],
				
				[ 
						[ 
								'confirm' 
						],
						'compare',
						'compareAttribute' => 'password' 
				],
				[ 
						[ 
								'password' 
						],
						'safe' 
				],
				
				[ 
						'confirm',
						'required' ,'on' => ['signup','vendor']
				],
				
				[
				'password',
				'match',
				// char and number and special symbol
				'pattern' => '/^(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[!@#$%^&*])[a-zA-Z0-9!@#$%^&*]{6,16}$/',
				'message' => 'should contain  minimum 6 char with atleast 1 letter and 1 number'
						],
				[ 
						[ 
								'uid',
								'firstName',
								'lastName',
								'middleName',
								'mobile',
								'mobile2',
								'address2',
								'city',
								'state',
								'country',
								'zip',
								'createdDate',
								'modifiedDate',
								'secondLanguage',
								'thirdLanguage',
								'email',
								'storeName',
								'storeAddress',
								'storeImage',
								'roleId',
								'username',
								'password',
								'confirm',
								'countriesList',
								'statesData',
								'vendor_unique_id',
								'businessName2',
								'fax'
						],
						'safe' 
				],
				[ 
						[ 
								
								'createdDate',
								'modifiedDate' 
						],
						'safe' 
				],
				[ 
						[ 
								'uid'
						],
						'unique' 
				],
			[['firstName','lastName','mobile','storeName','storeAddress'],'required','on' => ['signup','vendor']]
		];
	}
	
	/**
	 * @inheritdoc
	 */
	public function attributeLabels() {
		return [ 
				'rId' => 'R ID',
				'uid' => 'Uid',
				'username' => 'User Name',
				'password' => 'Password',
				'confirm' => 'Confirm Password',
				'firstName' => 'First Name',
				'lastName' => 'Last Name',
				'middleName' => 'Middle Name',
				
				'mobile' => 'Mobile',
				'mobile2' => 'Mobile2',
				
				'address2' => 'Address2',
				'city' => 'City',
				'state' => 'State',
				'country' => 'Country',
				'zip' => 'Zip',
				'storeName' => 'Business Name',
				'storeAddress' => 'Business Address',
				
				'createdDate' => 'Created Date',
				'modifiedDate' => 'Modified Date',
				'storeImage' => 'storeImage' 
		]
		;
	}
	public function getU() {
		return $this->hasOne ( UserMaster::className (), [ 
				'id' => 'uid' 
		] );
	}
	public function signup() {
		if ($this->validate ()) {
			$user = new User ();
			$user->roleId = 2;
			$user->username = $this->username;
			$user->email = $this->email;
			$user->setPassword ( $this->password );
			$user->generateAuthKey ();
			if ($user->save ()) {
				return $user;
			}
		}
		
		return null;
	}
	public function sendEmail() {
		/* @var $user User */
		$user = User::findOne ( [ 
				'status' => User::STATUS_ACTIVE,
				'email' => $this->email 
		] );
		
		if ($user) {
			
			if ($user->save ()) {
				return \Yii::$app->mailer->compose ( [ 
						'text' => 'registerSuccess-text' 
				], [ 
						'user' => $user 
				] )->setFrom ( [ 
						\Yii::$app->params ['supportEmail'] => \Yii::$app->name . ' robot' 
				] )->setTo ( $this->email )->setSubject ( 'Successfully Registered ' . \Yii::$app->name )->send ();
			}
		}
    
    	return false;
    }
}
