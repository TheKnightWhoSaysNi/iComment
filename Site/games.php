<!--  

    games.php: répertorie tout les jeux du site par console par ordre alphabetique

-->
<?php
    
    if(!isset($_SESSION)) { session_start(); }

    require "includes/dbh.inc.php";

    $title = "Gametop - Games";
    require "header.php";

?>

<section id="gamesSection">
    <?php
        asort($consoles, 1);//on trie les consoles par ordre alphabetique
        foreach ($consoles as $console => $ratio) { //pour chaque console...
            $sql = "SELECT aId, aCover FROM games WHERE aConsole=?;"; //... on affiche les jeux de celle-ci
            $stmt = mysqli_stmt_init($conn);
            if (!mysqli_stmt_prepare($stmt, $sql)){
                header("Location: ../?error=sqlerror");
                exit();
            } else {
                mysqli_stmt_bind_param($stmt, "s", $console);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);

                if(mysqli_num_rows($result)){
                    ?>
                    <div id="consoleName">
                        <h1><?php echo $console . ":" ?></h1>
                    </div>
                    <?php
                }
                
                while($row = mysqli_fetch_assoc($result)){ ?>
                    
                    <a href=<?php echo "game.php?id=" . $row['aId'] ?> <?php cover_size($console, 150); ?> >
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