<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

$this->title = '登录';

$fieldOptions1 = [
    'options' => ['class' => 'form-group has-feedback'],
    'inputTemplate' => "{input}<span class='glyphicon glyphicon-user form-control-feedback'></span>"
];

$fieldOptions2 = [
    'options' => ['class' => 'form-group has-feedback'],
    'inputTemplate' => "{input}<span class='glyphicon glyphicon-lock form-control-feedback'></span>"
];

$this->registerCssFile('@web/css/login.css', ['depends' => ['backend\assets\AppAsset']]);
?>

<div class="warp">
    <div class="header">
        <img src="/images/logo.8680719.png" alt="">
    </div>
    <div class="login-body">
        <div class="col" style="text-align: right;">
            <img src="/images/login_img.3ca549e.png" alt="">
        </div>
        <div class="col" style="text-align: left;">
            <div class="login-form">
                <h2 class="title">登录</h2>
                <?php $form = ActiveForm::begin(['class' => 'el-form el-form--label-top', 'enableClientValidation' => false]); ?>

                <?= $form->field($model, 'username', $fieldOptions1)->textInput(['autofocus' => true]) ?>

                <?= $form->field($model, 'password', $fieldOptions2)->passwordInput() ?>

                <div class="row">
                    <div class="col-xs-8">
                        <?= $form->field($model, 'rememberMe')->checkbox() ?>
                    </div>
                    <!-- /.col -->
                    <div class="col-xs-4">
                        <?= Html::submitButton('登录', ['class' => 'btn btn-primary btn-block btn-flat', 'name' => 'login-button']) ?>
                    </div>
                    <!-- /.col -->
                </div>


                <?php ActiveForm::end(); ?>

            </div>
        </div>
    </div>
</div>
