<?php
/***********************************************************
*      Title:      index.php
*      Author:     Joe Cohen
*      Contact:    <deskofjoe@gmail.com>
*      GitHub:     https://github.com/cojohen
* 
*      Purpose:    Process URL requests for this domain.
* Rewrite URLs into create clean URLs. Direct flow by serving
* appropriate page resources.
*
*   Ex. http://this-site/bible.php?book=Genesis&chapter=1
*               will be rewritten to:
*       http://this-site/bible/genesis/1/
*
*      
***********************************************************/
require_once 'includes/globals.php';

$requestURI = explode('/', $_SERVER['REQUEST_URI']);
$scriptName = explode('/',$_SERVER['SCRIPT_NAME']);

for ($i = 0; $i < sizeof($scriptName); $i++) {
    if ($requestURI[$i] == $scriptName[$i]) unset($requestURI[$i]);
}

$urlSlugs = array_values($requestURI);
$baseSlug = $urlSlugs[0];

switch($baseSlug)
{
    case 'bible' :
        require 'bible.php';
        showBiblePage($urlSlugs);
        break;
    case 'topic':
        require 'topic.php';
        showTopicPage($urlSlugs);
        break;
    case '':
        // Show search
        require 'search.php';
        showSearchPage();
        break;
    default:
        // 404
        require '404.php';
        show404Page();
        break;
}

?>