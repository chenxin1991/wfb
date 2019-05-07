<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use backend\models\Platform;
use backend\models\Industry;
/* @var $this yii\web\View */
/* @var $model backend\models\WebsiteSearch */
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
        <?= $form->field($model, 'name')->textInput(['placeholder'=>'名称']) ?>
        <?= $form->field($model, 'platform_id')->dropDownList(Platform::dropdownlist(),
        ['prompt'=>'','data-placeholder'=>'平台','class'=>'form-control select2','style'=>'width:150px;']) ?>
        <?= $form->field($model, 'industry_id')->dropDownList(Industry::dropdownlist(),
        ['prompt'=>'','data-placeholder'=>'行业','class'=>'form-control select2','style'=>'width:200px;']) ?>
        <?= $form->field($model, 'status')->dropdownList([$model::STATUS_ACTIVE=>'正常', $model::STATUS_DISABLE=>'禁用'], 
        ['prompt'=>'', 'data-placeholder'=>'状态', 'class'=>'form-control select2', 'style'=>'width:100px;']) ?>
        <div class="form-group">
            <?= Html::submitButton('<i class="fa fa-search"></i>', ['class' => 'btn btn-default']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>
