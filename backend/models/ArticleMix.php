<?php

namespace backend\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "article_mix".
 *
 * @property int $id
 * @property int $website_id
 * @property int $product_id
 * @property int $word_head_id
 * @property int $longtail_keywords_id
 * @property int $word_end_id
 * @property int $template_id
 * @property int $created_at
 * @property int $updated_at
 */
class ArticleMix extends \yii\db\ActiveRecord
{
    const STATUS_ACTIVE = 0;//未生成
    const STATUS_COMPLETED = 1;//已生成
    const TYPE_ARTICLE = 1;//文章
    const TYPE_STATION = 2;//站内站

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'article_mix';
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
            [['type','website_id', 'product_id'], 'required'],
            [['type','website_id', 'product_id' ,'template_id', 'num' ,'status' , 'created_at', 'updated_at'], 'integer'],
            ['num', 'compare', 'compareValue' => 0, 'operator' => '>','message'=>'数量要大于0'],
            [['num'], 'validateNum', 'skipOnEmpty' => false, 'skipOnError' => false],
            ['longtail_keywords_ids','validateLong','skipOnEmpty' => false, 'skipOnError' => false]
        ];
    }

    public function validateNum($attribute, $params)
    {
        if (!$this->hasErrors()) {
            if($this->type==ArticleMix::TYPE_STATION && $this->num==""){
                $this->addError($attribute, '请输入生成数量。');
            }
        }
    }

    public function validateLong($attribute, $params)
    {
        if (!$this->hasErrors()) {
            if($this->type==ArticleMix::TYPE_ARTICLE && $this->longtail_keywords_ids==""){
                $this->addError($attribute, '请选择长尾关键词。');
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type' => '类型',
            'website_id' => '站点',
            'product_id' => '产品',
            'longtail_keywords_ids' => '长尾关键词',
            'template_id' => '模板',
            'num' => '生成数量',
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

    public function getTemplate()
    {
        return $this->hasOne(Template::className(), ['id' => 'template_id']);
    }

    public function getLongtailKeywords()
    {
        $longtail_keywords_ids=unserialize($this->longtail_keywords_ids);
        if(empty($longtail_keywords_ids)){
            return "";
        }else{
            $longtailKeywords=LongtailKeywords::find()->where(['in','id',$longtail_keywords_ids])->asArray()->all();
            $longtailKeywords_names=array_column($longtailKeywords,'name');
            return implode(',',$longtailKeywords_names);
        }

    }

    public function createArticle($longtail_keywords_id)
    {
        $config=yii\helpers\ArrayHelper::map(Config::find()->where(['status'=>0])->all(),'name','value');
        $article= new Article();
        $article->type=$this->type;
        $article->source_id=$this->id;
        $article->website_id=$this->website_id;
        $article->product_id=$this->product_id;
        $longtail_keywords=LongtailKeywords::findOne($longtail_keywords_id);
        $longtail_keywords_name=$longtail_keywords->name;
        $article->title=$this->getTitle($longtail_keywords_name);
        $article->seo_title=$article->title;
        $article->keywords=$longtail_keywords_name;
        $article->description=$this->getFirstParagraph($config);
        $article->content=$this->getContent($article->description,$config,$article->title,$article->keywords);
        $article->save();

        //将长尾关键词设置为已使用
        $longtail_keywords->status=LongtailKeywords::STATUS_COMPLETED;
        $longtail_keywords->save();

    }

    public function createStation()
    {
        $config=yii\helpers\ArrayHelper::map(Config::find()->where(['status'=>0])->all(),'name','value');
        $article= new Article();
        $article->type=$this->type;
        $article->source_id=$this->id;
        $article->website_id=$this->website_id;
        $article->product_id=$this->product_id;
        $article->title='1';
        $article->seo_title='1';
        $article->keywords='1';
        $article->description=$this->getFirstParagraph2($config);
        $article->content=$this->getContent2($article->description,$config);
        $article->save();
    }

    public function getTitle($longtail_keywords_name)
    {
        $word=$this->getWord();
        return $word['headname'].$longtail_keywords_name.$word['endname'];
    }

    public function getWord()
    {
        $words=Word::find()->where(['product_id'=>$this->product_id,'status'=>0])->asArray()->all();
        if(empty($words)){
            throw new \Exception('请确保有词头词尾组可以匹配！');
        }
        $random_key=array_rand($words,1);
        return $words[$random_key];
    }

    public function getFirstParagraph($config)
    {
        $paragraphs=FirstParagraph::find()->where(['product_id'=>$this->product_id,'status'=>0,'type'=>Article::TYPE_ARTICLE])
        ->andWhere(['<=','times',$config['FirstParagraph_MaxTimes']])->asArray()->all();
        if(empty($paragraphs)){
            throw new \Exception('请确保有首段可以匹配！');
        }
        $random_key=array_rand($paragraphs,1);
        $model=FirstParagraph::findOne($paragraphs[$random_key]['id']);
        $model->times=$model->times+1;
        if($model->times==$config['FirstParagraph_MaxTimes']){
            $model->status=FirstParagraph::STATUS_DISABLE;
        }
        $model->save();
        return $paragraphs[$random_key]['content'];
    }

    public function getFirstParagraph2($config)
    {
        $paragraphs=FirstParagraph::find()->where(['product_id'=>$this->product_id,'status'=>0,'type'=>Article::TYPE_STATION])
        ->andWhere(['<=','times',$config['FirstParagraph_MaxTimes']])->asArray()->all();
        if(empty($paragraphs)){
            throw new \Exception('请确保有首段可以匹配！');
        }
        $random_key=array_rand($paragraphs,1);
        $model=FirstParagraph::findOne($paragraphs[$random_key]['id']);
        $model->times=$model->times+1;
        if($model->times==$config['FirstParagraph_MaxTimes']){
            $model->status=FirstParagraph::STATUS_DISABLE;
        }
        $model->save();
        return $paragraphs[$random_key]['content'];
    }

    public function getEndParagraph($config)
    {
        $paragraphs=EndParagraph::find()->where(['product_id'=>$this->product_id,'status'=>0,'type'=>Article::TYPE_ARTICLE])
        ->andWhere(['<=','times',$config['EndParagraph_MaxTimes']])->asArray()->all();
        if(empty($paragraphs)){
            throw new \Exception('请确保有尾段可以匹配！');
        }
        $random_key=array_rand($paragraphs,1);
        $model=EndParagraph::findOne($paragraphs[$random_key]['id']);
        $model->times=$model->times+1;
        if($model->times==$config['EndParagraph_MaxTimes']){
            $model->status=FirstParagraph::STATUS_DISABLE;
        }
        $model->save();
        return $paragraphs[$random_key]['content'];
    }

    public function getEndParagraph2($config)
    {
        $paragraphs=EndParagraph::find()->where(['product_id'=>$this->product_id,'status'=>0,'type'=>Article::TYPE_STATION])
        ->andWhere(['<=','times',$config['EndParagraph_MaxTimes']])->asArray()->all();
        if(empty($paragraphs)){
            throw new \Exception('请确保有尾段可以匹配！');
        }
        $random_key=array_rand($paragraphs,1);
        $model=EndParagraph::findOne($paragraphs[$random_key]['id']);
        $model->times=$model->times+1;
        if($model->times==$config['EndParagraph_MaxTimes']){
            $model->status=FirstParagraph::STATUS_DISABLE;
        }
        $model->save();
        return $paragraphs[$random_key]['content'];
    }

    public function getContent($description,$config,$title,$keywords)
    {
        return $description."<br>".$this->getParagraphImages($config,$title,$keywords).$this->getEndParagraph($config);
    }

    public function getContent2($description,$config)
    {
        return $description."<br>".$this->getParagraph2($config).$this->getEndParagraph2($config);
    }

    public function getParagraphImages($config,$title,$keywords)
    {
        $image_power=$this->website->is_image_public;
        $paragraphImages="";
        if($this->template_id){
            $content=Template::findOne($this->template_id)->content;
        }else{
            $templates=Template::find()->where(['status'=>0])->asArray()->all();
            if(empty($templates)){
                throw new \Exception('请确保有模板可以匹配！');
            }
            $random_key=array_rand($templates,1);
            $content=$templates[$random_key]['content']; 
        }
        $content=substr($content,1);
        $content=substr($content,0,-1);
        $result=explode("}{",$content);
        $count=array_count_values($result);

        if(isset($count['段落']) && $count['段落']){
            $paragraphs=Paragraph::find()->where(['product_id'=>$this->product_id,'status'=>0,'type'=>Article::TYPE_ARTICLE])
            ->andWhere(['<=','times',$config['Paragraph_MaxTimes']])->asArray()->all();
            if(count($paragraphs)<$count['段落']){
                throw new \Exception('请确保有足够数量的段落可以匹配！');
            }
            $prandom_keys=array_rand($paragraphs,$count['段落']);
        }
        if(isset($count['图片']) && $count['图片']){
            if($image_power==Website::PUBLIC_IMAGE){
                $images=PublicImage::find()->where(['website_id'=>null,'product_id'=>$this->product_id,'status'=>0])
                ->andWhere(['<=','times',$config['Image_MaxTimes']])->asArray()->all();
            }elseif($image_power==Website::PRIVATE_IMAGE){
                $images=PrivateImage::find()->where(['website_id'=>$this->website_id,'product_id'=>$this->product_id,'status'=>0])
                ->andWhere(['<=','times',$config['Image_MaxTimes']])->asArray()->all();
            }

            if(count($images)<$count['图片']){
                throw new \Exception('请确保有足够数量的图片可以匹配！');
            }
            $irandom_keys=array_rand($images,$count['图片']);
        }

        $i=0;$j=0;
        foreach ($result as $key => $value) {
            switch ($value) {
                case '段落':
                if(is_array($prandom_keys)){
                    $paragraphImages.=$paragraphs[$prandom_keys[$i]]['content']."<br>";
                    $model=Paragraph::findOne($paragraphs[$prandom_keys[$i]]['id']);
                    $model->times=$model->times+1;
                    if($model->times==$config['Paragraph_MaxTimes']){
                        $model->status=Paragraph::STATUS_DISABLE;
                    }
                    $model->save();
                    $i++;
                }else{
                    $paragraphImages.=$paragraphs[$prandom_keys]['content']."<br>";
                    $model=Paragraph::findOne($paragraphs[$prandom_keys]['id']);
                    $model->times=$model->times+1;
                    if($model->times==$config['Paragraph_MaxTimes']){
                        $model->status=Paragraph::STATUS_DISABLE;
                    }
                    $model->save();
                }
                break;
                case '图片':
                if(is_array($irandom_keys)){
                    if($j==0){
                        $paragraphImages.="<p style='text-align:center;'><img src='".Yii::$app->params['domain'].$images[$irandom_keys[$j]]['url']."' alt='".$title."' title='".$title."'></p>";
                    }elseif($j==1){
                        $paragraphImages.="<p style='text-align:center;'><img src='".Yii::$app->params['domain'].$images[$irandom_keys[$j]]['url']."' alt='".$keywords."' title='".$keywords."'></p>";                        
                    }else{
                        $paragraphImages.="<p style='text-align:center;'><img src='".Yii::$app->params['domain'].$images[$irandom_keys[$j]]['url']."' alt='' title=''></p>";                          
                    }
                    if($image_power==Website::PUBLIC_IMAGE){
                        $model=PublicImage::findOne($images[$irandom_keys[$j]]['id']);
                        $model->times=$model->times+1;
                        if($model->times==$config['Image_MaxTimes']){
                            $model->status=PublicImage::STATUS_DISABLE;
                        }
                    }elseif($image_power==Website::PRIVATE_IMAGE){
                        $model=PrivateImage::findOne($images[$irandom_keys[$j]]['id']);
                        $model->times=$model->times+1;
                        if($model->times==$config['Image_MaxTimes']){
                            $model->status=PrivateImage::STATUS_DISABLE;
                        }
                    }

                    $model->save();
                    $j++;
                }else{
                    $paragraphImages.="<p style='text-align:center;'><img src='".Yii::$app->params['domain'].$images[$irandom_keys]['url']."' alt='".$title."' title='".$title."'></p>";
                    if($image_power==Website::PUBLIC_IMAGE){
                        $model=PublicImage::findOne($images[$irandom_keys]['id']);
                        $model->times=$model->times+1;
                        if($model->times==$config['Image_MaxTimes']){
                            $model->status=PublicImage::STATUS_DISABLE;
                        }
                    }elseif($image_power==Website::PRIVATE_IMAGE){
                        $model=PrivateImage::findOne($images[$irandom_keys]['id']);
                        $model->times=$model->times+1;
                        if($model->times==$config['Image_MaxTimes']){
                            $model->status=PrivateImage::STATUS_DISABLE;
                        }
                    }

                    $model->save();
                }
                break;
                default:
                    # code...
                    break;
            }
        }
        return $paragraphImages;
    }

    public function getParagraph2($config)
    {
        $paragraph_string="";
        $paragraphs=Paragraph::find()->where(['product_id'=>$this->product_id,'status'=>0,'type'=>Article::TYPE_STATION])
        ->andWhere(['<=','times',$config['Paragraph_MaxTimes']])->asArray()->all();
        if(count($paragraphs)<3){
            throw new \Exception('请确保有足够数量的段落可以匹配！');
        }
        $prandom_keys=array_rand($paragraphs,3);
        for($i=0;$i<3;$i++){
            $paragraph_string.=$paragraphs[$prandom_keys[$i]]['content']."<br>";
            $model=Paragraph::findOne($paragraphs[$prandom_keys[$i]]['id']);
            $model->times=$model->times+1;
            if($model->times==$config['Paragraph_MaxTimes']){
                $model->status=Paragraph::STATUS_DISABLE;
            }
            $model->save();
        }
        return $paragraph_string;
    }
}
