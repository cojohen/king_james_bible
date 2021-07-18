<?php
/**
 *      Title:      verse.class.php
 *      Author:     Joe Cohen
 *      Contact:    <deskofjoe@gmail.com>
 *      GitHub:     https://github.com/cojohen
 * 
 *      Purpose:    A single verse from the KJV      
 */
require_once(__DIR__.'/db.class.php');

class Verse {

    public int $id;
    public string $book;
    public int $chapter;
    public int $verse;
    protected string $text;

    public function __construct($id = 0) {
        $this->setID($id);
    }

    public function setID($id) {
        $this->id = intval($id);
    }

    public function fetch() {
        
        $q = "SELECT books.book, text.chapter, text.verse, text.text  FROM kjv.text LEFT JOIN books ON text.book=books.id WHERE text.id=".$this->id;
        $db = new db();
        $row = $db->query($q)->fetchArray();
        
        $this->book     = $row['book'];
        $this->chapter  = $row['chapter'];
        $this->verse    = $row['verse'];
        $this->text     = $row['text']; 
    }

    public function toListItem() {
        if (!isset($this->text)) { $this->fetch(); } 
        
        
        return '<li>'.$this->text.'</li>';
    }

    public function toJSON() {
        if (!isset($this->text)) { $this->fetch(); } 
        
        $JSON = '{'.
                    '"book" : "'.$this->book.'",'.
                    '"chap" : "'.$this->chapter.'",'.
                    '"verse": "'.$this->verse.'",'.
                    '"text" : "'.$this->text.'"'.
                '}';

        return $JSON;
    }
}

?>