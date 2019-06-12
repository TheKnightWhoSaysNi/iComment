<?php
    if(!isset($_SESSION)) { session_start(); }

    if(!isset($_SESSION["userUid"])){ header("Location: index.php#login"); }

    $title = "Gametop - Post";
    require "header.php";
        if(isset($_GET['name'])){
            $name = $_GET['name'];
    } else {$name = '';}
    if(isset($_GET['comment'])){
        $comment = $_GET['comment'];
    } else {$comment = '';}


?>

<script src="post.js"></script>

<script class="jsbin" src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>

<section>

    <div id="postContainer">
        <form autocomplete="off" action="includes/post.inc.php" method="post" enctype="multipart/form-data">
            <input type="text" autocomplete="off" name="name" placeholder="Game's name" required value="<?php echo $name ?>" class="postInfo">
            <div>
                <div>
                    <h3>Import a cover for the game:</h3>
                    <input type="file" name="cover" accept="image/jpeg, image/png" required onchange="readURL(this); document.getElementById('commentPost').rows = 18;">
                    <img id="cover" src="#" alt="your image" style="display: none;" />
                </div>
                <div>
                    <textarea name="comment" placeholder="Comment" id="commentPost" cols="25" rows="3"><?php echo $comment ?></textarea>
                </div>            
            </div>
            <div>
                <h3>Import the game: </h3>
                <input type="file" name="game" accept=".rar,.zip" required>
            </div>

            <div>
                <h3>Pick a console</h3>
                <input name="console" list="consoles" required>
                <datalist id="consoles">
                    <?php
                        $consoles = array("snes", "nes", "game boy", "atari 2600");
                        foreach ($consoles as $console) { ?>
                            <option value="<?php echo $console ?>">
                        <?php }
                    ?>
                    
                </datalist>
            </div>
            
            
            
            <button type="submit" name="post-submit" id="commentBtn">Post</button>
        </form>
    </div>

</section>

<?php
    require "footer.php";   
?>
