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

    public function getListItem() {
        if (!isset($this->text)) { $this->fetch(); } 
        
        
        return '<li>'.$this->text.'</li>';
    }

    public function getJSON() {
        if (!isset($this->text)) { $this->fetch(); } 
        
        $JSON = '{'.
                    '"book":"'.$this->book.'",'.
                    '"chap":"'.$this->chapter.'",'.
                    '"verse":"'.$this->verse.'",'.
                    '"text":"'.$this->text.'"'.
                '}';

        return $JSON;
    }
    /**
     * @param int[] flags -- verses to flag
     */
    public function getPageText($flags = array()) {
        if (!isset($this->text)) { $this->fetch(); }
        
        $pageText  = '<span class="v">' . $this->verse . '</span>';
        $verseText = $this->text;

        foreach ($flags as $flag) {
            if ($this->id == $flag) {
                $verseText = '<span class="flag">'.$this->text.'</span>';
            }
        }
        
        $pageText .= $verseText;

        return $pageText;
    }
    /**
     * @param string book
     * @param int chapter
     * @param int verse 
     * Ex: isValidReference('John', 3, 16)  returns TRUE
     *     isValidReference('John', 3)      returns TRUE
     *     isValidReference('John')         returns TRUE
     *     isValidReference(3, 16)          returns FALSE
     */
    public static function isValidReference($book = '', $chap = 0, $verse = 0) {
        if($book AND $chap AND $verse){
            $q = "SELECT text.id FROM kjv.text LEFT JOIN books ON text.book=books.id WHERE (books.book='$book' AND text.chapter=$chap AND text.verse=$verse) LIMIT 1"; 
        } elseif ($book AND $chap) {
            $q = "SELECT text.id FROM kjv.text LEFT JOIN books ON text.book=books.id WHERE (books.book='$book' AND text.chapter=$chap) LIMIT 1";
        } elseif ($book) {
            $q = "SELECT books.id FROM kjv.books WHERE (books.book='$book') LIMIT 1";
        } else {
            return FALSE;
        }

        $db = new db();

        return ($db->query($q)->numRows() > 0) 
            ?   TRUE
            :   FALSE;
    }
    /**
     * @param string book
     * @param int chapter
     * @param int verse 
     * Ex: getIDByReference('John', 3, 16)  returns int id for verse
     */
    public static function getIDByReference($book = '', $chap = 0, $verse = 0) {
        if($book AND $chap AND $verse){
            $q = "SELECT text.id AS id FROM kjv.text LEFT JOIN books ON text.book=books.id WHERE (books.book='$book' AND text.chapter=$chap AND text.verse=$verse) LIMIT 1"; 
        
            $db = new db();
            $id = $db->query($q)->fetchArray()['id'];
            
        }
        return $id;        
    }
}
?>
