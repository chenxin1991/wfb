<?php

namespace backend\controllers;

use Yii;
use backend\models\Website;
use backend\models\WebsiteSearch;
use backend\components\Controller;
use yii\web\NotFoundHttpException;
use backend\models\Platform;
use backend\models\Industry;
use yii\validators\FileValidator;
use yii\web\UploadedFile;

/**
 * WebsiteController implements the CRUD actions for Website model.
 */
class WebsiteController extends Controller
{
    /**
     * Lists all Website models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new WebsiteSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Website model.
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
     * Creates a new Website model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Website();

        if(Yii::$app->request->isPost){
            $posts=Yii::$app->request->post();
            $model->product_ids=serialize($posts['Website']['product_ids']);
            unset($posts['Website']['product_ids']);
            if($model->load($posts) && $model->save()){
                return $this->redirect(['index']);
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Website model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->product_ids=unserialize($model->product_ids);

        if(Yii::$app->request->isPost){
            $posts=Yii::$app->request->post();
            $model->product_ids=serialize($posts['Website']['product_ids']);
            unset($posts['Website']['product_ids']);
            if($model->load($posts) && $model->save()){
                return $this->redirect(['index']);
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Website model.
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
     * Finds the Website model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Website the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Website::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionDisable($id)
    {
        $model = $this->findModel($id);
        $model->status = Website::STATUS_DISABLE;
        $model->save();
        return $this->redirect(['index']);
    }

    public function actionEnable($id)
    {
        $model = $this->findModel($id);
        $model->status = Website::STATUS_ACTIVE;
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
                        //查询是否存在
                        $website=Website::findOne(['name'=>$attrs['name']]);
                        if($website != null) {
                            $errors[] = "第{$index}行数据“{$attrs['name']}”已存在，请删除后重试；";
                            continue;
                        }
                        // 查询平台
                        $platform = Platform::findOne(['name'=>trim($attrs['platform'])]);
                        if($platform === null){
                            $errors[] =  '第'.($index)."行存在错误：{$attrs['platform']}平台不存在";
                            continue;
                        }
                        $attrs['platform_id']=$platform->id;
                        // 查询行业
                        $industry = Industry::findOne(['name'=>trim($attrs['industry'])]);
                        if($industry === null){
                            $errors[] =  '第'.($index)."行存在错误：{$attrs['industry']}行业不存在";
                            continue;
                        }
                        $attrs['industry_id']=$industry->id;
                        //查询图片是否私有
                        if($attrs['is_image_public']=="是"){
                            $attrs['is_image_public']=Website::PUBLIC_IMAGE;
                        }elseif($attrs['is_image_public']=="否"){
                            $attrs['is_image_public']=Website::PRIVATE_IMAGE;
                        }else{
                            $errors[] =  '第'.($index)."行存在错误：{$attrs['is_image_public']}图片是否公有不存在";
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
                                $website=new Website();
                                $website->attributes=[
                                    'name'=>$datum['name'],
                                    'platform_id'=>$datum['platform_id'],
                                    'industry_id'=>$datum['industry_id'],
                                    'site_id'=>$datum['site_id'],
                                    'api_key'=>$datum['api_key'],
                                    'is_image_public'=>$datum['is_image_public'],
                                    'status'=>0
                                ];
                                $website->save();
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
