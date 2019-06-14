<?php
    if(!isset($_SESSION)) 
    { 
        session_start(); 
    } 
    $title = "Gametop";
    require "header.php";
    include "includes/dbh.inc.php";

?>

<link rel="stylesheet" href="index.css">
<section id="indexSection">

    <h1>Latest games:</h1>
    <div id="latestGames">
        <div>
            
            <?php

            $sql = "SELECT aId, aCover, aConsole FROM games ORDER BY aId DESC"; //on prend les derniers jeux
            $stmt = mysqli_stmt_init($conn);
            if (!mysqli_stmt_prepare($stmt, $sql)){
                header("Location: ?error=sqlerror");
                exit();
            } else {
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);
                $i = 0;
                while(($row = mysqli_fetch_assoc($result)) && ($i < 25)){ ?>
                    <a href=<?php echo "game.php?id=" . $row['aId'] ?> <?php cover_size($row["aConsole"], 150, "sameHeight"); ?> >
                        <img src="<?php echo $row["aCover"]; ?>" alt="Image">
                    </a>

                <?php
                    $i += 1;
                }
            }
        ?>
            

        </div>
            

    </div>

</section>

<?php
    require "footer.php";
?>
