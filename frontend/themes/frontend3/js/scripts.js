$(function(){
	
	$('body').on('click', '.feedback', function ()
	{	
		if($('#modal-message').data('bs.modal').isShown) 
		{
			$('#modal-message').find('#modal-message-text').load($(this).attr('value'));
			//dynamiclly set the header for the modal via title tag
			$('#modal-message-header').html('<h4>' + $(this).attr('title') + '</h4>');
		} 
		else 
		{
			//if modal isn't open; open it and load content
			$('#modal-message').modal('show').find('modal-message-text').load($(this).attr('value'));
			//dynamiclly set the header for the modal via title tag
			$('#modal-message-header').html('<h4>' + $(this).attr('title') + '</h4>');
		}
	});
});

function open_tab(link)
{
	var win = window.open();
	win.document.write('<script type="text/javascript" src="http://localhost/www/spillovermoney/js/jquery.js"></script><script type="text/javascript" src="http://localhost/www/spillovermoney/js/counter.js"></script><div><div class="counter">0</div></div><iframe width="100%" height="100%" src="' + link + '" frameborder="0" allowfullscreen></iframe>');	
}
