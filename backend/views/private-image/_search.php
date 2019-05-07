<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use backend\models\Website;
use backend\models\Product;

/* @var $this yii\web\View */
/* @var $model backend\models\ImageSearch */
/* @var $form yii\widgets\ActiveForm */
?>


<div class="row">
    <div class="col-xs-12">
        <?php $form = ActiveForm::begin([
            'action' => ['index'],
            'method' => 'get',
            'options'=>['class'=>'form-inline'],
            'fieldConfig'=>[
                'template'=>"{label}\n{input}\n",
                'labelOptions'=>['class'=>'sr-only'],
            ],
        ]); ?>
        <?= $form->field($model, 'website_id')->dropDownList(Website::dropdownlist(),
        ['prompt'=>'','data-placeholder'=>'站点','class'=>'form-control select2','style'=>'width:200px;',
        'onchange'=>'$.get("/product/bysite",{site_id:$(this).val()},function ($data) 
        {$("#privateimagesearch-product_id").html($data);})']) ?>
        <?= $form->field($model, 'product_id')->dropDownList(Product::bysite($model->website_id),
        ['prompt'=>'','data-placeholder'=>'产品','class'=>'form-control select2','style'=>'width:200px;']) ?>
        <?= $form->field($model, 'status')->dropdownList([$model::STATUS_ACTIVE=>'正常', $model::STATUS_DISABLE=>'禁用'], 
        ['prompt'=>'', 'data-placeholder'=>'状态', 'class'=>'form-control select2', 'style'=>'width:100px;']) ?>
        <div class="form-group">
            <?= Html::submitButton('<i class="fa fa-search"></i>', ['class' => 'btn btn-default']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>