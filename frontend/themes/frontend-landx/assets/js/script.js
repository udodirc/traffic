var url = '';
var urlList = [];

$(document).ready(function()
{
	$("#login-form").submit(function(e) 
    {
		e.preventDefault(); // avoid to execute the actual submit of the form.
		
		url = 'login';
		data = $(this).serialize();
		make_action(url, data);
		
		return false;
	});
	
	$("#signup-form").submit(function(e) 
    {
		e.preventDefault(); // avoid to execute the actual submit of the form.
		
		url = 'signup';
		data = $(this).serialize();
		
		make_action(url, data);
		 
		return false;
	});
	
	$("#contact-form").submit(function(e) 
    {
		e.preventDefault(); // avoid to execute the actual submit of the form.
		
		url = 'contacts';
		data = $(this).serialize();
		
		make_action(url, data);
		 
		return false;
	});
	
	$("#restore-password").submit(function(e) 
    {
		e.preventDefault(); // avoid to execute the actual submit of the form.
		
		url = 'restore-password';
		data = $(this).serialize();
		
		make_action(url, data);
		 
		return false;
	});
	
	$("#login-link").click(function () 
	{
		$(".error").html('');
		$(".success").html('');
		
		$("#login-modal").modal("show");
	});
			
	$("#signup-link").click(function () 
	{
		$(".error").html('');
		$(".success").html('');
		
		$("#signup-modal").modal("show");
	});
	
	$("#restore-password-link").click(function () 
	{	
		$(".error").html('');
		$(".success").html('');
		
		$('.fade.modal.in').modal('hide');
				
		$("#restore-password-modal").modal("show");
	});
});

function make_action(url, data)
{
	$.ajax(
	{
		url: url,
		type: 'POST',
		data: data,
		dataType: 'json',
		success: function(res)
		{	
			msg = res.msg;
			$(".error").html('');
			$(".success").html('');
			
			if(res.result != '' && res.result != 'false')
			{	
				if(url == 'login')
				{	
					window.location.replace(res.url);
				}
				else if(url == 'restore-password' || url == 'signup' | url == 'contacts')
				{
					$(".success").html('<span class="colored-text icon_check"></span>' + msg + '<br/>');
					$(".success").show();
				}
			}
			else
			{
				errors = res.errors;
				var errorList = '';
				
				if((Object.keys(errors).length > 0))		
				{							
					$.each(errors[1], function (error_field_id, msg) 
					{	
						errorList += '<span class="colored-text icon_error-circle_alt"></span>' + msg + '<br/>';
					});
				}
				else
				{
					errorList += '<span class="colored-text icon_error-circle_alt"></span>' + msg + '<br/>';
				}
					
					
				if(errorList != '')
				{
					if($('.error').is(':empty')) 
					{
						$(".error").append(errorList);
						$(".error").show();
					}
				}
			}
		},
		error: function()
		{	
			var msg = '';
				
			if (jqXHR.status === 0) {
				msg = 'Not connect.\n Verify Network.';
			} else if (jqXHR.status == 404) {
				msg = 'Requested page not found. [404]';
			} else if (jqXHR.status == 500) {
				msg = 'Internal Server Error [500].';
			} else if (exception === 'parsererror') {
				msg = 'Requested JSON parse failed.';
			} else if (exception === 'timeout') {
				msg = 'Time out error.';
			} else if (exception === 'abort') {
				msg = 'Ajax request aborted.';
			} else {
				msg = 'Uncaught Error.\n' + jqXHR.responseText;
			}
				
			alert(msg);
		}
	});
}
