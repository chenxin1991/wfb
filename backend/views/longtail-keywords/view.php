<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\LongtailKeywordsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '长尾关键词管理';
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
                <div class="btn-group">
                    <?= Html::a('<i class="glyphicon glyphicon-trash"></i>', "javascript:void(0);", ['class' => 'btn btn-default btn-sm batch-delete']) ?>
                </div>         
                <?= Html::a('<i class="fa fa-reply"></i>', ['index'], ['class' => 'btn btn-default btn-sm']) ?>          
                <div class="visible-lg-block pull-right">
                    <?php echo $this->render('_search2', [
                        'model' => $searchModel,
                    ]); ?>
                </div>
            </div>
            <?= GridView::widget([
                "options" => ["id" => "grid"],                
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
                    ["class" => "yii\grid\CheckboxColumn","name" => "id",],                    
                    'id',
                    'name',
                    ['attribute' => 'website.name','label' => '站点'],
                    ['attribute' => 'product.name','label' => '产品'],
                    ['attribute' => 'status', 'value' => function($model){
                        switch($model->status){
                            case $model::STATUS_ACTIVE:return '正常';break;
                            case $model::STATUS_COMPLETED:return '已使用';break;
                        }
                    }],
                    'created_at:datetime',
                    'updated_at:datetime',
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'header'=>'操作',
                        'template'=>'{update} {delete}',
                        'headerOptions'=>['style'=>'width:150px'],
                        'buttonOptions'=>['class'=>'btn btn-default btn-sm'],
                    ],
                ],
            ]); ?>
        </div>
    </div>
</div>

<?php
$this->registerJs(
    '$(".batch-delete").on("click", function () {
        var keys = $("#grid").yiiGridView("getSelectedRows");
        if(keys==""){
            alert("请先勾选将要删除项。");
        }else{
            if(confirm("确定要删除吗？")){
                $.post("/longtail-keywords/batch-delete", {ids:JSON.stringify(keys)}, function(data){
                    alert(data.msg);
                    location.reload();
                });
            }
        }
    });'
);
?>