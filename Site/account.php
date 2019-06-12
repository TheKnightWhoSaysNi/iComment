<?php
if(!isset($_SESSION)) { session_start(); } 

$title = "Gametop - Account";

require "header.php";



?>

<section id="accountSection">

    <h1>Games you have posted:</h1>
    <?php

    require "includes/dbh.inc.php";
    $user = $_SESSION["userUid"];
    $sql = "SELECT aId, aGame, aConsole, aComment, aCover FROM games WHERE aAuthor=?;";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)){
        header("Location: ../?error=sqlerror");
        exit();
    } else {
        mysqli_stmt_bind_param($stmt, "s", $user);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $i = 0;
        while($row = mysqli_fetch_assoc($result)){
            ?>
            <a class="searchResults" href=<?php echo "site.php?n=" . $row["aId"] ?> >
                <img src="<?php echo $row["aCover"] ?>" alt="Game cover">
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
                    
                    <form class="deleteBtn" action="includes/delCom.inc.php" method="post">
                            <button type="submit" name="delete-comment" value="<?php echo $row['aId'] ?>">Delete</button>
                    </form>

                    <h2>
                        <?php
                            if(strlen($row["aComment"]) <= 800){
                                echo $row["aComment"];
                            } else {
                                echo substr($row["aComment"], 0, 797) . "..."; //si le titre est trop long on le racourcit
                            }
                        ?>
                    </h2>
                </div>
            </a>
            <?php
            $i += 1;
            if(mysqli_num_rows($result) > 0 && $i != mysqli_num_rows($result)){ //si il y a un résultat mais c'est pas le premier
            ?>
                <div class="resultsSeparator"></div>
            <?php
            }
        }
    }


    ?>
</section>


<?php

require "footer.php";

?>