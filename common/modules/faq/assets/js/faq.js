var display = '';
var url = '';
var anchor = '';
var anchor_arr = [];
var url_arr = [];
var $this = '';

$(function(){
	// FAQ	
	$('.panel-heading').click(function(e){
		$this = $(this);
		slide_faq($this)
	});
	
	url = window.location.href; 
	anchor = url.indexOf("#"); 
	
	if(anchor > 0)
	{
		anchor_arr = url.split("#");
		
		if(typeof(anchor_arr[1]) != "undefined")
		{
			url_arr = anchor_arr[1].split("_");
			$this = $('#heading_' + url_arr[2]);
			slide_faq($this);
		}
	}
});

function slide_faq($this)
{
	display = $this.next(".answer").css("display");
		
	if(display == "none") 
	{
		$this.next(".answer").slideDown("slow");
		$this.find("a").removeClass('collapsed').addClass('');
	} 
	else
	{
		$this.next(".answer").slideUp("slow");
		$this.find("a").removeClass('').addClass('collapsed');
	}
}
