<!-- 

    account.php: permet d'accéder aux informations du compte de l'utilisateur: 
        -jeux qu'il a posté de les éditer et de les supprimmer
        -modifier son mot de passe
        -supprimmer son compte
        -modifier le nom d'utilisateur

    on doit pas pouvoir y accéder sans etre connecté

-->
<?php
ob_start();

if(!isset($_SESSION)) { session_start(); }

if(!isset($_SESSION["userUid"])){ // si l'utilisateur est pas connecté, il est venu en entrant l'url
    header("Location: index.php"); // on le renvoie vers l'index
    exit();
}

$title = "Gametop - Account";
require "header.php";

//on récupère les informations de l'utilisateur
$user = $_SESSION["userUid"];
$sql = "SELECT idUsers, emailUsers, pwdUsers, uidUsersChanged FROM users WHERE uidUsers=?;"; //on récupère les information du jeu dont l'auteur est l'utilisateur connecté
$stmt = mysqli_stmt_init($conn);
if (!mysqli_stmt_prepare($stmt, $sql)){
    header("Location: ../?error=sqlerror");
    exit();
} else {
    mysqli_stmt_bind_param($stmt, "s", $user);
    mysqli_stmt_execute($stmt);
    $userResult = mysqli_stmt_get_result($stmt);
    $rowUser = mysqli_fetch_assoc($userResult);
}

//on récupère les informations des jeux postés par l'utilisateur
require "includes/dbh.inc.php";
$user = $_SESSION["userUid"];
$sql = "SELECT aId, aGame, aConsole, aComment, aCover FROM games WHERE aAuthor=?;"; //on récupère les information du jeu dont l'auteur est l'utilisateur connecté
$stmt = mysqli_stmt_init($conn);
if (!mysqli_stmt_prepare($stmt, $sql)){
    header("Location: ?error=sqlerror");
    exit();
} else {
    mysqli_stmt_bind_param($stmt, "s", $user);
    mysqli_stmt_execute($stmt);
    $gameResult = mysqli_stmt_get_result($stmt);
}


//si l'utilisateur a demandé à changer son nom d'utilisateur
if(isset($_POST["newUsername"])){
    if(!$rowUser["uidUsersChanged"]){ //si l'utilisateur a jamais changé son nom d'utilisateur

        //on vérifie si l'utilisateur est déja pris
        $sql = "SELECT uidUsers FROM users WHERE uidUsers=?;"; //on récupère les information du jeu dont l'auteur est l'utilisateur connecté
        $stmt = mysqli_stmt_init($conn);
        if (!mysqli_stmt_prepare($stmt, $sql)){
            header("Location: ?error=sqlerror");
            exit();
        } else {
            mysqli_stmt_bind_param($stmt, "s", $_POST["newUsername"]);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $row = mysqli_fetch_assoc($result);
            if(!mysqli_num_rows($result)){ //si le nom d'utilisateur est pas pris
                $sql = "UPDATE users SET uidUsers = ? WHERE uidUsers=?;"; //on récupère les information du jeu dont l'auteur est l'utilisateur connecté
                $stmt = mysqli_stmt_init($conn);
                if (!mysqli_stmt_prepare($stmt, $sql)){
                    header("Location: ?error=sqlerrora");
                    ob_end_flush();
                    exit();
                } else {
                    mysqli_stmt_bind_param($stmt, "ss", $_POST["newUsername"], $user);
                    mysqli_stmt_execute($stmt);

                    //on change l'auteur des posts de l'utilisateur
                    $sql = "UPDATE games SET aAuthor = ? WHERE aAuthor=?;"; //on récupère les information du jeu dont l'auteur est l'utilisateur connecté
                    $stmt = mysqli_stmt_init($conn);
                    if (!mysqli_stmt_prepare($stmt, $sql)){
                        header("Location: ../?error=sqlerror");
                        ob_end_flush();
                        exit();
                    } else {
                        mysqli_stmt_bind_param($stmt, "ss", $_POST["newUsername"], $user);
                        mysqli_stmt_execute($stmt);

                        //on met à jour la session de l'utilisateur
                        session_unset();
                        session_destroy();
                        header("Location: index.php?success=changedUsername&mailuid=" . $_POST["newUsername"] . "#login");
                        ob_end_flush();
                        exit();
                    }
                }
            } else { //si le nom d'utilisateur est pris
                header("Location: account.php?error=usernameTaken");
                ob_end_flush();
                exit();
            }
        } 
    }
    else{ //si l'utilisateur a déja changé son nom d'utilisateur
        header("Location: account.php?error=usernameAlreadyChanged");
        ob_end_flush();
        exit();
    }
    
}
?>

<link rel="stylesheet" href="account.css">


<!-- liste des jeux postés par l'utilisateur -->
<section id="accountSection"> 
    <div id="accountInfo">
        <div class="infoBlock">
            <div id="username">
                <h1>Username: </h1>
                <form method="post" autocomplete="off" action="account.php">
                    <input type="text" name="newUsername" autocomplete="off" placeholder="<?php echo $user ?>" required>
                    <button type="submit"> <p>Save</p> </button>
                </form>
            </div>
            <div id="email">
                <h1>Email: </h1>
                <form method="post" autocomplete="off" action="account.php">
                    <input type="text" name="newEmail" autocomplete="off" placeholder="<?php echo $rowUser["emailUsers"] ?>" required>
                    <button type="submit"> <p>Save</p> </button>
                </form>
            </div>
            <div id="password">
                <h1>Password: </h1>
                <form method="post" autocomplete="off" action="account.php">
                    <div>
                        <input type="text" name="oldPassword" placeholder="********" required>
                        <input type="password" name="newPassword" placeholder="New password" required>
                        <input type="password" name="confirmPassword" placeholder="Confirm Password" required>
                    </div>
                    <button type="submit"> <p>Save</p> </button>
                </form>
            </div>
        </div>
    </div>

    <div id="postedGames">
        <?php
            
        
        if(mysqli_num_rows($gameResult)){ ?>
            <h1>Games you have posted: <span>(<?php echo mysqli_num_rows($gameResult)?>)</span></h1>

            <div id="accordion">
            <?php
                $i = 0;
                //tant qu'il y a des jeux à afficher on ajoute une ligne
                while($row = mysqli_fetch_assoc($gameResult)){
                    $console = $row["aConsole"];
                    ?>
                    <a class="searchResults" href=<?php echo "game.php?id=" . $row["aId"] . " "; if(!(($i+1) % 2)){echo "style='background-color: rgba(159, 184, 218, 0.4);'";} ?> >

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
                    if(mysqli_num_rows($gameResult) > 0 && $i != mysqli_num_rows($gameResult)){ //on veut pas tracer une ligne en dessous du dernier jeu ?>

                        <div class="resultsSeparator"></div>
                    <?php
                    }
                }
            
            ?>
        </div>

    </div>
        
        <?php
        } else { //si on a pas posté de jeu
        ?>
            <h1>You have not posted any games</h1>
        <?php
        }
        ?>

        

</section>


<?php

require "footer.php";

?>