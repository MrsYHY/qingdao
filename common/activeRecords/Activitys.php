<?php

namespace common\activeRecords;

use Yii;

/**
 * This is the model class for table "activitys".
 *
 * @property integer $id
 * @property string $title
 * @property string $content
 * @property string $start_time
 * @property string $end_time
 */
class Activitys extends \common\activeRecords\BaseActiveRecord
{
    public $id;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'activitys';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id','start_time', 'end_time'], 'safe'],
            [['title'], 'string', 'max' => 255],
            [['content'], 'string', 'max' => 2000],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => '活动主题',
            'content' => '活动内容',
            'start_time' => '活动开始时间',
            'end_time' => '活动结束时间',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPrize()
    {
        return $this->hasMany(Prizes::className(), ['child' => 'id']);
    }

    public static function findByPk($pk){
        $ra = Activitys::find()->where(['id'=>$pk])->one();
        if(empty($ra)){
            return false;
        }
        return $ra;
    }

}
