<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\Website */

$this->title = '站点详情';
$this->params['breadcrumbs'][] = ['label' => '站点管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="box">
    <div class="box-header">
        <div class="btn-group">
            <?= Html::a('<i class="fa fa-pencil"></i>', ['update', 'id' => $model->id], ['class' => 'btn btn-default']) ?>
        </div>
        <div class="pull-right">
            <?= Html::a('<i class="fa fa-reply"></i>', Yii::$app->request->referrer, ['class' => 'btn btn-default']) ?>
        </div>
    </div>
    <div class="box-body no-padding">
        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                'id',
                'name',
                ['attribute' => 'platform.name','label' => '平台'],
                ['attribute' => 'industry.name','label' => '行业'],
                ['attribute' => 'products','label' => '产品'],
                'site_id',
                'api_key',
                ['attribute' => 'is_image_public', 'value' => function($model){
                    switch($model->is_image_public){
                        case $model::PUBLIC_IMAGE:return '是';break;
                        case $model::PRIVATE_IMAGE:return '否';break;
                    }
                }],
                'created_at:datetime',
                'updated_at:datetime',
            ],
        ]) ?>
    </div>
</div>
