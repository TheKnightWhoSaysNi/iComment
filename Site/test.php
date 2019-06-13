<?php

$w = 16;
$h = 9;
$size = 100;

if ($w != $h){

    if($w > $h){
        $w = intval($w / $h * $size);
        $h = $size;

    } else {
        $h = intval($h / $w * $size);
        $w = $size;

    }

} else {
    $w = $h = $size;
}

echo "style='width: $w px; height: $h px;'";
