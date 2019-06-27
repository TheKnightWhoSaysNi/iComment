<?php

$consoles = array(
    "nes" => "20:29",
    "snes" => "7:5",
    "game boy" => "1:1",
    "gameboy advance" => "1:1",
    "gba" => "1:1",
    "atari 2600" => "4:5"
);

function cover_size($console, $size, $additional = Null){ //détermine les dimensions de la miniature d'un jeu.
    $ratio = explode(":", $GLOBALS["consoles"][$console]); //on utilise une variable globale parceque $console est déclaré en dehors de la fonction, on peut pas la déclarer à l'interieur parcequ'on l'utilise dans post.inc.php sans éxecuter la fonction
        $w = $ratio[0];
        $h = $ratio[1];
    if ($w != $h){
        if( ($w > $h) || ($additional == "sameHeight") && ($additional != "sameWidth") ){
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