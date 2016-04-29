<?php
namespace rest\versions\v1\controllers;


use rest\models\RegisterMaster;
use common\models\User;
use yii\helpers\ArrayHelper;
use yii\rest\Controller;


class ProfileController extends Controller
{
	
	
	 public function view($id,$roleId)
	 {
	 						$userMain = User::find()->select(['id','username','email'])->where(['id' => $id,'roleId' => $roleId])->one();
	                    	$userMain = ArrayHelper::toArray($userMain);
	                    	

	                    	$userData = RegisterMaster::find()->where(['uid'=> $id])->one();
	                    	$userData = ArrayHelper::toArray($userData);
	                    	$temp = array();
	                    	foreach ($userData as $key => $val){
	                    		$temp[$key] = ($val == '') ? "" : $val;
	                    		//echo 
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
			                    	   return $result;
			                    	}	else {
				    		
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
				    						}
				   
	 	
	 	
	 }
	public function actionProfile()
    {
    	
    	  
            $model = new RegisterMaster();
            if ($model->load(\Yii::$app->getRequest()->getBodyParams(), '')) 
       		{
                    $id = $model->uid;
                    $roleId = $model->roleId;
                    
                 
                   
          
                    if($roleId == '3')
				    {
				    	return  $this->view($id,$roleId);
				    	
				    }                 
                   else if($roleId == '2')
					{
						return  $this->view($id,$roleId);
					                    		
			 		}
                    
				 return array('status' => 'fail',"message" => "unauthorised user");
       		
       	}else
    	{
    		return $model;
    	}


               





					
    }//ACTION
     	 	
}

?>