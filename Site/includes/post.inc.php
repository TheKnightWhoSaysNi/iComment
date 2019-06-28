<!-- 

    post.inc.php: permet de stocker un jeu que l'utilisateur a posté

    il faut utiliser des prepared statements pour empécher l'utilisateur d'effectuer des injection SQL
    il faut empécher l'utilisateur de poster de l'HTML pour protéger le site des attaques XSS (cross site scripting)

-->

<?php //on utilisera un php procédural parceque je comprend pas l'object oriented

if(isset($_POST['post-submit'])) {  //la plupart des commentaires pour ce code sont dans signup.inc.php parceque flemme   isset($_POST['post-submit'])
    
    if(!isset($_SESSION)) { session_start(); } //parfois la session expire en lancant un programme donc on s'assure qu'elle est bien active, comme on aura besoin des informations de l'utilisateur
    include 'dbh.inc.php';
    include "../consoles.php"; //liste des consoles disponnibles


    //on vérifie si l'utilisateur a bien confirmé son compte
    $sql = "SELECT confirmed FROM users WHERE idUsers=?"; //on prend le status de confirmation de l'utilisateur
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)){
        header("Location: ../?error=sqlerror");
        exit();
    } else {


        mysqli_stmt_bind_param($stmt, "s", $_SESSION["userId"]);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        if ($row = mysqli_fetch_assoc($result)){
            if($row["confirmed"] != 1){ //si l'utilisateur n'a pas vérifié son compte
                header("Location: ../index.php?error=accountNotVerified"); //on reffuse la publication
                exit();
            }
        }
    }


    // si il est valide on le laisse poster
    $name = $_POST["name"];
    $console = $_POST["console"]; //on récupère les informations du jeu entré par l'utilisateur

    if(isset($_POST["comment"])){ $aComment = $_POST["comment"]; } //le commentaire est facultatif alors si on en a un on le récupere

    if(!isset($consoles[$console])){
        header("Location: ../post.php?error=consolenotsupported&name=" . $name . "&comment=" . $aComment);
        exit();
    }

    $imgPath = "img/";
    $gamePath = "games/"; // dossiers ou on met les informations du jeu qu'on ne peut pas mettre dans la base de données
    
    $imgTarget = $imgPath . basename($_FILES["cover"]["name"]); //on indique le nom de l'image qui sera stocké
    $imgTarget = str_replace(" ", "-", $imgTarget); //on enlève les espaces

    $gameTarget = $gamePath . $name . "." . pathinfo($_FILES["game"]["name"], PATHINFO_EXTENSION);
    $gameTarget = str_replace(" ", "-", $gameTarget); //idem

    // if(isset($_GET["comment"])){ //je sais plus pourquoi j'ai mis ca j'ai mais ca a l'air de servir à rien
    //     $comment = "&comment=" . $_GET["comment"];
    // } else {
    //     $comment = "";
    // }

    if($_FILES["cover"]["size"] < 1000000 && $_FILES["cover"]["size"] != 0){ //si la taille de la jaquette de jeu est conforme
        if(in_array(pathinfo($_FILES["game"]["name"], PATHINFO_EXTENSION), array("rar", "zip"))){ //si le jeu est bien sous format .rar ou .zip

            $sql = "SELECT aGame FROM games WHERE aGame=?"; //on cherche si le nom du jeu est déja dans la base de données
            $stmt = mysqli_stmt_init($conn);
            if (!mysqli_stmt_prepare($stmt, $sql)) {
                header("Location: ../?error=sqlerror");
                exit();
            } else {
                mysqli_stmt_bind_param($stmt, "s", $name);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_store_result($stmt);
                if(mysqli_stmt_num_rows($stmt)){ //si on a un résultat, mysqli_stmt_num_rows($stmt) > 0 == True
                    header("Location: ../post.php?error=nameTaken&" . $comment); //on renvoie l'utilisateur avec une erreur "on a déja ce jeu"
                } 
                else { //si tout est valide
                    $sql = "INSERT INTO games (aGame, aCover, aFile, aConsole, aComment, aAuthor) VALUES (?, ?, ?, ?, ?, ?)"; // on met toutes les informations du jeu dans la base de données
                    $stmt = mysqli_stmt_init($conn);
                    if (!mysqli_stmt_prepare($stmt, $sql)) {
                        header("Location: ../?error=sqlerror");
                        exit();
                    } else {

                        mysqli_stmt_bind_param($stmt, "ssssss", $name, $imgTarget, $gameTarget, $console, $aComment, $_SESSION['userUid']);
                        mysqli_stmt_execute($stmt);

                        move_uploaded_file($_FILES["game"]["tmp_name"], "../" . $gameTarget);
                        move_uploaded_file($_FILES["cover"]["tmp_name"], "../" . $imgTarget); //on met les les fichiers mis en ligne la ou ils sont accessibles par tout les utilisateur

                        header("Location: ../?success=upload"); //on renvoie l'utilisateur avec un notif "correctement mis en ligne"
                        exit();
                    }
                }
            }

        } else {
            header("Location: ../post.php?error=gamenotarchive&name=" . $name . $comment);
            exit();
        }
        
    } else {
        header("Location: ../post.php?error=covertoobig&name=" . $name . $comment);
        exit();
    }

} else { header("Location: ../"); exit();}