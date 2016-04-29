<?php

namespace rest\modules\user\controllers;

use yii\web\Controller;
use common\models\LoginForm;

class DefaultController extends Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }
    public function actionLogin()
    {
    	$model = new LoginForm();
    
    	if ($model->load(\Yii::$app->getRequest()->getBodyParams(), '') && $model->login()) {
    		return \Yii::$app->user->identity->getAuthKey();
    	} else {
    		return $model;
    	}
    }
}
