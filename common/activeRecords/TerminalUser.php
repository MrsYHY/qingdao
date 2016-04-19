<?php

namespace common\activeRecords;

use Yii;

/**
 * This is the model class for table "terminal_user".
 *
 * @property integer $id
 * @property string $terminal_user_token
 * @property integer $role
 * @property integer $sign_in_num
 * @property integer $draw_luck_num
 * @property integer $draw_luck_total
 */
class TerminalUser extends \common\activeRecords\BaseActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'terminal_user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['role', 'sign_in_num', 'draw_luck_num', 'draw_luck_total'], 'integer'],
            [['terminal_user_token'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'terminal_user_token' => 'Terminal User Token',
            'role' => 'Role',
            'sign_in_num' => 'Sign In Num',
            'draw_luck_num' => 'Draw Luck Num',
            'draw_luck_total' => 'Draw Luck Total',
        ];
    }
}
