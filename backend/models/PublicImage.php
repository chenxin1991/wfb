<?php

namespace backend\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "image".
 *
 * @property int $id
 * @property int $website_id
 * @property int $product_id
 * @property string $url
 * @property int $status
 * @property int $created_at
 */
class PublicImage extends \yii\db\ActiveRecord
{
    public $urls;
    
    const STATUS_ACTIVE = 0;//启用
    const STATUS_DISABLE = -1;//禁用

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'image';
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
            [['website_id','product_id', 'times' , 'status', 'created_at', 'updated_at'], 'integer'],
            [['product_id'], 'required'],
            [['url'],'safe']

        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'website_id' => '站点',
            'product_id' => '产品',
            'url' => '公有图片',
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
