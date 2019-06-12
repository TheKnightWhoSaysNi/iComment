<?php
if(!isset($_SESSION)) { session_start(); } 

$title = "Gametop - Account";

require "header.php";



?>

<section id="accountSection">

    <h1>Your comments:</h1>
    <?php

    require "includes/dbh.inc.php";
    $user = $_SESSION["userUid"];
    $sql = "SELECT aId, aWebsite, aUrl, aComment FROM articles WHERE aAuthor=?;";
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
                <h1>
                    <?php if(strlen($row["aWebsite"]) <= 18){
                            echo $row["aWebsite"];
                        } else {
                            echo substr($row["aWebsite"], 0, 15) . "..."; //si le titre est trop long on le racourcit
                        }
                    ?>
                </h1> 
                <h3>
                    <?php
                        if(strlen($row["aUrl"]) <= 40){
                            echo $row["aUrl"];
                        } else {
                            echo substr($row["aUrl"], 0, 37) . "..."; //si le titre est trop long on le racourcit
                        } 
                    ?>
                </h3>
                
                <form class="deleteBtn" action="includes/delCom.inc.php" method="post">
                        <button type="submit" name="delete-comment" value="<?php echo $row['aId'] ?>">Delete</button>
                </form>

                <h2>
                    <?php
                        if(strlen($row["aComment"]) <= 150){
                            echo $row["aComment"];
                        } else {
                            echo substr($row["aComment"], 0, 147) . "..."; //si le titre est trop long on le racourcit
                        }
                    ?>
                </h2>
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