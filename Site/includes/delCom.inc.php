<!-- 

    delCom.inc.php: permet de supprimmer une pubication. (com parcequ'avant de fusionner les projets c'était des commentaires et pas des jeux)

    Comme on utilise l'identifiant du jeu qui viens d'un post => input caché, cet identifiant peut etre modifié dans 'inspecter l'élement'
    on doit donc s'assurer qu'on peut pas supprimer le jeu de quelqu'un d'autre

-->

<?php
if(!isset($_SESSION)) { session_start(); } //parfois la session expire en lancant un programme donc on s'assure qu'elle est bien active, comme on aura besoin des informations de l'utilisateur
if (isset($_POST['delete-comment'])){ //on s'assure que l'utilisateur est bien arrivé sur ce programme en demandant la suppression d'un jeu
    $id = $_POST["delete-comment"]; //identifiant unique du jeu à supprimmer
    require 'dbh.inc.php';

    $sql = "SELECT aAuthor FROM games WHERE aId=?"; //on selectionne le nom de l'utilisateur qui a posté le jeu
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {  //si on ne peut pas se connecter à la base de donnée...
        header("Location: ../?error=sqlerror"); //...on retourne sur la page précedente avec une erreur
        exit();
    }
    else{
        mysqli_stmt_bind_param($stmt, "s", $id); //prepared statement pour empécher l'injection sql
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_assoc($result); //on obtient un dictionnaire $row = array("aAuthor" => nom de l'utilisateur)
        if (!mysqli_stmt_prepare($stmt, $sql)) {
            header("Location: ../?error=sqlerror");
            exit();
        } elseif (strtolower($row['aAuthor']) != strtolower($_SESSION['userUid'])) { //si l'utilisateur et l'auteur ne correspondent pas...
            header("Location: ../?error=notowncom"); //...on renvoie l'utilisateur avec une erreur disant qu'on ne peux supprimmer que nos propres jeux
            exit();
        } else { //si l'utilisateur et l'auteur correspondent on autorise la suppression du jeu
            $sql = "DELETE FROM games WHERE aId=?"; 
            $stmt = mysqli_stmt_init($conn);
            if (!mysqli_stmt_prepare($stmt, $sql)) {
                header("Location: ../?error=sqlerror");
                exit();
            }
            else{
                mysqli_stmt_bind_param($stmt, "s", $id);
                mysqli_stmt_execute($stmt);
                header("Location: ../account.php?success=delCom");
            }
        }
    }

    exit();

} else {
    header("Location: ../index.php");
    exit();
}
