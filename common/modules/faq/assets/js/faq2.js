$(function(){
	// FAQ
	$('.faq .name').click(function(e){
		if($(this).parent().hasClass('active')){
			$(this).parent().removeClass('active').find('.data').slideUp(300);
		}else{
			$('.faq .item').removeClass('active');
			$('.faq .data').slideUp(300);
			$(this).parent().addClass('active').find('.data').slideDown(300);
		}
	});
	
	var url = window.location.href; 
	var anchor = url.indexOf("#"); 
	
	if(anchor > 0)
	{
		var anchor_arr = url.split("#");
		
		if(typeof(anchor_arr[1]) != "undefined")
		{
			$("#name_" + anchor_arr[1]).parent().removeClass("active").find(".data").slideDown(300);
		}
	}
});
