<?php

namespace View;

/**
 * Class Format
 * Used as a view helper.
 *
 * @package View
 */
class Format
{

    /**
     * Converts a float to a formatted euro value.
     *
     * @param $float float float to convert
     * @return string formatted euro string
     */
    public function float2Euro($float) {
        $euros = floor($float);
        $cents = str_pad(($float-$euros)*100, 2, '0', STR_PAD_LEFT);

        return "&euro;&nbsp;".$euros.".".$cents;
    }

    /**
     * Converts an integer containing the number of minutes to a formatted time.
     *
     * @param $int integer minutes
     * @return string formatted hours and minutes
     */
    public function int2Time($int) {
        $hours   = floor($int/60);
        $minutes = str_pad($int%60, 2, '0', STR_PAD_LEFT);

        return $hours.":".$minutes;
    }

    /**
     * Converts BBCode to HTML using the SBBCodeParser Library.
     *
     * @param $bbcode string input BBCode
     * @return string converted HTML code
     * @throws \SBBCodeParser\Exception_MissingEndTag
     */
    public function bb2Html($bbcode) {
        $parser = new \SBBCodeParser\Node_Container_Document();

        $app = \Slim\Slim::getInstance();

        // locate smileys folder
        $sF = $app->config['componentsUrl'].'/ckeditor/plugins/smiley/images/';

        $parser->add_emoticons([
            ':D' => $sF.'teeth_smile.png',
            ':)' => $sF.'regular_smile.png',
            'o:)' => $sF.'angel_smile.png',
            ':(' => $sF.'sad_smile.png',
            ';)' => $sF.'wink_smile.png',
            ':P' => $sF.'tongue_smile.png',
            ':*)' => $sF.'embarrassed_smile.png',
            ':-o' => $sF.'omg_smile.png',
            ':|' => $sF.'whatchutalkingabout_smile.png',
            '8-)' => $sF.'shades_smile.png',
            ';(' => $sF.'cry_smile.png',
            ':-*' => $sF.'kiss.png'
        ]);

        $html = $parser->parse($bbcode)
            ->detect_links()
            ->detect_emails()
            ->detect_emoticons()
            ->get_html();
        return $html;
    }

}