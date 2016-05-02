<?php
namespace backend\controllers;

use backend\forms\LoginForm;
use common\controller\BaseController;
use common\models\Archive;
use common\models\ArchiveGame;
use common\models\ArchiveTag;
use common\models\Category;
use common\models\Dictionary;
use common\models\File;
use common\models\GamePublisher;
use common\models\Publisher;
use common\models\Tag;
use yii\base\Exception;
use yii\filters\VerbFilter;
use Yii;

/**
 * Site controller
 */
class SiteController extends BaseController
{

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['get'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }


    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionError()
    {
        $exception = Yii::$app->errorHandler->exception;
        if(Yii::$app->request->isAjax){
            echo  $exception->getMessage();
            exit;
        }
         else
            return $this->render('error',['exception'=>$exception]);//待修改
    }

    public function actionLogin()
    {
        $this->layout = false;
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionTest()
    {
        set_time_limit(0);
        return $this->render('test');

        //$dicArr = Dictionary::find()->where('parent_id = 1')->select(['id','name'])->createCommand()->queryAll(\PDO::FETCH_KEY_PAIR);
        //$dicArr = array_unique($dicArr);
//        $gameList = ArchiveGame::find()->all();
//        foreach($gameList as $game){
//            $publicer = Publisher::findOne($game->publisher);
//            if($publicer === null)
//                continue;
//            $game->publisher = $publicer->name;
//            if(!$game->save())
//                throw new Exception(current($game->getFirstErrors()));
//        }

    }

}
