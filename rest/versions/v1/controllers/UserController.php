<?php
namespace rest\versions\v1\controllers;

use yii;
use rest\models\RegisterMaster;
use rest\models\UserMaster;
use rest\models\Countries;
use rest\models\States;
use common\models\LoginForm;
use rest\models\SignupForm;
use yii\rest\Controller;
use common\models\User;
use yii\helpers\ArrayHelper;
use yii\web\BadRequestHttpException;

/**
 * Class UserController
 * @package rest\versions\v1\controllers
 */
class UserController extends Controller
{
    
    /**
     * This method implemented to demonstrate the receipt of the token.
     * Do not use it on production systems.
     * @return string AuthKey or model with errors
     */
	
    public function actionLogin()
    {
        $model = new LoginForm();
		if ($model->load(\Yii::$app->getRequest()->getBodyParams(), '') && $model->login()) {
			
        	 //*** No creaditial for vendor ***
        	$result = array();
	        if ($model->user->roleId == 2) {
	        	$userResult = ['status'=>'fail','message' => 'unauthorised user'];
	        
	        }else{
	        		// *** creaditial for customer only ***
	        		$user= $model->getUser();
	        		$userModel = new User();
	        		foreach ($user as $key => $val)
	        		{
	        			$result['userId'] = $user->id;
	        			$result['userName'] = $user->username;
	        			$result['userEmail'] = $user->email;
	        			$result['userRoleId'] = $user->roleId;
	        			$userModel->roleId = $user->roleId;
	        			//$result['userRoleName'] = $userModel->getroleName();
	        		}
	        		$userResult = ['status'=>'success'];
	        		$userResult['userData'] = $result;
	        	}
	        	return $userResult;
	        	
        }else {
        	$errors = array();
        	if($model->hasErrors())
        	{
        		$errors['status'] = 'fail';
        		foreach ($model->errors as $key => $val)
        		{
        			$errors['message'] = $val[0];
        		}
        	}
        	//print_r($errors);exit();
            return $errors;
        }
    }
    
    /**
	 **************  @return Register *************
     */
    
    public function actionRegister()
    {

    	$model = new SignupForm();
    	
    	if ($model->load(\Yii::$app->getRequest()->getBodyParams(), '')) 
        {
    		$userData =  $model->signup();
    		if($model->hasErrors())
    		{
    			$errors['status'] = 'fail';
    			foreach ($model->errors as $key => $val)
    			{
    				$errors['message'] = $val[0];
    			}
    		}
    		if($userData)
    		{
    		$result = ['status'=>'success'];
    		$result['userData'] = $userData;
    		return $result;
    		}
    		else {
    		
                	$errors = array();
                	if($model->hasErrors())
                	{
                		$errors['status'] = 'fail';
                		foreach ($model->errors as $key => $val)
                		{
                			$errors['message'] = $val[0];
                		}
                	}
                	//print_r($errors);exit();
                    return $errors;
                    
                 
    		}
    	} else {
    		return $model;
    	}
    }
    public function actionSocialregister()
    {
    
    	$model = new SignupForm();
    	$userModel = new User();
    	$model->username = $_POST['username'];
    	$model->email = $_POST['username'];
    	$model->password = 'dummypassword';
    	$useremail = User::findByUsername($model->username);
    	
    	if(!(isset($useremail->email)) || ($useremail->email == NULL))
    	{
    		$userData =  $model->signup();
    		$userResult = ['status'=>'success'];
    		$userResult['userData'] = $userData;
    	}
    	else 
    	{
    		$loginmodel = new LoginForm();
    		$user= $loginmodel->getUserByName($useremail->username);
    		$result = array();
    		foreach ($user as $key => $val)
    		{
    			$result['userName'] = $user->username;
    			$result['userEmail'] = $user->email;
    			$result['userRoleId'] = $user->roleId;
    			$userModel->roleId = $user->roleId;
    			$result['userRoleName'] = $userModel->getroleName();
    			$result['userId'] = $user->id;
    		}
    		$userResult = ['status'=>'success'];
    		$userResult['userData'] = $result;
    	}
    		return $userResult;
    }
    
    
		/**
		 * *************************profile***********************/
		   public function actionProfileview()
		   {
	    	   $model = new RegisterMaster();
			   if ($model->load(\Yii::$app->getRequest()->getBodyParams(), '')) 
			   {
					  $id = $model->uid;
					  if($id){
					   $roleId = 3;
					   return  $this->view($id,$roleId);
					 }else{
						$userResult =  array('status' => 'fail',"message" => "unauthorised user");
						return $userResult;
					}
						  
			   }else{
			    		return $model;
			 }
		    	  
		   }//action profile
	    
