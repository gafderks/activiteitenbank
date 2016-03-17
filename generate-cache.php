<?php
/**
 * This file is used to generate php files from twig templates such that they can be processed by xgettext.
 * Use a CLI for running this file.
 *
 * @see http://twig.sensiolabs.org/doc/extensions/i18n.html#extracting-template-strings
 */

/********************************************************************************
 * Bootstrap
 *******************************************************************************/

include_once "bootstrap.php";

/********************************************************************************
 * Iterate over all templates
 *******************************************************************************/

$templatesDirectory = dirname(__FILE__)."/view/{$config['template']}/templates";

foreach (new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($templatesDirectory),
    \RecursiveIteratorIterator::LEAVES_ONLY) as $file)
{
    // force compilation
    if ($file->isFile()) {
        $container['view']->getEnvironment()->loadTemplate(
            str_replace($templatesDirectory.DIRECTORY_SEPARATOR, '', $file));
    }
}