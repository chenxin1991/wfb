<?php

namespace backend\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "config".
 *
 * @property int $id
 * @property string $name
 * @property string $value
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 */
class Config extends \yii\db\ActiveRecord
{
    const STATUS_ACTIVE = 0;//启用
    const STATUS_DISABLE = -1;//禁用

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'config';
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
            [['introduce' , 'name', 'value'], 'required'],
            [['status', 'created_at', 'updated_at'], 'integer'],
            [['name'], 'string', 'max' => 50],
            [['value'], 'string', 'max' => 255],
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
            'introduce' => '配置名称',
            'name' => '名称',
            'value' => '值',
            'status' => '状态',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
        ];
    }
}
