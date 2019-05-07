<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use backend\models\Website;
use backend\models\Product;
use kartik\select2\Select2;
use backend\models\LongtailKeywords;
use backend\models\MainKeywords;

/* @var $this yii\web\View */
/* @var $model backend\models\Article */
/* @var $form yii\widgets\ActiveForm */
?>
<style>
#select2-w1-container{
    margin-top: 0px;
}
#select2-w2-container{
    margin-top: 0px;
}
</style>

<div class="box-body ">
    <?php $form = ActiveForm::begin([
        'options'=>['class'=>'form-horizontal col-sm-9'],
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
    <?= $form->field($model, 'content')->widget(\yii\redactor\widgets\Redactor::className(), [
        'clientOptions' => [
            'imageManagerJson' => ['/redactor/upload/image-json'],
            'imageUpload' => ['/redactor/upload/image'],
            'fileUpload' => ['/redactor/upload/file'],
            'lang' => 'zh_cn',
            'plugins' => ['clips', 'fontcolor','imagemanager']
        ]
    ])?>
    <div class="box-footer">
        <div class="col-sm-4"></div>
        <div class="col-sm-2 col-xs-2"><a href="javascript:history.back();" class="btn btn-default">取消</a></div>
        <div class="col-sm-2 col-xs-2"><?= Html::submitButton($model->isNewRecord ? '创建' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?></div>
    </div>
    <?php ActiveForm::end(); ?>
    <div class="col-sm-3" style="margin-top:500px;">
        <div style="padding:30px 0px;">
            <?php
            echo Select2::widget([
            'name' => 'kv-type-01',
            'data' => LongtailKeywords::by_site($model->website_id),
            'options' => [
                'placeholder' => '长尾关键词',
            ],
            'pluginOptions' => [
                'allowClear' => true
            ],
            'pluginEvents' => [
                "change" => 'function() { 
                    $.get("/longtail-keywords/getname",{id:$(this).val()},function ($data){
                        $(".p-longtail-keywords").html($data);
                    });
                }',
            ]
            ]);
            ?>
            <p class="redactor-editor p-longtail-keywords"></p>
        </div>
        <div style="padding:30px 0px;">
            <?php
            echo Select2::widget([
                'name' => 'kv-type-02',
                'data' => MainKeywords::by_site($model->website_id),
                'options' => [
                    'placeholder' => '主关键词',
                ],
                'pluginOptions' => [
                    'allowClear' => true
                ],
                'pluginEvents' => [
                    "change" => 'function() { 
                        $.get("/main-keywords/getname",{id:$(this).val()},function ($data){
                            $(".p-main-keywords").html($data);
                        });
                    }',
                ]
            ]);
            ?>
            <p class="redactor-editor p-main-keywords"></p>
        </div>
        <div style="padding:30px 0px;"><?php if($model->website) echo $model->website->name;?></div>
        <div style="padding:30px 0px;"><?php echo $model->keywords;?></div>
    </div>
</div>