<?php

namespace Exception;

/**
 * Class ActivityNotFoundException
 *
 * @package Exception
 */
class ActivityNotFoundException extends \Exception
{
    /**
     * ActivityNotFoundException constructor.
     *
     * @param string     $message
     * @param int        $code
     * @param \Exception $previous
     */
    public function __construct($message = 'Activity not found', $code = 1, \Exception $previous = null) {
        parent::__construct($message, $code, $previous);
    }
}