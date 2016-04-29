<?php

namespace rest\models;

use Yii;

/**
 * This is the model class for table "states".
 *
 * @property integer $id
 * @property string $name
 * @property integer $country_id
 *
 * @property Countries $country
 */
class States extends \yii\db\ActiveRecord
{
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'states';
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
				[['name'], 'required'],
				[['country_id'], 'integer'],
				[['name'], 'string', 'max' => 30]
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
				'id' => 'ID',
				'name' => 'Name',
				'country_id' => 'Country ID',
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getCountry()
	{
		return $this->hasOne(Countries::className(), ['id' => 'country_id']);
	}
	
	//** ###### StateName #####*/
 	public static function getStateName($stateId)
    {
    	 $statesModel = States::find()->select(['id','name'])->asArray()->where(['id'=>$stateId])
    	->one();
    	 return $statesModel;
    	//return $statesModel['name'];
    }
    
   	//** ###### Statecode #####*/
    public static function getStateCode($stateId)
    {
    	$statesModel = States::find()->select(['code'])->asArray()->where(['id'=>$stateId])
    	->one();
    	return $statesModel['code'];
    }
    
}
