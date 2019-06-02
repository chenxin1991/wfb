<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\LongtailKeywords */

$this->title = '更新长尾关键词';
$this->params['breadcrumbs'][] = ['label' => '长尾关键词管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = '更新';
?>

<div class="box box-info">
    <div class="box-header">
        <div class="pull-right">
            <?=  Html::a('<i class="fa fa-reply"></i>',['index'], ['class' => 'btn btn-default']) ?>
        </div>
    </div>
    <?= $this->render('_form', [
        'model' => $model
    ]) ?>
</div>