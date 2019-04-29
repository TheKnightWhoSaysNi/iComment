<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?php echo $title; ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" media="screen" href="main.css" />
    <script src="main.js"></script>
</head>
<body>

    <header id="header" class="glass">

        <div>
            
            <a href="index.php" id="logo">iComment</a>

            <ul class="navBar">

                <li onclick="location.href='index.php#start';">
                    <p>Get Started</p>
                </li>
                <!-- Pas de balise <a href=""> </a>  parceque c'est plus simple de centrer le texte en ayant une grande hitbox -->
                <li onclick="location.href='index.php#about';">
                    <p>About</p>
                </li>

                <?php

                    if (isset($_SESSION['userId'])) {    //comme il y a plein de guillemets et de balises c'est plus simple d'ouvrir et fermer du php autour de l'html plutot que de mettre l'html dans le php avec echo?>
                        <li onclick="location.href='includes/logout.inc.php';">
                            <p>Log Out</p>
                        </li> <?php
                    } 
                    else { ?>
                        <li onclick="document.getElementById('login').style.display='flex'">
                            <p>Login / Sign Up</p>
                        </li> <?php
                    }
                ?>
                
            </ul>

            <div id="searchBar">
                <input type="search" id="textInput" placeholder="Let's search for a website!">
                <input type="button" id="searchButton" value="⌕">
            </div>

        </div>

    </header>

    
    <div id='login' class='glass'>
        <form class='login' action='includes/login.inc.php' method='post'>
            <input type='text' name='mailuid' placeholder='Username/Email...'>
            <input type='password' name='pwd' placeholder='Password...'>
            <button type='submit' name='login-submit'>Login</button>
        </form>
        <a href='signup.php'>Not signed up yet?</a>
        <form action='includes/logout.inc.php' method='post'>
            <button type='submit' name='logout-submit'>Logout</button>
        </form>
    </div>
    
    <div id="content-blocker-holder"></div>
    <div id="signup" class="glass">
        <form class="signup" action="includes/signup.inc.php" method="post">
            <input type="text" name="uid" placeholder="Username..." value="">
            <input type="text" name="mail" placeholder="Email..." value="">
            <input type="password" name="pwd" placeholder="Password...">
            <input type="password" name="pwd-repeat" placeholder="Repeat Password...">
            <button type="submit" name="signup-submit">Sign up</button>
        </form>
        <a href="login.php">Already have an account?</a>
    </div>
        