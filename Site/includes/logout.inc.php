<!--  

    logout.inc.php: on déconnecte l'utilisateur

-->

<?php

session_start();
session_unset();
session_destroy();
header("Location: ../?success=logout"); // on renvoie l'utilisateur avec une notification "vous avez bien été déconnecté"