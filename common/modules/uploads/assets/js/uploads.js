$(document).ready(function()
{	
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
});

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
