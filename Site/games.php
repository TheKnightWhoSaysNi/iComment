<?php
    if(!isset($_SESSION)) { session_start(); }

    require "includes/dbh.inc.php";

    $title = "Gametop - Games";
    require "header.php";

    $consoles = array();

    $sql = "SELECT aConsole FROM games;";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)){
        header("Location: ?error=sqlerror");
        exit();
    } else {
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        while($row = mysqli_fetch_assoc($result)){
            if( !in_array($row["aConsole"], $consoles) ){
                array_push($consoles, $row["aConsole"]);
            }
        }
    }
    sort($consoles);

?>

<section id="gamesSection">
    <?php
        foreach ($consoles as $console) {
            $sql = "SELECT aId, aCover FROM games WHERE aConsole=?;";
            $stmt = mysqli_stmt_init($conn);
            if (!mysqli_stmt_prepare($stmt, $sql)){
                header("Location: ../?error=sqlerror");
                exit();
            } else {
                mysqli_stmt_bind_param($stmt, "s", $console);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);
                ?>
                    <div id="consoleName">
                        <h1><?php echo $console . ":" ?></h1>
                    </div>
                <?php
                while($row = mysqli_fetch_assoc($result)){ ?>
                    
                    <a href=<?php echo "game.php?id=" . $row['aId'] . " style='";
                    if(strtolower($console) == "nes"){
                        echo "width: 200px; height: 300px;";
                    } elseif(strtolower($console) == "snes") {
                        echo "width: 300px; height: 200px;";
                    } elseif(strtolower($console) == "atari 2600"){
                        echo "width: 200px; height: 250px;";
                    } elseif(strtolower($console) == "game boy"){
                        echo "width: 225px; height: 225px;";
                    }
                    echo "'";
                    ?> >
                        <img src="<?php echo $row["aCover"]; ?>" alt="Image">
                    </a>

                <?php                    
                }
            }
        }
    ?>

</section>

<?php

    require "footer.php";