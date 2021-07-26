<?php
/**
 *      Title:      404
 *      Author:     Joe Cohen
 *      Contact:    <deskofjoe@gmail.com>
 *      GitHub:     https://github.com/cojohen
 * 
 *      Purpose:    display 404 page
 *      
 */
function show404Page() {
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>404 - Not Found</title>
<?php 
        include 'includes/globals.php';
        include 'includes/styles.php';
        include 'includes/jquery.php';
        include 'includes/favicon.php';
?>
    </head>
    <body>
        <main id="main">   
            <h1><b class="KJV">404</b> Page not found</h1>
        </main>
    </body>
</html>
<?php
}
?>