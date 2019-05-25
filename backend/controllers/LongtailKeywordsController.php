<?php

namespace backend\controllers;

use Yii;
use backend\models\LongtailKeywords;
use backend\models\LongProductSearch;
use backend\models\LongtailKeywordsSearch;
use backend\components\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\validators\FileValidator;
use yii\web\UploadedFile;
use backend\models\Website;
use backend\models\Product;

/**
 * LongtailKeywordsController implements the CRUD actions for LongtailKeywords model.
 */
class LongtailKeywordsController extends Controller
{
    /**
     * Lists all LongtailKeywords models.
     * @return mixed
     */

    public function actionIndex()
    {
        $searchModel = new LongProductSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single LongtailKeywords model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($website_id,$product_id)
    {
        $searchModel = new LongtailKeywordsSearch();
        $searchModel->website_id=$website_id;
        $searchModel->product_id=$product_id;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('view', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new LongtailKeywords model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new LongtailKeywords();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'website_id' => $model->website_id,'product_id'=>$model->product_id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing LongtailKeywords model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            if(isset($_POST['remember_url']) && !empty($_POST['remember_url'])){
                return $this->redirect($_POST['remember_url']);
            }else{
                return $this->redirect(['view', 'website_id' => $model->website_id,'product_id'=>$model->product_id]);
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing LongtailKeywords model.
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
     * Finds the LongtailKeywords model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return LongtailKeywords the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = LongtailKeywords::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionDisable($id)
    {
        $model = $this->findModel($id);
        $model->status = LongtailKeywords::STATUS_DISABLE;
        $model->save();
        return $this->redirect(['index']);
    }

    public function actionEnable($id)
    {
        $model = $this->findModel($id);
        $model->status = LongtailKeywords::STATUS_ACTIVE;
        $model->save();
        return $this->redirect(['index']);
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

    public function actionBy_site_product($website_id,$product_id)
    {
        $model = LongtailKeywords::find()->where(['status'=>0,'website_id'=>$website_id,'product_id'=>$product_id])->all();
        $result = yii\helpers\ArrayHelper::map($model,'id','name');
        $str="<option value=''></option>";
        foreach($result as $key => $value){
            $str.="<option value='{$key}'>$value</option>";
        }
        return $str;
    }

    public function actionGetname($id)
    {
        if (($model = LongtailKeywords::findOne($id)) !== null) {
            return $model->name;
        }else{
            return "";
        }
    }

    public function actionImport()
    {
        if(Yii::$app->request->isPost){
            $param = Yii::$app->request->queryParams;
            $validator = new FileValidator(['skipOnEmpty' => true, 'checkExtensionByMimeType'=>false, 'extensions' => ['xls', 'xlsx'], 'maxSize'=>1024*1024, 'tooBig'=>'文件体积过大，不能超过{formattedLimit}.']);
            $file = UploadedFile::getInstanceByName('file');
            if($file !== null)
            {
                if($validator->validate($file))
                {
                    $filename = $file->tempName;
                    $objReader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader(ucfirst($file->getExtension()));
                    $objReader->setReadDataOnly (true);
                    $phpSpredsheet = $objReader->load ($filename);
                    $currentSheet = $phpSpredsheet->getActiveSheet();
                    $data = $currentSheet->toArray();
                    $errors = array();
                    $valid_data = array();
                    foreach($data as $index => $row)
                    {
                        if($index < 2) continue;
                        if(empty($row[0])) continue;
                        $attrs = array_combine($data[1], $row);
                        //查询是否存在
                        $longtail_keywords=LongtailKeywords::findOne(['name'=>$attrs['name']]);
                        if($longtail_keywords != null) {
                            $errors[] = "第{$index}行数据“{$attrs['name']}”已存在，请删除后重试；";
                            continue;
                        }
                        // 查询站点
                        $website = Website::findOne(['name'=>trim($attrs['website'])]);
                        if($website === null){
                            $errors[] =  '第'.($index)."行存在错误：{$attrs['website']}站点不存在";
                            continue;
                        }
                        $attrs['website_id']=$website->id;
                        // 查询当前站点下是否存在当前产品
                        $product = Product::findOne(['name'=>trim($attrs['product'])]);
                        if($product === null){
                            $errors[] =  '第'.($index)."行存在错误：{$attrs['product']}产品不存在";
                            continue;
                        }else{
                            if(!in_array($product->id,unserialize($website->product_ids))){
                                $errors[] =  '第'.($index)."行存在错误：{$attrs['product']}产品在{$attrs['website']}站点中不存在";
                                continue;
                            }
                        }
                        $attrs['product_id']=$product->id;                        
                        $valid_data[] = $attrs;
                    }
                    if(!empty($errors))
                    {
                        Yii::$app->session->setFlash('error', implode('；', $errors));
                        return $this->redirect(['import']+$param);
                    }
                    else
                    {
                        $transaction = Yii::$app->db->beginTransaction();
                        try
                        {
                            foreach($valid_data as $index=>$datum)
                            {
                                $longtail_keywords=new LongtailKeywords();
                                $longtail_keywords->attributes=[
                                    'name'=>$datum['name'],
                                    'website_id'=>$datum['website_id'],
                                    'product_id'=>$datum['product_id'],
                                    'status'=>0
                                ];
                                $longtail_keywords->save();
                            }
                            $transaction->commit();
                            Yii::$app->session->setFlash('success', '成功导入数据'.count($valid_data).'条!');
                            return $this->redirect(['index']);
                        }
                        catch(Exception $e)
                        {
                            $transaction->rollback();
                            Yii::$app->session->setFlash('error', $e->getMessage());
                            return $this->redirect(['import']+$param);
                        }
                    }
                }
                else{
                    Yii::$app->session->setFlash('error', '请上传文件');
                    return $this->redirect(['import']+$param);
                }
            }
        }
        return $this->render('import');
    }
}
