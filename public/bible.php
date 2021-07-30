<?php
 /**
  * @author Joe Cohen <joe@dingocode.com>
  */
function showBiblePage($slugs = NULL) {
    require '../templates/includes/globals.php';
    require_once '../src/Collection.php';
    require_once '../src/Bible.php';

    // set request variables
    $req['book']  = isset($slugs[1]) ? ucfirst(filter_var($slugs[1], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH)) : NULL;
    $req['book']  = str_replace('_', ' ', $req['book']);
    $req['chap']  = isset($slugs[2]) ? filter_var($slugs[2], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH) : NULL;
    $req['verse'] = isset($slugs[3]) ? filter_var($slugs[3], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH) : NULL;
    //print_r($req);

    $Collection = new Collection();
    $kjv_book = '';
    $kjv_chapter = '';
    $kjv_content = '';
    $are_you_lost = FALSE;

    // switch on URL slugs
    switch (TRUE) {
        case ($req['book'] && $req['chap'] && $req['verse']):
            if (Verse::isValidReference($req['book'], intval($req['chap']), intval($req['verse']))) {
                $verse_id = Verse::getIDByReference($req['book'], intval($req['chap']), intval($req['verse']));
                $Collection->loadChapter($req['book'], $req['chap']);
                $kjv_book    = $Collection->getBook();
                $kjv_chapter = $Collection->getChapter();
                $kjv_content = $Collection->getBibleText(array($verse_id));
            } else {
                $are_you_lost = TRUE;
            }
            break;
        case ($req['book'] && $req['chap']):
            if (Verse::isValidReference($req['book'], intval($req['chap']))) {
                $Collection->loadChapter($req['book'], $req['chap']);
                $kjv_book    = $Collection->getBook();
                $kjv_chapter = $Collection->getChapter();
                $kjv_content = $Collection->getBibleText();
            } else {
                $are_you_lost = TRUE;
                header("Location: " . $_site_document_root . 'bible/');
            }
            break;
        case ($req['book']):
            // Go to chapter 1 if no chapter specified
            header("Location: 1/");
            die();
            break;
        default:
            $are_you_lost = TRUE;
            break;
    }

?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title><?=$_site_title;?>-<?=$req['book'].' '.$req['chap'];?></title>
<?php 
        include '../templates/includes/styles.php';
        include '../templates/includes/jquery.php';
        include '../templates/includes/favicon.php';
?>
    </head>
    <body>
        <header id="page-header">
            <div id="logo">
                    <a href="<?=$_site_document_root;?>"><h1>Search <b class="KJV">KJV</b></h1></a>
            </div>
            <div id="bible-navigation">
                <?php
                //include 'includes/bible-navigation.php';
                ?>
            </div>
        </header>
<?php
        if ($are_you_lost) {
            // Display navigation menu
            $nav_menu_links = Bible::getBooksAsLinks();
?>
        <main id="nav-menu">
            <h2>Select a book</h2>
            <ul id="nav-links">
                <?=$nav_menu_links;?>
            </ul>  
        </main>
<?php            
        } else {
?>
        <main id="bible">   
            <h2 id="book-title"><?=$kjv_book;?></h2>
            <h3 class="chapter-title"><?=$kjv_chapter;?></h3>
            <p class="bible-text"><?=$kjv_content;?></p>
        </main>
<?php
        }
?>
    </body>
</html>
<?php
}
?>
