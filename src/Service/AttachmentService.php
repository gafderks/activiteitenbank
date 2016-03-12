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
    private $allowedMimetypes = [
        'application/msword', // .doc, .dot
        'application/octet-stream',
        'text/pdf', // .pdf
        'application/pdf', // .pdf
        'image/gif', // .gif
        'image/jpg', // .jpg
        'image/png', // .png
        'image/jpeg', // .jpg
        'image/bmp', // .bmp
        'application/vnd.ms-excel', // .xls, .xlt, .xla
        'application/vnd.ms-powerpoint', // .ppt, .pot, .pps, .ppa
        'plain/text', // .txt
        'text/plain', // .txt
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', // .xlsx
        'application/vnd.openxmlformats-officedocument.presentationml.presentation', // .pptx
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document', // .docx
        'application/zip', // .zip
    ];
    private $permission = 0644;
    private $maxSize = '20M';

    /**
     * AttachmentService constructor.
     */
    public function __construct($container) {
        parent::__construct($container);
        $this->storage = new \Upload\Storage\FileSystem($this->container['config']['uploadsDirectory']);
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
     * @return \Model\Activity\Attachment attachment object for the uploaded attachment
     * @throws \Exception if an error occurs during uploading
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

        try {
            $file->upload();
        } catch (\Exception $e) {
            throw new \Exception(implode(',', $file->getErrors()));
        }

        // set file permissions
        chmod($this->container['config']['uploadsDirectory'] . '/' . $file->getNameWithExtension(), $this->permission);

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
        return $this->container['mapper_activity'];
    }

}