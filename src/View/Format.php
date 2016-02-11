<?php

namespace View;

class Format
{

    public function float2Euro($float) {
        $euros = floor($float);
        $cents = str_pad(($float-$euros)*100, 2, '0', STR_PAD_LEFT);

        return "&euro;&nbsp;".$euros.".".$cents;
    }

    public function int2Time($int) {
        $hours   = floor($int/60);
        $minutes = str_pad($int%60, 2, '0', STR_PAD_LEFT);

        return $hours.":".$minutes;
    }

}