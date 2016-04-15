<?php

namespace View\Extension;

use Interop\Container\ContainerInterface;

/**
 * Class TranslateDummyTwigExtension
 *
 * This extension is coupled to a custom POEdit keyword.
 * To be able to translate arrays of strings, a new keyword needed to be defined.
 * If there is a nicer way to solve this problem, that'd be nice.
 *
 * Related problem: @see https://stackoverflow.com/questions/7644302/translate-variables-with-poedit-xgettext
 *
 * @package View
 */
class TranslateDummyTwigExtension extends \Twig_Extension
{

    private $container;

    /**
     * FormatTwigExtension constructor.
     *
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container){
        $this->container = $container;
    }

    /**
     * This method is registered with POEdit to add it to the translation catalog
     */
    public function dummyTranslate($input) {
        return $input;
    }

    /**
     * Returns the name of the extension.
     *
     * @return string
     */
    public function getName() {
        return 'translateDummy';
    }

    /**
     * Returns the filters that are defined in this extension.
     *
     * @return array
     */
    public function getFilters() {
        parent::getFilters();
        return [
            new \Twig_SimpleFilter('dt', [$this, 'dummyTranslate']),
        ];
    }

}