	      public function view($id,$roleId)
	      {
	     		//***************** get the user data ******************/
				$userMain = UserMaster::find()->select(['id','username','email'])->where(['id' => $id,'roleId' => $roleId])->one();
				$userMain = ArrayHelper::toArray($userMain);
				//***************** get the register data ******************//
		        $userData = RegisterMaster::find()->select(['firstName','middleName','lastName','mobile','dob','gender','address1','address2','city','state','country','zip'])->where(['uid'=> $id])->one();
		        $userData = ArrayHelper::toArray($userData);
    	
				$temp = array();
				foreach ($userData as $key => $val){
		    		$temp[$key] = ($val == '') ? "" : $val;
		    		
		    	}
		    	foreach ($userMain as $key => $val){
		    		$temp[$key] = ($val == '') ? "" : $val;
		    		 
		    	}
		    	if(!(@$userMain['id'] || @$userMain['roleId'] )){
		    		$userResult =  array('status' => 'fail',"message" => "unauthorised user");
		    		return $userResult;
		    	}
			    	
		    	/**
		    	 * ************* country name  ****************
		    	 * **/
		    	$country =  @$userData['country'];
		    	$countries = new Countries();
		    	$countryData  = $countries->getCountryName($country);
		    	 
		    	/**
		    	 * ************* state name *****************
		    	 * **/
		    	$state =  @$userData['state'];
		    	$states = new States();
		    	$stateData  = $states->getStateName($state);
			    	
		    	if($countryData == null || $stateData == null)
		    	{
		    		$result = ['status'=>'success'];
		    		$result['profile'] = $temp;
		    		return $result;
		    	}
		    	
		    	else if($temp)
		    	{
		    		$result = ['status'=>'success'];
		    		$result['profile'] = $temp;
		    		$result['profile']['country'] = $countryData;
		    		$result['profile']['state'] = $stateData;
		    		return $result;
		    	}else {
			    
			    	$errors = array();
			    	if($model->hasErrors())
			    	{
				    	 $errors['status'] = 'fail';
				    	 foreach ($model->errors as $key => $val)
				    	 {
				    		$errors['message'] = $val[0];
				    	 }
			    	}
			    
			    		return $errors;
    			}//else
	   }//view function 
	    
		 /**
	      ****************  @@Profileupdate  ***************
	      */
	    	
	    public function actionProfileupdate()
	    {
	    	$model = new RegisterMaster();
	    	if($model->load(\Yii::$app->getRequest()->getBodyParams(), ''))
	    	{
			    		$id = $model->uid;
			    
			    		// $roleId = $model->roleId;
			    		/*  if($roleId != 3)
			    		 {
			    		 $result = ['status'=>'fail','message' => 'unauthorised user'];
			    		 return $result;
			    		 } */
			    
			    		//print_r($model);
			    		// exit;
			    			
			    		$userMain = UserMaster::find()->select(['id','email'])->where(['id' => $id])->one();
			    		if(!$userMain){
			    			$result = ['status'=>'fail','message' => 'unauthorised user'];
			    			return $result;
			    		}
			    		
			    		//**************** get uid from register table  ******************//
			    		 //$userData = RegisterMaster::find()->where(['uid'=> $id])->one();
			    		
			    		//**************** countries list ********************************//
			    		/* $model->countriesList = Countries::getCountries();
			    		if($userData['country'] == NULL )
			    		{
			    
			    			$model->country = 231;
			    		}
			    		else
			    		{
			    
			    			$model->country = $userData['country'];
			    		} */
			    		//**************** state list *************************************//
			    		/* $states = Countries::getStatesByCountryDefault($model->country);
			    		if($userData['state'] == NULL )
			    		{
			    
			    			$model->state = 3975;
			    		}
			    		else
			    		{
			    
			    			$model->state = $userData['state'];
			    		}
			    		$model->state = $states;
			    		echo $model->state;
			    		exit; */
			    		
			    		//**************** updated in register table  *********************//
			    		$userExist = RegisterMaster::find()->where(['uid'=> $id])->one();
			    		if($userExist != NULL)
			    		{
			    			$userExist->firstName = $model->firstName;
			    			$userExist->lastName = $model->lastName;
			    			$userExist->middleName = $model->middleName;
			    			$userExist->gender = $model->gender;
			    			$userExist->dob = $model->dob;
			    			$userExist->mobile = $model->mobile;
			    			$userExist->mobile2 = $model->mobile2;
			    			$userExist->address1 = $model->address1;
			    			$userExist->address2 = $model->address2;
			    			$userExist->city = $model->city;
			    			$userExist->state = $model->state;
			    			$userExist->country = $model->country;
			    			$userExist->zip = $model->zip;
			    			
			    			// *******  updated *********
			    			$updated = $userExist->update();
			    			$result = ['status'=>'success','message' => 'success'];
			    			return $result;
			    	}else{
			    				
			    			// *******  inserted *********
			    			$model->email = 'dummy@mailinator.com';
			    			$inserted = $model->save();
			    			$result = ['status'=>'success','message' => 'success'];
			    			return $result;
			    		}
	    	}else{
	    		 
	    		return $model;
	    	}
	    	 
	    }//actionupdate
	    
