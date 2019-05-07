<?php

namespace backend\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "article_categories".
 *
 * @property int $id
 * @property string $name
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 */
class Product extends \yii\db\ActiveRecord
{
    const STATUS_ACTIVE = 0;//启用
    const STATUS_DISABLE = -1;//禁用
    
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'product';
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
            [['name','industry_id'], 'required'],
            [['industry_id','status', 'created_at', 'updated_at'], 'integer'],
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
            'name' => '产品',
            'industry_id' => '行业',
            'status' => '状态',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
        ];
    }

    public function getIndustry()
    {
        return $this->hasOne(Industry::className(), ['id' => 'industry_id']);
    }

    public static function dropdownlist($industry_id)
    {
        return yii\helpers\ArrayHelper::map(self::find()->where(['status'=>0,'industry_id'=>$industry_id])->all(),'id','name');
    }

    public static function all()
    {
        return yii\helpers\ArrayHelper::map(self::find()->where(['status'=>0])->all(),'id','name');
    }

    public static function bysite($site_id)
    {
        if($site_id){
            $product_ids=unserialize(Website::findone($site_id)->product_ids);
            $products=Product::find()->where(['in','id',$product_ids])->asArray()->all();
            return array_column($products,'name','id');
        }
        return [];
    }
}
