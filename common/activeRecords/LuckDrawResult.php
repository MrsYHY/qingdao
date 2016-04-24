<?php

namespace common\activeRecords;

use Yii;
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "luck_draw_result".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $result
 * @property integer $activity_id
 * @property integer $prize_id
 * @property integer $prize_level
 * @property integer $device_id
 * @property string $created_at
 * @property integer $is_award
 * @property integer $win_code
 */
class LuckDrawResult extends \common\activeRecords\BaseActiveRecord
{
    const AWARD = 0;//已兑奖
    const NOT_AWARD = 1;//没有兑奖

    const ZHONG = 1;//中奖
    const NOT_ZHONG = 0;//未中奖
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'luck_draw_result';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['result', 'activity_id', 'prize_id', 'prize_level'], 'integer'],
            [['created_at'], 'safe'],
        ];
    }


    /**
     * 判断是否还有未兑奖的记录 只拿出一条
     * @param $userId
     */
    public static function getNotAwardByUserId($userId){
        $result = self::find()->where(['user_id'=>$userId,'is_award'=>self::NOT_AWARD,'result'=>self::ZHONG])->one();
        if ( empty($result)){
            return false;
        }
        return $result;
    }

    public static function findByPk($pk){
        $ra = self::find()->where(['id'=>$pk])->one();
        if(empty($ra)){
            return false;
        }
        return $ra;
    }



    public static function generateQrCode($luckDrawResult,$user_token){
        $renderer = new \BaconQrCode\Renderer\Image\Png();
        $renderer->setHeight(180);
        $renderer->setWidth(180);
        $writer = new \BaconQrCode\Writer($renderer);
        $url = Yii::$app->urlManager->createAbsoluteUrl(['wc-site/comfirm','result_id'=>$luckDrawResult->id,'win_code'=>$luckDrawResult->win_code]);
        $imagePath = ""."user_".$user_token."_result_id_".$luckDrawResult->id.".png";
        $writer->writeFile($url, $imagePath);
        return rtrim(Yii::$app->urlManager->createAbsoluteUrl($imagePath),'.html');

    }

    public static function search($search){
        $query = self::find();
        if (isset($search['activity_id']) && !empty($search['activity_id'])) {
            $query = $query->andWhere(['activity_id'=>$search['activity_id']]);
        }
        if (isset($search ['result']) && $search['result'] !== null && $search['result'] !== '') {
            $query = $query->andWhere(['result'=>$search['result']]);
        }
        if (isset($search ['start_created_at']) && !empty($search['start_created_at'])) {
            $query = $query->andWhere('created_at>="'.$search['start_created_at']);
        }
        if (isset($search ['end_created_at']) && !empty($search['end_created_at'])) {
            $query = $query->andWhere('created_at<="'.$search['end_created_at']);
        }
        if (isset($search ['is_award']) && $search['is_award'] !== null && $search['is_award'] !== '') {
            $query = $query->andWhere(['is_award'=>$search['is_award']]);
        }
        if (isset($search['device_id']) && !empty($search['device_id'])) {
            $query = $query->andWhere(['device_id'=>$search['device_id']]);
        }

        $dataProvider = new ActiveDataProvider([
            'query'=>$query,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);
        return $dataProvider;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'=>'id',
            'activity_id'=>'活动',
            'result'=>'摇奖结果',
            'user_id'=>'用户',
            'prize_id'=>'奖品',
            'prize_level'=>'奖品等级',
            'created_at'=>'摇奖时间',
            'is_award'=>'是否兑奖',
            'win_code'=>'兑奖码',
        ];
    }
}
