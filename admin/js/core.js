$(document).ready(function()
{	
	$('#menu-menu').on('change', function() 
	{
		var menu_id = $("#menu-menu option:selected").val();
		
		get_submenu_list_by_menu_id(menu_id)
	});
	
	$('#menu-controller').on('change', function() 
	{
		var controller_id = $("#menu-controller option:selected").val();
		
		get_controller_data_by_controller_id(controller_id);
	});
	
	$('.file_delete').on('click', function() 
	{
		alert(123)
	});
	
	$('#files-upload').on('click', '.file_delete', function (){
        alert(this.parent().attr('id'));
    });
});

function get_submenu_list_by_menu_id(menu_id)
{
	$.ajax({
		url: 'index.php?r=menu/get-submenu-list-by-ajax',
		type: 'post',
		data: {menu_id:menu_id},
		dataType: 'json',
		success: function(data) 
		{	
			$("#submenu_list").html(data.result);
		},
		error:function()
		{
			alert("failure");
		}
	});
	
	return false;
}

function get_controller_data_by_controller_id(controller_id)
{
	$.ajax({
		url: 'index.php?r=menu/get-controller-data-by-ajax',
		type: 'post',
		data: {controller_id:controller_id},
		dataType: 'json',
		success: function(data) 
		{	
			if(data.error != '')
			{
				alert(data.error);
				window.location.reload()
			}
			else
			{
				$("#controller_data").html(data.result);
			}
		},
		error:function()
		{
			alert("failure");
		}
	});
	
	return false;
}
