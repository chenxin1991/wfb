<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\LongtailKeywords */

$this->title = '创建长尾关键词';
$this->params['breadcrumbs'][] = ['label' => '长尾关键词管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="box box-info">
    <div class="box-header">
        <div class="pull-right">
            <?=  Html::a('<i class="fa fa-reply"></i>', Yii::$app->request->referrer, ['class' => 'btn btn-default']) ?>
        </div>
    </div>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
