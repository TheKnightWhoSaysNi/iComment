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
        } else {
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

                //on connecte automatiquement l'utilisateur puisqu'on a 

                session_start();
                $_SESSION['userId'] = $row['idUsers'];
                $_SESSION['userUid'] = $row['uidUsers'];
                header("Location: ../?success=login");
                exit();
                
                //on envoie un mot de passe pour vérifier l'utilisateur
                $message = '

                    <!DOCTYPE html>
                    <html>
                        <meta charset="utf-8">
                        <meta http-equiv="X-UA-Compatible" content="IE=edge">
                        <title>Confirm your Gametop account!</title>
                        <meta name="viewport" content="width=device-width, initial-scale=1">
                        <style>

                            body * {
                                display: flex;
                                flex-direction: column;
                                font-family: Roboto, sans-serif;
                                color: rgb(83, 83, 83);
                            }

                            section{
                                width: 400px;
                                height: 500px;
                                margin: auto;
                                text-align: center;
                                background-color: #f3f3f3;
                                border: solid 1px #8c7ae6;
                                border-radius: 4px;
                            }
                        
                            h1{
                                font-weight: 300;
                            }

                            a{
                                height: 50px;
                                width: 100px;
                                background-color: #ccc2ff;
                                border: solid 1px #8c7ae6;
                                border-radius: 4px;
                                text-decoration: none;
                                margin: 0 auto;
                                transition: .1s;
                            }
                            a:hover{
                                background-color: white;
                            }
                            a p{
                                margin: auto;
                            }
                            h2{
                                font-size: 15px;
                            }
                            h2 span{
                                color: rgb(218, 67, 67);
                            }
                        
                        </style>
                    <body>

                        <section>

                            <h1>You have successfully created your Gametop account!</h1>

                            <a href="http://gametop.epizy.com?confirmEmail=' . $token . '"><p>Verify account</p></a>

                            <h2>If the button doesn\'t work use this code: <span>' . $token . '</span></h2>

                        </section>
                        
                    </body>
                    </html>

                    ';

                if (!mail($email, "Activate your account. ", $message ,"From:no-reply@gametop.epizy.com\r\n" . "Content-Type: text/html; charset=ISO-8859-1\r\n")){ //mail(to, subject, message, headers)
                    header("Location: ../?error=emailError");
                    exit();
                } else {
                    header("Location: ../?success=signup#login");
                }
            }
        }
    }
    mysqli_stmt_close($stmt);
    mysqli_close($conn); //on ferme la connexion pour économiser des ressources
}
else{
    header("Location: ../");  //si l'utilisateur a accédé a ce programme avec l'url on le redirige vers la page d'inscription
}

exit();