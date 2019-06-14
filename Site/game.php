<?php
    if(!isset($_SESSION)){ session_start(); } 

    if(isset($_GET["id"])){
        $id = $_GET["id"];
    } else {
        header("Location: index.php");
        exit();
    }

    require "includes/dbh.inc.php";
    $sql = "SELECT aGame, aCover, aComment, aFile, aConsole FROM games WHERE aId=?";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)){
        header("Location: index.php?error=sqlerror");
        exit();
    } else {
        mysqli_stmt_bind_param($stmt, "s", $id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_assoc($result);
    }

    $title = "Gametop - " . $row['aGame'];
    require "header.php";

?>

<link rel="stylesheet" href="game.css">

<section id="gameSection">

    <h1><?php echo $row['aGame'] ?></h1>
    <div>
        <img src="<?php echo $row['aCover'] ?> " alt="Game cover" <?php cover_size($row['aConsole'], 200) ?> class="gameCover" >
        <p><?php echo $row["aComment"] ?></p>
    </div>
    
    <a href="<?php echo $row["aFile"] ?>" class="downloadBtn" download> <p>Download</p> </a>

</section>


<?php 


?>