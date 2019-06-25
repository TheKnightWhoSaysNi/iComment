<?php
    if(!isset($_SESSION)) { session_start(); } 
    
    $title = "Gametop";
    require "header.php";
    include "includes/dbh.inc.php";

?>

<link rel="stylesheet" href="index.css">
<section id="indexSection">

    <div id="top">


        <?php
        $sql = "SELECT aId, aCover, aConsole FROM games ORDER BY aDownloads DESC"; //on prend les derniers jeux
            $stmt = mysqli_stmt_init($conn);
            if (!mysqli_stmt_prepare($stmt, $sql)){
                header("Location: ?error=sqlerror");
                exit();
            } else {
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);

                
                // #1
                    $row = mysqli_fetch_assoc($result); ?>

                    <a id='top1' href="<?php echo "game.php?id=" . $row["aId"] ?>">
                        <img src="<?php echo $row["aCover"] ?>" alt="#1 cover" <?php cover_size($row['aConsole'], 400) ?> >
                        <p>#1</p>
                    </a>
                    
                <?php
                // #2
                    $row = mysqli_fetch_assoc($result); ?>
                    
                    <a id="top2" href="<?php echo "game.php?id=" . $row["aId"] ?>">
                        <img src="<?php echo $row["aCover"] ?>" alt="#2 cover" <?php cover_size($row['aConsole'], 300) ?> >
                        <p>#2</p>
                    </a>

                <?php
                // #3
                    $row = mysqli_fetch_assoc($result); ?>

                    <a id="top3" href="<?php echo "game.php?id=" . $row["aId"] ?>">
                        <img src="<?php echo $row["aCover"] ?>" alt="#3 cover" <?php cover_size($row['aConsole'], 250) ?> >
                        <p>#3</p>
                    </a>

                <?php

            }

        ?>

    </div>



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
