<?php

namespace common\activeRecords;

use Yii;

/**
 * This is the model class for table "wechat_data".
 *
 * @property integer $id
 * @property string $keyword
 * @property string $wechat_data
 */
class WechatData extends \common\activeRecords\BaseActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'wechat_data';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'keyword'], 'required'],
            [['id'], 'integer'],
            [['keyword'], 'string', 'max' => 255],
            [['wechat_data'], 'string', 'max' => 2000],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'keyword' => 'Keyword',
            'wechat_data' => 'Wechat Data',
        ];
    }


    public static function getByKeyword($keyword){
        $datas = self::find()->where(['keyword'=>$keyword])->all();
        if (empty($datas)){
            return false;
        }
        return $datas;
    }

    /**
     * @param array $search key必须和数据表字段对应
     */
    public static function serarch($search=[]){
        $wechatData = self::find()->where($search)->all();
        if (empty($wechatData)) {
            return false;
        }
        return $wechatData;
    }

    /**
     * 该方法很危险 调用的时候一定要指明条件 不然将删除整张表
     */
    public static function deleteBySome($search = []){
        self::deleteAll($search);
    }

    /**
     * @param $search
     */
    public static function addData($search){
        $data = new WechatData();
        $data->keyword = isset($search['keyword']) ? $search ['keyword'] : '';
        $data->wechat_data = isset($search['wechat_data']) ? serialize('wechat_data') : '';
        if ($data->save()) {
            return true;
        }
        return $data->getErrors();
    }
}
