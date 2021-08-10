<?php

 /**
  * @author Joe Cohen <joe@dingocode.com>
  */
namespace KJV;

$requestURI = explode('/', $_SERVER['REQUEST_URI']);
$scriptName = explode('/', $_SERVER['SCRIPT_NAME']);

for ($i = 0; $i < sizeof($scriptName); $i++) {
    if ($requestURI[$i] == $scriptName[$i]) {
        unset($requestURI[$i]);
    }
}

$urlSlugs = array_values($requestURI);
$baseSlug = isset($urlSlugs[0]) ? $urlSlugs[0] : false;

switch ($baseSlug) {
    case 'bible':
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
