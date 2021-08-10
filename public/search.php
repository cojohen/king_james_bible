<?php

 /**
  * @author Joe Cohen <joe@dingocode.com>
  */
namespace KJV;

function showSearchPage(): void
{
    ?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Search KJV</title>
    <?php
        include '../templates/includes/globals.php';
        include '../templates/includes/styles.php';
        include '../templates/includes/jquery.php';
        include '../templates/includes/favicon.php';
    ?>
        <script defer type="text/javascript" 
        src="<?=$_site_document_root;?>assets/scripts/search.js"></script>
    </head>
    <body>
        <main id="main">
            <div id="logo">
                <h1 id="title">Search <b class="KJV">KJV</b></h1>
            </div>
            <input type="text" id="search-input" name="search">
            <input type="button" id="search-submit" name="submit" value="Search">
            <div id="search-results">
                <ul id="search-results-list"></ul>
            </div>
        </main>
    </body>
</html>
    <?php
}
?>
