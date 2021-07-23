<?php
/**
 *      Title:      Search API
 *      Author:     Joe Cohen
 *      Contact:    <deskofjoe@gmail.com>
 *      GitHub:     https://github.com/cojohen
 * 
 *      Purpose:    Process search requests and return results
 *      
 */
//require_once(__DIR__.'/../lib/db.class.php');
require_once(__DIR__.'/../lib/collection.class.php');

$response = '';

if (isset($_REQUEST['q'])) {
    $searchRequest = $_REQUEST['q'];
    
    $strippedRequest = filter_var($searchRequest, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH | FILTER_FLAG_NO_ENCODE_QUOTES);
    $response = '';
    
    $verses = new Collection();
    $verses->searchVerses($strippedRequest);
    $response = $verses->toJSON();

} 
    
echo $response;
?>