function submitAjaxForm(form,modalId)
{
	$.post(form.attr('action'),form.serialize())
	.done(function( data ) {
		if(data.status == true)
		{
			form[0].reset();
			$('#'+modalId).modal('hide');
		}	  
	});
}