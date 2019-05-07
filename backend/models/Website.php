<?php

namespace backend\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "website".
 *
 * @property int $id
 * @property string $name
 * @property int $platform_id
 * @property int $site_id
 * @property string $api_key
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 */
class Website extends \yii\db\ActiveRecord
{
    const STATUS_ACTIVE = 0;//启用
    const STATUS_DISABLE = -1;//禁用
    const PUBLIC_IMAGE = 1;//公有图片
    const PRIVATE_IMAGE = 2;//私有图片

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'website';
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
            [['name', 'platform_id', 'industry_id', 'site_id', 'api_key','is_image_public'], 'required'],
            [['platform_id', 'industry_id' , 'site_id', 'is_image_public', 'status', 'created_at', 'updated_at'], 'integer'],
            [['name'], 'string', 'max' => 30],
            [['api_key'], 'string', 'max' => 255],
            [['name'], 'unique'],
            [['site_id'], 'unique'],
            [['api_key'], 'unique'],                                    
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_DISABLE]],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '站点',
            'platform_id' => '平台',
            'industry_id' => '分类',
            'product_ids' => '产品',
            'site_id' => '站点id',
            'api_key' => '密钥',
            'is_image_public' => '图片是否公有',
            'status' => '状态',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
        ];
    }

    public function getPlatform()
    {
        return $this->hasOne(Platform::className(), ['id' => 'platform_id']);
    }

    public function getIndustry()
    {
        return $this->hasOne(Industry::className(), ['id' => 'industry_id']);
    }

    public function getProducts()
    {
        $product_ids=unserialize($this->product_ids);
        if(empty($product_ids)){
            return "";
        }else{
            $products=Product::find()->where(['in','id',$product_ids])->asArray()->all();
            $product_names=array_column($products,'name');
            return implode(',',$product_names);
        }

    }

    public static function dropdownlist()
    {
        return yii\helpers\ArrayHelper::map(self::find()->where(['status'=>0])->all(),'id','name');
    }
}
