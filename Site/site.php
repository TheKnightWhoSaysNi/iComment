<?php
    if(!isset($_SESSION)) { session_start(); }
    
    if(!isset($_GET["n"])){
        header("Location: index.php");
        exit();
    } else {
        $n = $_GET["n"];
    }

    require 'includes/dbh.inc.php';
    $sql = "SELECT * FROM articles WHERE aId=?;";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)){
        header("Location: ../?error=sqlerror");
        exit();
    } else {
        mysqli_stmt_bind_param($stmt, "s", $n); //on le met deux fois parcequ'il y a deux spaceholders dans $sql 
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $result = mysqli_fetch_assoc($result);
    }
    $website = $result["aWebsite"];
    $title = "iComment - " . $website;
    require "header.php";
?>

<section>
    <h1><?php echo $website ?></h1> 
    <h3><?php echo $result["aUrl"]; ?></h3>
    <h2><?php echo "Overall reputation: " ?> </h2>
</section>

<?php
require "footer.php";