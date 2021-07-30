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

$csv_output_location    = 'csv/';
$input_file_location     = 'csv/';

/**
 * Create the file we will write to
 */
$csv_file_name = 'KJV-BOOKS-IDs.csv';
$csv_path = $csv_output_location.$csv_file_name;
file_put_contents($csv_path, '', FILE_APPEND);

$lineCount = 1;

$handle = fopen($input_file_location."KJV.BOOKS.csv", "r");
if ($handle) {
    while (($line = fgets($handle)) !== false) {
        // process the line read.
        file_put_contents($csv_path, "(".$lineCount.", ".$line.")", FILE_APPEND);
        $lineCount++;
    }

    fclose($handle);
} else {
    // error opening the file.
} 


?>