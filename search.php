<?php
/**
 *      Title:      Search
 *      Author:     Joe Cohen
 *      Contact:    <deskofjoe@gmail.com>
 *      GitHub:     https://github.com/cojohen
 * 
 *      Purpose:    Search the database of KJV verses
 *      
 */
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title></title>

        <!-- CSS -->
        <link href="css/style.css" rel="stylesheet" type="text/css">

    </head>
    <body>
        <main id="main">
            <h1>Search the text</h1>
            <input type="text" id="search-input" name="search">
            <input type="submit" id="search-submit" name="submit">
            <div id="search-results">
                <ul id="search-results-list">
                    <li>
                        <div class="book">Genesis</div>
                        <div class="chapter">1</div>
                        <div class="verse">1</div>
                        <div class="verse-text">In the beginning God created the heaven and the earth.</div>
                    </li>
                    <li>
                        <div class="book">Genesis</div>
                        <div class="chapter">2</div>
                        <div class="verse">1</div>
                        <div class="verse-text">Thus the heavens and the earth were finished, and all the host of them.</div>
                    </li>
                </ul>
            </div>
        </main>
    </body>
</html>