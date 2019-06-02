<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Template */

$this->title = '更新模板';
$this->params['breadcrumbs'][] = ['label' => '模板管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = '更新';
?>

<div class="box box-info">
    <div class="box-header">
        <div class="btn-group">
            <button class="btn btn-primary" id="insert-page">插入段落</button>
        </div>
        <div class="btn-group">
            <button class="btn btn-primary" id="insert-image">插入图片</button>
        </div>
        <div class="pull-right">
            <?=  Html::a('<i class="fa fa-reply"></i>',['index'], ['class' => 'btn btn-default']) ?>
        </div>
    </div>
    <?= $this->render('_form', [
        'model' => $model
    ]) ?>
</div>
