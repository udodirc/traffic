$(document).ready(function()
{	
	$('#menu-category_id').on('change', function() 
	{
		var id = $("#menu-category_id option:selected").val();
			
		if($('#menu_list_selector').length) 
		{
			$('#menu_list_selector').remove();
		}
		
		get_menu_drop_down_list_by_menu_id(id, 6, true);
	});
	
	$('#menu-controller_id').on('change', function() 
	{
		var id = $("#menu-controller_id option:selected").val();
		var submenu = (id == 7) ? true : false;
		
		if($('#controllers-list-selector').length) 
		{	
			$('#controllers-list-selector').remove();
			//$('#controllers_list').hide();
		}
		
		if($('#submenu_controllers-list-selector').length) 
		{	
			$('#submenu_controllers-list-selector').remove();
			//$('#controllers_submenu_list').hide();
		}
		
		get_menu_drop_down_list_by_menu_id(id, id, submenu);
	});
	
	$('body').on('change', '#controllers-list-selector', function ()
	{
		var id = $("#controllers-list-selector option:selected").val();
		var controler_id = $("#menu-controller_id option:selected").val();
		
		if($('#submenu_controllers-list-selector').length) 
		{	
			$('#submenu_controllers-list-selector').remove();
		}
		
		get_menu_drop_down_list_by_menu_id(id, controler_id, true);
	});
	
	$('#services-category_id').on('change', function() 
	{
		var id = $("#services-category_id option:selected").val();
		controler_id = 9;
		
		if($('#submenu-list-selector').length) 
		{	
			$('#submenu-list-selector').remove();
		}
		
		get_submenu_drop_down_list_by_id(id, controler_id);
	});
	
	$('body').on('click', '.file_delete', function ()
	{
		var url = $(this).parent().parent().parent().attr('url');
		var id = $(this).parent().parent().parent().attr('item_id');
		var wrap_id = $(this).parent().parent().parent().attr('wrap_id');
		var category = $(this).parent().parent().parent().attr('class');
		var file = $(this).parent().parent().attr('file');
		var file_type = $(this).parent().parent().attr('file_type');
		category = category.substr(6, category.length);
		
		delete_file(category, file, url, id, wrap_id, file_type);
    });
    
    $('.module-form').on('click', '#add-field', function ()
	{
		var module_id = $("#modules-module_id").val();
		
		if(module_id.length === 0)
		{
			alert('Небходимо заполнить ID Модуля');
		}
		else
		{
			var id = 1;
			
			if($("#field-list div").length) 
			{ 
				id = $("#field-list").children().last().attr('id');
				id = parseInt(id.substring(20)) + 1;
			}
			alert(module_id);
			add_module_field(id);
		}
    });
    
    $('.module-form').on('change', '#field_type', function ()
	{
		var id = $(this).parent().parent().attr('id').substring(20);
		var type = $("#module_custom_field_" + id + " #field_type option:selected").val();
		
		//alert(id);
		//alert(type);
		get_field_data(id, type);
    });
});

function get_menu_drop_down_list_by_menu_id(id, controller_id, submenu)
{
	$.ajax({
		url: 'get-menu-drop-down-list-by-ajax',
		type: 'post',
		data: {id:id, controller_id:controller_id, submenu:submenu},
		dataType: 'json',
		success: function(data) 
		{	
			if(data != '')
			{
				if(controller_id == 6)
				{
					$("#menu_list .control-label").after(data);
					$("#menu_list").css('visibility', 'visible');
				}
				else
				{
					if(submenu)
					{
						$("#controllers_submenu_list .control-label").after(data);
						$("#controllers_submenu_list").css('visibility', 'visible');
					}
					else
					{
						$("#controllers_list .control-label").after(data);
						$("#controllers_list").css('visibility', 'visible');
					}
				}
			}
		},
		error:function()
		{
			alert("failure");
		}
	});
	
	return false;
}

function get_submenu_drop_down_list_by_id(id, controller_id)
{
	$.ajax({
		url: 'get-submenu-drop-down-list-by-ajax',
		type: 'post',
		data: {id:id, controller_id:controller_id},
		dataType: 'json',
		success: function(data) 
		{	
			if(data != '')
			{
				$("#services_list .control-label").after(data);
				$("#services_list").css('visibility', 'visible');
			}
		},
		error:function()
		{
			alert("failure");
		}
	});
	
	return false;
}

function delete_file(category, file, url, id, wrap_id, file_type)
{
	$.ajax({
		url: url + '/uploads/files-upload/delete-file',
		type: 'post',
		data: {category:category, file:file, id:id, file_type:file_type},
		dataType: 'json',
		success: function(data) 
		{	
			if(data !== false)
			{	
				$('#files-upload[wrap_id="' + wrap_id + '"] .file_wrap[file_type="' + file_type + '"][file="' + file + '"]').remove();
			}
		},
		error:function()
		{
			alert("failure");
		}
	});
	
	return false;
}

function add_module_field(id)
{
	$.ajax({
		url: 'add-field-by-ajax',
		type: 'post',
		data: {id:id},
		dataType: 'json',
		success: function(data) 
		{	
			if(data !== '')
			{	
				$(".module-form #field-list").append(data);
			}
		},
		error:function()
		{
			alert("failure");
		}
	});
}

function get_field_data(id, type)
{
	$.ajax({
		url: 'get-field-data-by-ajax',
		type: 'post',
		data: {id:id, type:type},
		dataType: 'json',
		success: function(data) 
		{	
			if(data !== '')
			{	
				if($("#module_custom_field_" + id + " #field_data div").length) 
				{
					$("#module_custom_field_" + id + " #field_data").html('');
				}
				
				$("#module_custom_field_" + id + " #field_data").append(data);
			}
		},
		error:function()
		{
			alert("failure");
		}
	});
}
