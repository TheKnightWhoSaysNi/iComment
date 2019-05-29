<?php //on utilisera un php procédural parceque je comprend pas l'object oriented

if(isset($_POST['post-submit'])) {  //la plupart des commentaires pour ce code sont dans signup.inc.php parceque flemme   isset($_POST['post-submit'])
    require 'dbh.inc.php';
    session_start();

    if(!($_POST["website"] == "" || $_POST["url"] == "" || $_POST["comment"] == "" || $_POST["review"] == "")){
        $website =  $_POST['website'];
        $url =  $_POST['url'];
        $comment =  $_POST['comment'];
        $review =  $_POST['review'];
        $author = $_SESSION['userUid'];
        $timeZone = date_default_timezone_get();
        $dateTime = $_SERVER['REQUEST_TIME']; //au format Unix time
    } else {
        header("Location: ../post.php?website=" . $_POST["website"] . "&comment=" . $_POST["comment"] . "&url=" . $_POST["url"] . "&review=" . $_POST["review"] . "&error=emptyfields");
        exit();
    }

    $cs = curl_init($url);
    curl_setopt($cs, CURLOPT_NOBODY, true);
    curl_setopt($cs, CURLOPT_FOLLOWLOCATION, true);
    curl_exec($cs); // donne 200 si le ping se passe bien
    $status = curl_getinfo($cs, CURLINFO_HTTP_CODE);

    if ($status != 200){
         header("Location: ../post.php?website=" . $_POST["website"] . "&comment=" . $_POST["comment"] . "&review=" . $_POST["review"] . "&error=websitenotreached");
         exit();
    }

    require 'dbh.inc.php';

        $sql = "INSERT INTO articles (aWebsite, aComment, aAuthor, aDateTime, aTimeZone, aUrl, aReview) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_stmt_init($conn);
        if (!mysqli_stmt_prepare($stmt, $sql)) {
            header("Location: ../?error=sqlerror");
            exit();
        } else {
            mysqli_stmt_bind_param($stmt, "sssssss", $website, $comment, $author, $dateTime, $timeZone, $url, $review);
            if(mysqli_stmt_execute($stmt)){
                header("Location: ../?success=post");
            }
            exit();
        }

} else { header("Location: ../");}