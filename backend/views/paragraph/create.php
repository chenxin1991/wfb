<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Paragraph */

$this->title = '创建段落';
$this->params['breadcrumbs'][] = ['label' => '段落管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="box box-info">
    <div class="box-header">
        <div class="pull-right">
            <?=  Html::a('<i class="fa fa-reply"></i>',['index'], ['class' => 'btn btn-default']) ?>
        </div>
    </div>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
