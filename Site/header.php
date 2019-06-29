<!-- crtl + / pour commenter une ligne

    header.php: header de la page, ainsi que les éléments communs à chaque page:
        
        -la liste des notifications
        -les popups de connexion/inscription/verification du mail
        -le haut de la page avec le logo, la barre de navigation et de recherche

-->

<?php



    include 'includes/dbh.inc.php';
    include "consoles.php";

    if((!isset($_SESSION)) && (!isset($_POST["newUsername"])) ){ session_start(); }

    //on vérifie que l'utilisateur a bien confirmé son email
    else {
        $sql = "SELECT confirmed FROM users WHERE idUsers=?;";
        $stmt = mysqli_stmt_init($conn);
        if (!mysqli_stmt_prepare($stmt, $sql)){
            header("Location: ?error=sqlerror");
            exit();
        } else {
            mysqli_stmt_bind_param($stmt, "s", $_SESSION["userId"]);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            if (($rowEmail = mysqli_fetch_assoc($result)) && ($rowEmail["confirmed"] != "1") && (!isset($_GET["confirmEmail"])) ) { // && (!isset($_POST["newUsername"])
                header("Location: index.php?confirmEmail="); //si l'utilisateur a pas un compte vérifié on le redirige vers un url qui active la popup de verification
                exit();
            }
        }
    }

    // on récupère les variables de l'url
    if(isset($_GET['uid'])){
        $uid = $_GET['uid'];
    } else {$uid = '';}
    
    if(isset($_GET['mail'])){
        $mail = $_GET['mail'];
    } else {$mail = '';}

    if(isset($_GET['mailuid'])){
        $mailuid = $_GET['mailuid'];
    } else { $mailuid = ""; }
    
    if(isset($_GET['error'])){
        $error = $_GET['error'];
    } else {$error = '';}

    if(isset($_GET['confirmEmail'])){
        $confirmationCode = $_GET['confirmEmail'];
    } else { $confirmationCode = ""; }
    

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title style="display: none"><?php echo $title; ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" media="screen" href="main.css" />
    <script src="main.js"></script>
    <meta name="theme-color" content="rgb(233, 233, 233)" /> <!-- couleur du haut de l'onglet sur chrome sur mobile -->
