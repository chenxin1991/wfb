<?php

namespace backend\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "site_type".
 *
 * @property int $id
 * @property string $name
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 */
class Industry extends \yii\db\ActiveRecord
{
    const STATUS_ACTIVE = 0;//启用
    const STATUS_DISABLE = -1;//禁用

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'industry';
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'status'], 'required'],
            [['status', 'created_at', 'updated_at'], 'integer'],
            [['name'], 'string', 'max' => 30],
            [['name'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '行业',
            'status' => '状态',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
        ];
    }

    public static function dropdownlist()
    {
        return yii\helpers\ArrayHelper::map(self::find()->where(['status'=>0])->all(),'id','name');
    }
}
