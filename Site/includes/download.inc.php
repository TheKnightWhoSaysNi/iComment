<?php

if (isset($_POST["download"])){

    $id = $_POST['download'];

    require "dbh.inc.php";

    $sql = "UPDATE games SET aDownloads = aDownloads+1 WHERE aId = $id";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)){
        header("Location: ../?error=sqlerror");

    } else {
        mysqli_stmt_execute($stmt);
        header("Location: ../" . $_POST["path"]);
        
    }
    
} else {
    header("Location/index.php");
    
}

exit();