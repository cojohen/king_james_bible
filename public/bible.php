<?php

/**
  * @author Joe Cohen <joe@dingocode.com>
  */
namespace KJV;

error_reporting(E_ALL);
ini_set('display_errors', '1');

function showBiblePage($slugs = null): void
{
    require '../templates/includes/globals.php';
    require_once '../src/Collection.php';
    require_once '../src/Bible.php';

    // set request variables
    $req['book']  = isset($slugs[1])
    ? ucfirst(filter_var(
        $slugs[1],
        FILTER_SANITIZE_STRING,
        FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH
    ))
    : null;

    $req['book'] = str_replace('_', ' ', $req['book']);
    $req['chap'] = isset($slugs[2])
    ? filter_var(
        $slugs[2],
        FILTER_SANITIZE_STRING,
        FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH
    )
    : null;

    $req['verse'] = isset($slugs[3])
    ? filter_var(
        $slugs[3],
        FILTER_SANITIZE_STRING,
        FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH
    )
    : null;

    $Collection = new Bible\Collection();
    $kjv_book = '';
    $kjv_chapter = '';
    $kjv_content = '';
    $are_you_lost = false;

    // switch on URL slugs
    switch (true) {
        case ($req['book'] && $req['chap'] && $req['verse']):
            if (Bible\Verse::isValidReference($req['book'], intval($req['chap']), intval($req['verse']))) {
                $verse_id = Bible\Verse::getIDByReference(
                    $req['book'],
                    intval($req['chap']),
                    intval($req['verse'])
                );
                $Collection->loadChapter($req['book'], $req['chap']);
                $kjv_book    = $Collection->getBook();
                $kjv_chapter = $Collection->getChapter();
                $kjv_content = $Collection->getBibleText(array($verse_id));
                $kjv_links   = $Collection->getChapterLinks();
            } else {
                $are_you_lost = true;
            }
            break;
        case ($req['book'] && $req['chap']):
            if (Bible\Verse::isValidReference($req['book'], intval($req['chap']))) {
                $Collection->loadChapter($req['book'], $req['chap']);
                $kjv_book    = $Collection->getBook();
                $kjv_chapter = $Collection->getChapter();
                $kjv_content = $Collection->getBibleText(array());
                $kjv_links   = $Collection->getChapterLinks();
            } else {
                $are_you_lost = true;
                header("Location: " . $_site_document_root . 'bible/');
            }
            break;
        case ($req['book']):
            // Go to chapter 1 if no chapter specified
            header("Location: 1/");
            die();
            break;
        default:
            $are_you_lost = true;
            break;
    }
    ?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>
            <?=$kjv_book . ' ' . $kjv_chapter
            . (isset($req['verse']) and  $req['verse'] != '' ? ':'
            . $req['verse'] : '' );
            ?>
        </title>
    <?php
        include '../templates/includes/styles.php';
        include '../templates/includes/jquery.php';
        include '../templates/includes/favicon.php';
    ?>
    </head>
    <body>
        <header id="page-header">
            <!--<div id="logo">
                    <a href="<?=$_site_document_root;?>">
                        <h1>Search <b class="KJV">KJV</b></h1>
                    </a>
            </div>-->
            <div id="bible-navigation">
                <?php
                //include 'includes/bible-navigation.php';
                ?>
            </div>
        </header>
    <?php
    if ($are_you_lost) {
            // Display navigation menu
            $nav_menu_links = Bible\Bible::getBooksAsLinks();
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
        <!----------------------------------------------------------------------
        --
        --      Navigation
        --
        ----------------------------------------------------------------------->
        <nav id="bible-nav">
    <?php
        if ($kjv_links['previous']) {
    ?>
            <a href="<?=$kjv_links['previous']['link'];?>"><?=$kjv_links['previous']['title'];?></a>
    <?php    
        }
    ?>    
            <h2 id="nav-title"><?=$kjv_book;?>&nbsp;<?=$kjv_chapter;?></h2>
    <?php
        if ($kjv_links['next']) {
    ?>        
            <a href="<?=$kjv_links['next']['link'];?>"><?=$kjv_links['next']['title'];?></a>
    <?php
        }
    ?>
        </nav>
        <!----------------------------------------------------------------------
        --
        --      Bible Content
        --
        ----------------------------------------------------------------------->
        <main id="bible">
            <h2 id="book-title"><?=$kjv_book;?></h2>
            <h3 class="chapter-title">Chapter <?=$kjv_chapter;?></h3>
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
