<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

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
                    <?=Html::a('<i class="fa fa-upload"></i>', ['import'], ['class' => 'btn btn-default btn-sm', 'title' => '导入数据'])?>
                </div>
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
                    ['attribute' => 'website.name','label' => '站点'],
                    ['attribute' => 'product.name','label' => '产品'],
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'header'=>'操作',
                        'template'=>'{view}',
                        'buttons' => [
                            'view' => function ($url, $model, $key) {
                                $options = array_merge(
                                    ['title' => '详情','aria-label' => '详情'],
                                    ['class'=>'btn btn-default btn-sm']
                                );
                                return Html::a('<span class="glyphicon glyphicon-list"></span>', Url::toRoute(['longtail-keywords/view', 'website_id' => $model->website_id,'product_id'=>$model->product_id]), $options ); 
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