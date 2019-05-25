<?php

namespace backend\controllers;

use Yii;
use backend\models\Product;
use backend\models\ProductSearch;
use backend\models\Industry;
use backend\components\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use backend\models\Website;
use backend\components\Upload;
use yii\helpers\Json;
use yii\validators\FileValidator;
use yii\web\UploadedFile;

/**
 * ProductController implements the CRUD actions for Product model.
 */
class ProductController extends Controller
{
    /**
     * Lists all Product models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ProductSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Product model.
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
     * Creates a new Product model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Product();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Product model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Product model.
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
     * Finds the Product model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Product the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Product::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionDisable($id)
    {
        $model = $this->findModel($id);
        $model->status = Product::STATUS_DISABLE;
        $model->save();
        return $this->redirect(['index']);
    }

    public function actionEnable($id)
    {
        $model = $this->findModel($id);
        $model->status = Product::STATUS_ACTIVE;
        $model->save();
        return $this->redirect(['index']);
    }

    public function actionDropdownlist($industry_id){
        $model = Product::find()->where('industry_id =:industry_id',[':industry_id'=>$industry_id])->andWhere(['status'=>0])->all();
        $result = yii\helpers\ArrayHelper::map($model,'id','name');
        $str="<option value=''></option>";
        foreach($result as $key => $value){
            $str.="<option value='{$key}'>$value</option>";
        }
        return $str;
    }

    public function actionBysite($site_id){
        $str="<option value=''></option>";
        $website=Website::findone($site_id);
        if($website){
            $product_ids=unserialize($website->product_ids);
            $products=Product::find()->where(['in','id',$product_ids])->andWhere(['status'=>0])->asArray()->all();
            $result=array_column($products,'name','id');
            foreach($result as $key => $value){
                $str.="<option value='{$key}'>$value</option>";
            }
        }
        return $str;
    }

    public function actionUpload()
    {
        try {
            $model = new Upload();
            $info = $model->upImage();
            $info && is_array($info) ? exit(Json::htmlEncode($info)):exit(Json::htmlEncode([
                'code' => 1, 
                'msg' => 'error'
            ]));
        } catch (\Exception $e) {
            exit(Json::htmlEncode([
                'code' => 1, 
                'msg' => $e->getMessage()
            ]));
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
                        $product=Product::findOne(['name'=>$attrs['name']]);
                        if($product != null) {
                            $errors[] = "第{$index}行数据“{$attrs['name']}”已存在，请删除后重试；";
                            continue;
                        }
                        // 查询行业
                        $industry = Industry::findOne(['name'=>trim($attrs['industry'])]);
                        if($industry === null){
                            $errors[] =  '第'.($index)."行存在错误：{$attrs['industry']}行业不存在";
                            continue;
                        }
                        $attrs['industry_id']=$industry->id;
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
                                $product=new Product();
                                $product->attributes=[
                                    'name'=>$datum['name'],
                                    'industry_id'=>$datum['industry_id'],
                                    'status'=>0
                                ];
                                $product->save();
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
