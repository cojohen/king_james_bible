<?php 
/**
 *      Title:      BuildCSV.php
 *      Author:     Joe Cohen
 *      Contact:    <deskofjoe@gmail.com>
 *      GitHub:     https://github.com/cojohen
 * 
 *      Purpose:
 *      
 *      Iterate through the working directory and 
 *      build a CSV file for the KJV, verse by verse
 *      where each row is:
 *      
 *      int book, int chapter, string verse
 *      
 */

require_once('../lib/page.class.php');

$csv_output_location    = '../csv/';
$html_file_location     = '../html/';
/**
 * Build a list of the HTML files to process
 */
$file_list = array_diff(scandir($html_file_location) , array('.', '..'));

/**
 * Create the file we will write to
 */
$csv_file_name = 'KJV.'.time().'.csv';
$csv_path = $csv_output_location.$csv_file_name;
file_put_contents($csv_path, '', FILE_APPEND);

/**
 * Iterate over the files, convert them to CSV and 
 * write the content to external file 
 */
$chap_count = 0;
$page_limit = 1200;
foreach($file_list as $input_file){

    if($chap_count < $page_limit){
        $page = new Page( $html_file_location.$input_file );
        $rows = $page->toCSV();

        if( file_put_contents($csv_path, $rows, FILE_APPEND) ){
            
            echo "wrote chapter ".($chap_count+1)." \n";
        }else{
            echo "failed to write chapter ".($chap_count+1)." \n";
        }
    }
    $chap_count++;
}

?>