<?php

namespace backend\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "template".
 *
 * @property int $id
 * @property string $content
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 */
class Template extends \yii\db\ActiveRecord
{
    const STATUS_ACTIVE = 0;//启用
    const STATUS_DISABLE = -1;//禁用

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'template';
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['content'], 'required'],
            [['status', 'created_at', 'updated_at'], 'integer'],
            [['content'], 'string', 'max' => 255],
            ['content', 'validateContent'],
        ];
    }

    public function validateContent($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $flag=false;
            $content=$this->content;
            $len=strlen($content);
            if($content[0]!=='{' || $content[$len-1]!=='}'){
                $flag=true; 
            }else{
                $content=substr($content,1);
                $content=substr($content,0,-1);
                $result=explode("}{",$content);
                foreach($result as $key => $value){
                    if($value!='段落' && $value!='图片'){
                        $flag=true;
                        break;
                    }
                }
            }
            if($flag){
                $this->addError($attribute, '请检查内容格式。');
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'content' => '内容',
            'status' => '状态',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
        ];
    }

    public static function all()
    {
        return yii\helpers\ArrayHelper::map(self::find()->where(['status'=>0])->all(),'id','content');
    }
}
