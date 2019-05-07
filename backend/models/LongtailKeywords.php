<?php

namespace backend\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "longtail_keywords".
 *
 * @property int $id
 * @property int $website_id
 * @property int $product_id
 * @property string $name
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 */
class LongtailKeywords extends \yii\db\ActiveRecord
{
    const STATUS_ACTIVE = 0;//启用
    const STATUS_DISABLE = -1;//禁用
    const STATUS_COMPLETED = 1;//已使用

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'longtail_keywords';
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
            [['website_id', 'product_id', 'name'], 'required'],
            [['website_id', 'product_id', 'status', 'created_at', 'updated_at'], 'integer'],
            [['name'], 'string', 'max' => 50],
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
            'name' => '长尾关键词',
            'status' => '状态',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
        ];
    }

    public function getWebsite()
    {
        return $this->hasOne(Website::className(), ['id' => 'website_id']);
    }

    public function getProduct()
    {
        return $this->hasOne(Product::className(), ['id' => 'product_id']);
    }

    public static function by_site_product($website_id,$product_id)
    {
        if($website_id && $product_id){
            return yii\helpers\ArrayHelper::map(self::find()->where(['status'=>0,'website_id'=>$website_id,'product_id'=>$product_id])->all(),'id','name');
        }
        return [];
    }

    public static function by_site_product2($website_id,$product_id)
    {
        if($website_id && $product_id){
            return yii\helpers\ArrayHelper::map(self::find()->where(['website_id'=>$website_id,'product_id'=>$product_id])->all(),'id','name');
        }
        return [];
    }

    public static function by_site($website_id)
    {
        if($website_id){
            return yii\helpers\ArrayHelper::map(self::find()->where(['website_id'=>$website_id])->all(),'id','name');
        }
        return [];
    }
}
