<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\Article */

$this->title = '文章详情';
$this->params['breadcrumbs'][] = ['label' => '文章管理', 'url' => ['index']];
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
                'source_id',
                ['attribute' => 'website.name','label' => '站点'],
                ['attribute' => 'product.name','label' => '产品'],
                'title',
                // 'seo_title',
                // 'keywords',
                // 'description',
                'content:html',
                ['attribute' => 'status', 'value' => function($model){
                    switch($model->status){
                        case $model::STATUS_ACTIVE:return '未修改';break;
                        case $model::STATUS_PUBLISHED:return '已发布';break;
                        case $model::STATUS_MODIFIED:return '已修改未发布';break;
                    }
                }],
                'created_at:datetime',
                'updated_at:datetime',
            ],
            
        ]) ?>
    </div>
</div>