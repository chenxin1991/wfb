<?php

namespace backend\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use linslin\yii2\curl;

/**
 * This is the model class for table "article".
 *
 * @property int $id
 * @property int $word_head_id
 * @property int $longtail_keywords_id
 * @property int $word_end_id
 * @property string $title
 * @property string $seo_title
 * @property string $keywords
 * @property string $description
 * @property string $content
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 */
class Article extends \yii\db\ActiveRecord
{
    const STATUS_ACTIVE = 0;//未发布
    const STATUS_PUBLISHED = 1;//已发布
    const STATUS_MODIFIED=2;//已修改
    const TYPE_ARTICLE = 1;//文章
    const TYPE_STATION = 2;//站内站

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'article';
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
            [['type','website_id','product_id', 'title', 'seo_title', 'keywords', 'description', 'content'], 'required'],
            [['type','source_id','website_id','product_id', 'status', 'created_at', 'updated_at'], 'integer'],
            [['content'], 'string'],
            [['title', 'seo_title'], 'string', 'max' => 100],
            [['keywords'], 'string', 'max' => 50],
            [['description'], 'string', 'max' => 255],
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
            'source_id' => '组合id',
            'website_id' => '站点',
            'product_id' => '产品',
            'title' => '标题',
            'seo_title' => 'SEO标题',
            'keywords' => '关键词',
            'description' => '描述',
            'content' => '内容',
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

    public function publish($type)
    {
        $curl = new curl\Curl();
        $website=Website::findOne($this->website_id);
        $params=[
            'api_key'=>$website->api_key,
            'site_id'=>$website->site_id,
            'title' => $this->title,
            'seo_title' => $this->seo_title,
            'keywords' => $this->keywords,
            'description' => $this->description,
            'content' => $this->content,
            'release' => 0,
            'type' => $type
        ];
        $url="http://".$website->site_id.".seohost.cn/api/";
        $response = $curl->setOption(CURLOPT_POSTFIELDS,http_build_query($params))->post($url);
        return $response;
    }
}
