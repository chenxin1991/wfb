<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use backend\models\Website;
use backend\models\Product;
use kartik\select2\Select2;
use backend\models\LongtailKeywords;
use backend\models\MainKeywords;
use dosamigos\tinymce\TinyMce;

/* @var $this yii\web\View */
/* @var $model backend\models\Article */
/* @var $form yii\widgets\ActiveForm */
?>

<?php
$url=Yii::$app->assetManager->getPublishedUrl('@vendor/2amigos/yii2-tinymce-widget/src/assets');
$domain=Yii::$app->params['domain'];
$setup="";
if (!$model->isNewRecord){
    $setup="setup:function (editor) {
        editor.ui.registry.addButton('keywordsButton', {
          text: '本篇关键词',
          tooltip: '插入关键词',
          onAction: function (_) {
            editor.insertContent('<strong>{$model->keywords}</strong>');
          }
        });
        editor.ui.registry.addButton('websiteButton', {
            text: '站点名',
            tooltip: '插入站点名',
            onAction: function (_) {
              editor.insertContent('{$model->website->name}');
            }
        });
        editor.ui.registry.addSplitButton('mainKeywordsButton', {
            text: '主关键词',
            onAction: function (_) {},  
            onItemAction: function (buttonApi, value) {
              editor.insertContent(value);
            },
            fetch: function (callback) {
                $.get('/station/main-keywords',{website_id:{$model->website_id}},function (data){
                    callback(data);
                });
            }
        });    
        editor.ui.registry.addSplitButton('longtailKeywordsButton', {
            text: '长尾关键词',
            onAction: function (_) {},  
            onItemAction: function (buttonApi, value) {
              editor.insertContent(value);
            },
            fetch: function (callback) {
                $.get('/station/longtail-keywords',{website_id:{$model->website_id}},function (data){
                    callback(data);
                });
            }
        });                 
    }";
}
?>

<div class="box-body">
    <?php $form = ActiveForm::begin([
        'options'=>['class'=>'form-horizontal'],
        'fieldConfig'=>[
            'template'=>"{label}\n<div class=\"col-sm-11\">{input}\n{hint}\n{error}</div>",
            'labelOptions'=>['class'=>'col-sm-1 control-label'],
        ],
    ]); ?>
    <?=$form->field($model, 'website_id')->dropDownList(Website::dropdownlist(), 
    ['prompt' => '', 'data-placeholder' => '选择站点', 'class' => 'form-control select2', 'style' => 'width:100%;',
    'onchange'=>'$.get("/product/bysite",{site_id:$(this).val()},function ($data) 
    {$("#article-product_id").html($data);})'])?>
    <?=$form->field($model, 'product_id')->dropDownList(Product::bysite($model->website_id), 
    ['prompt' => '', 'data-placeholder' => '选择产品', 'class' => 'form-control select2', 'style' => 'width:100%;'])?>
    <?= $form->field($model, 'title')->textInput() ?>
    <?= $form->field($model, 'seo_title')->textInput() ?>
    <?= $form->field($model, 'keywords')->textInput() ?>
    <?= $form->field($model, 'description')->textarea(['rows' => 4]) ?>
    <?= $form->field($model, 'content')->widget(TinyMce::className(), [
        'language' => 'zh_CN',
        'clientOptions' => "{
            selector: '#article-content',
            height: 550,
            language_url:'{$url}',
            language:'zh_CN',
            relative_urls:false,
            remove_script_host:false,
            document_base_url:'{$domain}',
            plugins:[
                'advlist autolink lists link charmap print preview anchor',
                'searchreplace visualblocks code fullscreen',
                'insertdatetime media table contextmenu paste wordcount'
            ],
            toolbar:'undo redo | styleselect fontsizeselect| bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image fullscreen | keywordsButton websiteButton mainKeywordsButton longtailKeywordsButton',
            $setup
        }"
    ]);?>
    <div class="box-footer">
        <div class="col-sm-4"></div>
        <div class="col-sm-2 col-xs-2"><a href="javascript:history.back();" class="btn btn-default">取消</a></div>
        <div class="col-sm-2 col-xs-2"><?= Html::submitButton($model->isNewRecord ? '创建' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?></div>
    </div>
    <?php ActiveForm::end(); ?>
</div>

