<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\ArticleMix */

$this->title = '创建文章发布';
$this->params['breadcrumbs'][] = ['label' => '文章发布管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="box box-info">
    <div class="box-header">
        <div class="pull-right">
            <?=  Html::a('<i class="fa fa-reply"></i>', ['index'], ['class' => 'btn btn-default']) ?>
        </div>
    </div>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>

<?php
$this->registerJs(
    '$("#articlemix-product_id").on("change",function(){
        var website_id=$("#articlemix-website_id").val();
        if(website_id){
            $.get("/longtail-keywords/by_site_product",{website_id:website_id,product_id:$(this).val()},function ($data){
                $("#articlemix-longtail_keywords_ids").html($data);
            });
        }
    });'
);
?>