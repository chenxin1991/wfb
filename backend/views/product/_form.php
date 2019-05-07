<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use backend\models\Industry;

/* @var $this yii\web\View */
/* @var $model backend\models\ArticleCategories */
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
    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
    <?=$form->field($model, 'industry_id', ['labelOptions' => ['label' => '行业']])->dropDownList(Industry::dropdownlist(), 
    ['prompt' => '', 'data-placeholder' => '选择行业', 'class' => 'form-control select2', 'style' => 'width:100%;'])?>
    <div class="box-footer">
        <div class="col-sm-4"></div>
        <div class="col-sm-2 col-xs-2"><a href="javascript:history.back();" class="btn btn-default">取消</a></div>
        <div class="col-sm-2 col-xs-2"><?= Html::submitButton($model->isNewRecord ? '创建' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?></div>
    </div>
    <?php ActiveForm::end(); ?>
</div>
