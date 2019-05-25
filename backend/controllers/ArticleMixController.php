<?php

namespace backend\controllers;

use Yii;
use backend\models\ArticleMix;
use backend\models\ArticleMixSearch;
use backend\components\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ArticleMixController implements the CRUD actions for ArticleMix model.
 */
class ArticleMixController extends Controller
{

    /**
     * Lists all ArticleMix models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ArticleMixSearch();
        $searchModel->type=ArticleMix::TYPE_ARTICLE;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ArticleMix model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new ArticleMix model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ArticleMix();
        $model->type=ArticleMix::TYPE_ARTICLE;

        if(Yii::$app->request->isPost){
            $posts=Yii::$app->request->post();
            $longtail_keywords_array=$posts['ArticleMix']['longtail_keywords_ids'];//获取长尾关键词id数组
            if(empty($longtail_keywords_array)){
                $model->longtail_keywords_ids="";
            }else{
                $model->longtail_keywords_ids=serialize($longtail_keywords_array);
            }
            unset($posts['ArticleMix']['longtail_keywords_ids']);
            if ($model->load($posts) && $model->validate()) {
                $transaction = Yii::$app->db->beginTransaction();
                try {
                    $model->save(false);
                    foreach ($longtail_keywords_array as $key => $value) {  
                        $model->createArticle($value);
                    }
                    $transaction->commit(); 
                    return $this->redirect(['index']);
                } catch (\Exception $e) {
                    $transaction->rollBack();
                    Yii::$app->session->setFlash('danger', $e->getMessage());
                    return $this->redirect(Yii::$app->request->referrer);
                }
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing ArticleMix model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->longtail_keywords_ids=unserialize($model->longtail_keywords_ids);

        if(Yii::$app->request->isPost){
            $posts=Yii::$app->request->post();
            $model->longtail_keywords_ids=serialize($posts['ArticleMix']['longtail_keywords_ids']);
            unset($posts['ArticleMix']['longtail_keywords_ids']);
            if($model->load($posts) && $model->save()){
                return $this->redirect(['index']);
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing ArticleMix model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the ArticleMix model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ArticleMix the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ArticleMix::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionBatchDelete()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        if(Yii::$app->request->isPost){
            $ids=json_decode(Yii::$app->request->post('ids'));
            if(is_array($ids) && !empty($ids)){
                foreach($ids as $id){
                    $this->findModel($id)->delete();
                }
                return ['code'=>'200','msg'=>'删除成功'];
            }else{
                return ['code'=>'-1','msg'=>'缺少参数'];
            }
        }
    }
    
}
