<?php

namespace Exception;

/**
 * Class RatingNotFoundException
 *
 * @package Exception
 */
class RatingNotFoundException extends \Exception
{
    /**
     * RatingNotFoundException constructor.
     *
     * @param string     $message
     * @param int        $code
     * @param \Exception $previous
     */
    public function __construct($message = 'Rating not found', $code = 3, \Exception $previous = null) {
        parent::__construct($message, $code, $previous);
    }
}