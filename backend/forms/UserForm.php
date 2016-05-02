<?php
/**
 * User: yoyo
 * Date: 15-5-30
 * Time: 下午9:07
 */

namespace backend\forms;


use common\forms\BaseForm;
use common\activeRecords\User;

class UserForm extends BaseForm{

    public $username;

    public $email;

    public $password;

    public $rePassword;

    public $status;

    public $isNewRecord = true;

    public $authList;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['status', 'default', 'value' => User::STATUS_ACTIVE],
            ['status', 'in', 'range' => [User::STATUS_ACTIVE, User::STATUS_DELETED]],
            ['email', 'email'],
            ['username','unique','targetClass' => '\common\activeRecords\User','message'=>'系统中已存在此用户名','on'=>'register'],
            [['username','password','rePassword'],'required','on'=>'register'],
            ['username','match','pattern'=>'/^[a-z][0-9a-z]{4,10}$/'],
            ['password', 'match','pattern'=>'/^(?=.*[0-9])(?=.*[a-zA-Z])(?=.*[!@*#$]).{6,12}$/','message'=>'密码必须包含数子字母和!@#$特殊字符'],
            ['rePassword','compare','compareAttribute'=>'password','message'=>'两次输入的密码不一致'],
        ];
    }

    public function scenarios()
    {
        return [
            'login' => ['username', 'password'],
            'search'=>['username','email','created_at','status'],
            'register'=>['username','password','rePassword','email','authList'],
            'update'=>['username','password','rePassword','email','authList'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'username' => '用户名',
            'password' => '密码',
            'rePassword' => '确认密码',
            'email' => '邮箱',
            'authList' =>'分配权限'
        ];
    }

} 