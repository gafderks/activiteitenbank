<?php

namespace Exception;

/**
 * Class AttachmentNotFoundException
 *
 * @package Exception
 */
class AttachmentNotFoundException extends \Exception
{
    /**
     * AttachmentNotFoundException constructor.
     *
     * @param string          $message
     * @param int             $code
     * @param \Exception|null $previous
     */
    public function __construct($message = 'Attachment not found', $code = 2, \Exception $previous = null) {
        parent::__construct($message, $code, $previous);
    }
}