<?php
namespace rest\versions\v1\controllers;

use yii;
use common\models\User;

use rest\models\RegisterMaster;
use rest\models\UserMaster;
use rest\models\VendorRegister;
use rest\models\Countries;
use rest\models\States;
use common\models\LoginForm;
use rest\models\SignupForm;
use rest\models\ResetPasswordForm;
use yii\rest\Controller;
use yii\helpers\ArrayHelper;
use yii\web\UploadedFile;

/**
 * Class UserController
 * @package rest\versions\v1\controllers
 */
	class VendorController extends Controller
	{
		
		/**
		 * ************* profileview ****************
		 * ***/
		public function actionProfileview()
		{
			 
				$model = new VendorRegister();
				if ($model->load(\Yii::$app->getRequest()->getBodyParams(), ''))
				{
					$id = $model->uid;
					$roleId = $model->roleId;
					 
				 if($roleId == '2'){
						return  $this->view($id,$roleId);
					}
			
					return array('status' => 'fail',"message" => "unauthorised user");
				}else{
					return $model;
				}
		
		}//action profile
		 
		public function view($id,$roleId)
		{
				$userMain = UserMaster::find()->select(['id','username','email'])->where(['id' => $id,'roleId' => $roleId])->one();
				$userMain = ArrayHelper::toArray($userMain);
				
				$userData = VendorRegister::find()->where(['uid'=> $id])->one();
				$userData = ArrayHelper::toArray($userData);
				
				 /**
				  * ************* country name ************
				  * ***/
				 $country =  @$userData['country'];
				 $countries = new Countries();
				 $countryData  = $countries->getCountryName($country);
				 
				 /**
				  * ************* state name *************
				  * ***/
				 $state =  @$userData['state'];
				 $states = new States();
				 $stateData  = $states->getStateName($state);
				 
				  
				 
			
				foreach ($userData as $key => $val){
					$temp[$key] = ($val == '') ? "" : $val;
					 
				}
			
				foreach ($userMain as $key => $val){
					$temp[$key] = ($val == '') ? "" : $val;
			
				}
				 
				if(!(@$userMain['id'] || @$userMain['roleId'] )){
					return array('status' => 'fail',"message" => "unauthorised user");
			
				}
				
				if($temp)
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
		 * ******************* Profileupdate *********************
		 * ***/
		public function actionProfileupdate()
		{
			$model = new VendorRegister();
			
			if($model->load(\Yii::$app->getRequest()->getBodyParams(), ''))
			{
				
				$id = $model->uid;
				$roleId = $model->roleId;
				
				$userData = VendorRegister::find()->where(['uid'=> $id])->one();
				$model->countriesList = Countries::getCountriesUS();
				
				//print_r($model->countriesList);exit;
			
				$states = Countries::getStatesByCountry($userData['country']);
			    $statesAry = array();
				foreach ($states as $state)
				{
					$statesAry[$state['id']] = $state['name'];
				}
				
				//print_r($statesAry);exit;
				$model->statesData = $statesAry;
				//print_r($model->statesData);exit;
				$userMain = UserMaster::find()->select(['id','email'])->where(['id' => $id])->one();
				if($roleId != 2)
				{
					$result = ['status'=>'fail','message' => 'unauthorised user'];
					return $result;
				}
				if(!$userMain){
					$result = ['status'=>'fail','message' => 'unauthorised user'];
					return $result;
				}
				
				     
			//$model->storeImage = $this->Uploadfile();
			//$model->storeImage = $this->Uploadfile2();
				
			switch ($model) 
			{
						case $model->firstName == '' :
							$result = ['status'=>'fail','message' => 'please enter the FirstName'];
							return $result;
							break;
						case $model->lastName == '' :
							$result = ['status'=>'fail','message' => 'please enter the LastName'];
							return $result;
							break;
						case $model->mobile == '' :
							$result = ['status'=>'fail','message' => 'please enter the Mobile'];
							return $result;
							break;
						case $model->storeName == '' :
							$result = ['status'=>'fail','message' => 'please enter the storeName'];
							return $result;
							break;
						case $model->storeAddress == '' :
							$result = ['status'=>'fail','message' => 'please enter the storeAddress'];
							return $result;
							break;
						case $model->city == '' :
							$result = ['status'=>'fail','message' => 'please enter  the city'];
							return $result;
							break;
						case $model->state == '' :
							$result = ['status'=>'fail','message' => 'please select  the state'];
							return $result;
							break;
						case $model->country == '' :
							$result = ['status'=>'fail','message' => 'please select  the country'];
							return $result;
							break;
					 
					   default: 
							$userExist = VendorRegister::find()->where(['uid'=> $id])->one();
							if($userExist != NULL)
							{
								$userExist->firstName = $model->firstName;
								$userExist->lastName = $model->lastName;
								$userExist->middleName = $model->middleName;
							    $userExist->mobile = $model->mobile;
								$userExist->mobile2 = $model->mobile2;
								$userExist->storeAddress = $model->storeAddress;
								$userExist->address2 = $model->address2;
								$userExist->city = $model->city;
						    	$userExist->state = $model->state;
						    	$userExist->country = $model->country;
						    	$userExist->zip = $model->zip;
						    	$userExist->storeName = $model->storeName;
						    	$userExist->businessName2 = $model->businessName2;
						    	$userExist->storeImage    = $model->storeImage;
						        $userExist->fax = $model->fax;
						    	
						    	$updated = $userExist->update();
							    $result = ['status'=>'success','message' => 'success'];
							    return $result;
									 
								
							
						   }//end if
			   }//switch case
				
			}//model
			
		}//profileupdate
		
		
		/**
		 * ******************* countries  *********************
		 * ***/
		public function actionCountries()
		{
			
			$result['countries'] = Countries::getCountriesUSByservice();
			return $result;
		}
		
		/**
		 * ******************* states  *********************
		 * ***/
		public function actionStates()
		{ 
			$cId = $_GET['cId'];
			$result['states'] = Countries::getStatesByCountryService($cId);
			return $result;
		}
		
		
		public function Uploadfile()
		{
			if(!empty($_FILES['fileUpload']['tmp_name']))
		      {
					 	$params = Yii::$app->request->post();
					 	
					 
					 	$target_path = yii::$app->basePath . "/web/uploads/storeimages/" . $_FILES['fileUpload']['name'];
					 	$ext = pathinfo($target_path, PATHINFO_EXTENSION);
					 	$ext=($ext)?$ext:'.jpg';
					 	$img_name = time() . "." . $ext;
					 	
					 	$path = yii::$app->basePath . "/web/uploads/storeimages/" . $img_name;
					 	$syntax = move_uploaded_file($_FILES['fileUpload']['tmp_name'], $path);
					 	if($syntax)
					 	{
					 		$params['files'] = $img_name;
					 		
					 	    return $params['files'];
					 	
					 		
					 		
					 	}else{
					 		echo "not upload";
					 	}
					 	
					 	
					 }
			}
			
			public function Uploadfile2()
			{
				$model->storeImage = UploadedFile::getInstance($model,'storeImage');
				
				if($model->storeImage != '')
				{
					$imageName = rand(1000,100000).$model->storeImage->baseName;
					 
					$model->storeImage->saveAs(Yii::getAlias('@frontend').'/web/uploads/storeimages/'.$imageName.'.'.$model->storeImage->extension );
					 
					$model->storeImage = 'uploads/storeimages/'.$imageName.'.'.$model->storeImage->extension;
					$userExist->storeImage = $model->storeImage;
					$model->storeImage;
					 
				}
			}
		
		
		
		
	}//class