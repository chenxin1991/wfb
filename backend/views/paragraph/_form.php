<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use backend\models\Product;
use dosamigos\tinymce\TinyMce;

/* @var $this yii\web\View */
/* @var $model backend\models\Paragraph */
/* @var $form yii\widgets\ActiveForm */
?>

<?php
$url=Yii::$app->assetManager->getPublishedUrl('@vendor/2amigos/yii2-tinymce-widget/src/assets');
$domain=Yii::$app->params['domain'];
$setup="";
?>

<div class="box-body">
    <?php $form = ActiveForm::begin([
        'options'=>['class'=>'form-horizontal'],
        'fieldConfig'=>[
            'template'=>"{label}\n<div class=\"col-sm-10\">{input}\n{hint}\n{error}</div>",
            'labelOptions'=>['class'=>'col-sm-2 control-label'],
        ],
    ]); ?>
    <?= $form->field($model, 'type')->dropdownList([$model::TYPE_ARTICLE=>'文章', $model::TYPE_STATION=>'站内站'], 
    ['prompt'=>'', 'data-placeholder'=>'类型', 'class'=>'form-control select2', 'style'=>'width:100%']) ?>
    <?=$form->field($model, 'product_id')->dropDownList(Product::all(), 
    ['prompt' => '', 'data-placeholder' => '选择产品', 'class' => 'form-control select2', 'style' => 'width:100%;'])?>
    <?= $form->field($model, 'content')->widget(TinyMce::className(), [
        'language' => 'zh_CN',
        'clientOptions' => "{
            selector: '#paragraph-content',
            height: 300,
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
        }"
    ]);?>
    <div class="box-footer">
        <div class="col-sm-4"></div>
        <div class="col-sm-2 col-xs-2"><a href="javascript:history.back();" class="btn btn-default">取消</a></div>
        <div class="col-sm-2 col-xs-2"><?= Html::submitButton($model->isNewRecord ? '创建' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?></div>
    </div>
    <?php ActiveForm::end(); ?>
</div>
