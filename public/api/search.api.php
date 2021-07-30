<?php
 /**
  * @author Joe Cohen <joe@dingocode.com>
  */
require_once('../../src/Collection.php');

$response = '';

if (isset($_REQUEST['q'])) {
    $searchRequest = $_REQUEST['q'];
    
    $strippedRequest = filter_var($searchRequest, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH | FILTER_FLAG_NO_ENCODE_QUOTES);
    $response = '';
    
    $verses = new Collection();
    $verses->searchVerses($strippedRequest);
    $response = $verses->getJSON();
} 
    
echo $response;
?>