<?php
if(isset($_POST['login-submit'])) {  //la plupart des commentaires pour ce code sont dans signup.inc.php parceque flemme
    require 'dbh.inc.php';
    $mailuid =  strtolower($_POST['mailuid']);
    $password =  $_POST['pwd'];
    
    if (empty($mailuid) || empty($password)) {
        header("Location: ../?error=emptyfields");
        exit();
    }
    else {

        $sql = "SELECT * FROM users WHERE uidUsers=? OR emailUsers=?;";
        $stmt = mysqli_stmt_init($conn);
        if (!mysqli_stmt_prepare($stmt, $sql)){
            header("Location: ../?error=sqlerror");
            exit();
        } else {
            mysqli_stmt_bind_param($stmt, "ss", $mailuid, $mailuid); //on le met deux fois parcequ'il y a deux spaceholders dans $sql 
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            if ($row = mysqli_fetch_assoc($result)) {
                $pwdCheck = password_verify($password, $row['pwdUsers']);
                if ($pwdCheck == false) {  //on met pas if(!$pwdCheck){} parcequ'une erreur peut arriver telle que $pwdCheck est pas une bool 
                    header("Location: ../?error=wrongpassword");
                    exit();
                }
                else if ($pwdCheck == true) {
                    session_start();
                    $_SESSION['userId'] = $row['idUsers'];
                    $_SESSION['userUid'] = $row['uidUsers'];
                    header("Location: ../?success=login");
                    exit();
                }
            }
            else{
                header("Location: ../?error=nouser");
                exit();
            }
        }
    }
}
else{
    header("Location: ../");  //si l'utilisateur a accédé a ce programme avec l'url on le redirige vers la page d'inscription
    exit();
}