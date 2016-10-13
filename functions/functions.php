<?php

function fix_width($string, $width) {
    $string = trim($string);
    $strln = intval(strlen($string));
    switch ($strln) {
        case 0:
            $string = str_repeat(' ', $width);
            break;

        case $strln < $width:
            $string = $string . str_repeat(' ', $width - $strln);
            break;

        case $strln > $width:
            $string = substr($string, 0, $width - $strln);
            break;

        case $strln == $width:
            $string = $string;
            break;
    }

    return $string;
}

function margin($width) {
    return str_repeat(' ', $width);
}

?>
