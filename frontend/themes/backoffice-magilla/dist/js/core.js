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
