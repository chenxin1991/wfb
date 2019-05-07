<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use backend\models\Product;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model backend\models\WordendSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="row">
    <div class="col-xs-12">
        <?php $form = ActiveForm::begin([
            'action' => Url::toRoute(['word/view', 'product_id'=>$model->product_id]),
            'method' => 'get',
            'options'=>['class'=>'form-inline'],
            'fieldConfig'=>[
                'template'=>"{label}\n{input}\n",
                'labelOptions'=>['class'=>'sr-only'],
            ],
        ]); ?>
        <?= $form->field($model, 'product_id')->dropDownList(Product::all(),
        ['prompt'=>'','data-placeholder'=>'产品','class'=>'form-control select2','style'=>'width:200px;']) ?>
        <?= $form->field($model, 'headname')->textInput(['placeholder'=>'词头']) ?>
        <?= $form->field($model, 'endname')->textInput(['placeholder'=>'词尾']) ?>
        <div class="form-group">
            <?= Html::submitButton('<i class="fa fa-search"></i>', ['class' => 'btn btn-default']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>
