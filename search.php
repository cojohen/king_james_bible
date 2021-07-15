<?php
/**
 *      Title:      Search
 *      Author:     Joe Cohen
 *      Contact:    <deskofjoe@gmail.com>
 *      GitHub:     https://github.com/cojohen
 * 
 *      Purpose:    Search the database of KJV verses
 *      
 */
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title></title>

        <!-- CSS -->
        <link href="css/style.css" rel="stylesheet" type="text/css">
        <!-- jQuery -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    </head>
    <body>
        <main id="main">
            <h1>Search the text</h1>
            <input type="text" id="search-input" name="search">
            <input type="button" id="search-submit" name="submit" value="Search">
            <div id="search-results">
                <ul id="search-results-list">
                    <li>
                        <div class="book">Genesis</div>
                        <div class="chapter">1</div>
                        <div class="verse">1</div>
                        <div class="verse-text">In the beginning God created the heaven and the earth.</div>
                    </li>
                    <li>
                        <div class="book">Genesis</div>
                        <div class="chapter">2</div>
                        <div class="verse">1</div>
                        <div class="verse-text">Thus the heavens and the earth were finished, and all the host of them.</div>
                    </li>
                </ul>
            </div>
        </main>

        <script type="text/javascript">

        const apiURL = 'http://localhost/php/kjv/api/search.api.php';

        $("#search-submit").on('click', function(){
            //alert(searchTerm);
            let searchTerm = $("#search-input").val();
            $.ajax({
                url : 'http://localhost/php/kjv/api/search.api.php',
                data: {
                q: searchTerm
                },
                success: function( result ){
                    // result is JSON
                    const rows = JSON.parse(result);
                    const results = rows.results;

                    var list_li = '';
                    
                    for(var i=0; i < results.length; i++){

                        const verse = results[i];
                        
                        list_li += '<li>';
                        list_li += '<div>'+verse["book"]+'</div>';
                        list_li += '<div>'+verse["chap"]+'</div>';
                        list_li += '<div>'+verse["verse"]+'</div>';
                        
                        var verse_mod = verse["text"];
                        var search_words = searchTerm.split(" ");
                        
                        search_words.forEach(function(item){
                            verse_mod.replace(item, '<h2>'+item+'</h2>');
                            //alert(item +'is now <b>'+item+'</b>');
                        });
                        
                        list_li += '<div>'+verse_mod+'</div>';
                        list_li += '</li>';
                    }

                    $("#search-results-list").html(list_li);
                }
            });
        });

        </script>
    </body>
</html>