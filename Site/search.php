<?php
    $title = "iComment - Search";
    require "header.php";
    if (!isset($_POST['search'])){
        if (!isset($_GET["s"])){
            header("Location: index.php");
        }
        
    } else {
        header("Location: ?s=" . $_POST['search']);
    }
?>

<section>
    <?php 
    
        require 'includes/dbh.inc.php'; //on se connecte à la base de donnée
        $search = strtolower(strval($_GET['s']));
        $sql = "SELECT * FROM articles WHERE aWebsite=? OR aUrl=?;";
        $stmt = mysqli_stmt_init($conn);
        if (!mysqli_stmt_prepare($stmt, $sql)){
            header("Location: ../?error=sqlerror");
            exit();
        } else {
            mysqli_stmt_bind_param($stmt, "ss", $search, $search); //on le met deux fois parcequ'il y a deux spaceholders dans $sql 
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $i = 0;
            if (mysqli_num_rows($result) > 0) {
                while($row = mysqli_fetch_assoc($result)) {?>

                    <a class="searchResults" href=<?php echo "site.php?n=" . $row["aId"] ?> >
                        <h1>
                            <?php if(strlen($row["aWebsite"]) <= 20){
                                    echo $row["aWebsite"];
                                } else {
                                    echo substr($row["aWebsite"], 0, 17) . "..."; //si le titre est trop long on le racourcit
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
                        <h2>
                            <?php
                                if(strlen($row["aComment"]) <= 150){
                                    echo $row["aComment"];
                                } else {
                                    echo substr($row["aComment"], 0, 147) . "..."; //si le titre est trop long on le racourcit
                                }
                            ?>
                        </h2>
                    </a> <?php
                    
                    $i += 1; //pour la ligne entre les résultats
                    if(mysqli_num_rows($result) > 1 && $i != mysqli_num_rows($result)){ //si le résultat est ni le premier ni le dernier
                        ?>
                        <div class="resultsSeparator"></div>
                        <?php
                    }
                    
                }

            } else {
                echo "<h1>No result.</h1>";
            }
            if(isset($_SESSION["userUid"])){ ?>
                <a class="noresultBtn" href="post.php"><p>You can add one yourself</p></a>
            <?php
            } else { ?>
                <a class="noresultBtn" href="#login"><p>Sign in to add one yourself</p></a>
            <?php
            }

        mysqli_close($conn);
            
        }

    ?>

</section>

<?php
    require "footer.php";
?>