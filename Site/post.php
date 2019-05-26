<?php
    if(!isset($_SESSION)) 
    { 
        session_start(); 
    }
    if(!isset($_SESSION["userUid"])){
        header("Location: index.php#login");
    }
    $title = "iComment - Post";
    require "header.php";
    if(isset($_GET['website'])){
        $website = $_GET['website'];
    } else {$website = '';}
    if(isset($_GET['url'])){
        $url = $_GET['url'];
    } else {$url = '';}
    if(isset($_GET['comment'])){
        $comment = $_GET['comment'];
    } else {$comment = '';}
    if(isset($_GET['review'])){
        $review = $_GET['review'];
    } else {$review = '';}
?>

<section>

    <div id="postContainer">
        <form autocomplete="off" action="includes/post.inc.php" method="post">
            <input type="text" name="website" placeholder="Website/article's name" value="<?php echo $website ?>" class="postInfo">
            <input type="text" name="url" placeholder="Full URL" value="<?php echo $url ?>" class="postInfo">
            <textarea name="comment" placeholder="Your comment" id="commentPost" cols="25" rows="5"><?php echo $comment ?></textarea>
            <div>
                <input type="radio" value="good" name="review" id="good" <?php if($review == "good"){echo "checked";} ?> >Good<br>
                <input type="radio" value="fake" name="review" id="fake" <?php if($review == "fake"){echo "checked";} ?> >Fake<br>
                <input type="radio" value="humor" name="review" id="humor" <?php if($review == "humor"){echo "checked";} ?> >humor<br>
                <input type="radio" value="idk" name="review" id="idk" <?php if($review == "idk"){echo "checked";} ?> >I don't know<br>
            </div>
            <button type="submit" name="post-submit" id="commentBtn">Post</button>
        </form>
    </div>

</section>

<?php
    require "footer.php";
?>
