<?php
if(!isset($_SESSION)) { session_start(); } 
if (isset($_POST['delete-comment'])){ //il faudrait vérifier le nom de l'utilisateur pout pas qu'il puisse supprimmer n'importe quoi
    $id = $_POST["delete-comment"];
    require 'dbh.inc.php';

    $sql = "SELECT aAuthor FROM articles WHERE aId=?";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("Location: ../?error=sqlerror");
        exit();
    }
    else{
        mysqli_stmt_bind_param($stmt, "s", $id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_assoc($result);
        if (!mysqli_stmt_prepare($stmt, $sql)) {
            header("Location: ../?error=sqlerror");
            exit();
        } elseif (strtolower($row['aAuthor']) != strtolower($_SESSION['userUid'])) {
            header("Location: ../?error=notowncom");
            exit();
        } else {
            $sql = "DELETE FROM articles WHERE aId=?";
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


} else {
    header("Location: ../index.php");
    exit();
}
