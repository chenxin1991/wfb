<?php

namespace backend\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "first_paragraph".
 *
 * @property int $id
 * @property int $product_id
 * @property string $content
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 */
class FirstParagraph extends \yii\db\ActiveRecord
{
    const STATUS_ACTIVE = 0;//启用
    const STATUS_DISABLE = -1;//禁用
    const TYPE_ARTICLE = 1;//文章
    const TYPE_STATION = 2;//站内站

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'first_paragraph';
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
            [['type','product_id', 'content'], 'required'],
            [['type','product_id', 'times' , 'status', 'created_at', 'updated_at'], 'integer'],
            [['content'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type' => '类型',
            'product_id' => '产品',
            'content' => '首段',
            'times' => '引用次数',
            'status' => '状态',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
        ];
    }

    public function getProduct()
    {
        return $this->hasOne(Product::className(), ['id' => 'product_id']);
    }
}
