<?php
/**
 * 
 * @author: shenchao
 * @since: 2016-04-22
 */

namespace backend\forms;


use common\forms\BaseForm;

class WeChatForm extends BaseForm{

    public $user_token;
    public $activity_id;
    public $device_id;
    public $open_id;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_token','activity_id','device_id','open_id'], 'required','on'=>'luck_draw_request'],
            [['user_token','activity_id','device_id'],'required','on'=>'luck_draw_page'],
        ];
    }

} 