<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use backend\models\Website;
use backend\models\Product;
use backend\models\Template;
use backend\models\Wordhead;
use backend\models\LongtailKeywords;
use backend\models\Wordend;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model backend\models\ArticleMix */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="box-body">
    <?php $form = ActiveForm::begin([
        'options'=>['class'=>'form-horizontal'],
        'fieldConfig'=>[
            'template'=>"{label}\n<div class=\"col-sm-10\">{input}\n{hint}\n{error}</div>",
            'labelOptions'=>['class'=>'col-sm-2 control-label'],
        ],
    ]); ?>
    <?=$form->field($model, 'website_id')->dropDownList(Website::dropdownlist(), 
    ['prompt' => '', 'data-placeholder' => '选择站点', 'class' => 'form-control select2', 'style' => 'width:100%;',
    'onchange'=>'$.get("/product/bysite",{site_id:$(this).val()},function ($data) 
    {$("#articlemix-product_id").html($data);})'])?>
    <?=$form->field($model, 'product_id')->dropDownList(Product::bysite($model->website_id), 
    ['prompt' => '', 'data-placeholder' => '选择产品', 'class' => 'form-control select2', 'style' => 'width:100%;'])?>
    <?= $form->field($model, 'longtail_keywords_ids')->widget(Select2::classname(),['data' => LongtailKeywords::by_site_product($model->website_id,$model->product_id), 
    'options' => ['multiple' => true,'placeholder' => '请选择 ...'],'pluginOptions' => ['allowClear'=>true,'hideSearch'=>true],]);?>
    <?=$form->field($model, 'template_id')->dropDownList(Template::all(), 
    ['prompt' => '', 'data-placeholder' => '选择模板', 'class' => 'form-control select2', 'style' => 'width:100%;'])?>
    <div class="box-footer">
        <a href="javascript:history.back();" class="btn btn-default">取消</a>
        <?= Html::submitButton($model->isNewRecord ? '创建' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success pull-right' : 'btn btn-primary pull-right']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
