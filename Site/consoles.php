<?php

function cover_size($console, $size){ //détermine les dimensions de la miniature d'un jeu.

    $consoles = array(
        "nes" => "20:29",
        "snes" => "7:5",
        "game boy" => "1:1",
        "gba" => "1:1",
        "atari 2600" => "4:5"
    );

    $ratio = $consoles[$console];
    $ratio = explode(":", $ratio);
    $w = $ratio[0];
    $h = $ratio[1];

    if ($w != $h){

        if($w > $h){
            $w = intval($w / $h * $size);
            $h = $size;
        }
        else {
            $h = intval($h / $w * $size);
            $w = $size;
        }

    } else { $w = $h = $size; }

    echo "style='width: " . $w . "px; height: " . $h . "px;'";
}