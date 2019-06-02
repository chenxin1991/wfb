<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\ArticleCategories */

$this->title = '更新产品';
$this->params['breadcrumbs'][] = ['label' => '产品管理', 'url' => ['index']];
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