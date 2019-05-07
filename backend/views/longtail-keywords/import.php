<?php


use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Client */

$this->title = '导入数据';
$this->params['breadcrumbs'][] = ['label' => '长尾关键词管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = '导入';
?>

<div class="box box-info">
    <div class="box-header">
        <div class="pull-right">
            <?=  Html::a('<i class="fa fa-reply"></i>', ['index'], ['class' => 'btn btn-default']) ?>
        </div>
    </div>
    <?php $form = ActiveForm::begin([
        'id' => 'upload-form',
        'options'=>['enctype'=>'multipart/form-data','class'=>'form-horizontal'],
    ]); ?>
    <div class="form-group">
        <label class="col-lg-3 control-label">导入Excel数据</label>
        <div class="col-lg-7">
            <?php echo \kartik\file\FileInput::widget(['name'=>'file','options' => ['multiple' => false],
        'pluginOptions'=>['showUpload' => false, 'dropZoneTitle'=>'拖入Excel文件来上传', 'showPreview'=>false, 'allowedFileExtensions'=>['xlsx','xls']]
    ]); ?>
            <span class="help-block m-b-none">请按照模板格式准备Excel数据，<a href="/excel_templet/longtail_keywords.xlsx">点击这里下载模板</a></span>
        </div>
    </div>

    <div class="box-footer">
        <a href="javascript:history.back();" class="btn btn-default">取消</a>
        <?=  Html::submitButton('导入', ['class' =>'btn btn-primary pull-right']) ?>
    </div>
    <?php  ActiveForm::end(); ?>
</div>