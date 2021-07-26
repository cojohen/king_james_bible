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
        success: function(result) {
            if (result && result != '') {
                // result is JSON as string
                const rows = JSON.parse(result);
                const results = rows.collection;
                var list_li = '';

                // build array of words/phrases to make bold
                let boldWords = [];
                const omitWords = ["a", "A", "the", "The", "and", "And" ];

                try {
                    // add "quoted search terms"
                    const re = /"([^"]+)"/g;     
                    searchTerm.match(re).forEach( (quoted) => {
                        quoted = quoted.replaceAll('"', '');
                        if (quoted.trim() != '' )
                            boldWords.push(quoted);
                    });
                } catch (e) { console.log('Quote regex failed: ' + e); }

                // Build array of search terms, omitting omitWords
                searchTerm.split(" ").forEach( (item) =>
                {
                    if (!omitWords.includes(item.replaceAll('"', '')) && item.trim() != '' )
                        boldWords.push(item.replaceAll('"', ''));
                });
                
                // Build the list and make search terms bold
                for (var i = 0; i < results.length; i++) {
                    const verse = results[i];
                    
                    list_li += '<li>';
                    list_li += '<div class="book">' + verse["book"] + '</div>';
                    list_li += '<div class="ref">' + verse["chap"] + ':' + verse["verse"] + '</div>';
                    
                    let verse_mod = verse["text"];

                    // Sort boldWords by string length so "beginning" matches before "beg"
                    boldWords.sort((a,b) => { return b.length - a.length; });

                    boldWords.forEach( (item) =>
                    {
                        const rex = '('+item+')';
                        let bold_re = new RegExp(rex, 'i');
                        verse_mod = verse_mod.replace(bold_re, '<b class="searchTerm">$1</b>');
                        //console.log(verse_mod);
                    });
                    
                    list_li += '<div>' + verse_mod + '</div>';
                    list_li += '</li>';
                }
                //$("#search-results-list").fadeOut().next().delay(100);
                $("#search-results-list").hide().html(list_li).fadeIn("slow");
            } else {
                $("#search-results-list").html('No results').hide().fadeIn("slow");
            }
        }
    });
});