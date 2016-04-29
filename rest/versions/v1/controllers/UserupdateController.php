<?php
namespace rest\versions\v1\controllers;

use rest\models\RegisterMaster;
use common\models\User;
use yii\helpers\ArrayHelper;
use yii\rest\Controller;
use yii;

/**
 * Class UserController
 * @package rest\versions\v1\controllers
 */
class UserupdateController extends Controller
{
	
			  public function actionUpdate()
			  {
			  			$model = new RegisterMaster();
			  			if($model->load(\Yii::$app->getRequest()->getBodyParams(), ''))
			  			{
			  				     $id = $model->uid;
			  				     $roleId = $model->roleId;
			  				   
			  				     $firstName = $model->firstName;
			  				     $lastName = $model->lastName;
			  				     $middleName = $model->middleName;
			  				     $dob	 = $model->dob;
			  				  
			  				     $mobile = $model->mobile;
			  				     $mobile2 = $model->mobile2;
			  				     $address1 = $model->address1;
			  				     $address2 = $model->address2;
			  				     $city = $model->city;
			  				     $state = $model->state;
			  				     $country = $model->country;
			  				     $zip = $model->zip;
			  				     $gender = $model->gender;
			  				
		                         $userMain = User::find()->select(['id','email'])->where(['id' => $id])->one();
		                         if(!$userMain){
		                         	$result = ['status'=>'fail','message' => 'fail'];
		                         	return $result;
		                         }
			                     $userMain = ArrayHelper::toArray($userMain);
			                     
			                     
			                   
			                   
			                    	$userExist = RegisterMaster::find()->where(['uid'=> $id])->one();
			                    	if($userExist != NULL)
			                     	{
			                     		
			                     	
			                     		
			                     	
			                     	  
			                     	 $userExist->uid = $model->uid;
			                     	 $userExist->firstName = $model->firstName;
			                     	 $userExist->lastName = $model->lastName;
									 $userExist->middleName = $model->middleName;
			                     	 
			                     	  $userExist->dob = $model->dob;
			                     	
                                 	 $userExist->mobile = $model->mobile;
						         	 $userExist->mobile2 = $model->mobile2;
			                     	 
                                     $userExist->address1 = $model->address1;
                                     $userExist->address2 = $model->address2;
			                     	 
				                     $userExist->city = $model->city;
				                 	 $userExist->state = $model->state;
					            	 $userExist->country = $model->country;
				                 	 $userExist->zip = $model->zip;
				                 	 $userExist->gender = $model->gender;
				                 	 
								      if($roleId == '2')
								     {
								        
								        $roleId = $model->roleId;
								       
		                 	          /************* Update Query  *******/
		                 	 		    $command = Yii::$app->db->createCommand("UPDATE user SET `roleId` = '$roleId' WHERE `id` = $id");
		                 	 			$command->execute();
		                 	 			$userExist->storeName = $model->storeName;
		                 	 			$userExist->storeAddress = $model->storeAddress;
						       		}
				                 	 

				                 	
				                 	 $updated =  $userExist->update();
			                     	 if($updated)
			                     	 {
			                     	 	$result = ['status'=>'success','message' => 'success'];
			                     	 	return $result;
			                     	 
			                     	 }else {
			                     	 	$result = ['status'=>'fail','message' => 'fail'];
			                     	 	return $result;
			                     	 }
			                     
			                     	
			                     }else {
			                     	
			                     
			                     
			                     	 $id = $model->uid;
			                      	 $roleId = $model->roleId;
			                     	 $firstName = $model->firstName;
			                     	 $lastName = $model->lastName;
			                     	 $middleName = $model->middleName;
			                     	 $dob	 = $model->dob;
			                     	 $mobile = $model->mobile;
			                     	 $mobile2 = $model->mobile2;
			                     	 $address1 = $model->address1;
			                     	 $address2 = $model->address2;
			                     	 $city = $model->city;
			                     	 $state = $model->state;
			                     	 $country = $model->country;
			                     	 $zip = $model->zip;
			                     	 $gender = $model->gender;
			                     	
			                     	 
			                     	 if($roleId == '2')
			                     	 {
			                     	 	
			                     	 	$roleId = $model->roleId;
			                     	 	
			                     	 	
			                     	 	$command = Yii::$app->db->createCommand("UPDATE user SET `roleId` = '$roleId' WHERE `id` = $id");
			                     	 	$command->execute();
			                     	 	
			                     	 	 $firstName = $model->firstName;
			                     	 	 $lastName = $model->lastName;
			                     	 	 $middleName = $model->middleName;
			                     	 	 $mobile = $model->mobile;
			                     	 	 $mobile2 = $model->mobile2;
			                     	 	 $storeName = $model->storeName;
			                     	     $storeAddress = $model->storeAddress;
			                     	 	$insert2 = Yii::$app->db->createCommand()
			                     	 	->insert('register', [
			                     	 		
			                     	 			'uid' => $id,
			                     	 			'firstName' => $firstName,
			                     	 			'lastName' => $lastName,
			                     	 			'middleName' => $middleName,
			                     	 			'mobile' => $mobile,
			                     	 			'mobile2' => $mobile2,
			                     	 			'storeName' => $storeName,
			                     	 			'storeAddress' => $storeAddress
			                     	 	])->execute();
			                     	 	if($insert2)
			                     	 	{
			                     	 		$result = ['status'=>'success','message'=>'user converted into vendor'];
			                     	 		return $result;
			                     	 	}
			                     	 	 
			                     	 }
			                     	  
			                     	
			                     	 
			                     	
			                     	$insert = Yii::$app->db->createCommand()
			                     	->insert('register', [
			                     			'uid' => $id,
			                     			'firstName' => $firstName,
			                     			'lastName' => $lastName,
			                     			'middleName' => $middleName,
			                     			'dob' => $dob,
			                     			'mobile' => $mobile,
			                     			'mobile2' => $mobile2,
			                     			'address1' => $address1,
			                     			'address2' => $address2,
			                     			'city' => $city,
			                     			'state' => $state,
			                     			'country' => $country,
			                     			'zip' => $zip,
			                     			'gender' => $gender
			                     			
			                     	])->execute();
			                     	
			                     	
			                     	
			                     	
			                     	if($insert)
			                     	{
			                     		$result = ['status'=>'success','message'=>'success'];;
			                     		return $result;
			                     	}
			                     
			                     	
			                     
			                     }//END ELSE
			                     
			                   
			                     
		                
			                     
			            }//LOAD MODEL
			  			
			  	
			  }//ACTION
	
}


?>