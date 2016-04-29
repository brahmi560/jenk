<?php

namespace rest\models;

use Yii;

/**
 * This is the model class for table "role".
 *
 * @property integer $RoleId
 * @property string $RoleName
 * @property string $status
 * @property string $description
 * @property string $createdDate
 * @property string $updatedDate
 * @property string $ipAddress
 */
class Role extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'role';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['RoleName', 'status', 'description', 'createdDate', 'updatedDate', 'ipAddress'], 'required'],
            [['status', 'description'], 'string'],
            [['createdDate', 'updatedDate'], 'safe'],
            [['RoleName', 'ipAddress'], 'string', 'max' => 200]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'RoleId' => 'Role ID',
            'RoleName' => 'Role Name',
            'status' => 'Status',
            'description' => 'Description',
            'createdDate' => 'Created Date',
            'updatedDate' => 'Updated Date',
            'ipAddress' => 'Ip Address',
        ];
    }
}
