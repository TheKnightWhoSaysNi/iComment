<!-- 

    game.php: page qui permet d'afficher les détails d'un jeu, de le télécharger, commenter et de le noter

    on a un identifiant dans l'url, on charge les informations du jeu qui y correspond.

-->

<?php

    if(!isset($_SESSION)){ session_start(); } 

    if(isset($_GET["id"])){
        $id = $_GET["id"];
    } else {
        header("Location: index.php");
        exit();
    }

    require "includes/dbh.inc.php";


    if (isset($_POST["download"])){

        $id = $_POST['download'];
        $sql = "UPDATE games SET aDownloads = aDownloads+1 WHERE aId = $id";
        $stmt = mysqli_stmt_init($conn);
        if (!mysqli_stmt_prepare($stmt, $sql)){
            header("Location: ?error=sqlerror");

        } else {
            mysqli_stmt_execute($stmt);
            header("Location: " . $_POST["path"]);
        }
    }


    // quand on vote pour un jeu
    if (isset($_POST["rate"])){

        function execute($conn, $sql){
            $stmt = mysqli_stmt_init($conn);
            if (!mysqli_stmt_prepare($stmt, $sql)){
                header("Location: ?error=sqlerror");
                exit();
            } else {
                mysqli_stmt_execute($stmt);
            }
        }

        $id = $_POST['id'];
        $uId = $_SESSION['userId'];

        if($_POST["rate"] == "like") { //on met un like et on retire le dislike
            $sql = "UPDATE games SET aLikes = REPLACE(aLikes, CONCAT($uId, space(1)), '') WHERE aId = $id"; //on retire le like pour pas qu'on puisse pas liker deux fois
            execute($conn, $sql);
            $sql = "UPDATE games SET aLikes = CONCAT(aLikes, $uId, space(1)) WHERE aId = $id";  //on peut pas faire de concatenation avec str + str ou str . str en sql donc on utilise la fonction CONCAT()
            execute($conn, $sql);
            $sql = "UPDATE games SET aDislikes = REPLACE(aDislikes, CONCAT($uId, space(1)), '') WHERE aId = $id";
            execute($conn, $sql);
            
        }
        elseif($_POST["rate"] == "dislike") { //on met un dislike et on retire le like
            $sql = "UPDATE games SET aDislikes = REPLACE(aDislikes, CONCAT($uId, space(1)), '') WHERE aId = $id";
            execute($conn, $sql);
            $sql = "UPDATE games SET aDisLikes = CONCAT(aDislikes, $uId, space(1)) WHERE aId = $id";
            execute($conn, $sql);
            $sql = "UPDATE games SET aLikes = REPLACE(aLikes, CONCAT($uId, space(1)), '') WHERE aId = $id";
            execute($conn, $sql);
        }
        else {
            header("Location: index.php?error=welltried");
            exit();
        }

        unset($_POST['id']); //sinon on a des problèmes quand on passe d'un article à l'autre
    
    } else { 
        
        //on ajoute une vue au jeu
        $sql = "UPDATE games SET aViews = aViews+1 WHERE aId = $id";
        $stmt = mysqli_stmt_init($conn);
        if (!mysqli_stmt_prepare($stmt, $sql)){ header("Location: ../?error=sqlerror"); } 
        else { mysqli_stmt_execute($stmt); }
    }

    //on récupère les informations du jeu

    $sql = "SELECT aGame, aCover, aComment, aFile, aConsole, aLikes, aDislikes FROM games WHERE aId=?";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)){
        header("Location: index.php?error=sqlerror");
        exit();
    } else {
        mysqli_stmt_bind_param($stmt, "s", $id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_assoc($result);
    }

    $title = "Gametop - " . $row['aGame'];
    require "header.php";

?>


<section>

    <link rel="stylesheet" href="game.css">

    <h1><?php echo $row['aGame'] ?></h1>

    <div>
        <img src="<?php echo $row['aCover'] ?> " alt="Game cover" <?php cover_size($row['aConsole'], 200) ?> class="gameCover" >
        <div>
            <h2><?php echo $row['aConsole'] ?></h2>
            <p> <?php echo $row['aComment'] ?> </p>
        </div>
        
    </div>

    <!-- like / dislike uniquement si l'utilisateur est connecté -->
    <?php
    if(isset($_SESSION["userUid"])) {

        ?>
        <form method="post" id="rate" action="game.php?id=<?php echo $id ?>">
            <input onchange="this.form.submit();" name="rate" id="like" type="radio" value="like" <?php if(strpos($row["aLikes"], $_SESSION["userId"] . " ") !== false){echo "checked";} ?>>
                <label for="like"><img src="website-img/like.png" alt="like"></label>
            
            <input onchange="this.form.submit();" name="rate" id="dislike" type="radio" value="dislike" <?php if(strpos($row["aDislikes"], $_SESSION["userId"] . " ") !== false){echo "checked";} ?>>
                <label for="dislike"><img src="website-img/dislike.png" alt="dislike"></label>
            <input name="id" type="hidden" value="<?php echo $id ?>">
            <p>(<?php echo (count(explode(" ", $row["aLikes"])) - 1) . " / " . (count(explode(" ", $row["aDislikes"])) - 1) ?>)</p>
        </form> <?php

    }
    ?>

    

    <form method="post" action="game.php?id=" <?php echo $id ?> class="downloadBtn" >
        <input type="hidden" name="path" value=<?php echo $row["aFile"] ?> >
        <button type="submit" name="download" value=<?php echo $id ?> > <p>Download</p> </button>
    </form>

</section>


<?php 


?>