$(function(){
    $("#menu").jstree({
        "types" : {
            "types" : {
                "default" : {
                    "select_node" : function(e) {
                        this.toggle_node(e);
                        if ($(e).hasClass('jstree-leaf')) {
                            location.href=$(e).children('a').attr('href');
                        }
                        else {
                            return false;
                        }
                    }
                }
            }
        },
        "core" : {
            "animation" : 0
        },
        "themes": {
            "theme": "default",
            "dots": false,
            "icons": false
        },
        "plugins" : ["themes", "html_data", "types", "cookies", "ui"]
    });

    $('#menu').css('display', 'block');

    /**
     * Source:
     * http://www.jankoatwarpspeed.com/post/2009/08/20/Table-of-contents-using-jQuery.aspx
     */
    $("h1, h2, h3").each(function(i) {
        var current = $(this);
        current.attr("id", "title" + i);
        $("#table-of-contents").append("<a id='link" + i + "' " +
            "href='#title" + i + "' " +
            "title='" + current.prop("tagName") + "'>&#187; " + current.html() + "</a>");
    });

    $("h1, h2, h3").append(' <a href="#top">&uarr;</a>');

    $('#toggle-toc').click(function(e){
        $('#table-of-contents').slideToggle();
    });

    $('#search').keypress(function(event){
        if (event.which == 13) {
            event.preventDefault();
            $('#menu .jstree-leaf').show();
            $.post('/search.php', {search: $('#search').val()}, function(response){
                console.log(response);
                // expand menu
                $('#menu').jstree('open_all');
                $('#menu .jstree-leaf').each(function(i, elem){
                    title = $(elem).attr('title');
                    if ($.inArray(title, response) == -1) {
                        $(elem).hide();
                    }
                });
            }, 'json');
        }
    });
});