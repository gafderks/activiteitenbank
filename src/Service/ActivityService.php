<?php

namespace Service;

/**
 * Class ActivityService
 *
 * Provides methods dealing with Activities.
 *
 * @package Service
 */
class ActivityService extends Service
{

    /**
     * Generates a URL-friendly version of a string.
     *
     * @param string $name input string
     * @return string URL-version friendly version of $name
     */
    public function generateSlug($name) {
        $name = strtolower($name); // convert to lower case
        $name = preg_replace('/[^\w ]+/', '', $name); // remove illegal characters
        $name = preg_replace('/\s+/', '-', $name); // replace spaces
        return $name;
    }

    public function sanitizeElaboration($elaboration) {
        // TODO
        return $elaboration;
    }

}