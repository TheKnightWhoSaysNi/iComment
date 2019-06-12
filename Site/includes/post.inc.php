<?php //on utilisera un php procédural parceque je comprend pas l'object oriented

if(isset($_POST['post-submit'])) {  //la plupart des commentaires pour ce code sont dans signup.inc.php parceque flemme   isset($_POST['post-submit'])
    require 'dbh.inc.php';

    if(!isset($_SESSION)) { session_start(); }

    $name = $_POST["name"];
    $console = $_POST["console"];

    if(isset($_POST["comment"])){ $aComment = $_POST["comment"]; }   

    $imgPath = "img/";
    $gamePath = "games/";
    
    $imgTarget = $imgPath . $name . basename($_FILES["cover"]["name"]);
    $gameTarget = $gamePath . $name . "." . pathinfo($_FILES["game"]["name"], PATHINFO_EXTENSION);

    if(isset($_GET["comment"])){
        $comment = "&comment=" . $_GET["comment"];
    } else {
        $comment = "";
    }

    if($_FILES["cover"]["size"] < 1000000 && $_FILES["cover"]["size"] != 0){
        if(in_array(pathinfo($_FILES["game"]["name"], PATHINFO_EXTENSION), array("rar", "zip"))){

            $sql = "SELECT aGame FROM games WHERE aGame=?";
            $stmt = mysqli_stmt_init($conn);
            if (!mysqli_stmt_prepare($stmt, $sql)) {
                header("Location: ../?error=sqlerror");
                exit();
            } else {
                mysqli_stmt_bind_param($stmt, "s", $name);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_store_result($stmt);
                if(mysqli_stmt_num_rows($stmt)){
                    header("Location: ../post.php?error=nameTaken&" . $comment);
                } else {

                    $sql = "INSERT INTO games (aGame, aCover, aFile, aConsole, aComment, aAuthor) VALUES (?, ?, ?, ?, ?, ?)";
                    $stmt = mysqli_stmt_init($conn);
                    if (!mysqli_stmt_prepare($stmt, $sql)) {
                        header("Location: ../?error=sqlerror");
                        exit();
                    } else {

                        mysqli_stmt_bind_param($stmt, "ssssss", $name, $imgTarget, $gameTarget, $console, $aComment, $_SESSION['userUid']);
                        mysqli_stmt_execute($stmt);

                        move_uploaded_file($_FILES["game"]["tmp_name"], "../" . $gameTarget);
                        move_uploaded_file($_FILES["cover"]["tmp_name"], "../" . $imgTarget);

                        header("Location: ../?success=upload");
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