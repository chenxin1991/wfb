<?php

namespace backend\controllers;

use Yii;
use backend\models\Article;
use backend\models\ArticleSearch;
use backend\models\MainKeywords;
use backend\models\LongtailKeywords;
use backend\components\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ArticleController implements the CRUD actions for Article model.
 */
class ArticleController extends Controller
{

    /**
     * Lists all Article models.
     * @return mixed
     */
    public function actionIndex()
    {   
        $searchModel = new ArticleSearch();
        $searchModel->type=$searchModel::TYPE_ARTICLE;

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Article model.
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
     * Creates a new Article model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Article();
        $model->type=Article::TYPE_ARTICLE;
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Article model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {            
            $br_array=explode("<br />",$model->content);
            $first_paragraph=$br_array[0];
            $flag=strpos($first_paragraph,$model->keywords);
            if($flag){
                $replace_str="<strong>".$model->keywords."</strong>";
                $br_array[0]=substr_replace($first_paragraph,$replace_str,strpos($first_paragraph,$model->keywords),strlen($model->keywords));
                $model->content=implode("<br />",$br_array);
            }
            if($model->save()){
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Article model.
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
     * Finds the Article model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Article the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Article::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionPublish()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $type="article";
        if(Yii::$app->request->isPost){
            $article_ids=json_decode(Yii::$app->request->post('article_ids'));
            if(is_array($article_ids)){
                $i=0;
                foreach($article_ids as $value){
                    $article=Article::find()->where(['id'=>$value,'status'=>0])->one();
                    if($article){
                        $response=$article->publish($type);
                        $arr_response=json_decode($response, true);
                        if($arr_response['status']==200){
                            $article->status=Article::STATUS_PUBLISHED;
                            $article->save();
                            $i++;
                        }
                    }
                }
                return ['code'=>'200','msg'=>'发布成功'.$i.'条'];
            }else{
                return ['code'=>'-1','msg'=>'缺少参数'];
            }
        }
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

    public function actionMainKeywords($website_id)
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $result=MainKeywords::find()->select('name')->where(['website_id'=>$website_id])->asArray()->all();
        foreach($result as $key => $value){
            $result[$key]['type']='choiceitem';
            $result[$key]['text']=$value['name'];
            $result[$key]['value']=$value['name'];
        }
        return $result;
    }

    public function actionLongtailKeywords($website_id)
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $result=LongtailKeywords::find()->select('name')->where(['website_id'=>$website_id])->asArray()->all();
        foreach($result as $key => $value){
            $result[$key]['type']='choiceitem';
            $result[$key]['text']=$value['name'];
            $result[$key]['value']=$value['name'];
        }
        return $result;
    }
}
