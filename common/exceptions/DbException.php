<?php
/**
 * @author kouga-huang
 * @since 15-12-3 下午12:52
 */
namespace common\exceptions;

use yii\base\UserException;

class DbException extends UserException{

    /**
     * Constructor.
     * @param string $message error message-manage
     * @param integer $code error code
     * @param \Exception $previous The previous exception used for the exception chaining.
     */
    public function __construct($message = null, $code = 0, \Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
 