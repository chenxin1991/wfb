<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PlatformSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '平台管理';
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
                    'name',
                    ['attribute'=>'created_at','format'=>'datetime','options'=>['style' => 'width:10em']],
                    ['attribute'=>'updated_at','format'=>'datetime','options'=>['style' => 'width:10em']],
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'header'=>'操作',
                        'template'=>'{update} {delete}',
                        'headerOptions'=>['style'=>'width:500px'],
                        'buttonOptions'=>['class'=>'btn btn-default btn-sm'],
                    ],
                ],
            ]); ?>
        </div>
    </div>
</div>

