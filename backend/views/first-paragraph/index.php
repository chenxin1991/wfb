<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\bootstrap\Modal;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\FirstParagraphSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '首段管理';
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
                <div class="btn-group">
                    <?=Html::a('<i class="fa fa-upload"></i>', ['import'], ['class' => 'btn btn-default btn-sm', 'title' => '导入数据'])?>
                </div>
                <div class="visible-lg-block pull-right">
                    <?php echo $this->render('_search', [
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
                    ['attribute' => 'type', 'value' => function($model){
                        switch($model->type){
                            case $model::TYPE_ARTICLE:return '文章';break;
                            case $model::TYPE_STATION:return '站内站';break;
                        }
                    }],
                    ['attribute' => 'product.name','label' => '产品'],
                    [
                        'attribute'=>'content',
                        'value'=>function($model){
                            $tmpStr = strip_tags($model->content);//去掉html的标签
                            $tmplength = mb_strlen($tmpStr);//计算长度
                            return mb_substr($tmpStr,0,50,'utf-8').(($tmplength>50)?'...':'');//判断长度大于20以后做拼接
                        }
                    ],
                    'times',
                    ['attribute' => 'status', 'value' => function($model){
                        switch($model->status){
                            case $model::STATUS_ACTIVE:return '正常';break;
                            case $model::STATUS_DISABLE:return '禁用';break;
                        }
                    }],
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'header'=>'操作',
                        'template'=>'{view} {update} {delete}',
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
                $.post("/first-paragraph/batch-delete", {ids:JSON.stringify(keys)}, function(data){
                    alert(data.msg);
                    location.reload();
                });
            }
        }
    });'
);
?>