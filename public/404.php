<?php

 /**
  * @author Joe Cohen <joe@dingocode.com>
  */
function show404Page()
{
    ?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>404 - Not Found</title>
    <?php
        include '../templates/includes/globals.php';
        include '../templates/includes/styles.php';
        include '../templates/includes/jquery.php';
        include '../templates/includes/favicon.php';
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