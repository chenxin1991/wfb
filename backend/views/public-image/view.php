<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\Image */

$this->title = '公有图片详情';
$this->params['breadcrumbs'][] = ['label' => '公有图片管理', 'url' => ['index']];
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
                ['attribute' => 'product.name','label' => '产品'],
                ['attribute'=>'url','format'=>'raw','value'=>function($model){
                    if($model->url){
                        return "<a href='".$model->url."' data-lightbox='group'><img src='".$model->url."' height='80' width='80'></a>";
                    }else{
                        return "";
                    }
                }],
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
