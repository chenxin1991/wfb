<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use backend\models\Website;
use backend\models\Product;

/* @var $this yii\web\View */
/* @var $model backend\models\Image */
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
    <input type="hidden" name="remember_url" value="<?php echo Yii::$app->request->referrer; ?>">
    <?=$form->field($model, 'website_id')->dropDownList(Website::dropdownlist(), 
    ['prompt' => '', 'data-placeholder' => '选择站点', 'class' => 'form-control select2', 'style' => 'width:100%;',
    'onchange'=>'$.get("/product/bysite",{site_id:$(this).val()},function ($data) 
    {$("#privateimage-product_id").html($data);})'])?>
    <?=$form->field($model, 'product_id')->dropDownList(Product::bysite($model->website_id), 
    ['prompt' => '', 'data-placeholder' => '选择产品', 'class' => 'form-control select2', 'style' => 'width:100%;'])?>
    <?php if ($model->isNewRecord): ?>
    <?=$form->field($model, 'urls',['labelOptions' => ['label' => '图片']])->widget('manks\FileInput', [
        'clientOptions' => [
            'pick' => [
                'multiple' => true,
            ],
        ],
    ]); ?>
    <?php endif ?>
    <?php if (!$model->isNewRecord): ?>
    <?php 
    echo $form->field($model, 'url')->widget('manks\FileInput', [
    ]); 
?>

    <?php endif ?>
    <div class="box-footer">
        <div class="col-sm-4"></div>
        <div class="col-sm-2 col-xs-2"><a href="javascript:history.back();" class="btn btn-default">取消</a></div>
        <div class="col-sm-2 col-xs-2"><?= Html::submitButton($model->isNewRecord ? '创建' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?></div>
    </div>
    <?php ActiveForm::end(); ?>
</div>
