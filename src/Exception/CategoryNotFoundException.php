<?php

namespace Exception;

/**
 * Class CategoryNotFoundException
 *
 * @package Exception
 */
class CategoryNotFoundException extends \Exception
{
    /**
     * CategoryNotFoundException constructor.
     *
     * @param string     $message
     * @param int        $code
     * @param \Exception $previous
     */
    public function __construct($message = 'Category not found', $code = 5, \Exception $previous = null) {
        parent::__construct($message, $code, $previous);
    }
}