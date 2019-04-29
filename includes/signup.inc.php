<?php
if (isset($_POST['signup-submit'])){ //si l'utilisateur est bien arrivé la en appuyant sur "s'inscrire" et pas en mettant juste l'url
    require 'dbh.inc.php'; //on se connecte à la base de donnée

    $username = $_POST['uid'];
    $email = $_POST['mail'];
    $password = $_POST['pwd'];
    $passwordRepeat = $_POST['pwd-repeat'];

    if(empty($username) || empty($email) || empty($password) || empty($passwordRepeat)) { //si l'utilisateur a laissé une valeur vide
        header("Location: ../signup.php?error=emptyfields&uid=".$username."&mail=".$email); //on renvoie l'utilisateur sur la page en réécrivant les valeurs valides
        exit(); //si il y a une erreur on continue pas le code
    }
    else if (!filter_var($email, FILTER_VALIDATE_EMAIL) && !preg_match("/^[a-zA-Z0-9]*/", $username)){ //si ni l'email ni le nom d'utilisateur est invalide
        header("Location: ../signup.php?error=invalidmailuid");
    }
    else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) { //si l'email est invalide
        header("Location: ../signup.php?error=invalidmail&uid=".$username);
        exit(); //si il y a une erreur on continue pas le code
    }
    else if (!preg_match("/^[a-zA-Z0-9]*/", $username)){
        header("Location: ../signup.php?error=invaliduid&mail=".$email); //si le nom d'utilisateur est invalide
        exit(); //si il y a une erreur on continue pas le code
    }
    else if ($password != $passwordRepeat){ //si les mdp correspondent pas
        header("Location: ../signup.php?error=passwordcheck&uid=".$username."&mail=".$email);
    }
    else{
        $sql = "SELECT uidUsers FROM users WHERE uidUsers=?"; //si on envoie directement $username on pourrait mettre des commandes sql dans "utilisateur" et corrompre la base de données du coup on met un "spaceholder" ==> (?)
        $stmt = mysqli_stmt_init($conn);
        if (!mysqli_stmt_prepare($stmt, $sql)) { //mysli_stmt_prepare($stmt, $sql) = True quand ca marche donc si ca marche pas...
            header("Location: ../signup.php?error=sqlerror");
            exit();
        }
        else{
            mysqli_stmt_bind_param($stmt, "s", $username); //on envoie une string (s)
            mysqli_stmt_execute($stmt);
            mysqli_stmt_store_result($stmt);
            $resultCheck = mysqli_stmt_num_rows($stmt); //nombre de correspondances
            if($resultCheck > 0) { // si on a plus que 0 correspondances, le nom d'utilisateur est déja pris
                header("Location: ../signup.php?error=usernametaken&mail=".$email);
                exit();
            }
            else{ //normalement il y a plus d'erreurs du coup
                $sql = "INSERT INTO users (uidUsers, emailUsers, pwdUsers) VALUES (?, ?, ?)";
                $stmt = mysqli_stmt_init($conn);
                if (!mysqli_stmt_prepare($stmt, $sql)) { //mysli_stmt_prepare($stmt, $sql) = True quand ca marche donc si ca marche pas...
                    header("Location: ../signup.php?error=sqlerror");
                    exit();
                }
                else{
                    $hashedPwd = password_hash($password, PASSWORD_DEFAULT);
                    mysqli_stmt_bind_param($stmt, "sss", $username, $email, $hashedPwd); //on envoie trois strings (sss)
                    mysqli_stmt_execute($stmt);
                    header("Location: ../signup.php?signup=success");
                    exit();
                }
            }
        }
    }
    mysqli_stmt_close($stmt);
    mysqli_close($conn); //on ferme la connexion pour économiser des ressources
}
else{
    header("Location: ../signup.php");  //si l'utilisateur a accédé a ce programme avec l'url on le redirige vers la page d'inscription
    exit();
}