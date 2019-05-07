<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ConfigSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '系统设置';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <div class="btn-group">
                    <?= Html::a('<i class="fa fa-pencil-square-o"></i>', ['create'], ['class' => 'btn btn-default btn-sm']) ?>
                </div>
                <a type="button" class="btn btn-default btn-sm" href="javascript:window.location.reload()"><i class="fa fa-refresh"></i></a>
                <div class="visible-lg-block pull-right">
                    <?php echo $this->render('_search', [
                        'model' => $searchModel,
                    ]); ?>
                </div>
            </div>
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'layout' => "<div class=\"box-body table-responsive\">{items}</div>\n<div class=\"box-footer clearfix\"><div class=\"row\"><div class=\"col-xs-12 col-sm-7\">{pager}</div><div class=\"col-xs-12 col-sm-5 text-right\">{summary}</div></div></div>",
                'tableOptions'=>['class'=>'table table-bordered table-hover'],
                'summary'=>'第{page}页，共{pageCount}页，当前第{begin}-{end}项，共{totalCount}项',
                'pager'=>[
                    'options' => [
                        'class' => 'pagination pagination-sm no-margin',
                    ],
                ],
                'columns' => [
                    'id',
                    'introduce',
                    'value',
                    ['attribute' => 'status', 'value' => function($model){
                        switch($model->status){
                            case $model::STATUS_ACTIVE:return '正常';break;
                            case $model::STATUS_DISABLE:return '禁用';break;
                        }
                    }],
                    'created_at:datetime',
                    'updated_at:datetime',
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'header'=>'操作',
                        'template'=>'{view} {update} {disable} {enable}',
                        'buttons' => [
                            'disable' => function ($url, $model, $key) {
                                $options = array_merge(
                                    ['title' => '禁用','aria-label' => '禁用','data-pjax' => '0'],
                                    ['data-confirm' => '确定要禁用吗？','data-method' => 'post'], 
                                    ['class'=>'btn btn-default btn-sm']
                                );
                                return $model->status == $model::STATUS_ACTIVE ? Html::a('<span class="glyphicon glyphicon-pause"></span>', $url, $options ) : ''; 
                            },
                            'enable' => function ($url, $model, $key) {
                                $options = array_merge(
                                    ['title' => '启用','aria-label' => '启用','data-pjax' => '0'],
                                    ['data-confirm' => '确定要启用吗？','data-method' => 'post'], 
                                    ['class'=>'btn btn-default btn-sm']
                                );
                                return $model->status == $model::STATUS_DISABLE ? Html::a('<span class="glyphicon glyphicon-play"></span>', $url, $options ) : ''; 
                            },
                        ],
                        'headerOptions'=>['style'=>'width:150px'],
                        'buttonOptions'=>['class'=>'btn btn-default btn-sm'],
                    ],
                ],
            ]); ?>
        </div>
    </div>
</div>
