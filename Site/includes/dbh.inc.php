<?php

$location = "local";

if($location == "local"){
    $servername = "localhost"; //on met le nom du serveur quand on met le code en ligne sinon "localhost" sinon sql201.epizy.com
    $dBUsername = "root"; // root pour XAMPP sinon epiz_23878476
    $dBPassword = ""; // pas de mdp pour XAMPP sinon Xn2Kz5np
    $dBName = "icomment"; //icomment en local et epiz_23878476_icomment sur icomment.epizy.com

} elseif ($location == "online") {
    $servername = "sql201.epizy.com"; 
    $dBUsername = "epiz_23878476"; 
    $dBPassword = "Xn2Kz5np";
    $dBName = "epiz_23878476_icomment";
}

$conn = mysqli_connect($servername, $dBUsername, $dBPassword, $dBName);

if(!$conn){  //$conn = False si la connexion a pas eu lieu correctement
    die("Connection failed: ".mysqli_connect_error());
} else {
    mysqli_set_charset($conn,"utf8");
}