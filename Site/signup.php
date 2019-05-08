<?php
    session_start();
    $title = "iComment - Login";
    $content = '
        <script src="signup.js"></script>
        <div id="content-blocker-holder"></div>
        <div id="sectionContainer">
            <h1>Login</h1>
            
            <div id="log" class="glass">
                <form class="signup" action="includes/signup.inc.php" method="post">
                    <input type="text" name="uid" placeholder="Username..." value="">
                    <input type="text" name="mail" placeholder="Email..." value="">
                    <input type="password" name="pwd" placeholder="Password...">
                    <input type="password" name="pwd-repeat" placeholder="Repeat Password...">
                    <button type="submit" name="signup-submit">Sign up</button>
                </form>
                <a href="login.php">Already have an account?</a>
            </div>
        </div>';

    include "template.php";
?>