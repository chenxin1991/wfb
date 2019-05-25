<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\ArticleMix */

$this->title = '文章发布详情';
$this->params['breadcrumbs'][] = ['label' => '发布管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="box">
    <div class="box-header">
        <div class="pull-right">
            <?= Html::a('<i class="fa fa-reply"></i>', ['index'], ['class' => 'btn btn-default']) ?>
        </div>
    </div>
    <div class="box-body no-padding">
        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                'id',
                ['attribute' => 'website.name','label' => '站点'],
                ['attribute' => 'product.name','label' => '产品'],
                ['attribute' => 'longtailKeywords','label' => '长尾关键词'],
                ['attribute' => 'template.content','label' => '模板'],
                ['attribute' => 'status', 'value' => function($model){
                    switch($model->status){
                        case $model::STATUS_ACTIVE:return '正常';break;
                        case $model::STATUS_COMPLETED:return '已发布到草稿箱';break;                            
                    }
                }],
                'created_at:datetime',
            ],
        ]) ?>
    </div>
</div>
