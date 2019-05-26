<?php
if (isset($_POST['signup-submit'])){ //si l'utilisateur est bien arrivé la en appuyant sur "s'inscrire" et pas en mettant juste l'url
    require 'dbh.inc.php'; //on se connecte à la base de donnée

    $username = strtolower($_POST['uid']);
    $email = strtolower($_POST['mail']);
    $password = $_POST['pwd'];
    $passwordRepeat = $_POST['pwd-repeat'];

    if(empty($username) || empty($email) || empty($password) || empty($passwordRepeat)) { //si l'utilisateur a laissé une valeur vide
        header("Location: ../?error=emptyfields&uid=".$username."&mail=".$email."#signup"); //on renvoie l'utilisateur sur la page en réécrivant les valeurs valides
        exit(); //si il y a une erreur on continue pas le code
    }
    else if (!filter_var($email, FILTER_VALIDATE_EMAIL) && !preg_match("/^[a-z\d_]{2,20}$/i", $username)){ //si ni l'email ni le nom d'utilisateur est invalide \d pour n'importe quel chiffre {taille acceptée} i = on s'en fout des majuscules
        header("Location: ../?error=invalidmailuid"."#signup");
    }
    else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) { //si l'email est invalide
        header("Location: ../?error=invalidmail&uid=".$username."#signup");
        exit(); //si il y a une erreur on continue pas le code
    }
    else if (!preg_match("/^[a-z\d_]{2,20}$/i", $username)){
        header("Location: ../?error=invaliduid&mail=".$email."#signup"); //si le nom d'utilisateur est invalide
        exit(); //si il y a une erreur on continue pas le code
    }
    else if ($password != $passwordRepeat){ //si les mdp correspondent pas
        header("Location: ../?error=passwordcheck&uid=".$username."&mail=".$email."#signup");
    }
    else{
        $sql = "SELECT emailUsers FROM users WHERE emailUsers=?";
        $stmt = mysqli_stmt_init($conn);
        if (!mysqli_stmt_prepare($stmt, $sql)) { //mysli_stmt_prepare($stmt, $sql) = True quand ca marche donc si ca marche pas...
            header("Location: ../?error=sqlerror");
            exit();
        }
        else{
            mysqli_stmt_bind_param($stmt, "s", $email); //on envoie une string (s)   on vérifie si l'uid est pas déja pris
            mysqli_stmt_execute($stmt);
            mysqli_stmt_store_result($stmt);
            $resultCheck = mysqli_stmt_num_rows($stmt); //nombre de correspondances
            if($resultCheck > 0) { // si on a plus que 0 correspondances, l'email est déja pris
                header("Location: ../?error=emailtaken&uid=".$username."#signup");
                exit();
            }
            else{
                $sql = "SELECT uidUsers FROM users WHERE uidUsers=?"; //spaceholder PDO pour empecher une injection sql, prepared statement
                $stmt = mysqli_stmt_init($conn);
                if (!mysqli_stmt_prepare($stmt, $sql)) { //mysli_stmt_prepare($stmt, $sql) = True quand ca marche donc si ca marche pas...
                    header("Location: ../?error=sqlerror"."#signup");
                    exit();
                }
                mysqli_stmt_bind_param($stmt, "s", $username); //à travers la connexion $stmt     on envoie une string (s)     le nom d'utilisateur
                mysqli_stmt_execute($stmt);
                mysqli_stmt_store_result($stmt);
                $resultCheck = mysqli_stmt_num_rows($stmt); //nombre de correspondances
                if($resultCheck > 0) { // si on a plus que 0 correspondances, le nom d'utilisateur est déja pris
                    header("Location: ../?error=usernametaken&mail=".$email."#signup");
                    exit();
                }
            }
            

            $sql = "INSERT INTO users (uidUsers, emailUsers, pwdUsers, token) VALUES (?, ?, ?, ?)";
            $stmt = mysqli_stmt_init($conn);
            if (!mysqli_stmt_prepare($stmt, $sql)) { //mysli_stmt_prepare($stmt, $sql) = True quand ca marche donc si ca marche pas...
                header("Location: ../?error=sqlerror"."#signup");
                exit();
            }
            else{
                $hashedPwd = password_hash($password, PASSWORD_DEFAULT);
                $token = "";
                $characters = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"; //on créé un code de vérification à envoyer par mail
                for ($i = 0; $i < 5; $i++) { 
                    $index = rand(0, strlen($characters) - 1); 
                    $token .= $characters[$index]; 
                }

                mysqli_stmt_bind_param($stmt, "ssss", $username, $email, $hashedPwd, $token); //on envoie 4 strings (ssss)
                mysqli_stmt_execute($stmt);
                if (!mail($email, "Activate your account. ", "Please activate your iComment with this code: ".$token." . " ,"From:no-reply@icomment.epizy.com")){ //mail(to, subject, message, headers)
                    header("Location: ../?error=emailError");
                    exit();
                } 
                header("Location: ../?success=signup#login");
                exit();
            }
        }
    }
    mysqli_stmt_close($stmt);
    mysqli_close($conn); //on ferme la connexion pour économiser des ressources
}
else{
    header("Location: ../");  //si l'utilisateur a accédé a ce programme avec l'url on le redirige vers la page d'inscription
    exit();
}