	     /**
	     ******************  Forgot password **************
	     * **/
	    
			public function actionForgotpassword()
			{
				
						//$model = new RegisterMaster();
							 $model = new SignupForm();
						if ($model->load(\Yii::$app->getRequest()->getBodyParams(), '')) 
						{
					 	         //$user = new User;
							   $email = $model->email;
							  if($email !="")
							  {   
							 
								     $user = User::find()->select(['id','email'])->where(['or',
	        						 ['email'=> $email],
	          						 ['username'=> $email]])->one();
								     $user = ArrayHelper::toArray($user);
							     
								     $userEmail = @$user['email'];
								     if($userEmail)
								     {
								       	 
								       	 $userId = $user['id'];
								     
								     	 $Otp = "";
								     	 for ($i = 0; $i<6; $i++)
								     	 {
								     	 	$Otp .= mt_rand(0,9);
								     	 }
								     	 
								     	 $command = Yii::$app->db->createCommand("UPDATE user SET `OtpNumber` = '$Otp' WHERE `id` =  $userId");
								     	 $command->execute();
								     	 
								     	/*  $SendEmail =  \Yii::$app->mailer->compose(['user' => $user])
								     	->setFrom([\Yii::$app->params['supportEmail'] => \Yii::$app->name . ' robot'])
								        ->setTo($userEmail)
								     	 ->setSubject('OTP NUMBER:' . \Yii::$app->name)
								     	 ->setTextBody("OtpNumer is :" .$Otp)
								     	 ->send();
										 if($SendEmail);
										 {
												 $result = ['status'=>'success','message'=>'please check OtpNumber in your email'];
										 }  */
										 
								     	  $result = ['status'=>'success','message'=>'please check OtpNumber in your email'];
								     	 
										
									 }else {
								      	     $result = ['status'=>'fail','message'=>'please Enter correct email or username'];
			
						             
								     }
							 }//
							 else{
							  		$result = ['status'=>'fail','message'=>'please enter email or username'];
							  	
							      }
							  return $result;
							}//if
				}//action
	    
			/**
			 ****************  Checkotpno ***************
			 * **/
				  
	 
			public function actionCheckotpno()
			{
					$model = new SignupForm();
					if ($model->load(\Yii::$app->getRequest()->getBodyParams(), ''))
					{
								$email = $model->email;
								$OtpNumber = $model->OtpNumber;
								
								switch ($model)
								{
									case $model->email == '' :
										$result = ['status'=>'fail','message' => 'please enter the email or username'];
										return $result;
										break;
									case $model->OtpNumber == '' :
										$result = ['status'=>'fail','message' => 'please enter the OtpNumber'];
										return $result;
										break;
										
									default:
										$user = User::find()->select(['OtpNumber'])->where(['or',['email' => $email],['username' => $email]])->one();
										$user = ArrayHelper::toArray($user);
										$userOtpNumber = @$user['OtpNumber'];
										if($userOtpNumber == $OtpNumber)
										{
											$result = ['status'=>'success','message'=>'otp number matched'];
										}else{
											$result = ['status'=>'fail','message'=>'please enter the correct otp number'];
										}
										
											
										return $result;
								}
						
						
					 }
			 }
			

			
	 
    
		   
		    
    
}
