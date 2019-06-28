<!-- 

dbh.inc.php: database handler, on s'en sers pour établir une connexion entre le programme en php et la base de données SQL
Comme on en a très souvent besoin on créé un unique fichier que l'on importe partout après

Les identifiants sont différents selon si on est en local avec xampp ou en ligne, il faut donc les changer en fonction

-->

<?php

if(in_array($_SERVER['REMOTE_ADDR'], array('127.0.0.1','::1'))){ // Si c'est effectué en local
    $servername = "localhost"; //on met le nom du serveur quand on met le code en ligne sinon "localhost" sinon sql201.epizy.com
    $dBUsername = "root"; // root pour XAMPP sinon epiz_23878476
    $dBPassword = ""; // pas de mdp pour XAMPP sinon Xn2Kz5np
    $dBName = "icomment"; //icomment en local et epiz_23878476_icomment sur icomment.epizy.com

} else {
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