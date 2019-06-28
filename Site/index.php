<?php
    if(!isset($_SESSION)) { session_start(); } 
    
    $title = "Gametop";
    require "header.php";
    include "includes/dbh.inc.php";

?>

<link rel="stylesheet" href="index.css">

<script type="application/javascript" style="display: none">
    
    var expanded = false;
    function expandLatestGames() {
    if (!expanded) {
        document.getElementById("accordion").style.maxHeight = "930px";
        document.getElementById("arrow").style.transform = "rotate(-90deg)";
    }
    else {
        document.getElementById("accordion").style.maxHeight = "310px";
        document.getElementById("arrow").style.transform = "rotate(90deg)";
    }

    expanded = !expanded;
    }

    //quand on clique sur un 'top' selectionné on est mené sur la page du jeu mais sinon on le selectionne
    function click(o){
        alert(o);
        if(document.getElementById(o).checked){
            alert("caca");
        } else { alert("pipi")}
    }

</script>

<section id="indexSection">

    <div id="top">
        <h1>Most downloaded:</h1>
        <div>
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

                        <input type="radio" name="top" id="labelTop1" checked>
                            <label onclick='if(document.getElementById("labelTop1").checked == true){ window.location.href = "game.php?id=<?php echo $row["aId"] ?>"; }' id="top1" for="labelTop1">
                                <img src="<?php echo $row["aCover"] ?>" alt="Game cover" <?php cover_size($row["aConsole"], 250) ?>draggable="false">
                                <a href="<?php echo "game.php?id=" . $row["aId"] ?>">1</a>
                            </label>

                        
                    <?php
                    // #2
                        $row = mysqli_fetch_assoc($result); ?>

                        <input type="radio" name="top" id="labelTop2">
                            <label onclick='if(document.getElementById("labelTop2").checked == true){ window.location.href = "game.php?id=<?php echo $row["aId"] ?>"; }' id="top2" for="labelTop2">
                                <img src="<?php echo $row["aCover"] ?>" alt="Game cover" <?php cover_size($row["aConsole"], 180) ?>draggable="false">
                                <a href="<?php echo "game.php?id=" . $row["aId"] ?>">2</a>
                            </label>


                    <?php
                    // #3
                        $row = mysqli_fetch_assoc($result); ?>

                        <input type="radio" name="top" id="labelTop3">
                            <label onclick='if(document.getElementById("labelTop3").checked == true){ window.location.href = "game.php?id=<?php echo $row["aId"] ?>"; }' id="top3" for="labelTop3">
                                <img src="<?php echo $row["aCover"] ?>" alt="Game cover" <?php cover_size($row["aConsole"], 160) ?>draggable="false">
                                <a href="<?php echo "game.php?id=" . $row["aId"] ?>" >3</a>               
                            </label>


                    <?php

                }

            ?>
        </div>

    </div>



    
    <div id="latestGames">

        <h1>Latest games:</h1>
        <div id="accordion">

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
                while(($row = mysqli_fetch_assoc($result)) && ($i < 14)){ ?>
                    <a href=<?php echo "game.php?id=" . $row['aId'] ?> <?php cover_size($row["aConsole"], 150, "sameHeight"); ?> draggable="false">
                        <img src="<?php echo $row["aCover"]; ?>" alt="Image" draggable="false">
                    </a>

                <?php
                    $i += 1;
                }
            }
        
        ?>
        
        </div>

        <button onclick="expandLatestGames()"><p id="arrow">»</p></button>

    </div>

</section>

<?php
    require "footer.php";
?>
