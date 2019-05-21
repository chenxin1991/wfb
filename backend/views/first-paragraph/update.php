<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\FirstParagraph */

$this->title = '更新首段';
$this->params['breadcrumbs'][] = ['label' => '首段管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = '更新';
?>

<div class="box box-info">
    <div class="box-header">
        <div class="pull-right">
            <?=  Html::a('<i class="fa fa-reply"></i>', Yii::$app->request->referrer, ['class' => 'btn btn-default']) ?>
        </div>
    </div>
    <?= $this->render('_form', [
        'model' => $model
    ]) ?>
</div>
