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
        <title>KJV Search</title>

        <!-- CSS -->
        <link href="assets/css/style.css?" rel="stylesheet" type="text/css">
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
                    
                </ul>
            </div>
        </main>
        <script type="text/javascript">
        /**
         *  Bind enter key press to submit button
        */
        $(document).keypress( (e) => {
            if (e.which == 13){
                $("#search-submit").click();
            }
        });
   
        const apiURL = 'http://localhost/php/kjv/api/search.api.php';

        /**
         *  Process api call and JSON response
        */
        $("#search-submit").on('click', function(){
            let searchTerm = $("#search-input").val();
            $.ajax({
                url : apiURL,
                data: {
                q: searchTerm
                },
                success: function( result ){
                    if (result && result != '') {
                        // result is JSON as string
                        const rows = JSON.parse(result);
                        results = rows.results;

                        var list_li = '';
                        
                        for (var i = 0; i < results.length; i++) {

                            const verse = results[i];
                            
                            list_li += '<li>';
                            list_li += '<div>' + verse["book"] + '</div>';
                            list_li += '<div>' + verse["chap"] + '</div>';
                            list_li += '<div>' + verse["verse"]+ '</div>';
                            
                            let verse_mod = verse["text"];
                            
                            searchTerm.split(" ").forEach( (item) =>
                            {
                                verse_mod = verse_mod.replace(item, '<b class="searchTerm">'+item+'</b>');
                            });
                            
                            list_li += '<div>' + verse_mod + '</div>';
                            list_li += '</li>';
                        }

                        $("#search-results-list").html(list_li);
                    } else {
                        $("#search-results-list").html('No results');
                    }
                }
            });
        });
        </script>
    </body>
</html>