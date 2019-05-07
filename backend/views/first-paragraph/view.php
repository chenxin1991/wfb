<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\FirstParagraph */

$this->title = '首段详情';
$this->params['breadcrumbs'][] = ['label' => '首段管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="box">
    <div class="box-header">
        <div class="btn-group">
            <?= Html::a('<i class="fa fa-pencil"></i>', ['update', 'id' => $model->id], ['class' => 'btn btn-default']) ?>
        </div>
        <div class="pull-right">
            <?= Html::a('<i class="fa fa-reply"></i>', ['index'], ['class' => 'btn btn-default']) ?>
        </div>
    </div>
    <div class="box-body no-padding">
        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                'id',
                ['attribute' => 'type', 'value' => function($model){
                    switch($model->type){
                        case $model::TYPE_ARTICLE:return '文章';break;
                        case $model::TYPE_STATION:return '站内站';break;
                    }
                }],
                ['attribute' => 'product.name','label' => '产品'],
                'content:ntext',
                'times',
                ['attribute' => 'status', 'value' => function($model){
                    switch($model->status){
                        case $model::STATUS_ACTIVE:return '正常';break;
                        case $model::STATUS_DISABLE:return '禁用';break;
                    }
                }],
                'created_at:datetime',
                'updated_at:datetime',
            ],
        ]) ?>
    </div>
</div>
