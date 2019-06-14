<?php

namespace backend\controllers;

use Yii;
use backend\models\FirstParagraph;
use backend\models\FirstParagraphSearch;
use backend\components\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\validators\FileValidator;
use yii\web\UploadedFile;
use backend\models\Product;

/**
 * FirstParagraphController implements the CRUD actions for FirstParagraph model.
 */
class FirstParagraphController extends Controller
{

    /**
     * Lists all FirstParagraph models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new FirstParagraphSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single FirstParagraph model.
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
     * Creates a new FirstParagraph model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new FirstParagraph();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing FirstParagraph model.
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
     * Deletes an existing FirstParagraph model.
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
     * Finds the FirstParagraph model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return FirstParagraph the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = FirstParagraph::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionDisable($id)
    {
        $model = $this->findModel($id);
        $model->status = FirstParagraph::STATUS_DISABLE;
        $model->save();
        return $this->redirect(['index']);
    }

    public function actionEnable($id)
    {
        $model = $this->findModel($id);
        $model->status = FirstParagraph::STATUS_ACTIVE;
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
                        //查询类型
                        if($attrs['typename']=="文章"){
                            $attrs['type']=FirstParagraph::TYPE_ARTICLE;
                        }elseif($attrs['typename']=="站内站"){
                            $attrs['type']=FirstParagraph::TYPE_STATION;
                        }else{
                            $errors[] =  '第'.($index)."行存在错误：{$attrs['typename']}类型不存在";
                            continue;
                        }
                        // 查询产品
                        $product = Product::findOne(['name'=>trim($attrs['product'])]);
                        if($product === null){
                            $errors[] =  '第'.($index)."行存在错误：{$attrs['product']}产品不存在";
                            continue;
                        }
                        $attrs['product_id']=$product->id;
                        // 查询首段是否存在
                        $first_paragraph=FirstParagraph::findOne(['content'=>$attrs['content'],'product_id'=>$attrs['product_id']]);
                        if($first_paragraph != null) {
                            $errors[] = "第{$index}行数据已存在，请删除后重试；";
                            continue;
                        }
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
                                $first_paragraph=new FirstParagraph();
                                $first_paragraph->attributes=[
                                    'type'=>$datum['type'],
                                    'content'=>$datum['content'],
                                    'product_id'=>$datum['product_id'],
                                    'status'=>0
                                ];
                                $first_paragraph->save();
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
