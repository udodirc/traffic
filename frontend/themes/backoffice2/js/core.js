function showMainPageModalWindow() 
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
		$('#modal-message-header').html('<h4>' + 'Внимание!' + '</h4>');
	}
}

function copyToClipboard(element) 
{
	var $this = $(element);
	var $temp = $("<input>");
	$("body").append($temp);
	$temp.val($this.text()).select();
	document.execCommand("copy");
	$temp.remove();
    $this.select();

    // Work around Chrome's little problem
    $this.mouseup(function() {
        // Prevent further mouseup intervention
        $this.unbind("mouseup");
        return false;
    });
}
