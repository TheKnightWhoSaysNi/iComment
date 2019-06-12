<?php //on utilisera un php procédural parceque je comprend pas l'object oriented

if(isset($_POST['post-submit'])) {  //la plupart des commentaires pour ce code sont dans signup.inc.php parceque flemme   isset($_POST['post-submit'])
    require 'dbh.inc.php';
    session_start();

    $name = $_GET["name"];
    if(isset($_GET["comment"])){
        $comment = $_GET["comment"];
    }   

    $imgPath = "../img/";
    $gamePath = "../games/";
    $imgTarget = $imgPath . basename($_FILES["cover"]["name"]);
    $gameTarget = $gamePath . $name;

} else { header("Location: ../");}