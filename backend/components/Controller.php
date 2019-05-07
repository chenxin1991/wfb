<?php
namespace backend\components;

use yii;
use yii\web\Controller as BaseController;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
/**
* 控制器基类
*/
class Controller extends BaseController
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ]
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }
}