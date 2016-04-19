<?php

namespace backend\controllers;

use backend\components\SearchModel;
use backend\forms\UserForm;
use common\controller\BaseController;
use common\activeRecords\AuthAssignment;
use common\activeRecords\AuthItem;
use common\activeRecords\User;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\rbac\Item;
use yii\web\NotFoundHttpException;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends BaseController
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SearchModel(
            [
                'model' => ['class'=>'\common\activeRecords\User'],
                'partialMatchAttributes' => [
                    'username',
                    'email',
                ],
            ]
        );

        $dataProvider = $searchModel->search($_GET);

        return $this->render(
            'index',
            [
                'dataProvider' => $dataProvider,
                'searchModel' => $searchModel,
            ]
        );
    }

    public function actionCreate()
    {
        $form = new UserForm(['scenario'=>'register']);
        if($form->load($_POST) && $form->validate()){
            $user = new User();
            $user->generateAuthKey();
            $user->setPassword($form->password);
            $user->email = $form->email;
            $user->username = $form->username;
            $returnUrl = \Yii::$app->request->get('returnUrl', ['index']);
            if($user->save()){
                foreach($form->authList as $auth){
                    $role =  \Yii::$app->authManager->getRole($auth);
                    \Yii::$app->authManager->assign($role,$user->id);
                }
                return $this->redirect(
                    Url::toRoute(
                        [
                            'view',
                            'id' => $user->id,
                            'returnUrl' => $returnUrl
                        ]
                    )
                );
            }
            else{
                $form->addErrors($user->getErrors());
            }
        }
        $authList = AuthItem::find()->where('type = '.AuthItem::TYPE_ROLE)->select(['name','description'])->asArray()->all();
        $authList = ArrayHelper::map($authList,'name','description');
        return $this->render('create',['model'=>$form,'authList'=>$authList]);
    }

    public function actionUpdate($id)
    {
        $form = new UserForm(['scenario'=>'update','isNewRecord'=>false]);
        $user = $this->findModel($id);
        if($form->load($_POST) && $form->validate()){
            if($form->password){
                $user->generateAuthKey();
                $user->setPassword($form->password);
            }
            $user->email = $form->email;
            $returnUrl = \Yii::$app->request->get('returnUrl', ['index']);
            if($user->save()){
                $assignments = \Yii::$app->authManager->getAssignments($user->id);
                foreach($assignments as $assignment){
                    $key = array_search($assignment->roleName, $form->authList);
                    if($key === false)
                        \Yii::$app->authManager->revoke($assignment->roleName,$user->id);
                    else
                        unset($form->authList[$key]);
                }
                foreach($form->authList as $auth){
                    $role =  \Yii::$app->authManager->getRole($auth);
                    \Yii::$app->authManager->assign($role,$user->id);
                }
                return $this->redirect(
                    Url::toRoute(
                        [
                            'view',
                            'id' => $user->id,
                            'returnUrl' => $returnUrl
                        ]
                    )
                );
            }
            else{
                $form->addErrors($user->getErrors());
            }
        }
        $form->setAttributes($user->getAttributes());
        $authList = AuthItem::find()->where('type = '.AuthItem::TYPE_ROLE)->select(['name','description'])->asArray()->all();
        $authList = ArrayHelper::map($authList,'name','description');
        $hasAuthList = AuthAssignment::find()
            ->join('left join','{{%auth_item}} as item','item.name = {{%auth_assignment}}.item_name')
            ->select(['item.description','item_name','user_id'])
            ->where('user_id = :id',[':id'=>$id])
            ->asArray()
            ->all();
        $form->authList = ArrayHelper::getColumn($hasAuthList,'item_name');
        return $this->render('update',['model'=>$form,'authList'=>$authList]);
    }


    public function actionView($id)
    {
        $user = $this->findModel($id);

        return $this->render('view',['model'=>$user]);

    }

    /**
    * Updates or create an User model.
    * If update is successful, the browser will be redirected to the 'view' page.
    * @param integer $id
    * @return mixed
    */
