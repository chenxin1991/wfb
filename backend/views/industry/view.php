<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\SiteType */

$this->title = '行业详情';
$this->params['breadcrumbs'][] = ['label' => '行业管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="box">
    <div class="box-header">
        <div class="btn-group">
            <?= Html::a('<i class="fa fa-pencil"></i>', ['update', 'id' => $model->id], ['class' => 'btn btn-default']) ?>
        </div>
        <div class="pull-right">
            <?= Html::a('<i class="fa fa-reply"></i>',['index'], ['class' => 'btn btn-default']) ?>
        </div>
    </div>
    <div class="box-body no-padding">
        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                'id',
                'name',
                'created_at:datetime',
                'updated_at:datetime',
            ],
        ]) ?>
    </div>
</div>
