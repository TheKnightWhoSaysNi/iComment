<!-- 

    account.php: permet d'accéder aux informations du compte de l'utilisateur: 
        -jeux que l'on a posté de les éditer et de les supprimmer
        -modifier le mot de passe
        -

    on doit pas pouvoir y accéder sans etre connecté

-->
<?php

if(!isset($_SESSION)) { session_start(); }

if(!isset($_SESSION["userUid"])){ // si l'utilisateur est pas connecté, il est venu en entrant l'url
    header("Location: index.php"); // on le renvoie vers l'index
    exit();
}

$title = "Gametop - Account";
require "header.php";
?>

<link rel="stylesheet" href="account.css">


<!-- liste des jeux postés par l'utilisateur -->
<section id="accountSection">    
    <div id="postedGames">
        <?php
            require "includes/dbh.inc.php";
            $user = $_SESSION["userUid"];
            $sql = "SELECT aId, aGame, aConsole, aComment, aCover FROM games WHERE aAuthor=?;"; //on récupère les information du jeu dont l'auteur est l'utilisateur connecté
            $stmt = mysqli_stmt_init($conn);
            if (!mysqli_stmt_prepare($stmt, $sql)){
                header("Location: ../?error=sqlerror");
                exit();
            } else {
                mysqli_stmt_bind_param($stmt, "s", $user);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);
            }
        
        if(mysqli_num_rows($result)){ ?>
            <h1>Games you have posted:</h1>

            <div id="accordion">
            <?php
                $i = 0;
                //tant qu'il y a des jeux à afficher on ajoute une ligne
                while($row = mysqli_fetch_assoc($result)){
                    $console = $row["aConsole"];
                    ?>
                    <a class="searchResults" href=<?php echo "game.php?id=" . $row["aId"] ?> >
                        <img src="<?php echo $row["aCover"] ?>" alt="Game cover" <?php cover_size($console, 100) ?> class="gameCover">

                        <div id="text">
                            <div>
                                <h1>
                                    <?php if(strlen($row["aGame"]) <= 18){
                                            echo $row["aGame"];
                                        } else {
                                            echo substr($row["aGame"], 0, 15) . "..."; //si le titre est trop long on le racourcit
                                        }
                                    ?>
                                </h1> 
                                <h3>
                                    <?php
                                        if(strlen($row["aConsole"]) <= 40){
                                            echo $row["aConsole"];
                                        } else {
                                            echo substr($row["aConsole"], 0, 37) . "..."; //si le titre est trop long on le racourcit
                                        } 
                                    ?>
                                </h3>
                            </div>

                            <h2>
                                <?php
                                    if(strlen($row["aComment"]) <= 700){
                                        echo $row["aComment"];
                                    } else {
                                        echo substr($row["aComment"], 0, 697) . "..."; //si le titre est trop long on le racourcit
                                    }
                                ?>
                            </h2>
                            
                        </div>

                        <div class="buttons">
                            <form class="deleteBtn" action="includes/delCom.inc.php" method="post">
                                <button type="submit" name="delete-comment" value="<?php echo $row['aId'] ?>">Delete</button>
                            </form>
                            
                            <form class="editBtn" action="includes/edit.inc.php" method="post">
                                <button type="submit" name="edit-post" value="<?php echo $row['aId'] ?>"><p>Edit</p></button>
                            </form>
                        </div>

                        
                    </a>
                    <?php

                    //on met une ligne entre les jeux pour les séparer
                    $i += 1;
                    if(mysqli_num_rows($result) > 0 && $i != mysqli_num_rows($result)){ //on veut pas tracer une ligne en dessous du dernier jeu ?>

                        <div class="resultsSeparator"></div>
                    <?php
                    }
                }
            
            ?>
        </div>

    </div>
        
        <?php
        }
        ?>

        

</section>


<?php

require "footer.php";

?>