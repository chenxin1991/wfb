<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use backend\models\Product;

/* @var $this yii\web\View */
/* @var $model backend\models\EndParagraph */
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
    <?= $form->field($model, 'type')->dropdownList([$model::TYPE_ARTICLE=>'文章', $model::TYPE_STATION=>'站内站'], 
    ['prompt'=>'', 'data-placeholder'=>'类型', 'class'=>'form-control select2', 'style'=>'width:100%']) ?>
    <?=$form->field($model, 'product_id')->dropDownList(Product::all(), 
    ['prompt' => '', 'data-placeholder' => '选择产品', 'class' => 'form-control select2', 'style' => 'width:100%;'])?>
    <?= $form->field($model, 'content')->textarea(['rows' => 6]) ?>
    <div class="box-footer">
        <div class="col-sm-4"></div>
        <div class="col-sm-2 col-xs-2"><a href="javascript:history.back();" class="btn btn-default">取消</a></div>
        <div class="col-sm-2 col-xs-2"><?= Html::submitButton($model->isNewRecord ? '创建' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?></div>
    </div>
    <?php ActiveForm::end(); ?>
</div>
