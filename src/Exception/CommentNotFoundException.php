<?php

namespace Exception;

/**
 * Class CommentNotFoundException
 *
 * @package Exception
 */
class CommentNotFoundException extends \Exception
{
    /**
     * CommentNotFoundException constructor.
     *
     * @param string     $message
     * @param int        $code
     * @param \Exception $previous
     */
    public function __construct($message = 'Comment not found', $code = 4, \Exception $previous = null) {
        parent::__construct($message, $code, $previous);
    }
}