//    public function actionUpdate($id = null)
//    {
//        if (is_null($id)) {
//            $model = new User(['scenario' => 'adminSignup']);
//        } else {
//            $model = $this->findModel($id);
//            $model->scenario = 'admin';
//        }
//        $assignments = \Yii::$app->authManager->getAssignments($id);
//        if ($model->load(\Yii::$app->request->post()) && $model->validate()) {
//            $model->save();
//            $postAssignments = \Yii::$app->request->post('AuthAssignment', []);
//            $errors = [];
//            foreach ($assignments as $assignment) {
//                $key = array_search($assignment->roleName, $postAssignments);
//                if ($key === false) {
//                    \Yii::$app->authManager->revoke(new Item(['name' => $assignment->roleName]), $model->id);
//                } else {
//                    unset($postAssignments[$key]);
//                }
//            }
//            foreach ($postAssignments as $assignment) {
//                try {
//                    \Yii::$app->authManager->assign(new Item(['name' => $assignment]), $model->id);
//                } catch (\Exception $e) {
//                    $errors[] = 'Cannot assign "'.$assignment.'" to user';
//                }
//            }
//            if (count($errors) > 0) {
//                \Yii::$app->getSession()->setFlash('error', implode('<br />', $errors));
//            }
//            \Yii::$app->session->setFlash('success', \Yii::t('app', 'Record has been saved'));
//            $returnUrl = \Yii::$app->request->get('returnUrl', ['/backend/user/index']);
//            switch (\Yii::$app->request->post('action', 'save')) {
//                case 'next':
//                    return $this->redirect(
//                        [
//                            '/backend/user/update',
//                            'returnUrl' => $returnUrl,
//                        ]
//                    );
//                case 'back':
//                    return $this->redirect($returnUrl);
//                default:
//                    return $this->redirect(
//                        Url::toRoute(
//                            [
//                                '/backend/user/update',
//                                'id' => $model->id,
//                                'returnUrl' => $returnUrl
//                            ]
//                        )
//                    );
//            }
//        } else {
//            return $this->render(
//                'update',
//                [
//                    'model' => $model,
//                    'assignments' => ArrayHelper::map($assignments, 'roleName', 'roleName'),
//                ]
//            );
//        }
//    }

    /**
    * Deletes an existing User model.
    * If deletion is successful, the browser will be redirected to the 'index' page.
    * @param integer $id
    * @return mixed
    */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $model->scenario = 'admin';
        $model->status = User::STATUS_DELETED;
        $model->save(true, ['status']);
        return $this->redirect(['index']);
    }

    public function actionRemoveAll()
    {
        $items = \Yii::$app->request->post('items', []);
        if (!empty($items)) {
            $items = User::find()->where(['in', 'id', $items])->all();
            foreach ($items as $item) {
                $item->scenario = 'admin';
                $item->status = User::STATUS_DELETED;
                $item->save(true, ['status']);
            }
        }

        return $this->redirect(['index']);
    }

    public function actionAddAssignment($id, $userId)
    {
        try {
            \Yii::$app->authManager->assign(new Item(['name' => $id]), $userId);
            $result = [
            'status' => 1,
            'message' => 'Success',
            ];
        } catch (\Exception $e) {
            $result = [
                'status' => 0,
                'message' => 'Cannot add assignment',
            ];
        }
        return Json::encode($result);
    }

    public function actionRemoveAssignment($id, $userId)
    {
        if (\Yii::$app->authManager->revoke(new Item(['name' => $id]), $userId)) {
            $result = [
                'status' => 1,
                'message' => 'Success',
            ];
        } else {
            $result = [
            'status' => 0,
            'message' => 'Cannot remove assignment',
            ];
        }
        return Json::encode($result);
    }

    /**
    * Finds the User model based on its primary key value.
    * If the model is not found, a 404 HTTP exception will be thrown.
    * @param integer $id
    * @return User the loaded model
    * @throws NotFoundHttpException if the model cannot be found
    */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
