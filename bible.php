<?php
/**
 *      Title:      Bible
 *      Author:     Joe Cohen
 *      Contact:    <deskofjoe@gmail.com>
 *      GitHub:     https://github.com/cojohen
 * 
 *      Purpose:    Display section of the Bible as a single
 *          chapter or verse.  
 */


function showBiblePage($slugs = NULL) {
    require 'includes/globals.php';
    require_once 'lib/collection.class.php';

    /*
        [0] => bible      
        [1] => genesis 
        [2] => 1 
        [3] => 2
    */
    // set request variables
    $req['book']  = isset($slugs[1]) ? filter_var($slugs[1], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH) : NULL;
    $req['chap']  = isset($slugs[2]) ? filter_var($slugs[2], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH) : NULL;
    $req['verse'] = isset($slubs[3]) ? filter_var($slugs[3], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH) : NULL;
    print_r($req);
    $Collection = new Collection();
    $kjv_book = '';
    $kjv_chapter = '';
    $kjv_content = '';

    // switch on request variables
    switch (TRUE) {
        case ($req['book'] && $req['chap'] && $req['verse']):
            
            break;
        case ($req['book'] && $req['chap']):
            $Collection->loadChapter($req['book'], $req['chap']);
            $kjv_book = $Collection->getBook();
            $kjv_chapter = $Collection->getChapter();
            $kjv_content = $Collection->toBibleText();
            break;
        case ($req['book']):
            // Go to chapter 1 if no chapter specified
            header("Location: 1/");
            die();
            break;
        default:
            // Show navigation menu
            $kjv_content = "<h2>navigation menu</h2><p>Gen, Ex, Lev, ...</p>";
            break;
    }

?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title><?=$_site_title;?>-<?=$req['book'].' '.$req['chap'];?></title>
<?php 
        include 'includes/styles.php';
        include 'includes/jquery.php';
        include 'includes/favicon.php';
?>
    </head>
    <body>
        <header id="page-header">
            <div id="logo">
                    <a href="<?=$_site_document_root;?>"><h1 class="text-reflect">Search <b class="KJV">KJV</b></h1></a>
            </div>
            <div id="bible-navigation">
                <?php
                include 'includes/bible-navigation.php';
                ?>
            </div>
        </header>
        <main id="bible">   
            <h2 id="book-title"><?=$kjv_book;?></h2>
            <h3 class="chapter-title"><?=$kjv_chapter;?></h3>
            <p class="bible-text"><?=$kjv_content;?></p>
        </main>
    </body>
</html>
<?php
}
?>