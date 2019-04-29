<?php

$servername = "localhost"; //on met le nom du serveur quand on met le code en ligne sinon "localhost"
$dBUsername = "root"; //pour XAMPP
$dBPassword = "";
$dBName = "loginsystem1";

$conn = mysqli_connect($servername, $dBUsername, $dBPassword, $dBName);

if(!$conn){  //$conn = False si la connexion a pas eu lieu correctement
    die("Connection failed: ".mysqli_connect_error());
}