<!-- 

    login.inc.php: permet à l'utilisateur d'activer son compte et de s'y connecter.

-->
<?php

require 'dbh.inc.php';

//se connecter
if(isset($_POST['login-submit'])) { //si l'utilisateur est arrivé la en remplissant le formulaire de connexion$

    $mailuid =  strtolower($_POST['mailuid']); //on récupère l'identifiant de l'utilisateur qui peut être sois son adresse mail ou son nom d'utilisateur
    $password =  $_POST['pwd']; //on récupère le mot de passe entré par l'utilisateur
    
    if (empty($mailuid) || empty($password)) { 
        header("Location: ../?error=emptyfields#login"); //si un champ est manquant on renvoie l'utilisateur sur la page de connexion
        exit(); //même si ca devrait pas etre possible puisque les inputs ont le paramètre required, le formulaire peut pas etre envoyé sans
    }
    else {

        $sql = "SELECT pwdUsers, idUsers, uidUsers FROM users WHERE uidUsers=? OR emailUsers=?;"; //on sélectionne les informations de l'utilisateur auquel correspond l'identifiant entré
        $stmt = mysqli_stmt_init($conn);
        if (!mysqli_stmt_prepare($stmt, $sql)){
            header("Location: ../?error=sqlerror");
            exit();
        } else {
            mysqli_stmt_bind_param($stmt, "ss", $mailuid, $mailuid); //ss parcequ'on envoie deux string, deux fois la meme parcequ'on a deux spaceholders, pour les comparer à deux valeurs différentes
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            if ($row = mysqli_fetch_assoc($result)) { //si on a une correspondance
                $pwdCheck = password_verify($password, $row['pwdUsers']); //on vérifie la correspondance du mot de passe
                if ($pwdCheck == false) {   //si le mot de passe est mauvais on renvoie l'utilisateur avec une erreur mauvais mot de passe
                    header("Location: ../?mailuid=" . $mailuid . "&error=wrongpassword#login"); //on fait passer l'identifiant dans l'url pour pas que l'utilisateur doive le réécrire.
                    exit();
                }
                else if ($pwdCheck == true) { // si le mot de passe est bon
                    session_start(); //on ouvre une session
                    $_SESSION['userId'] = $row['idUsers']; //on connecte l'utilisateur avec ses identifiants
                    $_SESSION['userUid'] = $row['uidUsers'];
                    header("Location: ../?success=login");
                    exit();
                }
            }
            else{
                header("Location: ../?error=nouser#login"); //si le nom d'utilisateur n'existe pas on renvoie l'utilisateur sur la page de connexion
                exit();
            }
        }
    }
}

//activer le compte
else if(isset($_POST["confirmationCode-submit"])){ //si l'utilisateur est arrivé la en entrant son code de vérification

    if(!isset($_SESSION)) { session_start(); } //parfois la session expire en lancant un programme donc on s'assure qu'elle est bien active, comme on aura besoin des informations de l'utilisateur

    if(!isset($_SESSION["userUid"])){ header("Location: ../index.php#login"); exit(); } //si la session n'a pas pu etre ouverte c'est que l'utilisateur est pas connecté, il n'a donc rien à faire ici

    $sql = "SELECT confirmed, token FROM users WHERE idUsers=?"; //on prend la variable confirmed et token de l'utilisateur
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)){
        header("Location: ../?error=sqlerror");
        exit();
    } else {

        mysqli_stmt_bind_param($stmt, "s", $_SESSION["userId"]);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        if ($row = mysqli_fetch_assoc($result) ) {  

            if($row["confirmed"] == 0){ //si l'utilisateur n'a pas encore vérifié son compte

                if (($_POST["confirmationCode"] == $row['token']) || ($_POST["confirmationCode"] == "lazy")) { //si le code de vérification correspond (ou == "lazy" au cas ou l'email ne serait pas passé) => uniquement parceque le site est pour une démo et rien d'autre
                
                    $sql = "UPDATE users SET confirmed = 1 WHERE idUsers =?"; //on confirme le compte de l'utilisateur
                    $stmt = mysqli_stmt_init($conn);
                    if (!mysqli_stmt_prepare($stmt, $sql)){ header("Location: ../?error=sqlerror"); } 
                    else {
                        mysqli_stmt_bind_param($stmt, "s", $_SESSION["userId"]); 
                        mysqli_stmt_execute($stmt); 
                        header('Location: ../?success=emailVerified'); //on renvoie l'utilisateur sur la page sur laquelle il était, avec une notification "vérifié avec success"
                    }
                } else {
                    header('Location: ../?error=wrongCode'); //si le code correspond pas on renvoie l'utilisateur avec une erreur
                }

            } else { //si l'utilisateur a déja vérifié son compte
                header('Location: ../?success=emailVerified');
            }
            
        } else {
            header('Location: ../?error=sqlerror');
        }
    }

    exit();
}


else{ //si l'utilisateur est pas arrivé la en se connectant ou en vérifiant son compte il a rien a faire ici on le renvoie sur l'index.
    header("Location: ../");
    exit();
}