<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\SiteTypeSearch */
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
        <?= $form->field($model, 'status')->dropdownList([$model::STATUS_ACTIVE=>'正常', $model::STATUS_DISABLE=>'禁用'], 
        ['prompt'=>'', 'data-placeholder'=>'状态', 'class'=>'form-control select2', 'style'=>'width:100px;']) ?>
        <div class="form-group">
            <?= Html::submitButton('<i class="fa fa-search"></i>', ['class' => 'btn btn-default']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>