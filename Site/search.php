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

    $maxLevDistance = 9; //jusqu'a quelle distance de levenshtein on affiche une str comme résultat de recherche
?>

<section>
    <?php 
    
        require 'includes/dbh.inc.php'; //on se connecte à la base de donnée
        $search = strtolower(strval($_GET['s']));
        $sql = "SELECT aId, aWebsite, aUrl FROM articles;";
        $stmt = mysqli_stmt_init($conn);
        if (!mysqli_stmt_prepare($stmt, $sql)){
            header("Location: ../?error=sqlerror");
            exit();
        } else {
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $i = 0;
            if (mysqli_num_rows($result) > 0) {
                $array = array();

                while($row = mysqli_fetch_assoc($result)){
                    // print_r($row); //jusque la ca marche
                    $levWebsite = levenshtein($search, $row["aWebsite"], 1, 1, 1); //levenshtein est une fonction qui donne la distance de levenshtein entre deux str, plus elles se ressemblent plus la distance est faible, on peut ajuster le coût d'une insertion, remplacement et déletion pour avoir les meilleurs résultats, aussi str1 et str2 >= 255 lettres
                    $levUrl = levenshtein($search, $row["aUrl"], 1, 1, 1);
                    // print_r($levWebsite . " ET " . "$levUrl"); //jusque la ca marche aussi
                    if($levWebsite > $levUrl){
                        //array_push($array, $row["aId"], $levUrl); // comme append()
                        $array = $array + array($row["aId"] => $levUrl);
                    } else {
                        //array_push($array, $row["aId"], $levWebsite); //mais ca marche pas avec les dictionnaires.....
                        $array = $array + array($row["aId"] => $levWebsite);
                    }
                }
                    asort($array); //a pour dire trier par la valeur (arsort pour décroissant)

                // print_r($array);
                $i = 0;
                foreach ($array as $key => $value) {

                    // print_r($key);
                    // print_r($value);
                    if($value < $maxLevDistance){
                        if(count($array) > 0 && $i){ //si il y a un résultat mais c'est pas le premier
                        ?>
                            <div class="resultsSeparator"></div>
                        <?php
                        }
                        $i += 1;
                        //print_r($key);
                        $sql = "SELECT aWebsite, aComment, aUrl FROM articles WHERE aId=?";
                        if (!mysqli_stmt_prepare($stmt, $sql)){
                            header("Location: ../?error=sqlerror");
                            exit();
                        }
                        mysqli_stmt_bind_param($stmt, "s", $key);
                        mysqli_stmt_execute($stmt);
                        $result = mysqli_stmt_get_result($stmt);
                        $row = mysqli_fetch_assoc($result);
                        //print_r($row);
                        
                    ?>
                        <a class="searchResults" href=<?php echo "site.php?n=" . $key ?> >
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
                        </a> 
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
            var_dump(count($array));

        mysqli_close($conn);
            
        }

    ?>

</section>

<?php
    require "footer.php";
?>