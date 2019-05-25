<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use backend\models\Platform;
use backend\models\Industry;
use backend\models\Product;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model backend\models\Website */
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
    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
    <?=$form->field($model, 'platform_id', ['labelOptions' => ['label' => '平台']])->dropDownList(Platform::dropdownlist(), 
    ['prompt' => '', 'data-placeholder' => '选择平台', 'class' => 'form-control select2', 'style' => 'width:100%;'])?>
    <?=$form->field($model, 'industry_id', ['labelOptions' => ['label' => '行业']])->dropDownList(Industry::dropdownlist(), 
    ['prompt' => '', 'data-placeholder' => '选择行业', 'class' => 'form-control select2', 'style' => 'width:100%;',
    'onchange'=>'$.get("/product/dropdownlist",{industry_id:$(this).val()},function ($data) 
    {$("#website-product_ids").html($data);})'])?>
    <?= $form->field($model, 'site_id')->textInput() ?>
    <?= $form->field($model, 'api_key')->textInput() ?>
    <?= $form->field($model, 'product_ids')->widget(Select2::classname(),['data' => Product::dropdownlist($model->industry_id), 
    'options' => ['multiple' => true,'placeholder' => '请选择 ...'],'pluginOptions' => ['allowClear'=>true,'hideSearch'=>true],]);?>
    <?= $form->field($model, 'is_image_public')->dropdownList([$model::PUBLIC_IMAGE=>'是', $model::PRIVATE_IMAGE=>'否'], 
    ['prompt'=>'', 'data-placeholder'=>'图片是否公有', 'class'=>'form-control select2', 'style'=>'width:100%']) ?>
    <div class="box-footer">
        <div class="col-sm-4"></div>
        <div class="col-sm-2 col-xs-2"><a href="javascript:history.back();" class="btn btn-default">取消</a></div>
        <div class="col-sm-2 col-xs-2"><?= Html::submitButton($model->isNewRecord ? '创建' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?></div>
    </div>
    <?php ActiveForm::end(); ?>
</div>
