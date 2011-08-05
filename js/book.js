/**
 * @author mike
 */
jQuery.preloadImages = function()
{
	for(var i=0;i<arguments.length;i++)
	{
		jQuery("<img>").attr("src",arguments[i]);
	}
}
function prev_page()
{
	var mypage = $(".pagename:selected");
	$(mypage).removeAttr("selected");
	$(mypage).prev().attr("selected","selected");
	get_page();
}
function next_page()
{
	var mypage = $(".pagename:selected");
	$(mypage).removeAttr("selected");
	$(mypage).next().attr("selected","selected");
	get_page();
}
function get_page()
{
	$("#loading").show();
	$('body').animate({scrollTop:0},500);
	$.getJSON("view_page.php",
	{
		index:$('#pageinfo').val(),
		page:$("#pageinfo").val(),
		comic:$('#comic').val(),
		maxwidth:$('#windowsize').val()
	},
	function(data)
	{
		var img = new Image();
		img.src = data['src'];
		img.onload=function(){
			$('#comicbookpage').fadeOut('fast',function() {
				$('#comicbookpage').html("<a href='#' onClick='next_page();'><img width='"+data['width']+"' height='"+data['height']+"' src='"+data['src']+"'></a>");}
				);
			$('#comicbookpage').fadeIn('fast');
			$('#loading').hide();	
		}
		
	});
}
$(document).ready(function() {
	$('#windowsize').val(window.outerWidth - 60);	
	get_page();	
});

