<?php
/**
 * This file is used to generate php files from twig templates such that they can be processed by xgettext.
 * Use a CLI for running this file.
 *
 * @see http://twig.sensiolabs.org/doc/extensions/i18n.html#extracting-template-strings
 */

// load required files
require_once "vendor/autoload.php";
$config = include("config.php");

// create autoloader
function __autoload($className) {
    include __DIR__ . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . str_replace('\\', '/', $className) . '.php';
}
spl_autoload_register('__autoload');

\Slim\Slim::registerAutoloader();

$tplDir = dirname(__FILE__)."/view/{$config['template']}/templates";
$tmpDir = dirname(__FILE__)."/view/{$config['template']}/cache";
$loader = new \Twig_Loader_Filesystem($tplDir);

// force auto-reload to always have the latest version of the template
$twig = new \Twig_Environment($loader, array(
    'cache' => $tmpDir,
    'auto_reload' => true
));
$twig->addExtension(new \Twig_Extensions_Extension_I18n());
$twig->addExtension(new \Slim\Views\TwigExtension());
// register custom filters
$int2time = new Twig_SimpleFilter('int2time', ['\View\Format', 'int2Time']);
$float2euro = new Twig_SimpleFilter('float2euro', ['\View\Format', 'float2Euro']);
$bb2html = new Twig_SimpleFilter('bb2Html', ['\View\Format', 'bb2Html']);
$twig->addFilter($int2time);
$twig->addFilter($float2euro);
$twig->addFilter($bb2html);
// configure Twig the way you want

// iterate over all your templates
foreach (new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($tplDir), \RecursiveIteratorIterator::LEAVES_ONLY) as $file)
{
    // force compilation
    if ($file->isFile()) {
        $twig->loadTemplate(str_replace($tplDir.DIRECTORY_SEPARATOR, '', $file));
    }
}