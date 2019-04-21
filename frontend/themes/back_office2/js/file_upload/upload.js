$(document).ready(function()
{	
	if($('#file_uploader').length)
	{
		window.onload = createUploader(); 
	}
	
	$(".image_wrap").on(
	{	
		click:function(e)
		{	
			e.preventDefault();
			var remove_id = $(this).closest('.image_block').attr("id");
			var type = $('.image_wrap').attr("id");
			var edit_id = $('.image_wrap').attr("value");
			var tmp = $(this).attr("id");
			var image = $(this).closest('.image_block').attr("image");
			var row_id = $(this).closest('.image_block').is("[row_id]") ? $(this).closest('.image_block').attr("row_id") : 0;
			remove_id = remove_id.substring(9);
			type = type.substring(6);
			tmp = (tmp != 'tmp') ? 0 : 1;
			alert(row_id);
			delete_image(type, image, edit_id, tmp, remove_id, row_id);
		}
	}, ".image_delete a");
});

function createUploader()
{
	var uploader = new qq.FileUploader({
		element: document.getElementById('file_uploader'),
		action: 'file_upload/',
		debug: true,
		onComplete: function (id, fileName, responseJSON) 
		{
			var edit_id = $('.image_wrap').attr("value");
			var type = $('.image_wrap').attr("id");
			type = type.substring(6);
			
			create_image_container(edit_id, type);
		}
	});          
}

function create_image_container(id, type)
{
	$.ajax({
		url: 'get_image_list',
		type: 'post',
		data: {id:id, type:type},
		dataType: 'json',
		success: function(data) 
		{	
			$(".image_wrap").html(data);
		},
		error:function()
		{
			alert("failure");
		}
	});
	
	return false;
}

function delete_image(type, image, edit_id, tmp, remove_id, row_id)
{
	$.ajax({
		url: 'delete_image_by_ajax',
		type: 'post',
		data: {type:type, image:image, edit_id:edit_id, tmp:tmp, row_id:row_id},
		dataType: 'json',
		success: function(data) 
		{	
			$("#image_id_" + remove_id).remove();
		},
		error:function()
		{
			alert("failure");
		}
	});
	
	return false;
}
