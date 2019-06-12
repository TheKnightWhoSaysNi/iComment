<?php
    if(!isset($_SESSION)) 
    { 
        session_start(); 
    }

    
    if (!isset($_POST['search'])){
        if (!isset($_GET["s"])){
            header("Location: index.php");
        }
        
    } else {
        header("Location: ?s=" . $_POST['search']); //apres ca on aura toujours le terme cherché dans l'url
    }

    $search = strtolower(strval($_GET['s']));
    $title = "Gametop - ".$_GET['s']; //on a le terme recherché dans le titre de la page

    require "header.php";

    $maxLevDistance = 6; //jusqu'a quelle distance de levenshtein on affiche une str comme résultat de recherche
?>

<section>
    <?php 
    
        require 'includes/dbh.inc.php'; //on se connecte à la base de donnée
        
        $sql = "SELECT aId, aGame, aConsole FROM games;";
        $stmt = mysqli_stmt_init($conn);
        if (!mysqli_stmt_prepare($stmt, $sql)){
            header("Location: ../?error=sqlerror");
            exit();
        } else {
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $i = 0;
            $array = array();

            while($row = mysqli_fetch_assoc($result)){
                // print_r($row); //jusque la ca marche
                $levGame = levenshtein($search, $row["aGame"], 1, 1, 1); //levenshtein est une fonction qui donne la distance de levenshtein entre deux str, plus elles se ressemblent plus la distance est faible, on peut ajuster le coût d'une insertion, remplacement et déletion pour avoir les meilleurs résultats, aussi str1 et str2 >= 255 lettres
                $levConsole = levenshtein($search, $row["aConsole"], 1, 1, 1);
                // print_r($levGame . " ET " . "$levConsole"); //jusque la ca marche aussi
                if($levGame > $levConsole){
                    //array_push($array, $row["aId"], $levConsole); // comme append()
                    $array = $array + array($row["aId"] => $levConsole);
                } else {
                    //array_push($array, $row["aId"], $levGame); //mais ca marche pas avec les dictionnaires.....
                    $array = $array + array($row["aId"] => $levGame);
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
                    $sql = "SELECT aGame, aCover, aConsole, aComment FROM games WHERE aId=?";
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
                        <img src="<?php echo $row["aCover"] ?>" alt="game cover">
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
                }
            }    
            

            
                if ($i == 0) {
                echo "<h1>No result.</h1>";
            }
            if(isset($_SESSION["userUid"])){ ?>
                <a class="noresultBtn" href=<?php echo "post.php?name=" . $_GET['s'];?> ><p>You can add one yourself</p></a>
            <?php
            } else { ?>
                <a class="noresultBtn" href="#login"><p>Sign in to add one yourself</p></a>
            <?php
            }
            //var_dump(count($array));

        mysqli_close($conn);
            
        }

    ?>

</section>

<?php
    require "footer.php";
?>