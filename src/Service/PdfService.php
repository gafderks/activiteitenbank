<?php

namespace Service;

/**
 * Class PdfService
 *
 * Provides methods for dealing with PDF files.
 *
 * @package Service
 */
class PdfService extends Service
{

    /**
     * Renders the templates specified in the input to temporary files and returns their paths.
     * Use garbageCollectRenders to clean up the temporary files.
     *
     * @param array $templates array of type key => [template file, parameters[]]
     * @return array original keys with the path as value
     */
    public function renderTemplatesToUrls(array $templates) {
        foreach($templates as $key => &$template) {
            // fetch the render of the template with the specified parameters
            $htmlString = $this->container['view']->fetch($template[0], $template[1]);

            // generate a random filename with a html extension
            $htmlFilePath = null;
            while (true) {
                $htmlFilePath = $this->container['config']['tempDirectory'] . '/' . uniqid("pdf", true) . '.html';
                if (!file_exists($htmlFilePath)) break;
            }

            // write the html string to the temporary file
            $htmlFile = fopen($htmlFilePath, 'w');
            fwrite($htmlFile, $htmlString);
            fclose($htmlFile);

            // update templates array to put path of temporary file at value
            $template = $htmlFilePath;
        }
        return $templates;
    }

    /**
     * Removes the files specified in the array.
     *
     * @param array $renders array of paths to unlink
     */
    public function garbageCollectRenders(array $renders) {
        foreach($renders as $key => $url) {
            unlink($url);
        }
    }

}