</head>
<body>

    <header id="header" class="glass">

        <div>
            
            <a href="index.php" id="logo">Gametop</a>

            <ul class="navBar">

                <!-- Pas de balise <a href=""> </a>  parceque c'est plus simple de centrer le texte en ayant une grande hitbox -->
                <li onclick="location.href='games.php';" id="aboutBtn">
                    <p>Games</p>
                </li>
                <?php
                if (isset($_SESSION['userId'])){ ?> <!-- le boutton post s'affique que si l'utilisateur est connecté -->
                    <li onclick="location.href='post.php';">
                        <p>Post</p>
                    </li> 
                <?php
                } ?>

                <?php

                    if(strpos($_SERVER['REQUEST_URI'], 'account') !== false){ ?> <!-- si on est sur la page account on le bouton "account" est remplacé par un bouton "logout" -->
                        <li onclick="location.href='includes/logout.inc.php';">
                            <p>Log out</p>
                        </li> <?php
                        
                    } elseif (isset($_SESSION['userId'])) {    //comme il y a plein de guillemets et de balises c'est plus simple d'ouvrir et fermer du php autour de l'html plutot que de mettre l'html dans le php avec echo?>
                        <li onclick="location.href='account.php';">
                            <p>Account</p>
                        </li> <?php
                    } 
                    else { ?>
                        <li>
                            <a href="#login" id="logBtn"> <!-- si on est pas connecté -->
                                <p>Login / Sign Up</p>
                            </a>
                        </li><?php
                    }
                ?>
                
            </ul>

            <h2 id='username'> <?php if(isset($_SESSION['userUid'])){ echo "@" . $_SESSION['userUid'];}?></h2> <!-- si on est connecté on afiche le nom de l'utilisateur -->

            <!-- barre de recherche -->
            <form id="searchBar" action="search.php" method="post" name="search" autocomplete="off">
                <input type="text" name="search" id="textInput" placeholder="Search for a game" onfocusin="if(window.innerWidth < 1050){openSearch()}" onfocusout="if(window.innerWidth < 1050){closeSearch()}">
                <button type="submit" name="submit-search" id="searchButton">⌕</button>
            </form>

        </div>

    </header>

    <!-- popup de connexion -->
    <div id="login" class="log glass">
        <a href="" class="closeBtn"><p>x</p></a>
        <form class='login' action='includes/login.inc.php' method='post'>
            <input type='text' name='mailuid' placeholder='Username/Email...' value="<?php echo $mailuid ?>" required>
            <input type='password' name='pwd' placeholder='Password...' required>
            <div>
                <a href="#signup">Click here to sign up</a>
                <button type='submit' name='login-submit'><p> Login </p></button>
            </div>
        </form>
    </div>
    
    <!-- popup d'inscription -->
    <div id="signup" class="log glass">
        <a href="" class="closeBtn"><p>x</p></a>
        <form class="signup" action="includes/signup.inc.php" method="post">
            <input type="text" name="uid" placeholder="Username..." value="<?php echo $uid ?>" required/>
            <input type="text" name="mail" placeholder="Email..." value="<?php echo $mail ?>" required/>
            <input type="password" name="pwd" placeholder="Password..." required>
            <input type="password" name="pwd-repeat" placeholder="Repeat Password..." required>
            <div>
                <a href="#login">Click here to login</a>
                <button type="submit" name="signup-submit"><p> Sign up </p></button>
            </div>
        </form>

    </div>

    <!-- popup de confirmation du compte -->
    <div id="confirmEmail" class="log glass">
        <a href="" class="closeBtn"><p>x</p></a>
        <h3>Activate your account with the code you have received by email: </h3>

        <form method="post" action="includes/login.inc.php">
            <input type="text" name="confirmationCode" placeholder="Confirmation code" value="<?php echo $confirmationCode ?>">
            <div>
                <h4>Check your spam inbox or type "lazy" to skip</h4>
                <button type="submit" name="confirmationCode-submit"> <p> Confirm </p> </button>
            </div>
        </form>

        
    </div>

    <?php 

    //notifications
    $errors = array(
        "nouser" => "No such user",
        "wrongpassword" => "Wrong password",
        "emptyfields" => "Please fill all fields",
        "invalidmail" => "Please use a valid email",
        "emailtaken" => "This email is taken",
        "passwordcheck" => "Passwords do not match",
        "usernametaken" => "The username is taken",
        "notowncom" => "HMM well tried but you can only delete your own games",
        "covertoobig" => "Game image can only be 1 Mo or less",
        "gamenotarchive" => "Game file must be .rar or .zip",
        "nameTaken" => "We already have this game",
        "consolenotsupported" => "Sorry we do not support this console yet",
        "accountNotVerified" => "Verify your account before you do anything else",
        "emailError" => "Failed sending the email, keep in mind that it won't work the website is running on a local server",
        "usernameAlreadyChanged" => "You can only change your username once",
        "sqlerror" => "Something went wrong"
    );
    
    $successes = array(
        "signup" => "Signed in successfully!",
        "login" => "Loged in successfully!",
        "logout" => "Loged out successfully!",
        "post" => "Posted",
        "delCom" => "Post successfully deleted",
        "upload" => "Successfully uploaded",
        "emailVerified" => "Successfully verified",
        "changedUsername" => "Your username was successfully changed, you may now login"
    );

    if(isset($_GET['error'])){
            $error = $_GET['error'];
    } else {
        $error = '';
        if(isset($_GET['success'])){
                $success = $_GET['success'];
        } else {
            $success = '';
        }
    }


    if($error){ ?>
        <div id="errorBox" class="glass"> <!-- la notification disparait au bout de quelques secondes --> 
            <a onclick="document.getElementById('errorBox').style.maxHeight = '0'; setTimeout(function() {document.getElementById('errorBox').style.border = 'none'}, 300)">x</a> <!-- setTimeout(fonction, temps) c'est un peu comme un delay, pour pas qu'on voit un trait rouge apres la fermeture de la notification, mais que la bordure reste au moins jusqu'a ce qu'elle se soit barrée-->
            <p><?php echo $errors[$error] ?></p>
        </div> <?php 
    }

    else if($success){ ?>
        <div id="successBox" class="glass">
            <a onclick="document.getElementById('successBox').style.maxHeight = '0'; setTimeout(function() {document.getElementById('successBox').style.border = 'none'}, 300)">x</a> <!-- setTimeout(fonction, temps) c'est un peu comme un delay, pour pas qu'on voit un trait rouge apres la fermeture de la notification, mais que la bordure reste au moins jusqu'a ce qu'elle se soit barrée-->
            <p><?php echo $successes[$success] ?></p>
        </div> <?php 
    }



