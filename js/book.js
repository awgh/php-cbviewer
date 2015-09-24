
jQuery.preloadImages = function()
{
	for(var i=0;i<arguments.length;i++)
	{
		jQuery("<img>").attr("src",arguments[i]);
	}
}

// from: stackoverflow user brahn
var waitForFinalEvent = (function () {
    var timers = {};
    return function (callback, ms, uniqueId) {
        if (!uniqueId) {
            uniqueId = "Don't call this twice without a uniqueId";
        }
        if (timers[uniqueId]) {
            clearTimeout (timers[uniqueId]);
        }
        timers[uniqueId] = setTimeout(callback, ms);
    };
})();

function prev_page()
{
    if( pageIndex > 0 ) {
        pageIndex--;
        get_page();
    }
    console.log("prev pageIndex: "+pageIndex);
}

function next_page()
{
    if( pageIndex < pageTable.length-1) {
        pageIndex++;
        get_page();
    }
    console.log("next pageIndex: "+pageIndex);
}

var angle = 0;
var imgwidth = 0;
var imgheight = 0;
function rotate_page()
{
    angle = (angle+90)%360;

switch(angle){
    case 90:
        $('#comicbookpage img').animate({"transform": "rotate(90deg)"}, 0);
        break;
    case 180:
        $('#comicbookpage img').animate({"transform": "rotate(180deg)"}, 0);
        break;
    case 270:
        $('#comicbookpage img').animate({"transform": "rotate(270deg) translateX(-"+(imgheight*0.5)+")"}, 0);
        break;
    default:
        $('#comicbookpage img').animate({"transform": "rotate(0deg)"}, 0);
}

}

function get_page()
{
	$("#loading").show();
	$('body').animate({scrollTop:0},500);
    $('#pageInput').val(pageIndex+1);

    console.log("sending page: "+pageTable[pageIndex][0]);
    $.getJSON("view_page.php",
        {
            page: pageTable[pageIndex][0],
            path: relative_path,
            maxwidth: $('#windowsize').val()
        },  function(){}
    )
    .done(function(parsedResponse,statusText,jqXhr)
     {
        var img = new Image();
        img.className = "myimage";
        img.src = parsedResponse['src'];
        imgwidth = parsedResponse['width'];
        imgheight = parsedResponse['height'];

        img.onload = $('#comicbookpage').fadeOut('fast',
            function(){
                $('#comicbookpage').html(
                    "<a href='#' onClick='next_page();'><img width='"
                        + imgwidth + "' height='"
                        + imgheight + "' src='"
                        + parsedResponse['src'] + "' class='myimage'/></a>");
            });
        $('#comicbookpage').fadeIn('fast');
        $('#loading').hide();
    });
}


$(window).resize(function () {
    waitForFinalEvent(function(){
        $('#windowsize').val(window.outerWidth - 60);
        get_page();
    }, 500, "window resize event handler");
});

var tbshow = false;
var timer = null;
function hideToolbar() {
    $('.floater').fadeOut('slow');
    tbshow = false;
}
function resetTimer() {
    if(timer != null)
        clearTimeout(timer);
    timer = setTimeout(hideToolbar, 2000);

    if(!tbshow) {
        $('.floater').fadeIn('fast');
        tbshow = true;
    }
}

$(document).ready(function() {
	$('#windowsize').val(window.outerWidth - 60);

    jQuery(window).scroll(resetTimer);

    $('.floater').on({
        mouseenter: function() {
            if(timer != null)
                clearTimeout(timer);
        },
        mouseleave: function() {
            resetTimer();
        }
    });

    $( "#pageInput").on('change', function(){
        pageIndex = $("#pageInput").val() -1;
        console.log("inevent: "+pageIndex);
        get_page();
    });
    get_page();
});
