<?php

namespace Service;

/**
 * Class AttachmentService
 *
 * Provides methods for dealing with Attachments.
 *
 * @package Service
 */
class AttachmentService extends Service
{

    private $storage;
    private $allowedMimetypes = ['application/msword',
        'application/octet-stream',
        'text/pdf',
        'application/pdf',
        'image/gif',
        'image/jpg',
        'image/png',
        'image/jpeg',
        'image/bmp',
        'application/vnd.ms-excel',
        'application/vnd.ms-powerpoint',
        'plain/text',
        'text/plain',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'application/vnd.openxmlformats-officedocument.presentationml.presentation',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
    private $permission = 0644;
    private $maxSize = '20M';

    /**
     * AttachmentService constructor.
     */
    public function __construct() {
        parent::__construct();
        $this->storage = new \Upload\Storage\FileSystem($this->app->config['uploadsDirectory']);
    }

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

    /**
     * Tries to upload the file specified with the key and creates an attachment object if successful.
     *
     * @param string                   $key key from the request that the file is located in
     * @param \Model\Activity\Activity $activity activity to associate the attachment with
     * @return \Model\Activity\Attachment attachment object for the uploaded attachment
     * @throws \Exception
     */
    public function uploadAttachment($key) {
        $file = new \Upload\File($key, $this->storage);
        $originalName = $file->getNameWithExtension();

        // give the file a new name to avoid clashes
        $newFilename = sha1(date('Y-m-d H:i:s:u').mt_rand());
        $file->setName($newFilename);

        $file->addValidations([
            new \Upload\Validation\Mimetype($this->allowedMimetypes),
            new \Upload\Validation\Size($this->maxSize)
        ]);

        $data = [
            'name'       => $file->getNameWithExtension(),
            'extension'  => $file->getExtension(),
            'mime'       => $file->getMimetype(),
            'size'       => $file->getSize(),
        ];

        try {
            $file->upload();
        } catch (\Exception $e) {
            throw new \Exception(implode(',', $file->getErrors()));
        }

        // set file permissions
        chmod($this->app->config['uploadsDirectory'] . '/' . $file->getNameWithExtension(), $this->permission);

        // create attachment object
        $attachment = new \Model\Activity\Attachment();
        $attachment->setName($originalName);
        $attachment->setLocation($file->getNameWithExtension());

        return $attachment;
    }

    /**
     * Get the activity mapper.
     *
     * @return \Mapper\Activity
     */
    protected function getActivityMapper() {
        return $this->app->mapper_activity;
    }

}