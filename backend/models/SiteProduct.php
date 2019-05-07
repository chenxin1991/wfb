<?php

namespace backend\models;

use Yii;
use yii\behaviors\TimestampBehavior;


/**
 * This is the model class for table "site_product".
 *
 * @property int $id
 * @property int $website_id
 * @property int $product_id
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 */
class SiteProduct extends \yii\db\ActiveRecord
{
    const STATUS_ACTIVE = 0;//启用
    const STATUS_DISABLE = -1;//禁用
    
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'site_product';
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
            [['website_id', 'product_id'], 'required'],
            [['website_id', 'product_id', 'status', 'created_at', 'updated_at'], 'integer'],
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

    public function getIndustry()
    {
        return $this->hasOne(Industry::className(), ['id' => 'industry_id'])
        ->via('website');
    }
}
